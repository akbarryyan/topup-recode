<?php

namespace App\Http\Controllers;

use App\Models\GameTransaction;
use App\Models\VipResellerSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VipResellerWebhookController extends Controller
{
    /**
     * Handle webhook callback from VIP Reseller
     * 
     * Whitelist IP: 178.248.73.218
     * Header: X-Client-Signature = md5(API_ID + API_KEY)
     */
    public function handle(Request $request)
    {
        Log::info('VIP Reseller Webhook Received', [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
            'ip' => $request->ip(),
        ]);

        // Verify signature from header
        $signature = $request->header('X-Client-Signature');
        $expectedSignature = $this->generateSignature();

        if (!$signature || $signature !== $expectedSignature) {
            Log::warning('VIP Reseller Webhook - Invalid Signature', [
                'received' => $signature,
                'expected' => $expectedSignature,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Invalid signature',
            ], 403);
        }

        // Get data from webhook
        $data = $request->input('data', $request->all());
        
        // Handle both formats: {data: {...}} or direct {...}
        if (isset($data['data']) && is_array($data['data'])) {
            $data = $data['data'];
        }

        $providerTrxid = $data['trxid'] ?? null;
        $status = $data['status'] ?? null;
        $note = $data['note'] ?? null;
        $price = $data['price'] ?? null;

        if (!$providerTrxid) {
            Log::error('VIP Reseller Webhook - Missing trxid', $data);
            return response()->json([
                'success' => false,
                'message' => 'Missing trxid',
            ], 400);
        }

        // Find transaction by provider_trxid
        $transaction = GameTransaction::where('provider_trxid', $providerTrxid)->first();

        if (!$transaction) {
            Log::warning('VIP Reseller Webhook - Transaction not found', [
                'provider_trxid' => $providerTrxid,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Transaction not found',
            ], 404);
        }

        // Update transaction with webhook data
        $oldStatus = $transaction->status;
        
        $transaction->provider_status = $status;
        $transaction->provider_note = $note;
        if ($price) {
            $transaction->provider_price = $price;
        }

        // Map provider status to transaction status
        $transaction->status = match(strtolower($status ?? '')) {
            'success' => 'success',
            'failed', 'error' => 'failed',
            'waiting', 'processing' => 'processing',
            default => $transaction->status,
        };

        $transaction->save();

        Log::info('VIP Reseller Webhook - Transaction Updated', [
            'trxid' => $transaction->trxid,
            'provider_trxid' => $providerTrxid,
            'old_status' => $oldStatus,
            'new_status' => $transaction->status,
            'provider_status' => $status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Webhook processed successfully',
        ]);
    }

    /**
     * Generate signature for verification
     */
    private function generateSignature(): string
    {
        $settings = VipResellerSetting::first();
        
        if (!$settings) {
            return '';
        }

        return md5($settings->api_id . $settings->api_key);
    }
}
