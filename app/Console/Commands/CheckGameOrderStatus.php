<?php

namespace App\Console\Commands;

use App\Models\GameTransaction;
use App\Services\VipResellerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckGameOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:check-status {--trxid= : Check specific transaction by trxid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update game order status from VIP Reseller provider';

    /**
     * Execute the console command.
     */
    public function handle(VipResellerService $vipResellerService)
    {
        $specificTrxid = $this->option('trxid');

        // Get transactions that need status check
        // Status: processing (waiting for provider to complete)
        // Must have provider_trxid (already sent to provider)
        // payment_status must be paid
        $query = GameTransaction::where('payment_status', 'paid')
            ->whereNotNull('provider_trxid')
            ->whereIn('status', ['processing', 'waiting']);

        if ($specificTrxid) {
            $query->where('trxid', $specificTrxid);
        }

        $transactions = $query->get();

        if ($transactions->isEmpty()) {
            $this->info('No pending game transactions to check.');
            return Command::SUCCESS;
        }

        $this->info("Found {$transactions->count()} transaction(s) to check...");

        $updated = 0;
        $failed = 0;

        foreach ($transactions as $transaction) {
            $this->line("Checking: {$transaction->trxid} (Provider: {$transaction->provider_trxid})");

            try {
                $result = $vipResellerService->checkGameOrderStatus($transaction->provider_trxid);

                if ($result['success']) {
                    $providerData = $result['data'];
                    $providerStatus = $providerData['status'] ?? 'waiting';
                    
                    // Update provider fields
                    $transaction->provider_status = $providerStatus;
                    $transaction->provider_note = $providerData['note'] ?? $transaction->provider_note;
                    $transaction->provider_price = $providerData['price'] ?? $transaction->provider_price;

                    // Map provider status to our status
                    $newStatus = match(strtolower($providerStatus)) {
                        'success' => 'success',
                        'failed', 'error' => 'failed',
                        'waiting', 'processing' => 'processing',
                        default => 'processing',
                    };

                    $oldStatus = $transaction->status;
                    $transaction->status = $newStatus;
                    $transaction->save();

                    if ($oldStatus !== $newStatus) {
                        $this->info("  ✓ Updated: {$oldStatus} → {$newStatus}");
                        $updated++;
                    } else {
                        $this->line("  - No change: {$providerStatus}");
                    }

                    Log::info('Game Order Status Check Updated', [
                        'trxid' => $transaction->trxid,
                        'provider_trxid' => $transaction->provider_trxid,
                        'old_status' => $oldStatus,
                        'new_status' => $newStatus,
                        'provider_status' => $providerStatus,
                    ]);

                } else {
                    $this->error("  ✗ Failed: {$result['message']}");
                    $failed++;

                    Log::warning('Game Order Status Check Failed', [
                        'trxid' => $transaction->trxid,
                        'provider_trxid' => $transaction->provider_trxid,
                        'error' => $result['message'],
                    ]);
                }

            } catch (\Exception $e) {
                $this->error("  ✗ Error: {$e->getMessage()}");
                $failed++;

                Log::error('Game Order Status Check Exception', [
                    'trxid' => $transaction->trxid,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->newLine();
        $this->info("Summary: {$updated} updated, {$failed} failed");

        return Command::SUCCESS;
    }
}
