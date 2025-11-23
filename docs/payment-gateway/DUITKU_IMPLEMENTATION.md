# Duitku Payment Gateway - Implementation Guide

## Overview

Implementasi lengkap untuk Callback dan Redirect Duitku sesuai dokumentasi resmi.

## Files Modified/Created

### 1. Middleware - IP Whitelist

**File**: `app/Http/Middleware/DuitkuIpWhitelist.php`

Middleware untuk memvalidasi IP outgoing dari Duitku:

-   **Production IPs**: 182.23.85.8, 182.23.85.9, 182.23.85.10, 182.23.85.13, 182.23.85.14, 103.177.101.184, 103.177.101.185, 103.177.101.186, 103.177.101.189, 103.177.101.190
-   **Sandbox IPs**: 182.23.85.11, 182.23.85.12, 103.177.101.187, 103.177.101.188
-   Skip validasi di environment `local` untuk testing

### 2. Service Layer

**File**: `app/Services/DuitkuService.php`

#### Updated Methods:

-   `verifyCallbackSignature()` - Validasi signature callback dengan formula: `MD5(merchantCode + amount + merchantOrderId + apiKey)`
-   `processCallback()` - Process dan extract callback data dari Duitku

### 3. Controller

**File**: `app/Http/Controllers/TopUpController.php`

#### Methods:

##### a. `callback()` - POST Endpoint

-   **URL**: `/payment/duitku/callback`
-   **Method**: POST
-   **Protected**: IP Whitelist Middleware
-   **Purpose**: Server-to-server payment confirmation

**Flow**:

1. Validate required parameters (merchantCode, amount, merchantOrderId, signature)
2. Verify signature using `verifyCallbackSignature()`
3. Find transaction by merchantOrderId
4. Update transaction status based on resultCode:
    - `00` = Success → Update to paid, add balance to user
    - `01` = Failed → Update to failed
5. Store callback data and reference
6. Return JSON response to Duitku

##### b. `redirect()` - GET Endpoint

-   **URL**: `/payment/duitku/redirect`
-   **Method**: GET
-   **Protected**: None (public)
-   **Purpose**: User return URL after payment

**Flow**:

1. Receive merchantOrderId, resultCode, reference from GET parameters
2. Find transaction (optional - for display only)
3. Redirect to profile with success/warning message
4. **IMPORTANT**: Do NOT update transaction status here (can be manipulated by user)

### 4. Routes

**File**: `routes/web.php`

```php
// Callback - Server to Server (POST)
Route::post('/payment/duitku/callback', [TopUpController::class, 'callback'])
    ->middleware('duitku.ip')
    ->name('topup.callback');

// Redirect - User Return (GET)
Route::get('/payment/duitku/redirect', [TopUpController::class, 'redirect'])
    ->name('topup.redirect');
```

### 5. Middleware Registration

**File**: `bootstrap/app.php`

```php
$middleware->alias([
    'duitku.ip' => \App\Http\Middleware\DuitkuIpWhitelist::class,
]);
```

## Callback Parameters

### Request from Duitku (POST - x-www-form-urlencoded)

| Parameter        | Type    | Description                               | Example                  |
| ---------------- | ------- | ----------------------------------------- | ------------------------ |
| merchantCode     | string  | Kode merchant                             | DXXXX                    |
| amount           | integer | Nominal transaksi                         | 150000                   |
| merchantOrderId  | string  | ID transaksi merchant (unique)            | TOPUP-1-1234567890-1234  |
| productDetail    | string  | Detail produk                             | Top Up Saldo - User Name |
| additionalParam  | string  | Parameter custom                          | -                        |
| paymentCode      | string  | Metode pembayaran                         | VC                       |
| resultCode       | string  | Status pembayaran (00=success, 01=failed) | 00                       |
| merchantUserId   | string  | Username/email pelanggan                  | user@example.com         |
| reference        | string  | Nomor referensi dari Duitku               | DXXXXCX80TXXX5Q70QCI     |
| signature        | string  | Validasi callback                         | md5(...)                 |
| publisherOrderId | string  | ID unik pembayaran dari Duitku            | MGUHWKJX3M1KMSQN5        |
| spUserHash       | string  | Hash user ShopeePay                       | xxxyyyzzz                |
| settlementDate   | string  | Estimasi penyelesaian (YYYY-MM-DD)        | 2023-07-25               |
| issuerCode       | string  | QRIS issuer code                          | 93600523                 |

