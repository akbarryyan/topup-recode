# Guest Checkout Feature

## Overview

Fitur Guest Checkout memungkinkan pengguna melakukan pemesanan tanpa harus login atau membuat akun terlebih dahulu. User hanya perlu mengisi email dan nomor WhatsApp untuk menerima notifikasi pesanan.

## Updated Files

### 1. Routes (`routes/web.php`)

```php
// Order routes - No authentication required (guest checkout)
Route::post('/order/game', [App\Http\Controllers\GameOrderController::class, 'store'])
    ->name('order.game.store');
Route::post('/order/prepaid', [App\Http\Controllers\PrepaidOrderController::class, 'store'])
    ->name('order.prepaid.store');

// Order form pages (inside locale group, no auth)
Route::get('/order/{gameSlug}', function ($locale = null, $gameSlug) {
    // No auth check - guests can view
    ...
})->name('order.game');

Route::get('/order/prepaid/{brandSlug}', function ($locale = null, $brandSlug) {
    // No auth check - guests can view
    ...
})->name('order.prepaid');
```

**Key Changes:**

-   Removed `auth` middleware from order routes
-   Both GET (form view) and POST (order submission) accessible without login

### 2. GameOrderController (`app/Http/Controllers/GameOrderController.php`)

**Changes:**

```php
// Get user (can be null for guests)
$user = $request->user(); // Can be null for guest users

// Calculate price based on role (guest = 'member')
$userRole = $user ? $user->role : 'member';
$servicePrice = $service->calculateFinalPrice($userRole);

// Create transaction with nullable user_id
$transaction = GameTransaction::create([
    'user_id' => $user ? $user->id : null, // Allow guest orders
    'balance' => $user ? $user->balance : 0,
    // ... other fields
]);

// Duitku customer name
'customerVaName' => $user ? $user->name : 'Guest',
```

**Guest User Handling:**

-   `user_id`: Set to `null` for guest orders
-   `balance`: Set to `0` (not used for payment gateway orders)
-   `userRole`: Defaults to `'member'` pricing tier
-   `customerVaName`: Displays as "Guest" in payment gateway

### 3. PrepaidOrderController (`app/Http/Controllers/PrepaidOrderController.php`)

**Changes:**

```php
// Get user (can be null for guests)
$user = $request->user(); // Can be null for guest users

// Calculate price based on role (guest = 'member')
$userRole = $user ? $user->role : 'member';
$servicePrice = $service->calculateFinalPrice($userRole);

// Create transaction with nullable user_id
$transaction = PrepaidTransaction::create([
    'user_id' => $user ? $user->id : null, // Allow guest orders
    'balance' => $user ? $user->balance : 0,
    // ... other fields
]);

// Duitku customer name
'customerVaName' => $user ? $user->name : 'Guest',
```

Same handling as GameOrderController for consistency.

### 4. Database Migrations

**`2025_11_19_000023_create_game_transactions_table.php`:**

```php
$table->foreignId('user_id')
    ->nullable()
    ->constrained()
    ->onDelete('set null'); // Allow guest orders
```

**`2025_11_19_000024_create_prepaid_transactions_table.php`:**

```php
$table->foreignId('user_id')
    ->nullable()
    ->constrained()
    ->onDelete('set null'); // Allow guest orders
```

**Key Changes:**

-   `user_id` now nullable (allows NULL values)
-   Foreign key constraint with `onDelete('set null')` instead of `cascade`
-   If user account deleted, guest transactions remain with user_id = null

## User Flow

### Guest Order Flow:

1. **Browse Products:** User visits website without logging in
2. **Select Product:** Choose game/prepaid service
3. **Fill Order Form:**
    - Game Account ID/Zone
    - Email (required for notifications)
    - WhatsApp (optional but recommended)
    - Select payment method
4. **Submit Order:** Creates transaction with `user_id = null`
5. **Payment:** Redirected to Duitku payment page
6. **Callback:** Payment gateway updates transaction status
7. **Confirmation:** Email/WhatsApp notification sent with order details

