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
            'payment_method_id' => 'required',
        ]);

        $user = $request->user(); // Can be null for guest users

        try {
            DB::beginTransaction();

            // Get service
            $service = GameService::where('code', $validated['service_code'])
                ->where('is_active', true)
                ->where('status', 'available')
                ->firstOrFail();

            // Check if payment is Credits
            $isCreditsPayment = $validated['payment_method_id'] === 'credits';
            
            // For Credits payment, user must be logged in
            if ($isCreditsPayment && !$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda harus login untuk menggunakan Credits.',
                ], 401);
            }

            // Calculate final price based on user role (guest = 'member')
            $userRole = $user ? $user->role : 'member';
            $servicePrice = $service->calculateFinalPrice($userRole);
            
            // Calculate payment fee (Credits has no fee)
            $paymentFee = 0;
            $paymentMethod = null;
            
            if (!$isCreditsPayment) {
                $paymentMethod = PaymentMethod::where('id', $validated['payment_method_id'])
                    ->where('is_active', true)
                    ->firstOrFail();
                $paymentFee = $paymentMethod->total_fee;
            }
            
            $totalAmount = $servicePrice + $paymentFee;

            // Check stock
            if ($service->stock !== null && $service->stock < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok produk tidak tersedia.',
                ], 400);
            }

            // For Credits payment, validate balance
            if ($isCreditsPayment) {
                if ($user->balance < $totalAmount) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Saldo Anda tidak mencukupi. Saldo: Rp ' . number_format($user->balance, 0, ',', '.') . ', Total: Rp ' . number_format($totalAmount, 0, ',', '.'),
                    ], 400);
                }
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
                'user_id' => $user ? $user->id : null,
                'service_code' => $service->code,
                'service_name' => $service->name,
                'data_no' => $userId,
                'data_zone' => $zoneId,
                'status' => $isCreditsPayment ? 'success' : 'waiting', // Credits payment is instant success
                'price' => $servicePrice,
                'balance' => $user ? $user->balance : 0,
                'note' => json_encode([
                    'game' => $validated['game'],
                    'whatsapp' => $validated['whatsapp'] ?? null,
                    'account_fields' => $accountFields,
                ]),
                // Payment Fields
                'payment_method_id' => $isCreditsPayment ? null : $paymentMethod->id,
                'payment_method_code' => $isCreditsPayment ? 'CREDITS' : $paymentMethod->code,
                'payment_amount' => $totalAmount,
                'payment_fee' => $paymentFee,
                'email' => $validated['email'],
                'whatsapp' => $validated['whatsapp'] ?? null,
                'payment_status' => $isCreditsPayment ? 'paid' : 'pending',
                'paid_at' => $isCreditsPayment ? now() : null,
            ]);

            // Handle Credits Payment
            if ($isCreditsPayment) {
                // Deduct balance
                $user->balance -= $totalAmount;
                $user->save();

                // Create mutation record
                Mutation::create([
                    'user_id' => $user->id,
                    'type' => 'debit',
                    'amount' => $totalAmount,
                    'balance_before' => $transaction->balance,
                    'balance_after' => $user->balance,
                    'description' => "Pembelian {$validated['game']} - {$service->name}",
                    'reference_type' => 'game_transaction',
                    'reference_id' => $transaction->id,
                ]);

                // Reduce stock
                if ($service->stock !== null) {
                    $service->decrement('stock');
                }

                DB::commit();

                Log::info('Game Order with Credits Completed', [
                    'trxid' => $trxid,
                    'user_id' => $user->id,
                    'service' => $service->name,
                    'amount' => $totalAmount,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil dibuat dan dibayar dengan Credits',
                    'data' => [
                        'trxid' => $trxid,
                        'payment_url' => route('payment.success', ['trxid' => $trxid]),
                        'redirect_url' => route('payment.success', ['trxid' => $trxid]),
                    ]
                ]);
            }

            // Handle Payment Gateway (existing logic)
            // Prepare Duitku parameters
            $duitkuParams = [
                'merchantOrderId' => $trxid,
                'paymentAmount' => (int) $totalAmount,
                'paymentMethod' => $paymentMethod->code,
                'productDetails' => "Pembelian {$validated['game']} - {$service->name}",
                'email' => $validated['email'],
                'phoneNumber' => $validated['whatsapp'] ?? '081234567890',
                'customerVaName' => $user ? $user->name : 'Guest',
                'callbackUrl' => url('/payment/callback'),
                'returnUrl' => url('/payment/success/' . $trxid),
                'expiryPeriod' => 60,
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

            // Reduce stock (optimistic)
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
