<?php

$merchantCode = 'DS26199'; // Lihat di .env DUITKU_MERCHANT_CODE
$apiKey = '14d50783655851b11046017348fce140';             // Lihat di .env DUITKU_API_KEY
$merchantOrderId = 'PREPAID-1764219389-4842';
$amount = '6930'; // Gunakan nominal integer (tanpa .00)

$signature = md5($merchantCode . $amount . $merchantOrderId . $apiKey);
echo $signature;