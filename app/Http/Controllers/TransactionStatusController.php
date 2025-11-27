<?php

namespace App\Http\Controllers;

use App\Models\GameTransaction;
use App\Models\PrepaidTransaction;
use App\Services\DuitkuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionStatusController extends Controller
{
    protected $duitkuService;

    public function __construct(DuitkuService $duitkuService)
    {
        $this->duitkuService = $duitkuService;
    }

    /**
     * Check transaction status
     * Used for polling from frontend
     */
    public function check(Request $request, string $trxid)
    {
        try {
            // Detect transaction type
            $transactionType = $this->detectTransactionType($trxid);
            
            if (!$transactionType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid transaction ID',
                ], 404);
            }

            // Get transaction from database
            $transaction = match($transactionType) {
                'game' => GameTransaction::where('trxid', $trxid)->first(),
                'prepaid' => PrepaidTransaction::where('trxid', $trxid)->first(),
                default => null
            };

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found',
                ], 404);
            }

            // Check if user owns this transaction
            if ($transaction->user_id !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            // If payment is already final (paid or failed), return from DB
            if (in_array($transaction->payment_status, ['paid', 'failed'])) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'trxid' => $transaction->trxid,
                        'payment_status' => $transaction->payment_status,
                        'status' => $transaction->status,
                        'service_name' => $transaction->service_name,
                        'amount' => $transaction->payment_amount,
                        'paid_at' => $transaction->paid_at,
                    ]
                ]);
            }

            // If still pending, check with Duitku API
            $duitkuStatus = $this->duitkuService->checkTransactionStatus($trxid);

            if ($duitkuStatus['success']) {
                $statusCode = $duitkuStatus['data']['statusCode'] ?? '01';
                
                // Update local status if changed
                if ($statusCode === '00' && $transaction->payment_status !== 'paid') {
                    // Payment confirmed by Duitku but callback not received yet
                    // Update status optimistically
                    $transaction->payment_status = 'paid';
                    $transaction->status = 'processing';
                    $transaction->paid_at = now();
                    $transaction->payment_reference = $duitkuStatus['data']['reference'] ?? null;
                    $transaction->save();

                    Log::info('Transaction Status Updated via Polling', [
                        'trxid' => $trxid,
                        'status' => 'paid',
                    ]);
                } elseif ($statusCode === '02' && $transaction->payment_status !== 'failed') {
                    // Payment canceled
                    $transaction->payment_status = 'failed';
                    $transaction->status = 'failed';
                    $transaction->save();

                    Log::info('Transaction Status Updated via Polling', [
                        'trxid' => $trxid,
                        'status' => 'failed',
                    ]);
                }
            }

            // Return current status
            return response()->json([
                'success' => true,
                'data' => [
                    'trxid' => $transaction->trxid,
                    'payment_status' => $transaction->payment_status,
                    'status' => $transaction->status,
                    'service_name' => $transaction->service_name,
                    'amount' => $transaction->payment_amount,
                    'paid_at' => $transaction->paid_at,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Transaction Status Check Error', [
                'trxid' => $trxid,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to check transaction status',
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
        }
        
        return null;
    }
}
