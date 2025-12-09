<?php

namespace App\Http\Controllers;

use App\Models\GameTransaction;
use App\Models\PrepaidTransaction;
use App\Models\TopUpTransaction;
use App\Services\DuitkuService;
use App\Services\VipResellerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    protected $duitkuService;
    protected $vipResellerService;

    public function __construct(DuitkuService $duitkuService, VipResellerService $vipResellerService)
    {
        $this->duitkuService = $duitkuService;
        $this->vipResellerService = $vipResellerService;
    }

    /**
     * Handle unified payment callback from Duitku
     * Supports TopUp, Game, and Prepaid transactions
     */
    public function handle(Request $request)
    {
        Log::info('Duitku Unified Callback Received', $request->all());

        // Get callback parameters
        $merchantCode = $request->input('merchantCode');
        $amount = $request->input('amount');
        $merchantOrderId = $request->input('merchantOrderId');
        $signature = $request->input('signature');
        $resultCode = $request->input('resultCode');
        $reference = $request->input('reference');

        // Validate required parameters
        if (empty($merchantCode) || empty($amount) || empty($merchantOrderId) || empty($signature)) {
            Log::error('Duitku Callback - Bad Parameter', $request->all());
            return response()->json([
                'success' => false,
                'message' => 'Bad Parameter',
            ], 400);
        }

        // Verify signature
        if (!$this->duitkuService->verifyCallbackSignature($merchantCode, $amount, $merchantOrderId, $signature)) {
            Log::warning('Duitku Callback - Invalid Signature', [
                'merchant_order_id' => $merchantOrderId,
                'received_signature' => $signature,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Bad Signature',
            ], 403);
        }

        try {
            DB::beginTransaction();

            // Detect transaction type based on trxid prefix
            $transactionType = $this->detectTransactionType($merchantOrderId);
            
            if (!$transactionType) {
                throw new \Exception('Unknown transaction type for: ' . $merchantOrderId);
            }

            // Process based on transaction type
            $result = match($transactionType) {
                'topup' => $this->processTopUpCallback($merchantOrderId, $resultCode, $reference, $request->all()),
                'game' => $this->processGameCallback($merchantOrderId, $resultCode, $reference, $request->all()),
                'prepaid' => $this->processPrepaidCallback($merchantOrderId, $resultCode, $reference, $request->all()),
                default => throw new \Exception('Unsupported transaction type: ' . $transactionType)
            };

            DB::commit();

            Log::info('Duitku Callback - Success', [
                'merchant_order_id' => $merchantOrderId,
                'type' => $transactionType,
                'result_code' => $resultCode,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Callback processed successfully',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Duitku Callback Error', [
                'merchant_order_id' => $merchantOrderId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process callback',
            ], 500);
        }
    }

    /**
     * Detect transaction type from trxid prefix
     */
    private function detectTransactionType(string $trxid): ?string
    {
        if (str_starts_with($trxid, 'GAME-')) {
            return 'game';
        } elseif (str_starts_with($trxid, 'PREPAID-')) {
            return 'prepaid';
        } elseif (str_starts_with($trxid, 'TOPUP-')) {
            return 'topup';
        }
        
        return null;
    }

    /**
     * Process TopUp transaction callback
     */
    private function processTopUpCallback(string $trxid, string $resultCode, string $reference, array $callbackData): bool
    {
        $transaction = TopUpTransaction::where('merchant_order_id', $trxid)->firstOrFail();

        // Check if already processed
        if ($transaction->status === 'paid') {
            Log::info('TopUp Transaction Already Paid', ['trxid' => $trxid]);
            return true;
        }

        // Store callback data
        $transaction->callback_data = $callbackData;
        $transaction->reference = $reference;
        $transaction->save();

        if ($resultCode === '00') {
            // Payment successful
            $transaction->markAsPaid();

            // Add balance to user
            $user = $transaction->user;
            $previousBalance = $user->balance;
            $user->balance += $transaction->amount;
            $user->save();

            // Create mutation record
            \App\Models\Mutation::create([
                'user_id' => $user->id,
                'type' => 'credit',
                'amount' => $transaction->amount,
                'balance_before' => $previousBalance,
                'balance_after' => $user->balance,
                'description' => 'Top Up Saldo',
                'notes' => 'Top up via ' . ($transaction->paymentMethod->name ?? 'Payment Gateway') . ' - ' . $transaction->merchant_order_id,
                'reference_type' => 'App\Models\TopUpTransaction',
                'reference_id' => $transaction->id,
            ]);

            Log::info('TopUp Payment Success', [
                'trxid' => $trxid,
                'user_id' => $user->id,
                'amount' => $transaction->amount,
            ]);

            return true;
        } else {
            // Payment failed
            $transaction->markAsFailed('Payment failed with result code: ' . $resultCode);
            return false;
        }
    }

    /**
     * Process Game transaction callback
     */
    private function processGameCallback(string $trxid, string $resultCode, string $reference, array $callbackData): bool
    {
        $transaction = GameTransaction::where('trxid', $trxid)->firstOrFail();

        // Check if already processed
        if ($transaction->payment_status === 'paid') {
            Log::info('Game Transaction Already Paid', ['trxid' => $trxid]);
            return true;
        }

        // Update payment reference
        $transaction->payment_reference = $reference;
        $transaction->save();

        if ($resultCode === '00') {
            // Payment successful - update status
            $transaction->payment_status = 'paid';
            $transaction->status = 'processing'; // Will be processed by game service
            $transaction->paid_at = now();
            $transaction->save();

            Log::info('Game Payment Success', [
                'trxid' => $trxid,
                'user_id' => $transaction->user_id,
                'service' => $transaction->service_name,
            ]);

            // Process order to VIP Reseller
            $this->processGameOrderToProvider($transaction);

            return true;
        } else {
            // Payment failed
            $transaction->payment_status = 'failed';
            $transaction->status = 'failed';
            $transaction->save();

            Log::warning('Game Payment Failed', [
                'trxid' => $trxid,
                'result_code' => $resultCode,
            ]);

            return false;
        }
    }

    /**
     * Process Game Order to VIP Reseller Provider
     */
    private function processGameOrderToProvider(GameTransaction $transaction): void
    {
        try {
            Log::info('Processing Game Order to VIP Reseller', [
                'trxid' => $transaction->trxid,
                'service_code' => $transaction->service_code,
                'data_no' => $transaction->data_no,
                'data_zone' => $transaction->data_zone,
            ]);

            // Call VIP Reseller API to place order
            $result = $this->vipResellerService->orderGame(
                $transaction->service_code,
                $transaction->data_no,
                $transaction->data_zone
            );

            if ($result['success']) {
                // Order successful - update transaction with provider data
                $providerData = $result['data'];
                
                // Get provider status and map to our status
                $providerStatus = $providerData['status'] ?? 'waiting';
                
                $transaction->provider_trxid = $providerData['trxid'] ?? null;
                $transaction->provider_status = $providerStatus;
                $transaction->provider_note = $providerData['note'] ?? $result['message'];
                $transaction->provider_price = $providerData['price'] ?? null;
                
                // Map provider status to transaction status
                // Provider status: waiting, processing, success, failed/error
                $transaction->status = $this->mapProviderStatusToTransactionStatus($providerStatus);
                $transaction->save();

                Log::info('Game Order to VIP Reseller Success', [
                    'trxid' => $transaction->trxid,
                    'provider_trxid' => $providerData['trxid'] ?? null,
                    'provider_status' => $providerStatus,
                    'mapped_status' => $transaction->status,
                    'message' => $result['message'],
                ]);

            } else {
                // Order failed - mark transaction as failed
                $transaction->status = 'failed';
                $transaction->provider_note = 'Provider Error: ' . $result['message'];
                $transaction->save();

                Log::error('Game Order to VIP Reseller Failed', [
                    'trxid' => $transaction->trxid,
                    'error' => $result['message'],
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Game Order to VIP Reseller Exception', [
                'trxid' => $transaction->trxid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Mark as failed but don't throw - payment is already confirmed
            $transaction->status = 'failed';
            $transaction->provider_note = 'System Error: ' . $e->getMessage();
            $transaction->save();
        }
    }

    /**
     * Map VIP Reseller provider status to our transaction status
     */
    private function mapProviderStatusToTransactionStatus(string $providerStatus): string
    {
        return match(strtolower($providerStatus)) {
            'success' => 'success',
            'failed', 'error' => 'failed',
            'waiting', 'processing' => 'processing',
            default => 'processing',
        };
    }

    /**
     * Process Prepaid transaction callback
     */
    private function processPrepaidCallback(string $trxid, string $resultCode, string $reference, array $callbackData): bool
    {
        $transaction = PrepaidTransaction::where('trxid', $trxid)->firstOrFail();

        // Check if already processed
        if ($transaction->payment_status === 'paid') {
            Log::info('Prepaid Transaction Already Paid', ['trxid' => $trxid]);
            return true;
        }

        // Update payment reference
        $transaction->payment_reference = $reference;
        $transaction->save();

        if ($resultCode === '00') {
            // Payment successful - update status
            $transaction->payment_status = 'paid';
            $transaction->status = 'processing';
            $transaction->paid_at = now();
            $transaction->save();

            Log::info('Prepaid Payment Success', [
                'trxid' => $trxid,
                'user_id' => $transaction->user_id,
                'service' => $transaction->service_name,
            ]);

            // Process order to VIP Reseller
            $this->processPrepaidOrderToProvider($transaction);

            return true;
        } else {
            // Payment failed
            $transaction->payment_status = 'failed';
            $transaction->status = 'failed';
            $transaction->save();

            Log::warning('Prepaid Payment Failed', [
                'trxid' => $trxid,
                'result_code' => $resultCode,
            ]);

            return false;
        }
    }

    /**
     * Process Prepaid Order to VIP Reseller Provider
     */
    private function processPrepaidOrderToProvider(PrepaidTransaction $transaction): void
    {
        try {
            Log::info('Processing Prepaid Order to VIP Reseller', [
                'trxid' => $transaction->trxid,
                'service_code' => $transaction->service_code,
                'data_no' => $transaction->data_no,
            ]);

            // Call VIP Reseller API to place order
            $result = $this->vipResellerService->orderPrepaid(
                $transaction->service_code,
                $transaction->data_no
            );

            if ($result['success']) {
                // Order successful - update transaction with provider data
                $providerData = $result['data'];
                
                // Get provider status and map to our status
                $providerStatus = $providerData['status'] ?? 'waiting';
                
                $transaction->provider_trxid = $providerData['trxid'] ?? null;
                $transaction->provider_status = $providerStatus;
                $transaction->provider_note = $providerData['note'] ?? $result['message'];
                $transaction->provider_price = $providerData['price'] ?? null;
                
                // Map provider status to transaction status
                $transaction->status = $this->mapProviderStatusToTransactionStatus($providerStatus);
                $transaction->save();

                Log::info('Prepaid Order to VIP Reseller Success', [
                    'trxid' => $transaction->trxid,
                    'provider_trxid' => $providerData['trxid'] ?? null,
                    'provider_status' => $providerStatus,
                    'mapped_status' => $transaction->status,
                ]);

            } else {
                // Order failed - mark transaction as failed
                $transaction->status = 'failed';
                $transaction->provider_note = 'Provider Error: ' . $result['message'];
                $transaction->save();

                Log::error('Prepaid Order to VIP Reseller Failed', [
                    'trxid' => $transaction->trxid,
                    'error' => $result['message'],
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Prepaid Order to VIP Reseller Exception', [
                'trxid' => $transaction->trxid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Mark as failed but don't throw - payment is already confirmed
            $transaction->status = 'failed';
            $transaction->provider_note = 'System Error: ' . $e->getMessage();
            $transaction->save();
        }
    }
}