### Registered User Flow:

1. **Login:** User logs in to account
2. **Same as guest** BUT:
    - `user_id` linked to their account
    - Can view order history in profile
    - May get different pricing based on role (member/vip/reseller)
    - Balance recorded for reference

## Benefits

✅ **Lower Friction:** Users can purchase immediately without registration
✅ **Faster Checkout:** Skip account creation process
✅ **More Conversions:** Reduce cart abandonment
✅ **Email/WhatsApp Contact:** Still maintain customer communication

## Pricing Logic

Guest users automatically get **member pricing** tier:

```php
$userRole = $user ? $user->role : 'member';
$servicePrice = $service->calculateFinalPrice($userRole);
```

**Pricing Tiers:**

-   `member`: Default pricing (guest users get this)
-   `vip`: Discounted pricing for VIP members
-   `reseller`: Special pricing for resellers

## Database Structure

### Transactions with user_id = NULL (Guest Orders)

```sql
SELECT * FROM game_transactions WHERE user_id IS NULL;
SELECT * FROM prepaid_transactions WHERE user_id IS NULL;
```

### Transactions with user_id (Registered Users)

```sql
SELECT * FROM game_transactions WHERE user_id IS NOT NULL;
SELECT * FROM prepaid_transactions WHERE user_id IS NOT NULL;
```

## Order Tracking for Guests

**Current Implementation:**

-   Guest orders rely on email/WhatsApp for status updates
-   No order history page for guests (no login)

**Future Enhancement Ideas:**

1. Order tracking page by transaction ID
2. Email with tracking link
3. Check order status without login using:
    - Transaction ID + Email
    - Transaction ID + WhatsApp
4. Anonymous order history via browser session/cookies

## Testing Checklist

### ✅ Guest Checkout Test:

1. [ ] Open browser in incognito/private mode
2. [ ] Visit order page without login
3. [ ] Fill order form with email/WhatsApp
4. [ ] Submit order
5. [ ] Check database: `user_id` should be NULL
6. [ ] Complete payment
7. [ ] Verify callback updates transaction status
8. [ ] Check email notification received

### ✅ Registered User Test:

1. [ ] Login to account
2. [ ] Create order
3. [ ] Check database: `user_id` should be set
4. [ ] View order in profile/history
5. [ ] Verify pricing matches user role

## Migration Commands

```bash
# Fresh migration (WARNING: drops all data)
php artisan migrate:fresh --seed

# Or rollback specific migrations
php artisan migrate:rollback --step=2
php artisan migrate
```

## Security Considerations

✅ **Email Validation:** Required and validated
✅ **Rate Limiting:** Add to prevent abuse
⚠️ **Spam Protection:** Consider adding reCAPTCHA for guest orders
⚠️ **Order Limit:** May want to limit guest orders per IP/email

## Notifications

Both guest and registered users receive:

-   **Email:** Order confirmation, payment status, completion
-   **WhatsApp:** (if number provided) Status updates

Guest users **cannot**:

-   View order history (no profile access)
-   Track multiple orders easily
-   Get VIP/reseller pricing

Registered users **can**:

-   View all orders in profile
-   Get role-based pricing
-   Manage account settings

## Admin Panel

Admins can view all transactions including guest orders:

```php
// Filter guest orders
$guestOrders = GameTransaction::whereNull('user_id')->get();

// Display in admin panel
foreach($guestOrders as $order) {
    echo "Guest Order: " . $order->email;
}
```

## Summary

✅ Guest checkout enabled on all order routes
✅ Controllers handle null user safely
✅ Database schema supports nullable user_id
✅ Pricing defaults to member tier for guests
✅ Payment gateway integration unchanged
✅ Email/WhatsApp notifications work for guests

**Result:** Users can now purchase without creating an account, reducing friction and improving conversion rates while still maintaining order tracking via email/WhatsApp.
