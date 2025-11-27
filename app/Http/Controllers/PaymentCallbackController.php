<?php

namespace App\Http\Controllers;

use App\Models\GameTransaction;
use App\Models\PrepaidTransaction;
use App\Models\TopUpTransaction;
use App\Services\DuitkuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    protected $duitkuService;

    public function __construct(DuitkuService $duitkuService)
    {
        $this->duitkuService = $duitkuService;
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
            $user->balance += $transaction->amount;
            $user->save();

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

            // TODO: Trigger game order processing (DigiFlazz API call)
            // This should be handled by a job or service

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
            $transaction->status = 'processing'; // Will be processed by prepaid service
            $transaction->paid_at = now();
            $transaction->save();

            Log::info('Prepaid Payment Success', [
                'trxid' => $trxid,
                'user_id' => $transaction->user_id,
                'service' => $transaction->service_name,
            ]);

            // TODO: Trigger prepaid order processing (DigiFlazz API call)
            // This should be handled by a job or service

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
}
