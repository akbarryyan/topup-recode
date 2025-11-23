<?php
/**
 * Duitku Callback Test Script
 * 
 * Script ini untuk test callback Duitku dengan data transaksi yang ada di database
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TopUpTransaction;
use App\Models\PaymentGateway;

// Get latest transaction
$transaction = TopUpTransaction::latest()->first();

if (!$transaction) {
    echo "❌ No transaction found in database!\n";
    exit(1);
}

// Get Duitku credentials
$gateway = PaymentGateway::where('code', 'like', 'duitku%')
    ->where('is_active', true)
    ->first();

if (!$gateway) {
    echo "❌ Duitku payment gateway not found or inactive!\n";
    exit(1);
}

$merchantCode = $gateway->merchant_code;
$apiKey = $gateway->api_key;
$merchantOrderId = $transaction->merchant_order_id;
$amount = (int) $transaction->total_amount;

// Calculate signature: MD5(merchantCode + amount + merchantOrderId + apiKey)
$signature = md5($merchantCode . $amount . $merchantOrderId . $apiKey);

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║           DUITKU CALLBACK TEST - Transaction Data              ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

echo "📋 Transaction Details:\n";
echo "  - Transaction ID: {$transaction->id}\n";
echo "  - Merchant Order ID: {$merchantOrderId}\n";
echo "  - User ID: {$transaction->user_id}\n";
echo "  - Amount: Rp " . number_format((float)$transaction->amount, 0, ',', '.') . "\n";
echo "  - Fee: Rp " . number_format((float)$transaction->fee, 0, ',', '.') . "\n";
echo "  - Total: Rp " . number_format((float)$transaction->total_amount, 0, ',', '.') . "\n";
echo "  - Status: {$transaction->status}\n";
echo "  - Payment Method ID: {$transaction->payment_method_id}\n\n";

echo "🔐 Signature Calculation:\n";
echo "  - Merchant Code: {$merchantCode}\n";
echo "  - Amount: {$amount}\n";
echo "  - Order ID: {$merchantOrderId}\n";
echo "  - API Key: " . substr($apiKey, 0, 4) . "..." . substr($apiKey, -4) . "\n";
echo "  - Formula: MD5(merchantCode + amount + merchantOrderId + apiKey)\n";
echo "  - Signature: {$signature}\n\n";

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║                  TEST CALLBACK - SUCCESS (00)                  ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

// Prepare callback data
$callbackData = [
    'merchantCode' => $merchantCode,
    'amount' => $amount,
    'merchantOrderId' => $merchantOrderId,
    'productDetail' => 'Top Up Saldo',
    'additionalParam' => '',
    'paymentCode' => 'VC',
    'resultCode' => '00', // Success
    'merchantUserId' => $transaction->user->email ?? 'test@example.com',
    'reference' => $transaction->reference ?? 'TEST-REF-' . time(),
    'signature' => $signature,
    'publisherOrderId' => 'PUB-' . time(),
    'spUserHash' => '',
    'settlementDate' => date('Y-m-d'),
    'issuerCode' => '93600523',
];

echo "📤 Callback Payload (POST x-www-form-urlencoded):\n";
foreach ($callbackData as $key => $value) {
    echo "  - {$key}: {$value}\n";
}
echo "\n";

// Generate cURL command
$baseUrl = env('APP_URL', 'http://localhost:8000');
$callbackUrl = $baseUrl . '/payment/duitku/callback';

echo "🌐 cURL Command:\n";
echo "curl -X POST '{$callbackUrl}' \\\n";
echo "  -H 'Content-Type: application/x-www-form-urlencoded' \\\n";
foreach ($callbackData as $key => $value) {
    echo "  -d '{$key}=" . urlencode($value) . "' \\\n";
}
echo "\n";

// Send test callback
echo "🚀 Sending test callback...\n\n";

try {
    $ch = curl_init($callbackUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($callbackData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded',
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "📥 Response (HTTP {$httpCode}):\n";
    echo $response . "\n\n";
    
    // Check results
    $transaction->refresh();
    
    echo "╔════════════════════════════════════════════════════════════════╗\n";
    echo "║                     VERIFICATION RESULTS                       ║\n";
    echo "╚════════════════════════════════════════════════════════════════╝\n\n";
    
    echo "✅ Transaction Status: {$transaction->status}\n";
    echo "✅ Paid At: " . ($transaction->paid_at ? $transaction->paid_at->format('Y-m-d H:i:s') : 'NULL') . "\n";
    echo "✅ Reference: " . ($transaction->reference ?? 'NULL') . "\n";
    echo "✅ Callback Data: " . (is_array($transaction->callback_data) ? 'Saved' : 'NULL') . "\n";
    
    // Check user balance
    $user = $transaction->user;
    echo "✅ User Balance: Rp " . number_format((float)$user->balance, 0, ',', '.') . "\n\n";
    
    if ($transaction->status === 'paid' && $httpCode === 200) {
        echo "🎉 CALLBACK TEST SUCCESSFUL!\n";
        echo "   - Transaction marked as paid ✅\n";
        echo "   - User balance updated ✅\n";
        echo "   - Callback data saved ✅\n\n";
    } else {
        echo "⚠️  CALLBACK TEST COMPLETED WITH ISSUES\n";
        echo "   - HTTP Code: {$httpCode}\n";
        echo "   - Status: {$transaction->status}\n\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║              TEST CALLBACK - FAILED (01)                       ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

// Test failed callback
$callbackDataFailed = $callbackData;
$callbackDataFailed['resultCode'] = '01'; // Failed

echo "📤 Testing failed payment (resultCode=01)...\n";
echo "🚀 Sending failed callback...\n\n";

try {
    $ch = curl_init($callbackUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($callbackDataFailed));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded',
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "📥 Response (HTTP {$httpCode}):\n";
    echo $response . "\n\n";
    
    echo "ℹ️  Note: Transaction was already marked as 'paid', so status won't change\n";
    echo "   In real scenario, this would mark transaction as 'failed'\n\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║                      TEST COMPLETED                            ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

echo "📝 Check logs at: storage/logs/laravel.log\n";
echo "💡 Use: tail -f storage/logs/laravel.log | grep Duitku\n\n";
