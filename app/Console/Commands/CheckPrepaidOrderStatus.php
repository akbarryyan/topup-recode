<?php

namespace App\Console\Commands;

use App\Models\PrepaidTransaction;
use App\Services\VipResellerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckPrepaidOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prepaid:check-status {--trxid= : Check specific transaction by trxid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update prepaid order status from VIP Reseller provider';

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
        $query = PrepaidTransaction::where('payment_status', 'paid')
            ->whereNotNull('provider_trxid')
            ->whereIn('status', ['processing', 'waiting']);

        if ($specificTrxid) {
            $query->where('trxid', $specificTrxid);
        }

        $transactions = $query->get();

        if ($transactions->isEmpty()) {
            $this->info('No pending prepaid transactions to check.');
            return Command::SUCCESS;
        }

        $this->info("Found {$transactions->count()} prepaid transaction(s) to check...");

        $updated = 0;
        $failed = 0;

        foreach ($transactions as $transaction) {
            $this->line("Checking: {$transaction->trxid} (Provider: {$transaction->provider_trxid})");

            try {
                $result = $vipResellerService->checkPrepaidOrderStatus($transaction->provider_trxid);

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

                    Log::info('Prepaid Order Status Check Updated', [
                        'trxid' => $transaction->trxid,
                        'provider_trxid' => $transaction->provider_trxid,
                        'old_status' => $oldStatus,
                        'new_status' => $newStatus,
                        'provider_status' => $providerStatus,
                    ]);

                } else {
                    $this->error("  ✗ Failed: {$result['message']}");
                    $failed++;

                    Log::warning('Prepaid Order Status Check Failed', [
                        'trxid' => $transaction->trxid,
                        'provider_trxid' => $transaction->provider_trxid,
                        'error' => $result['message'],
                    ]);
                }

            } catch (\Exception $e) {
                $this->error("  ✗ Error: {$e->getMessage()}");
                $failed++;

                Log::error('Prepaid Order Status Check Exception', [
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
