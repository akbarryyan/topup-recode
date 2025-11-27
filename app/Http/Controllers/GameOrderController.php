<?php

namespace App\Http\Controllers;

use App\Models\GameService;
use App\Models\GameTransaction;
use App\Models\Mutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\PaymentMethod;
use App\Services\DuitkuService;

class GameOrderController extends Controller
{
    protected $duitkuService;

    public function __construct(DuitkuService $duitkuService)
    {
        $this->duitkuService = $duitkuService;
    }

    /**
     * Process game order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'game' => 'required|string',
            'service_code' => 'required|string',
            'account_fields' => 'required|array',
            'whatsapp' => 'nullable|string',
            'email' => 'required|email',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $user = $request->user(); // Can be null for guest users

        try {
            DB::beginTransaction();

            // Get service
            $service = GameService::where('code', $validated['service_code'])
                ->where('is_active', true)
                ->where('status', 'available')
                ->firstOrFail();

            // Get payment method
            $paymentMethod = PaymentMethod::where('id', $validated['payment_method_id'])
                ->where('is_active', true)
                ->firstOrFail();

            // Calculate final price based on user role (guest = 'member')
            $userRole = $user ? $user->role : 'member';
            $servicePrice = $service->calculateFinalPrice($userRole);
            
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
            $trxid = 'GAME-' . time() . '-' . rand(1000, 9999);

            // Extract account fields
            $accountFields = $validated['account_fields'];
            $userId = $accountFields['user_id'] ?? $accountFields['id'] ?? null;
            $zoneId = $accountFields['zone_id'] ?? $accountFields['server'] ?? null;

            // Create transaction
            $transaction = GameTransaction::create([
                'trxid' => $trxid,
                'user_id' => $user ? $user->id : null, // Allow guest orders
                'service_code' => $service->code,
                'service_name' => $service->name,
                'data_no' => $userId,
                'data_zone' => $zoneId,
                'status' => 'waiting', // Waiting for payment
                'price' => $servicePrice,
                'balance' => $user ? $user->balance : 0,
                'note' => json_encode([
                    'game' => $validated['game'],
                    'whatsapp' => $validated['whatsapp'] ?? null,
                    'account_fields' => $accountFields,
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
                'productDetails' => "Pembelian {$validated['game']} - {$service->name}",
                'email' => $validated['email'],
                'phoneNumber' => $validated['whatsapp'] ?? '081234567890', // Default if empty
                'customerVaName' => $user ? $user->name : 'Guest',
                'callbackUrl' => url('/payment/callback'),
                'returnUrl' => url('/invoices'),
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

            // Reduce stock if applicable (Optimistic stock reduction, or move to callback)
            // For now keeping it here as per previous logic, but ideally should be on success
            if ($service->stock !== null) {
                $service->decrement('stock');
            }

            DB::commit();

            Log::info('Game Order Created', [
                'trxid' => $trxid,
                'user_id' => $user ? $user->id : null,
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
            Log::error('Game Order Error', [
                'user_id' => $user ? $user->id : null,
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