## Signature Validation

### Formula

```
MD5(merchantCode + amount + merchantOrderId + apiKey)
```

### Example

```php
merchantCode = "D1234"
amount = 150000
merchantOrderId = "ORDER123"
apiKey = "ABCD1234"

signature = MD5("D1234150000ORDER123ABCD1234")
         = "506f88f1000dfb4a6541ff94d9b8d1e6"
```

### Implementation

```php
$calculatedSignature = md5($merchantCode . $amount . $merchantOrderId . $apiKey);
$isValid = hash_equals($calculatedSignature, $signature);
```

## Redirect Parameters

### Request from Duitku (GET)

| Parameter       | Type   | Description                                   | Example                 |
| --------------- | ------ | --------------------------------------------- | ----------------------- |
| merchantOrderId | string | Nomor transaksi merchant                      | TOPUP-1-1234567890-1234 |
| reference       | string | Nomor referensi dari Duitku                   | DXXXXCX80TXXX5Q70QCI    |
| resultCode      | string | Status transaksi (**JANGAN untuk update DB**) | 00                      |

**⚠️ IMPORTANT**:

-   Redirect parameters can be manipulated by users
-   **NEVER** update transaction status based on redirect
-   Use redirect only for UI feedback

## Security Best Practices

1. ✅ **Always validate signature** in callback
2. ✅ **Whitelist Duitku IPs** using middleware
3. ✅ **Use hash_equals()** for signature comparison (timing-safe)
4. ✅ **Update status only via callback**, never from redirect
5. ✅ **Log all callback attempts** for audit trail
6. ✅ **Use DB transactions** for atomic updates
7. ✅ **Never expose API key** in logs or responses

## Testing

### Local Testing (Development)

1. IP whitelist automatically skipped in `local` environment
2. Use tools like Postman to simulate callback
3. Test callback endpoint: `POST http://localhost/payment/duitku/callback`

### Callback Test Payload

```json
{
    "merchantCode": "D1234",
    "amount": "150000",
    "merchantOrderId": "TOPUP-1-1234567890-1234",
    "productDetail": "Top Up Saldo",
    "paymentCode": "VC",
    "resultCode": "00",
    "merchantUserId": "user@example.com",
    "reference": "DXXXXCX80TXXX5Q70QCI",
    "signature": "<calculated_md5>",
    "publisherOrderId": "MGUHWKJX3M1KMSQN5"
}
```

### Redirect Test URL

```
http://localhost/payment/duitku/redirect?merchantOrderId=TOPUP-1-1234567890-1234&resultCode=00&reference=DXXXXCX80TXXX5Q70QCI
```

## Transaction Flow

```
User Creates Top Up
    ↓
Generate merchantOrderId
    ↓
Call Duitku Create Transaction API
    ← callbackUrl: /payment/duitku/callback
    ← returnUrl: /payment/duitku/redirect
    ↓
Save to database (status: pending)
    ↓
Return payment URL to user
    ↓
User completes payment
    ↓
┌─────────────────────────────────────┐
│ Duitku sends POST to callback URL   │ ← Server-to-Server
│ - Validate IP whitelist             │
│ - Validate signature                │
│ - Update transaction status         │
│ - Add balance to user (if success)  │
└─────────────────────────────────────┘
    ↓
User redirected to returnUrl (GET)   ← Browser redirect
    ↓
Show success/failure message (UI only)
```

## Environment Variables

Add to `.env`:

```env
DUITKU_MERCHANT_CODE=your_merchant_code
DUITKU_API_KEY=your_api_key
DUITKU_API_URL=https://sandbox.duitku.com/webapi  # or production URL
```

## Monitoring & Logging

All callback and redirect activities are logged:

-   `Log::info()` - Normal operations
-   `Log::warning()` - Invalid signatures, unauthorized IPs
-   `Log::error()` - Exceptions and errors

Check logs at: `storage/logs/laravel.log`

## Support

For issues or questions:

1. Check documentation: `docs/payment-gateway/create-transactions/duitku/callback-redirect.md`
2. Review Duitku official documentation
3. Check Laravel logs for detailed error messages
