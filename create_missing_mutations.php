<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get all paid TopUp transactions
$transactions = App\Models\TopUpTransaction::where('status', 'paid')
    ->with('user', 'paymentMethod')
    ->get();

echo "Found {$transactions->count()} paid top-up transactions.\n\n";

$created = 0;
$skipped = 0;

foreach ($transactions as $transaction) {
    // Check if mutation already exists for this transaction
    $existingMutation = App\Models\Mutation::where('reference_type', 'App\Models\TopUpTransaction')
        ->where('reference_id', $transaction->id)
        ->first();
    
    if ($existingMutation) {
        echo "○ Skipped {$transaction->merchant_order_id} (mutation already exists)\n";
        $skipped++;
        continue;
    }
    
    try {
        // Calculate balance before (current balance - transaction amount)
        $balanceBefore = $transaction->user->balance - $transaction->amount;
        
        App\Models\Mutation::create([
            'user_id' => $transaction->user_id,
            'type' => 'credit',
            'amount' => $transaction->amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $transaction->user->balance,
            'description' => 'Top Up Saldo',
            'notes' => 'Top up via ' . ($transaction->paymentMethod->name ?? 'Payment Gateway') . ' - ' . $transaction->merchant_order_id,
            'reference_type' => 'App\Models\TopUpTransaction',
            'reference_id' => $transaction->id,
            'created_at' => $transaction->paid_at ?? $transaction->updated_at,
            'updated_at' => $transaction->paid_at ?? $transaction->updated_at,
        ]);
        
        echo "✓ Created mutation for {$transaction->merchant_order_id} (Rp " . number_format($transaction->amount, 0, ',', '.') . ")\n";
        $created++;
    } catch (\Exception $e) {
        echo "✗ Failed for {$transaction->merchant_order_id}: {$e->getMessage()}\n";
    }
}

echo "\n=== Summary ===\n";
echo "Created: {$created}\n";
echo "Skipped: {$skipped}\n";
echo "Total: {$transactions->count()}\n";
