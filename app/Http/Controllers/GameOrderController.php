<?php

namespace App\Http\Controllers;

use App\Models\GameService;
use App\Models\GameTransaction;
use App\Models\Mutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GameOrderController extends Controller
{
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
        ]);

        $user = $request->user();

        try {
            DB::beginTransaction();

            // Get service
            $service = GameService::where('code', $validated['service_code'])
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
            $trxid = 'GAME-' . time() . '-' . rand(1000, 9999);

            // Extract account fields
            $accountFields = $validated['account_fields'];
            $userId = $accountFields['user_id'] ?? $accountFields['id'] ?? null;
            $zoneId = $accountFields['zone_id'] ?? $accountFields['server'] ?? null;

            // Create transaction
            $transaction = GameTransaction::create([
                'trxid' => $trxid,
                'user_id' => $user->id,
                'service_code' => $service->code,
                'service_name' => $service->name,
                'data_no' => $userId,
                'data_zone' => $zoneId,
                'status' => 'processing',
                'price' => $finalPrice,
                'balance' => $user->balance,
                'note' => json_encode([
                    'game' => $validated['game'],
                    'whatsapp' => $validated['whatsapp'] ?? null,
                    'account_fields' => $accountFields,
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
                referenceType: GameTransaction::class,
                referenceId: $transaction->id,
                notes: "Pembelian {$validated['game']} - {$service->name}",
                metadata: [
                    'trxid' => $trxid,
                    'game' => $validated['game'],
                    'service_code' => $service->code,
                    'user_id' => $userId,
                    'zone_id' => $zoneId,
                ]
            );

            // Reduce stock if applicable
            if ($service->stock !== null) {
                $service->decrement('stock');
            }

            // TODO: Process with game provider API
            // For now, we'll mark as success immediately
            // In production, this should be processed with actual game provider
            $transaction->update(['status' => 'success']);

            DB::commit();

            Log::info('Game Order Created', [
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
            Log::error('Game Order Error', [
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
