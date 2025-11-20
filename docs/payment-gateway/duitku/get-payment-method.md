Duitku API – Get Payment Method

Endpoint:

    Method: POST
    Content-Type: application/json

Development (Sandbox):

    https://sandbox.duitku.com/webapi/api/merchant/paymentmethod/getpaymentmethod

Production:

    https://passport.duitku.com/webapi/api/merchant/paymentmethod/getpaymentmethod

Request Parameters:

    | Nama | Tipe | Wajib | Keterangan | Contoh |
    | ------------ | ----------- | ----- | ------------------------------------------------- | ------------------- |
    | merchantcode | string(50) | ✓ | Kode merchant dari Duitku | DXXXX |
    | amount | integer | ✓ | Nominal transaksi tanpa desimal atau titik | 10000 |
    | datetime | date | ✓ | Format: `yyyy-MM-dd HH:mm:ss` | 2022-01-25 16:23:08 |
    | signature | string(255) | ✓ | Sha256(merchantcode + amount + datetime + apiKey) | — |

Cara Membuat Signature:

    $merchantCode = "DS26199";
    $apiKey = "14d50783655851b11046017348fce140";

    $datetime = date('Y-m-d H:i:s');
    $paymentAmount = 10000;

    $signature = hash('sha256', $merchantCode . $paymentAmount . $datetime . $apiKey);

Contoh Request Body:

    {
    "merchantcode": "DS26199",
    "amount": 10000,
    "datetime": "2022-01-25 16:23:08",
    "signature": "hasil_sha256"
    }

Contoh Implementasi PHP (Curl):

    // Set kode merchant dan API key
    $merchantCode = "DS26199";
    $apiKey = "14d50783655851b11046017348fce140";

    // Datetime dan nominal transaksi
    $datetime = date('Y-m-d H:i:s');
    $paymentAmount = 10000;

    // Signature
    $signature = hash('sha256', $merchantCode . $paymentAmount . $datetime . $apiKey);

    $params = [
        'merchantcode' => $merchantCode,
        'amount' => $paymentAmount,
        'datetime' => $datetime,
        'signature' => $signature
    ];

    $params_string = json_encode($params);

    $url = 'https://sandbox.duitku.com/webapi/api/merchant/paymentmethod/getpaymentmethod';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($params_string)
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Execute
    $request = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode == 200) {
        $results = json_decode($request, true);
        print_r($results, false);
    } else {
        $request = json_decode($request);
        $error_message = "Server Error " . $httpCode ." ". $request->Message;
        echo $error_message;
    }

Response Parameters:

    Type: application/json
    | Nama            | Tipe   | Keterangan                     |
    |-----------------|--------|---------------------------------|
    | paymentFee | array | Daftar metode pembayaran |
    | responseCode | string | Kode respon |
    | responseMessage | string | Keterangan hasil respon |

Contoh Response:

    {
    "paymentFee": [
        {
        "paymentMethod": "VA",
        "paymentName": "MAYBANK VA",
        "paymentImage": "https://images.duitku.com/hotlink-ok/VA.PNG",
        "totalFee": "0"
        },
        {
        "paymentMethod": "BT",
        "paymentName": "PERMATA VA",
        "paymentImage": "https://images.duitku.com/hotlink-ok/PERMATA.PNG",
        "totalFee": "0"
        }
    ],
    "responseCode": "00",
    "responseMessage": "SUCCESS"
    }
