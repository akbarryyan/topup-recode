<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\TopUpTransaction;
use App\Services\DuitkuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TopUpController extends Controller
{
    protected $duitkuService;

    public function __construct(DuitkuService $duitkuService)
    {
        $this->duitkuService = $duitkuService;
    }

    /**
     * Create top up transaction
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|integer|min:20000',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $user = $request->user();
        $amount = $validated['amount'];
        
        // Load payment method
        $paymentMethod = PaymentMethod::with('paymentGateway')->findOrFail($validated['payment_method_id']);

        if (!$paymentMethod->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Metode pembayaran tidak tersedia',
            ], 400);
        }

        // Calculate fee and total
        $fee = $paymentMethod->calculateCustomerFee($amount);
        $totalAmount = $amount + $fee;

        try {
            DB::beginTransaction();

            // Generate unique merchant order ID
            $merchantOrderId = 'TOPUP-' . $user->id . '-' . time() . '-' . rand(1000, 9999);

            // Prepare Duitku transaction params
            $duitkuParams = [
                'merchantOrderId' => $merchantOrderId,
                'paymentAmount' => (int) $totalAmount,
                'paymentMethod' => $paymentMethod->code,
                'productDetails' => 'Top Up Saldo - ' . $user->name,
                'customerVaName' => $user->name,
                'email' => $user->email,
                'phoneNumber' => $user->phone ?? '',
                'callbackUrl' => url('/payment/duitku/callback'), // Use absolute URL for callback
                'returnUrl' => url('/payment/duitku/redirect'), // Use absolute URL for redirect
                'expiryPeriod' => 60, // 60 minutes
                'itemDetails' => [
                    [
                        'name' => 'Top Up Saldo',
                        'price' => (int) $amount,
                        'quantity' => 1,
                    ],
                    [
                        'name' => 'Biaya Admin',
                        'price' => (int) $fee,
                        'quantity' => 1,
                    ],
                ],
            ];

            // Create transaction via Duitku
            $result = $this->duitkuService->createTransaction($duitkuParams);

            if (!$result['success']) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Gagal membuat transaksi',
                ], 400);
            }

            $duitkuData = $result['data'];

            // Save transaction to database
            $transaction = TopUpTransaction::create([
                'user_id' => $user->id,
                'merchant_order_id' => $merchantOrderId,
                'reference' => $duitkuData['reference'],
                'amount' => $amount,
                'fee' => $fee,
                'total_amount' => $totalAmount,
                'payment_method_id' => $paymentMethod->id,
                'payment_url' => $duitkuData['payment_url'],
                'va_number' => $duitkuData['va_number'],
                'qr_string' => $duitkuData['qr_string'],
                'status' => 'pending',
                'expired_at' => now()->addMinutes(60),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibuat',
                'data' => [
                    'merchant_order_id' => $merchantOrderId,
                    'reference' => $duitkuData['reference'],
                    'payment_url' => $duitkuData['payment_url'],
                    'va_number' => $duitkuData['va_number'],
                    'qr_string' => $duitkuData['qr_string'],
                    'amount' => $amount,
                    'fee' => $fee,
                    'total_amount' => $totalAmount,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Top Up Create Error', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat transaksi',
            ], 500);
        }
    }

    /**
     * Handle callback from Duitku
     * POST request from Duitku server
     * Used for server-to-server payment confirmation
     */
    public function callback(Request $request)
    {
        Log::info('Duitku Callback Received', $request->all());

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

            // Find transaction by merchantOrderId
            $transaction = TopUpTransaction::where('merchant_order_id', $merchantOrderId)->firstOrFail();

            // Check if transaction is already paid - prevent status change (idempotent)
            if ($transaction->status === 'paid') {
                DB::rollBack();
                Log::info('Duitku Callback - Transaction Already Paid (Idempotent)', [
                    'merchant_order_id' => $merchantOrderId,
                    'status' => $transaction->status,
                    'paid_at' => $transaction->paid_at,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Transaction already paid',
                ]);
            }

            // Process callback data
            $callbackData = $this->duitkuService->processCallback($request->all());
            
            // Store callback data
            $transaction->callback_data = $callbackData;
            $transaction->reference = $reference; // Update reference if not set
            $transaction->save();

            // Update status based on resultCode
            // 00 = Success, 01 = Failed
            if ($resultCode === '00') {
                // Payment successful
                $transaction->markAsPaid();

                // Add balance to user
                $user = $transaction->user;
                $user->balance += $transaction->amount;
                $user->save();

                Log::info('Duitku Callback - Payment Success', [
                    'merchant_order_id' => $merchantOrderId,
                    'user_id' => $user->id,
                    'amount' => $transaction->amount,
                    'reference' => $reference,
                ]);

            } else {
                // Payment failed
                $transaction->markAsFailed('Payment failed with result code: ' . $resultCode);
                
                Log::warning('Duitku Callback - Payment Failed', [
                    'merchant_order_id' => $merchantOrderId,
                    'result_code' => $resultCode,
                    'reference' => $reference,
                ]);
            }

            DB::commit();

            // Return success response to Duitku
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
     * Handle redirect from Duitku
     * GET request when user returns from payment page
     * Used only for UI display, NOT for updating transaction status
     */
    public function redirect(Request $request)
    {
        Log::info('Duitku Redirect Received', $request->all());

        $merchantOrderId = $request->input('merchantOrderId');
        $resultCode = $request->input('resultCode');
        $reference = $request->input('reference');

        try {
            // Find transaction
            $transaction = TopUpTransaction::where('merchant_order_id', $merchantOrderId)->first();

            if (!$transaction) {
                return redirect()->route('profile')
                    ->with('error', 'Transaksi tidak ditemukan');
            }

            // Redirect based on status (only for display purposes)
            // Actual status should be updated via callback
            if ($resultCode === '00') {
                return redirect()->route('profile', ['tab' => 'mutations'])
                    ->with('success', 'Pembayaran berhasil! Saldo akan segera ditambahkan.');
            } else {
                return redirect()->route('profile', ['tab' => 'mutations'])
                    ->with('warning', 'Pembayaran pending atau gagal. Silakan cek status transaksi Anda.');
            }
            
        } catch (\Exception $e) {
            Log::error('Duitku Redirect Error', [
                'merchant_order_id' => $merchantOrderId,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('profile')
                ->with('error', 'Terjadi kesalahan saat memproses redirect');
        }
    }
}
