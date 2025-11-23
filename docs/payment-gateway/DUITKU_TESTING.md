# Duitku Integration - Testing Checklist

## Pre-Testing Setup

-   [ ] Pastikan `.env` sudah dikonfigurasi dengan credentials Duitku
-   [ ] Pastikan database migration sudah dijalankan
-   [ ] Pastikan `TopUpTransaction` model memiliki method `markAsPaid()` dan `markAsFailed()`
-   [ ] Pastikan `User` model memiliki kolom `balance`

## 1. IP Whitelist Middleware Testing

### Test 1: Local Environment (Should Pass)

```bash
# Set APP_ENV=local in .env
curl -X POST http://localhost/payment/duitku/callback \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "merchantCode=D1234"
```

**Expected**: Request should pass (IP check skipped in local environment)

### Test 2: Production Environment with Valid IP

**Expected**: Request from whitelisted IP should pass

### Test 3: Production Environment with Invalid IP

**Expected**: Should return 403 Forbidden

## 2. Callback Signature Validation

### Test 1: Valid Signature

```php
// Example test data
$merchantCode = "D1234";
$amount = 150000;
$merchantOrderId = "TOPUP-1-1234567890-1234";
$apiKey = "your_api_key";

// Calculate signature
$signature = md5($merchantCode . $amount . $merchantOrderId . $apiKey);

// Send POST request with calculated signature
```

**Expected**: Callback should be processed successfully

### Test 2: Invalid Signature

```php
// Send POST request with wrong signature
$signature = "invalid_signature_here";
```

**Expected**: Should return "Bad Signature" error

### Test 3: Missing Parameters

```php
// Send POST without required parameters
```

**Expected**: Should return "Bad Parameter" error

## 3. Callback Processing

### Test 1: Successful Payment (resultCode = '00')

```bash
curl -X POST http://localhost/payment/duitku/callback \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "merchantCode=D1234" \
  -d "amount=150000" \
  -d "merchantOrderId=TOPUP-1-1234567890-1234" \
  -d "resultCode=00" \
  -d "reference=DXXXXCX80TXXX5Q70QCI" \
  -d "signature=<calculated_signature>"
```

**Expected**:

-   [ ] Transaction status updated to 'paid'
-   [ ] User balance increased by transaction amount
-   [ ] Callback data saved in transaction
-   [ ] Reference updated
-   [ ] Return JSON: `{"success": true, "message": "Callback processed successfully"}`

**Database Checks**:

```sql
-- Check transaction status
SELECT status, reference, amount FROM top_up_transactions
WHERE merchant_order_id = 'TOPUP-1-1234567890-1234';

-- Check user balance
SELECT balance FROM users WHERE id = <user_id>;
```

### Test 2: Failed Payment (resultCode = '01')

```bash
curl -X POST http://localhost/payment/duitku/callback \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "merchantCode=D1234" \
  -d "amount=150000" \
  -d "merchantOrderId=TOPUP-1-1234567890-1234" \
  -d "resultCode=01" \
  -d "reference=DXXXXCX80TXXX5Q70QCI" \
  -d "signature=<calculated_signature>"
```

**Expected**:

-   [ ] Transaction status updated to 'failed'
-   [ ] User balance NOT increased
-   [ ] Callback data saved in transaction
-   [ ] Return JSON: `{"success": true, "message": "Callback processed successfully"}`

### Test 3: Transaction Not Found

```bash
# Use non-existent merchantOrderId
curl -X POST http://localhost/payment/duitku/callback \
  -d "merchantOrderId=NON_EXISTENT_ORDER"
```

**Expected**: Should return 500 error with "Failed to process callback"

## 4. Redirect Testing

### Test 1: Success Redirect

```
http://localhost/payment/duitku/redirect?merchantOrderId=TOPUP-1-1234567890-1234&resultCode=00&reference=DXXXXCX80TXXX5Q70QCI
```

**Expected**:

-   [ ] Redirect to profile page with success message
-   [ ] Flash message: "Pembayaran berhasil! Saldo akan segera ditambahkan."
-   [ ] Transaction status NOT changed (only callback can change status)

### Test 2: Failed Redirect

```
http://localhost/payment/duitku/redirect?merchantOrderId=TOPUP-1-1234567890-1234&resultCode=01&reference=DXXXXCX80TXXX5Q70QCI
```

**Expected**:

-   [ ] Redirect to profile page with warning message
-   [ ] Flash message: "Pembayaran pending atau gagal. Silakan cek status transaksi Anda."

### Test 3: Transaction Not Found

```
http://localhost/payment/duitku/redirect?merchantOrderId=NON_EXISTENT&resultCode=00
```

