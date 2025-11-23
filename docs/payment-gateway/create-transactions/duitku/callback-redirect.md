Duitku – Callback & Redirect Documentation

# 1. Callback Overview

callbackUrl adalah URL yang kamu kirim saat melakukan request Create Transaction.
Setelah pelanggan melakukan pembayaran (berhasil / gagal), Duitku mengirimkan HTTP POST ke URL ini sebagai konfirmasi pembayaran.

Callback digunakan untuk:

Memvalidasi transaksi dari server ke server

Mengupdate status transaksi di database

Menyimpan reference dari Duitku

Jangan gunakan redirect untuk update status — HARUS lewat callback.

2. Callback HTTP Request

Method: POST

Type: x-www-form-urlencoded

3. Whitelist IP Outgoing Duitku
   Production
   182.23.85.8
   182.23.85.9
   182.23.85.10
   182.23.85.13
   182.23.85.14
   103.177.101.184
   103.177.101.185
   103.177.101.186
   103.177.101.189
   103.177.101.190

Sandbox
182.23.85.11
182.23.85.12
103.177.101.187
103.177.101.188

4. Callback Parameters
   Parameter Description Example
   merchantCode Kode merchant kamu DXXXX
   amount Jumlah nominal transaksi 150000
   merchantOrderId ID transaksi merchant (unik) abcde12345
   productDetail Detail produk Pembayaran untuk Toko Contoh
   additionalParam Parameter custom dari awal request –
   paymentCode Metode pembayaran VC
   resultCode Status pembayaran (00 success, 01 failed) 00
   merchantUserId Username/email pelanggan test@example.com
   reference Nomor referensi dari Duitku DXXXXCX80TXXX5Q70QCI
   signature Validasi callback md5(...)
   publisherOrderId ID unik pembayaran dari Duitku MGUHWKJX3M1KMSQN5
   spUserHash Hash user ShopeePay jika pembayaran via Shopee xxxyyyzzz
   settlementDate Estimasi penyelesaian (YYYY-MM-DD) 2023-07-25
   issuerCode QRIS issuer code 93600523
5. Signature Validation
   Formula
   MD5(merchantCode + amount + merchantOrderId + apiKey)

Contoh
merchantCode = D1234
amount = 150000
merchantOrderId = ORDER123
apiKey = ABCD1234

MD5("D1234150000ORDER123ABCD1234")
→ 506f88f1000dfb4a6541ff94d9b8d1e6

6. Callback PHP Example (Official Reference)
 <?php
 $apiKey = 'XXXXXXXXXX7968XXXXXXXXXFB05332AF'; // API key anda
 $merchantCode = $_POST['merchantCode'] ?? null;
 $amount = $_POST['amount'] ?? null;
 $merchantOrderId = $_POST['merchantOrderId'] ?? null;
 $productDetail = $_POST['productDetail'] ?? null;
 $additionalParam = $_POST['additionalParam'] ?? null;
 $paymentMethod = $_POST['paymentCode'] ?? null;
 $resultCode = $_POST['resultCode'] ?? null;
 $merchantUserId = $_POST['merchantUserId'] ?? null;
 $reference = $_POST['reference'] ?? null;
 $signature = $_POST['signature'] ?? null;
 $publisherOrderId = $_POST['publisherOrderId'] ?? null;
 $spUserHash = $_POST['spUserHash'] ?? null;
 $settlementDate = $_POST['settlementDate'] ?? null;
 $issuerCode = $_POST['issuerCode'] ?? null;

if (!empty($merchantCode) && !empty($amount) && !empty($merchantOrderId) && !empty($signature)) {
$params = $merchantCode . $amount . $merchantOrderId . $apiKey;
    $calcSignature = md5($params);

    if ($signature == $calcSignature) {
        // Callback tervalidasi
        // Update status transaksi di database

    } else {
        throw new Exception('Bad Signature');
    }

} else {
throw new Exception('Bad Parameter');
}
?>

7. Redirect Overview

returnUrl adalah URL tempat pengguna akan diarahkan kembali setelah:

pembayaran berhasil

pembayaran gagal

pengguna membatalkan pembayaran

Redirect hanya untuk tampilan UI,
tidak boleh dipakai untuk update status transaksi (karena bisa dimanipulasi user).

8. Redirect Request Format

Method: GET
Format:

https://merchant.com/redirect?merchantOrderId=abcde12345&resultCode=00&reference=DXXXXCX80TXXX5Q70QCI

9. Redirect Parameters
   Parameter Description Example
   merchantOrderId Nomor transaksi merchant abcde12345
   reference Nomor referensi dari Duitku DXXXXCX80TXXX5Q70QCI
   resultCode Status transaksi (jangan dipakai update database) 00

Catatan penting:
User bisa mengubah parameter redirect secara manual di URL.
Redirect = untuk informasi tampilan → bukan validasi status.

10. Redirect Usage Example (Frontend)
<?php
$merchantOrderId = $_GET['merchantOrderId'] ?? null;
$resultCode = $_GET['resultCode'] ?? null;
$reference = $_GET['reference'] ?? null;

// HANYA untuk menampilkan hasil ke user
// Jangan update database
?>

<h2>Transaksi Anda</h2>
<p>Order ID: <?= $merchantOrderId ?></p>
<p>Status: <?= $resultCode == '00' ? 'Berhasil' : 'Gagal / Pending' ?></p>
<p>Reference: <?= $reference ?></p>

11. Best Practices

Update status transaksi hanya dari callback

Simpan reference untuk pelacakan pembayaran

Selalu validasi signature

Whitelist seluruh IP outgoing Duitku

Jangan expose apiKey

Redirect = UI feedback, bukan data valid
