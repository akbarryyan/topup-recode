# Callback Troubleshooting Guide

## Issue: Payment Callback Not Working

### Symptoms

-   Payment status di Duitku sudah "paid/sukses"
-   Status di database masih "waiting" atau "pending"
-   Tidak ada log callback di Laravel

### Root Causes & Solutions

#### 1. Callback URL Salah ✅ FIXED

**Problem:**

-   Callback URL menggunakan `route()` helper yang menambahkan locale prefix
-   Example: `http://localhost:8000/id/payment/callback` (SALAH)
-   Should be: `http://localhost:8000/payment/callback` (BENAR)

**Solution Applied:**

```php
// GameOrderController.php
'callbackUrl' => url('/payment/callback'),  // ✅ Correct
'returnUrl' => url('/invoices'),             // ✅ Correct

// PrepaidOrderController.php
'callbackUrl' => url('/payment/callback'),  // ✅ Correct
'returnUrl' => url('/invoices'),             // ✅ Correct

// TopUpController.php
'callbackUrl' => url('/payment/callback'),  // ✅ Changed from /payment/duitku/callback
'returnUrl' => url('/invoices'),             // ✅ Changed from /payment/duitku/redirect
```

#### 2. Route Configuration ✅ VERIFIED

Routes are correctly outside locale group:

```php
// routes/web.php
Route::post('/payment/callback', [PaymentCallbackController::class, 'handle'])
    ->middleware('duitku.ip')
    ->name('payment.callback');

Route::any('/invoices', function () {...})->name('invoices');
```

#### 3. PaymentCallbackController ✅ VERIFIED

Unified callback handler supports all transaction types:

-   `GAME-*` → Game transactions
-   `PREPAID-*` → Prepaid transactions
-   `TOPUP-*` → TopUp transactions

### How to Test Callback

#### Method 1: Manual Test with Postman/cURL

```bash
POST http://localhost:8000/payment/callback
Content-Type: application/x-www-form-urlencoded

merchantCode=DS26199
amount=50000
merchantOrderId=GAME-1764257960-5755
signature=<MD5_SIGNATURE>
resultCode=00
reference=DS261992524XXXXXX
```

**Calculate Signature:**

```php
$signature = md5($merchantCode . $amount . $merchantOrderId . $apiKey);
// Example: md5('DS26199' . '50000' . 'GAME-1764257960-5755' . 'your-api-key')
```

#### Method 2: Check Logs

```bash
# Tail Laravel logs
tail -f storage/logs/laravel.log

# Search for callback logs
grep "Duitku Unified Callback" storage/logs/laravel.log
grep "Game Payment Success" storage/logs/laravel.log
```

#### Method 3: Database Check

```sql
SELECT trxid, status, payment_status, paid_at
FROM game_transactions
WHERE trxid = 'GAME-1764257960-5755';

-- After successful callback:
-- payment_status: 'paid'
-- status: 'processing'
-- paid_at: '2025-11-27 12:00:00'
```

### Common Issues

#### Issue: No Callback Received

**Possible Causes:**

1. **Local Development** - Duitku cannot reach `localhost`

    - Solution: Use ngrok or similar tunneling service
    - `ngrok http 8000`
    - Update callback URL to ngrok URL

2. **IP Whitelist** - Request blocked by middleware

    - Check middleware: `app/Http/Middleware/DuitkuIpWhitelist.php`
    - Add test IP if needed for local testing

3. **Signature Mismatch** - Invalid signature calculation
    - Check API key in `.env` (DUITKU_MERCHANT_KEY)
    - Verify signature calculation matches Duitku docs

#### Issue: Callback Received But Status Not Updated

**Check:**

1. Transaction exists in database with correct `trxid`
2. `resultCode` is `'00'` (string, not integer)
3. No exceptions in logs
4. Idempotency check not blocking (status already 'paid')

### Testing on Production

1. **Update .env:**

```env
DUITKU_ENVIRONMENT=production
DUITKU_MERCHANT_CODE=your_prod_code
DUITKU_MERCHANT_KEY=your_prod_key
DUITKU_API_KEY=your_prod_api_key
```

2. **Verify Callback URL:**

```php
// Should be production domain
https://yourdomain.com/payment/callback
```

3. **Whitelist Duitku IPs:**
   Ensure production IPs are in `DuitkuIpWhitelist` middleware

### Debug Checklist

-   [ ] Route `/payment/callback` exists and accessible
-   [ ] Callback URL uses `url()` not `route()`
-   [ ] PaymentCallbackController handles transaction type
-   [ ] Signature verification passes
-   [ ] Transaction found in database
-   [ ] No duplicate callback (idempotency check)
-   [ ] Logs show "Callback Received" and "Payment Success"
-   [ ] Database updated: payment_status='paid', status='processing'

### Expected Callback Flow

```
Duitku Server
    ↓
POST /payment/callback
    ↓
DuitkuIpWhitelist Middleware (verify IP)
    ↓
PaymentCallbackController@handle
    ↓
Verify Signature (MD5)
    ↓
Detect Transaction Type (GAME/PREPAID/TOPUP)
    ↓
Process Callback (update status)
    ↓
Return 200 OK to Duitku
```

### Status Update Logic

```php
if (resultCode === '00') {
    // Game/Prepaid
    transaction.payment_status = 'paid';
    transaction.status = 'processing';
    transaction.paid_at = now();

    // TopUp
    transaction.status = 'paid';
    user.balance += transaction.amount;
    create_mutation_record();
}
```

## Current Status

✅ Routes configured correctly
✅ Callback URL using absolute URL
✅ Unified callback handler ready
✅ Profile view restored from git
⏳ Waiting for real callback from Duitku to verify

## Next Steps

1. Create new test transaction
2. Complete payment in sandbox
3. Monitor logs for callback
4. Verify status updated in database
5. If still not working, use ngrok for local testing
