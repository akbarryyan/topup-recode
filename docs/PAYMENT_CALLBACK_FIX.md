# Payment Callback & Route Fix

## Masalah yang Ditemukan

1. **URL Invoices tanpa locale prefix** - URL redirect dari payment gateway: `http://localhost:8000/invoices?merchantOrderId=GAME-1764257180-5071&resultCode=00` tidak memiliki `/id` atau `/en`

2. **Status game_transactions tidak terupdate** - Setelah pembayaran sukses (paid), status di table `game_transactions` tidak otomatis berubah dari `waiting` menjadi `processing`

3. **Route callback menggunakan route() helper** - GameOrderController dan PrepaidOrderController menggunakan `route()` yang menghasilkan URL dengan locale prefix

## Perubahan yang Dilakukan

### 1. Route Structure Reorganization

**File:** `routes/web.php`

#### Payment Routes (Di Luar Locale Group)

```php
// Payment Callback Routes (MUST be outside locale group - no /id or /en prefix)
Route::post('/payment/duitku/callback', ...) // URL: /payment/duitku/callback
Route::post('/payment/callback', ...)        // URL: /payment/callback
Route::get('/payment/duitku/redirect', ...)  // URL: /payment/duitku/redirect
Route::any('/invoices', ...)                 // URL: /invoices
```

#### User Routes (Di Dalam Locale Group)

```php
Route::group(['prefix' => '{locale?}', 'where' => ['locale' => 'id|en']], function () {
    Route::get('/profile', ...)              // URL: /{locale}/profile
    Route::post('/order/game', ...)          // URL: /{locale}/order/game
    Route::post('/order/prepaid', ...)       // URL: /{locale}/order/prepaid
    ...
});
```

### 2. Invoices Route Handler

**Sebelum:**

```php
Route::any('/invoices', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    return redirect()->route('profile', ['tab' => 'transactions']);
})->name('invoices');
```

**Sesudah:**

```php
Route::any('/invoices', function () {
    if (!auth()->check()) {
        $locale = session('locale', 'id');
        return redirect()->route('login', ['locale' => $locale]);
    }
    // Redirect dengan locale prefix
    $locale = session('locale', 'id');
    return redirect()->to('/' . $locale . '/profile?' . http_build_query(['tab' => 'transactions']));
})->name('invoices');
```

### 3. Controller Updates

#### GameOrderController.php

**Sebelum:**

```php
'callbackUrl' => route('payment.callback'),
'returnUrl' => route('invoices'),
```

**Sesudah:**

```php
'callbackUrl' => url('/payment/callback'),
'returnUrl' => url('/invoices'),
```

#### PrepaidOrderController.php

**Sebelum:**

```php
'callbackUrl' => route('payment.callback'),
'returnUrl' => route('invoices'),
```

**Sesudah:**

```php
'callbackUrl' => url('/payment/callback'),
'returnUrl' => url('/invoices'),
```

## Cara Kerja Payment Callback

### Flow Diagram

```
User Bayar → Duitku → Payment Gateway
                ↓
         Callback (POST)
         /payment/callback
                ↓
    PaymentCallbackController
                ↓
        Detect Transaction Type
      (GAME- / PREPAID- / TOPUP-)
                ↓
         Update Status:
    - payment_status: 'paid'
    - status: 'processing'
    - paid_at: now()
                ↓
         Return URL (GET)
            /invoices
                ↓
    Redirect ke /{locale}/profile
```

### PaymentCallbackController Logic

```php
// Detect transaction type dari trxid prefix
if (str_starts_with($trxid, 'GAME-')) {
    return 'game';
} elseif (str_starts_with($trxid, 'PREPAID-')) {
    return 'prepaid';
} elseif (str_starts_with($trxid, 'TOPUP-')) {
    return 'topup';
}

// Update game transaction
if ($resultCode === '00') {
    $transaction->payment_status = 'paid';
    $transaction->status = 'processing'; // ✅ Status berubah otomatis
    $transaction->paid_at = now();
    $transaction->save();
}
```

## Testing Callback

### 1. Manual Test dengan Postman/cURL

```bash
POST http://localhost:8000/payment/callback
Content-Type: application/x-www-form-urlencoded

merchantCode=DS26199
amount=50000
merchantOrderId=GAME-1764257180-5071
signature=<calculated_md5>
resultCode=00
reference=DS261992524SBTNIXO0EBVYQ
```

### 2. Check Logs

```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Look for:
# - "Duitku Unified Callback Received"
# - "Game Payment Success"
# - "Game Transaction Already Paid"
```

### 3. Verify Database

```sql
SELECT trxid, status, payment_status, paid_at
FROM game_transactions
WHERE trxid = 'GAME-1764257180-5071';

-- Expected result after callback:
-- status: 'processing'
-- payment_status: 'paid'
-- paid_at: '2025-11-27 12:00:00'
```

## URL Structure

### Before Fix

-   ❌ `/id/payment/callback` (404 - tidak ditemukan)
-   ❌ `/id/invoices` (route tidak match)
-   ❌ Callback URL: `route('payment.callback')` → includes locale

### After Fix

-   ✅ `/payment/callback` (accessible dari Duitku)
-   ✅ `/invoices` (redirect ke `/{locale}/profile`)
-   ✅ Callback URL: `url('/payment/callback')` → no locale prefix

## Important Notes

1. **Route `/payment/callback` HARUS di luar locale group** - Duitku akan hit URL exact tanpa locale prefix

2. **Route `/invoices` HARUS di luar locale group** - User akan di-redirect dari payment gateway tanpa locale prefix, kemudian kita redirect lagi dengan locale

3. **Gunakan `url()` bukan `route()` untuk callback/return URL** - `url()` membuat absolute URL tanpa locale prefix

4. **Status akan otomatis update via callback** - Tidak perlu manual update, PaymentCallbackController akan handle

5. **Idempotent callback handling** - Callback dapat dipanggil multiple times, tapi status tidak akan berubah jika sudah `paid`

## Related Files

-   `routes/web.php` - Route definitions
-   `app/Http/Controllers/PaymentCallbackController.php` - Unified callback handler
-   `app/Http/Controllers/GameOrderController.php` - Game order creation
-   `app/Http/Controllers/PrepaidOrderController.php` - Prepaid order creation
-   `app/Http/Controllers/TopUpController.php` - TopUp with separate callback

## Status Flow

```
Game/Prepaid Transaction Status:
waiting → (payment) → processing → success/failed
   ↑                       ↑
   |                       |
Created               Callback paid

TopUp Transaction Status:
pending → (payment) → paid/failed
   ↑                    ↑
   |                    |
Created            Callback
```
