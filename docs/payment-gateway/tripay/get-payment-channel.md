Tripay – Get Payment Channel API

Request:

    Endpoint:
    Method GET
    Environment URL
    Sandbox https://tripay.co.id/api-sandbox/merchant/payment-channel
    Production https://tripay.co.id/api/merchant/payment-channel

    Headers
    | Key | Value | Keterangan |
    |---------------|------------------------|--------------------------------------------------|
    | Authorization | Bearer {api_key} | Ganti `{api_key}` dengan API Key merchant Anda |

Contoh Request (PHP cURL):

    <?php

    $apiKey = 'api_key_anda';

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_FRESH_CONNECT  => true,
    CURLOPT_URL            => 'https://tripay.co.id/api/merchant/payment-channel',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER         => false,
    CURLOPT_HTTPHEADER     => ['Authorization: Bearer '.$apiKey],
    CURLOPT_FAILONERROR    => false,
    CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
    ));

    $response = curl_exec($curl);
    $error = curl_error($curl);

    curl_close($curl);

    echo empty($error) ? $response : $error;

    Response
    Response Sukses
    {
    "success": true,
    "message": "Success",
    "data": [
        {
        "group": "Virtual Account",
        "code": "MYBVA",
        "name": "Maybank Virtual Account",
        "type": "direct",
        "fee_merchant": {
            "flat": 4250,
            "percent": 0
        },
        "fee_customer": {
            "flat": 0,
            "percent": 0
        },
        "total_fee": {
            "flat": 4250,
            "percent": "0.00"
        },
        "minimum_fee": 4000,
        "maximum_fee": 4500,
        "minimum_amount": 10000,
        "maximum_amount": 10000000,
        "icon_url": "https://tripay.co.id/xxxxxxxxx.png",
        "active": true
        }
    ]
    }

Response Gagal:

    {
    "success": false,
    "message": "Invalid API Key"
    }

Struktur Data Response (Per Channel):

    | Field           | Tipe     | Keterangan                                |
    |-----------------|----------|--------------------------------------------|
    | group           | string   | Grup channel (Virtual Account, E-Wallet)   |
    | code            | string   | Kode channel                               |
    | name            | string   | Nama channel                               |
    | type            | string   | Jenis transaksi (direct, dll)              |
    | fee_merchant    | object   | Biaya untuk merchant                        |
    | fee_customer    | object   | Biaya untuk customer                        |
    | total_fee       | object   | Total biaya                                 |
    | minimum_fee     | integer  | Minimal biaya                               |
    | maximum_fee     | integer  | Maksimal biaya                              |
    | minimum_amount  | integer  | Minimal nominal transaksi                   |
    | maximum_amount  | integer  | Maksimal nominal transaksi                  |
    | icon_url        | string   | URL ikon metode pembayaran                  |
    | active          | boolean  | Status aktif / tidak                        |

Library Resmi PHP:

    Tripay SDK (ZeroSDev):
    https://github.com/zerosdev/tripay-sdk-php
