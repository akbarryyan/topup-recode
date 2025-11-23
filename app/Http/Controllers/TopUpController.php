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
                'callbackUrl' => route('topup.callback'),
                'returnUrl' => route('profile') . '?tab=mutations',
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
     */
    public function callback(Request $request)
    {
        Log::info('Duitku Callback Received', $request->all());

        $merchantOrderId = $request->input('merchantOrderId');
        $amount = $request->input('amount');
        $signature = $request->input('signature');
        $resultCode = $request->input('resultCode');

        // Verify signature
        if (!$this->duitkuService->verifyCallback($merchantOrderId, $amount, $signature)) {
            Log::warning('Duitku Callback Invalid Signature', [
                'merchant_order_id' => $merchantOrderId,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Invalid signature',
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Find transaction by merchantOrderId
            $transaction = TopUpTransaction::where('merchant_order_id', $merchantOrderId)->firstOrFail();

            // Store callback data
            $transaction->callback_data = $request->all();
            $transaction->save();

            // Update status based on resultCode
            if ($resultCode === '00') {
                // Payment successful
                $transaction->markAsPaid();

                // TODO: Add balance to user
                // $user = $transaction->user;
                // $user->balance += $transaction->amount;
                // $user->save();

                // TODO: Create mutation record
                // Mutation::create([...]);

            } else {
                // Payment failed
                $transaction->markAsFailed('Payment failed with result code: ' . $resultCode);
            }

            DB::commit();

            Log::info('Duitku Callback Processed', [
                'merchant_order_id' => $merchantOrderId,
                'result_code' => $resultCode,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Callback processed',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Duitku Callback Error', [
                'merchant_order_id' => $merchantOrderId,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process callback',
            ], 500);
        }
    }
}
