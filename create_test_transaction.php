<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::first();
$paymentMethod = App\Models\PaymentMethod::where('code', 'GQ')->first();

if (!$paymentMethod) {
    echo json_encode(['error' => 'Payment method GQ not found']);
    exit(1);
}

$trxid = 'PREPAID-' . time() . '-' . rand(1000, 9999);
$amount = 7542;

$transaction = App\Models\PrepaidTransaction::create([
    'trxid' => $trxid,
    'user_id' => $user->id,
    'service_code' => 'AXIS5',
    'service_name' => 'Axis 5.000',
    'data_no' => '081234567890',
    'email' => 'test@test.com',
    'whatsapp' => '081234567890',
    'status' => 'waiting',
    'payment_status' => 'pending',
    'price' => $amount,
    'amount' => $amount,
    'payment_amount' => $amount,
    'payment_fee' => 0,
    'payment_method_id' => $paymentMethod->id,
    'payment_method_code' => $paymentMethod->code,
    'balance' => $user->balance,
]);

echo json_encode([
    'success' => true,
    'trxid' => $trxid,
    'amount' => $amount,
    'merchant_code' => env('DUITKU_MERCHANT_CODE'),
    'api_key' => env('DUITKU_API_KEY'),
]);
