<?php

namespace App\Http\Controllers;

use App\Models\PrepaidService;
use App\Models\PrepaidTransaction;
use App\Models\Mutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\PaymentMethod;
use App\Services\DuitkuService;

class PrepaidOrderController extends Controller
{
    protected $duitkuService;

    public function __construct(DuitkuService $duitkuService)
    {
        $this->duitkuService = $duitkuService;
    }

    /**
     * Process prepaid order
     */
    public function store(Request $request)
    {
        Log::info('Prepaid Order Request Received', $request->all());

        $validated = $request->validate([
            'brand' => 'required|string',
            'service_code' => 'required|string',
            'phone_number' => 'required|string',
            'whatsapp' => 'nullable|string',
            'email' => 'required|email',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $user = $request->user();

        try {
            DB::beginTransaction();

            // Get service
            $service = PrepaidService::where('code', $validated['service_code'])
                ->where('is_active', true)
                ->where('status', 'available')
                ->firstOrFail();

            // Get payment method
            $paymentMethod = PaymentMethod::where('id', $validated['payment_method_id'])
                ->where('is_active', true)
                ->firstOrFail();

            // Calculate final price (service price + payment fee)
            // Note: Prepaid service price is fixed, not based on role for now as per previous logic
            // But we should respect the previous logic if it was intended
            $servicePrice = $service->price_basic; // Using price_basic from DB directly as per route logic
            
            // Calculate payment fee
            $paymentFee = $paymentMethod->total_fee;
            $totalAmount = $servicePrice + $paymentFee;

            // Check stock
            if ($service->stock !== null && $service->stock < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok produk tidak tersedia.',
                ], 400);
            }

            // Generate unique transaction ID
            $trxid = 'PREPAID-' . time() . '-' . rand(1000, 9999);

            // Create transaction with pending status
            $transaction = PrepaidTransaction::create([
                'trxid' => $trxid,
                'user_id' => $user->id,
                'service_code' => $service->code,
                'service_name' => $service->name,
                'data_no' => $validated['phone_number'],
                'status' => 'waiting', // Waiting for payment
                'price' => $servicePrice,
                'balance' => $user->balance, // Snapshot of balance (not used for payment)
                'note' => json_encode([
                    'brand' => $validated['brand'],
                    'whatsapp' => $validated['whatsapp'] ?? null,
                ]),
                // Payment Fields
                'payment_method_id' => $paymentMethod->id,
                'payment_method_code' => $paymentMethod->code,
                'payment_amount' => $totalAmount,
                'payment_fee' => $paymentFee,
                'email' => $validated['email'],
                'whatsapp' => $validated['whatsapp'] ?? null,
                'payment_status' => 'pending',
            ]);

            // Prepare Duitku parameters
            $duitkuParams = [
                'merchantOrderId' => $trxid,
                'paymentAmount' => (int) $totalAmount,
                'paymentMethod' => $paymentMethod->code,
                'productDetails' => "Pembelian {$validated['brand']} - {$service->name}",
                'email' => $validated['email'],
                'phoneNumber' => $validated['phone_number'],
                'customerVaName' => $user->name,
                'callbackUrl' => route('topup.callback'), // Reuse existing callback or create new one
                'returnUrl' => route('invoices'), // Redirect to invoices page
                'expiryPeriod' => 60, // 60 minutes
            ];

            // Call Duitku API
            $duitkuResponse = $this->duitkuService->createTransaction($duitkuParams);

            if (!$duitkuResponse['success']) {
                throw new \Exception($duitkuResponse['message']);
            }

            // Update transaction with payment details
            $paymentData = $duitkuResponse['data'];
            $transaction->update([
                'payment_reference' => $paymentData['reference'],
                'payment_url' => $paymentData['payment_url'],
                'va_number' => $paymentData['va_number'],
                'qr_string' => $paymentData['qr_string'],
            ]);

            DB::commit();

            Log::info('Prepaid Order Created', [
                'trxid' => $trxid,
                'user_id' => $user->id,
                'service' => $service->name,
                'amount' => $totalAmount,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'data' => [
                    'trxid' => $trxid,
                    'payment_url' => $paymentData['payment_url'],
                    'redirect_url' => $paymentData['payment_url'] ?? route('invoices'),
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Prepaid Order Error', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
