<?php

namespace App\Http\Controllers;

use App\Models\PrepaidService;
use App\Models\PrepaidTransaction;
use App\Models\Mutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PrepaidOrderController extends Controller
{
    /**
     * Process prepaid order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'required|string',
            'service_code' => 'required|string',
            'phone_number' => 'required|string',
            'whatsapp' => 'nullable|string',
        ]);

        $user = $request->user();

        try {
            DB::beginTransaction();

            // Get service
            $service = PrepaidService::where('code', $validated['service_code'])
                ->where('is_active', true)
                ->where('status', 'available')
                ->firstOrFail();

            // Calculate final price based on user role
            $finalPrice = $service->calculateFinalPrice($user->role ?? 'member');

            // Check user balance
            if ($user->balance < $finalPrice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Saldo tidak mencukupi. Silakan top up terlebih dahulu.',
                    'data' => [
                        'required_balance' => $finalPrice,
                        'current_balance' => $user->balance,
                        'shortage' => $finalPrice - $user->balance,
                    ]
                ], 400);
            }

            // Check stock
            if ($service->stock !== null && $service->stock < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok produk tidak tersedia.',
                ], 400);
            }

            // Generate unique transaction ID
            $trxid = 'PREPAID-' . time() . '-' . rand(1000, 9999);

            // Create transaction
            $transaction = PrepaidTransaction::create([
                'trxid' => $trxid,
                'user_id' => $user->id,
                'service_code' => $service->code,
                'service_name' => $service->name,
                'data_no' => $validated['phone_number'],
                'status' => 'processing',
                'price' => $finalPrice,
                'balance' => $user->balance,
                'note' => json_encode([
                    'brand' => $validated['brand'],
                    'whatsapp' => $validated['whatsapp'] ?? null,
                ]),
            ]);

            // Deduct balance
            $balanceBefore = $user->balance;
            $user->balance -= $finalPrice;
            $balanceAfter = $user->balance;
            $user->save();

            // Record mutation
            Mutation::record(
                userId: $user->id,
                type: 'debit',
                amount: (float)$finalPrice,
                balanceBefore: (float)$balanceBefore,
                balanceAfter: (float)$balanceAfter,
                description: $service->name,
                referenceType: PrepaidTransaction::class,
                referenceId: $transaction->id,
                notes: "Pembelian {$validated['brand']} - {$service->name}",
                metadata: [
                    'trxid' => $trxid,
                    'brand' => $validated['brand'],
                    'service_code' => $service->code,
                    'phone_number' => $validated['phone_number'],
                ]
            );

            // Reduce stock if applicable
            if ($service->stock !== null) {
                $service->decrement('stock');
            }

            // TODO: Process with prepaid provider API
            // For now, we'll mark as success immediately
            // In production, this should be processed with actual prepaid provider
            $transaction->update(['status' => 'success']);

            DB::commit();

            Log::info('Prepaid Order Created', [
                'trxid' => $trxid,
                'user_id' => $user->id,
                'service' => $service->name,
                'price' => $finalPrice,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'data' => [
                    'trxid' => $trxid,
                    'status' => $transaction->status,
                    'service_name' => $service->name,
                    'price' => $finalPrice,
                    'balance_after' => $balanceAfter,
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
