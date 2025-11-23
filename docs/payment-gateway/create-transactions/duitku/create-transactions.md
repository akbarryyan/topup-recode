# Duitku Payment Gateway — API Permintaan Transaksi

Dokumentasi ini menjelaskan cara melakukan permintaan transaksi (create payment) menggunakan API Duitku. Endpoint ini digunakan untuk membuat transaksi baru, menghasilkan payment URL, VA number, QR string, dan reference yang diperlukan pelanggan untuk melakukan pembayaran.

---

## ## Base URL

| Lingkungan     | URL                                                          |
| -------------- | ------------------------------------------------------------ |
| **Sandbox**    | `https://sandbox.duitku.com/webapi/api/merchant/v2/inquiry`  |
| **Production** | `https://passport.duitku.com/webapi/api/merchant/v2/inquiry` |

---

## ## Endpoint

POST /webapi/api/merchant/v2/inquiry

**Content-Type:** `application/json`

---

# Parameters (Request)

| Parameter          | Tipe    | Wajib | Deskripsi                                                    |
| ------------------ | ------- | ----- | ------------------------------------------------------------ |
| merchantCode       | string  | ✓     | Kode merchant dari Duitku                                    |
| paymentAmount      | integer | ✓     | Nominal transaksi                                            |
| merchantOrderId    | string  | ✓     | ID transaksi unik dari merchant                              |
| productDetails     | string  | ✓     | Deskripsi produk/jasa                                        |
| email              | string  | ✓     | Email pelanggan                                              |
| additionalParam    | string  | ✗     | Parameter tambahan                                           |
| paymentMethod      | string  | ✓     | Kode metode pembayaran (VA, VC, OL, SL, dll)                 |
| merchantUserInfo   | string  | ✗     | Username/email pelanggan pada sistem merchant                |
| customerVaName     | string  | ✓     | Nama VA untuk bank transfer                                  |
| phoneNumber        | string  | ✗     | Nomor HP pelanggan                                           |
| itemDetails        | array   | ✗     | Detail barang                                                |
| customerDetail     | object  | ✗     | Detail pelanggan (billing/shipping)                          |
| returnUrl          | string  | ✓     | Redirect setelah transaksi selesai                           |
| callbackUrl        | string  | ✓     | Callback dari Duitku                                         |
| signature          | string  | ✓     | MD5(merchantCode + merchantOrderId + paymentAmount + apiKey) |
| expiryPeriod       | integer | ✗     | Masa berlaku transaksi (menit)                               |
| accountLink        | object  | ✗     | Khusus OL/SL                                                 |
| creditCardDetail   | object  | ✗     | Khusus kartu kredit                                          |
| isSubscription     | boolean | ✗     | Menandakan subscription CC                                   |
| subscriptionDetail | object  | ✗     | Detail subscription                                          |

---

## Format itemDetails (opsional)

| Parameter | Tipe    | Deskripsi |
| --------- | ------- | --------- |
| name      | string  | Nama item |
| price     | integer | Harga     |
| quantity  | integer | Jumlah    |

---

## Format customerDetail (opsional)

### customerDetail

| Field           | Tipe   |
| --------------- | ------ |
| firstName       | string |
| lastName        | string |
| email           | string |
| phoneNumber     | string |
| billingAddress  | object |
| shippingAddress | object |

### billingAddress / shippingAddress

| Field       | Tipe   |
| ----------- | ------ |
| firstName   | string |
| lastName    | string |
| address     | string |
| city        | string |
| postalCode  | string |
| phone       | string |
| countryCode | string |

---

# Contoh Request (PHP)

```php
<?php
$merchantCode = 'DXXXXX';
$apiKey = 'XXXXXXXXXX7968XXXXXXXXXFB05332AF';

$paymentAmount = 40000;
$paymentMethod = 'VC';
$merchantOrderId = time() . '';
$productDetails = 'Tes pembayaran menggunakan Duitku';
$email = 'test@test.com';
$phoneNumber = '08123456789';
$customerVaName = 'John Doe';
$callbackUrl = 'https://example.com/callback';
$returnUrl = 'https://example.com/return';
$expiryPeriod = 10;

$signature = md5($merchantCode . $merchantOrderId . $paymentAmount . $apiKey);

$address = [
    'firstName' => 'John',
    'lastName' => 'Doe',
    'address' => 'Jl. Kembangan Raya',
    'city' => 'Jakarta',
    'postalCode' => '11530',
    'phone' => $phoneNumber,
    'countryCode' => 'ID'
];

$customerDetail = [
    'firstName' => 'John',
    'lastName' => 'Doe',
    'email' => $email,
    'phoneNumber' => $phoneNumber,
    'billingAddress' => $address,
    'shippingAddress' => $address
];

$itemDetails = [
    ['name' => 'Test Item 1', 'price' => 10000, 'quantity' => 1],
    ['name' => 'Test Item 2', 'price' => 30000, 'quantity' => 3]
];

$params = [
    'merchantCode' => $merchantCode,
    'paymentAmount' => $paymentAmount,
    'paymentMethod' => $paymentMethod,
    'merchantOrderId' => $merchantOrderId,
    'productDetails' => $productDetails,
    'customerVaName' => $customerVaName,
    'email' => $email,
    'phoneNumber' => $phoneNumber,
    'itemDetails' => $itemDetails,
    'customerDetail' => $customerDetail,
    'callbackUrl' => $callbackUrl,
    'returnUrl' => $returnUrl,
    'signature' => $signature,
    'expiryPeriod' => $expiryPeriod
];

$params_string = json_encode($params);
$url = 'https://sandbox.duitku.com/webapi/api/merchant/v2/inquiry';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($httpCode == 200) {
    echo $response;
} else {
    echo "Server Error " . $httpCode;
}

Contoh Response (Sukses)
{
  "merchantCode": "DXXXX",
  "reference": "DXXXXCX80TZJ85Q70QCI",
  "paymentUrl": "https://sandbox.duitku.com/topup/topupdirectv2.aspx?ref=XXXX",
  "vaNumber": "7007014001444348",
  "qrString": "00020101021226660014ID.DANA.WWW011893600...",
  "amount": "40000",
  "statusCode": "00",
  "statusMessage": "SUCCESS"
}
```

Parameter Response
| Parameter | Deskripsi |
| ------------- | ------------------------ |
| merchantCode | Kode merchant |
| reference | ID referensi transaksi |
| paymentUrl | URL pembayaran |
| vaNumber | Nomor Virtual Account |
| qrString | String QRIS |
| amount | Nominal pembayaran |
| statusCode | Kode status (00: sukses) |
| statusMessage | Pesan status |

Catatan Penting

merchantOrderId wajib unik setiap transaksi.

signature harus menggunakan format:

MD5(merchantCode + merchantOrderId + paymentAmount + apiKey)

Jika total itemDetails ≠ paymentAmount → transaksi gagal.

callbackUrl harus dapat diakses publik (HTTPS disarankan).

expiryPeriod minimal 5 menit (tergantung metode pembayaran).