**Expected**:

-   [ ] Redirect to profile page with error message
-   [ ] Flash message: "Transaksi tidak ditemukan"

## 5. Complete Flow Testing

### End-to-End Test

1. **Create Transaction**

```bash
# Login as user first, then:
curl -X POST http://localhost/topup/create \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <token>" \
  -d '{
    "amount": 50000,
    "payment_method_id": 1
  }'
```

2. **Note the merchantOrderId from response**

3. **Simulate Callback from Duitku**

```bash
# Calculate signature first
# signature = MD5(merchantCode + amount + merchantOrderId + apiKey)

curl -X POST http://localhost/payment/duitku/callback \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "merchantCode=<your_merchant_code>" \
  -d "amount=<total_amount>" \
  -d "merchantOrderId=<from_step_1>" \
  -d "resultCode=00" \
  -d "reference=TEST_REF_123" \
  -d "signature=<calculated_signature>"
```

4. **Check Database**

```sql
-- Verify transaction updated
SELECT * FROM top_up_transactions WHERE merchant_order_id = '<from_step_1>';

-- Verify user balance increased
SELECT balance FROM users WHERE id = <user_id>;
```

5. **Test Redirect**

```
http://localhost/payment/duitku/redirect?merchantOrderId=<from_step_1>&resultCode=00&reference=TEST_REF_123
```

**Expected Results**:

-   [ ] Transaction created successfully
-   [ ] Callback processed correctly
-   [ ] Transaction status = 'paid'
-   [ ] User balance increased
-   [ ] Redirect shows success message

## 6. Security Testing

### Test 1: Signature Tampering

Try to change amount in callback without updating signature
**Expected**: Should reject with "Bad Signature"

### Test 2: Replay Attack

Send the same callback twice
**Expected**: Should handle gracefully (idempotent)

### Test 3: SQL Injection

Try injecting SQL in merchantOrderId
**Expected**: Should be sanitized by Laravel

## 7. Logging Verification

Check `storage/logs/laravel.log` for:

-   [ ] "Duitku Callback Received" - All callback attempts
-   [ ] "Duitku Callback - IP Validated" - IP validation success
-   [ ] "Duitku Callback - Payment Success" - Successful payments
-   [ ] "Duitku Callback - Payment Failed" - Failed payments
-   [ ] "Duitku Callback - Bad Parameter" - Missing parameters
-   [ ] "Duitku Callback - Invalid Signature" - Signature validation failures
-   [ ] "Duitku Redirect Received" - All redirect attempts

## 8. Error Handling Testing

### Test 1: Database Connection Error

Temporarily break database connection
**Expected**: Should return 500 error and log exception

### Test 2: User Not Found

**Expected**: Should handle gracefully (already handled by transaction relationship)

### Test 3: Duplicate Callback

Send same callback multiple times
**Expected**: Should be idempotent (safe to process multiple times)

## Postman Collection

Import this collection for easier testing:

```json
{
    "info": {
        "name": "Duitku Integration Tests",
        "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
    },
    "item": [
        {
            "name": "Callback - Success",
            "request": {
                "method": "POST",
                "url": "{{base_url}}/payment/duitku/callback",
                "body": {
                    "mode": "urlencoded",
                    "urlencoded": [
                        { "key": "merchantCode", "value": "{{merchant_code}}" },
                        { "key": "amount", "value": "150000" },
                        {
                            "key": "merchantOrderId",
                            "value": "{{merchant_order_id}}"
                        },
                        { "key": "resultCode", "value": "00" },
                        { "key": "reference", "value": "TEST_REF_123" },
                        { "key": "signature", "value": "{{signature}}" }
                    ]
                }
            }
        },
        {
            "name": "Redirect - Success",
            "request": {
                "method": "GET",
                "url": "{{base_url}}/payment/duitku/redirect?merchantOrderId={{merchant_order_id}}&resultCode=00&reference=TEST_REF_123"
            }
        }
    ]
}
```

## Notes

-   Always test in **sandbox environment** first
-   Use **local environment** for initial testing (IP check skipped)
-   Monitor logs during testing
-   Test both success and failure scenarios
-   Verify database changes after each test
-   Test signature calculation separately before integration testing

## Checklist Summary

-   [ ] All middleware tests pass
-   [ ] Signature validation works correctly
-   [ ] Successful callback updates transaction and user balance
-   [ ] Failed callback updates transaction only
-   [ ] Redirect works for both success and failure
-   [ ] All security tests pass
-   [ ] Logging is comprehensive
-   [ ] Error handling is robust
-   [ ] End-to-end flow works completely
