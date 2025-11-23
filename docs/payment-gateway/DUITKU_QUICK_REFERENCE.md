# Duitku Quick Reference

## 🔗 URLs

### Callback (Server-to-Server)

```
POST /payment/duitku/callback
```

### Redirect (User Return)

```
GET /payment/duitku/redirect
```

---

## 🔐 Signature Calculation

```php
// Callback Signature
$signature = md5($merchantCode . $amount . $merchantOrderId . $apiKey);

// Example:
md5("D1234" . "150000" . "TOPUP-1-123-4567" . "YOUR_API_KEY")
```

---

## 📋 Callback Parameters (POST)

| Parameter       | Required | Example                    |
| --------------- | -------- | -------------------------- |
| merchantCode    | ✅       | D1234                      |
| amount          | ✅       | 150000                     |
| merchantOrderId | ✅       | TOPUP-1-123-4567           |
| signature       | ✅       | abc123...                  |
| resultCode      | ✅       | 00 (success) / 01 (failed) |
| reference       | ✅       | DXXXXCX80...               |
| productDetail   | ❌       | Top Up Saldo               |
| paymentCode     | ❌       | VC                         |

---

## 🌐 Redirect Parameters (GET)

| Parameter       | Description      |
| --------------- | ---------------- |
| merchantOrderId | Transaction ID   |
| resultCode      | 00 or 01         |
| reference       | Duitku reference |

---

## 🔢 Result Codes

| Code | Meaning            |
| ---- | ------------------ |
| 00   | Payment Success ✅ |
| 01   | Payment Failed ❌  |

---

## 🖥️ Whitelisted IPs

### Production:

```
182.23.85.8, 182.23.85.9, 182.23.85.10
182.23.85.13, 182.23.85.14
103.177.101.184, 103.177.101.185, 103.177.101.186
103.177.101.189, 103.177.101.190
```

### Sandbox:

```
182.23.85.11, 182.23.85.12
103.177.101.187, 103.177.101.188
```

---

## 🧪 Quick Test

### Test Callback (curl)

```bash
curl -X POST http://localhost/payment/duitku/callback \
  -d "merchantCode=D1234" \
  -d "amount=150000" \
  -d "merchantOrderId=TEST-123" \
  -d "resultCode=00" \
  -d "reference=REF123" \
  -d "signature=YOUR_CALCULATED_SIGNATURE"
```

### Test Redirect (browser)

```
http://localhost/payment/duitku/redirect?merchantOrderId=TEST-123&resultCode=00&reference=REF123
```

---

## 📊 Database Queries

### Check Transaction Status

```sql
SELECT merchant_order_id, status, amount, paid_at, reference
FROM top_up_transactions
WHERE merchant_order_id = 'TOPUP-1-123-4567';
```

### Check User Balance

```sql
SELECT id, name, email, balance
FROM users
WHERE id = 1;
```

### Recent Transactions

```sql
SELECT merchant_order_id, status, amount, created_at
FROM top_up_transactions
ORDER BY created_at DESC
LIMIT 10;
```

---

## 🔍 Log Locations

### Application Logs

```
storage/logs/laravel.log
```

### Search Logs

```bash
# All callback attempts
grep "Duitku Callback" storage/logs/laravel.log

# Success payments
grep "Payment Success" storage/logs/laravel.log

# Failed signatures
grep "Invalid Signature" storage/logs/laravel.log
```

---

## ⚙️ Environment Variables

```env
DUITKU_MERCHANT_CODE=D1234
DUITKU_API_KEY=your_api_key_here
DUITKU_API_URL=https://sandbox.duitku.com/webapi
APP_ENV=local  # or production
```

---

## 🚨 Common Issues

### "Bad Signature"

→ Check signature calculation
→ Verify API key
→ Ensure parameter order is correct

### "Unauthorized IP"

→ Set `APP_ENV=local` for testing
→ Verify IP is whitelisted in production

### Balance not updating

→ Check if `resultCode === '00'`
→ Verify callback was processed
→ Check logs for errors

---

## 📞 Routes Reference

```php
// Create top-up transaction
POST /topup/create

// Callback from Duitku (protected)
POST /payment/duitku/callback

// Redirect from Duitku (public)
GET /payment/duitku/redirect
```

---

## 🎯 Important Notes

1. **Never** update transaction status from redirect
2. **Always** validate signature in callback
3. **Use** database transactions for atomic updates
4. **Log** everything for debugging
5. **Test** in sandbox before production

---

## 📚 Full Documentation

-   Implementation: `docs/payment-gateway/DUITKU_IMPLEMENTATION.md`
-   Testing: `docs/payment-gateway/DUITKU_TESTING.md`
-   Summary: `docs/payment-gateway/DUITKU_INTEGRATION_SUMMARY.md`
