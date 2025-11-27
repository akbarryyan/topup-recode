Duitku – Cek Transaksi Documentation
# 1. Overview

Fitur Cek Transaksi digunakan untuk mengetahui status pembayaran berdasarkan merchantOrderId.
Cocok untuk:

Menampilkan status pembayaran ke pelanggan

Validasi status ketika callback diterima

Double-check status pembayaran untuk keamanan

Melakukan pengecekan manual dari sisi server

Cek transaksi direkomendasikan digunakan bersamaan dengan callback untuk memastikan status pembayaran benar-benar valid dari server Duitku.

2. Endpoint Cek Transaksi
Environment	URL
Sandbox	https://sandbox.duitku.com/webapi/api/merchant/transactionStatus
Production	https://passport.duitku.com/webapi/api/merchant/transactionStatus
3. Request Details

Method: POST

Type: application/json

Meski dokumentasi awal menyebut x-www-form-urlencoded, contoh resmi Duitku menggunakan JSON POST.

4. Request Parameters
Parameter	Description	Example
merchantCode	Kode merchant dari dashboard Duitku	DXXXX
merchantOrderId	ID transaksi merchant yang ingin dicek	abcde12345
signature	MD5 hash untuk verifikasi request	md5(merchantCode + merchantOrderId + apiKey)
5. Signature Formula
MD5(merchantCode + merchantOrderId + apiKey)


Contoh:

merchantCode = D1234
merchantOrderId = ORDER789
apiKey = ABCD1234

MD5("D1234ORDER789ABCD1234")
→ 506f88f1000dfb4a6541ff94d9b8d1e6

6. Sample PHP Code (Official Reference)
<?php
$merchantCode = 'DXXXX'; // dari duitku
$apiKey = 'XXXXXXXXXX7968XXXXXXXXXFB05332AF'; // dari duitku
$merchantOrderId = 'abcde12345'; // dari merchant

$signature = md5($merchantCode . $merchantOrderId . $apiKey);

$params = array(
    'merchantCode' => $merchantCode,
    'merchantOrderId' => $merchantOrderId,
    'signature' => $signature
);

$params_string = json_encode($params);
$url = 'https://sandbox.duitku.com/webapi/api/merchant/transactionStatus';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($params_string)
));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

$request = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($httpCode == 200) {
    $results = json_decode($request, true);
    print_r($results, false);

    // Field contoh:
    // $results['merchantOrderId']
    // $results['reference']
    // $results['amount']
    // $results['fee']
    // $results['statusCode']
    // $results['statusMessage']

} else {
    $request = json_decode($request);
    $error_message = "Server Error " . $httpCode . " " . $request->Message;
    echo $error_message;
}
?>

7. Response Format
Contoh Response
{
  "merchantOrderId": "abcde12345",
  "reference": "DXXXXCX80TZJ85Q70QCI",
  "amount": "100000",
  "fee": "0.00",
  "statusCode": "00",
  "statusMessage": "SUCCESS"
}

8. Response Parameters
Parameter	Description
merchantOrderId	Order ID dari merchant
reference	Nomor referensi transaksi dari Duitku
amount	Nominal transaksi
fee	Biaya transaksi
statusCode	Status pembayaran:
• 00 = Success
• 01 = Pending
• 02 = Canceled
statusMessage	Pesan status pembayaran
9. Best Practices

Lakukan cek transaksi setelah menerima callback, untuk keamanan tambahan

Pastikan signature valid setiap request

Simpan reference untuk rekonsiliasi

Jangan panggil API terlalu sering (hindari spamming)

Gunakan retry dengan interval 3–10 detik bila status pending