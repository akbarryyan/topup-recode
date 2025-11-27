# Routing Structure Documentation

## Overview

Aplikasi ini menggunakan **multi-locale routing** dengan pemisahan yang jelas antara **Admin Panel** dan **User Frontend**.

## Route Structure

### 1. Admin Routes (No Locale Prefix)

**File:** `routes/admin.php`  
**Prefix:** `/admin`  
**Middleware:** `web`, `admin`

Admin panel **TIDAK menggunakan** prefix locale (`/id` atau `/en`).

**Examples:**

```
GET  /admin                    → Dashboard
GET  /admin/login              → Admin Login
GET  /admin/profile            → Admin Profile
GET  /admin/users              → User Management
GET  /admin/game-services      → Game Services
GET  /admin/payment-gateways   → Payment Gateway Settings
```

### 2. User Routes (With Locale Prefix)

**File:** `routes/web.php`  
**Prefix:** `/{locale?}` where locale = `id|en`  
**Middleware:** `web`, `SetLocale`

Frontend untuk user dan guest **menggunakan** prefix locale.

**Examples:**

```
GET  /id                       → Homepage (Indonesian)
GET  /en                       → Homepage (English)
GET  /id/auth/login            → User Login (Indonesian)
GET  /en/auth/login            → User Login (English)
GET  /id/profile               → User Profile (Indonesian)
GET  /en/profile               → User Profile (English)
GET  /id/order/mobile-legends  → Order Page (Indonesian)
GET  /en/order/mobile-legends  → Order Page (English)
```

### 3. Payment Callback Routes (No Locale Prefix)

**File:** `routes/web.php`  
**Location:** Outside locale group

Payment gateway callbacks **MUST NOT** use locale prefix because external servers (Duitku, Tripay) send callbacks to exact URLs.

**Examples:**

```
POST /payment/callback          → Unified callback (Game, Prepaid, TopUp)
POST /payment/duitku/callback   → Legacy Duitku callback
GET  /payment/duitku/redirect   → User redirect after payment
ANY  /invoices                  → Invoice redirect
```

## Route Groups Breakdown

### A. Routes Outside All Groups

```php
// Locale switching
GET /locale/{locale}

// Payment callbacks (external requests)
POST /payment/callback
POST /payment/duitku/callback
GET  /payment/duitku/redirect

// Invoice redirect
ANY /invoices
```

### B. Routes Inside Locale Group `{locale?}`

```php
// Homepage
GET /{locale?}/

// Authentication (guest only)
GET  /{locale?}/auth/login
POST /{locale?}/auth/login
GET  /{locale?}/auth/register
POST /{locale?}/auth/register
POST /{locale?}/auth/logout

// Protected routes (auth required)
GET  /{locale?}/profile
POST /{locale?}/profile/update
POST /{locale?}/topup/create

// Guest checkout (no auth)
POST /{locale?}/order/game
POST /{locale?}/order/prepaid
GET  /{locale?}/order/{gameSlug}
GET  /{locale?}/order/prepaid/{brandSlug}

// Public pages
GET /{locale?}/check-invoice
GET /{locale?}/leaderboard
GET /{locale?}/article
GET /{locale?}/contact-us
POST /{locale?}/contact-us
```

### C. Admin Routes (Separate File)

```php
// Admin authentication
GET  /admin/login
POST /admin/login
POST /admin/logout

// Admin dashboard
GET /admin

// Admin profile
GET /admin/profile
PUT /admin/profile

// Management pages
GET /admin/users
GET /admin/game-services
GET /admin/prepaid-services
GET /admin/game-transactions
GET /admin/prepaid-transactions
GET /admin/payment-gateways
GET /admin/deposits
GET /admin/mutations
```

## Middleware Structure

### Global Middleware (All Routes)

-   `CheckMaintenanceMode` - Check if site is in maintenance
-   `TrackVisitor` - Track visitor statistics
-   `SetLocale` - Set locale from URL or session

### Admin Middleware

-   `web` - Web middleware group
-   `admin` - Admin role verification

### User Auth Middleware

-   `web` - Web middleware group
-   `auth` - User authentication check

### Guest Middleware

-   `guest` - Redirect if already logged in

### Special Middleware

-   `duitku.ip` - IP whitelist for payment callbacks

## Locale Handling

### Default Locale

```php
// If no locale specified, defaults to 'id'
/{locale?}/profile  → /id/profile
```

### Locale Detection Priority

1. URL parameter: `/{locale}/...`
2. Session value: `session('locale')`
3. Default: `'id'`

### Locale Switching

```php
// Change locale via route
GET /locale/{locale}

// Stores in session
session(['locale' => $locale]);

// Redirects back to previous page with new locale
```

## Authentication Flow

### Admin Login

```
GET  /admin/login          → Show admin login form
POST /admin/login          → Process admin login
     → Success: redirect to /admin (dashboard)
     → Failed: back with errors

POST /admin/logout         → Logout admin
     → redirect to /admin/login
```

### User Login

```
GET  /{locale}/auth/login  → Show user login form
POST /{locale}/auth/login  → Process user login
     → Success: redirect to /{locale}/profile
     → Failed: back with errors

POST /{locale}/auth/logout → Logout user
     → redirect to /{locale}/auth/login
```

## Route Naming Convention

### Admin Routes

```php
admin.dashboard
admin.profile.edit
admin.users.index
admin.game-services.index
```

### User Routes

```php
login
register
profile
profile.update
order.game
order.prepaid
```

### Payment Routes

```php
payment.callback
topup.callback
topup.redirect
invoices
```

## Important Notes

### ⚠️ DO NOT Add Locale to:

-   Admin routes (`/admin/*`)
-   Payment callbacks (`/payment/*`)
-   API endpoints (`/api/*`)
-   Invoice redirect (`/invoices`)

### ✅ MUST Use Locale for:

-   User frontend pages
-   Authentication pages (user)
-   Order pages
-   Profile pages
-   Public pages (article, contact, etc)

### Guest Checkout Support

Guest users can access order pages without authentication:

```php
// No auth required
GET  /{locale}/order/{gameSlug}
POST /{locale}/order/game

GET  /{locale}/order/prepaid/{brandSlug}
POST /{locale}/order/prepaid
```

## Testing Routes

### View All Routes

```bash
php artisan route:list
```

### Filter by Path

```bash
php artisan route:list --path=admin
php artisan route:list --path=profile
php artisan route:list --path=order
```

### Filter by Name

```bash
php artisan route:list --name=admin
php artisan route:list --name=order
```

### Filter by Method

```bash
php artisan route:list --method=POST
```

## Common Issues & Solutions

### Issue: 404 on Admin Pages

**Cause:** Trying to access admin with locale prefix  
**Solution:** Use `/admin/...` NOT `/id/admin/...`

### Issue: Payment Callback Not Working

**Cause:** Callback route inside locale group  
**Solution:** Move callback routes outside locale group

### Issue: Undefined method 'check'

**Cause:** Using `auth()->check()` instead of `auth()->user()`  
**Solution:** Replace with:

```php
// Wrong
if (!auth()->check()) { ... }

// Correct
if (!auth()->user()) { ... }
```

### Issue: Route Not Found with Locale

**Cause:** Route not inside locale group  
**Solution:** Move route inside `Route::group(['prefix' => '{locale?}'])`

## File Structure

```
routes/
├── web.php       → User frontend routes (with locale)
├── admin.php     → Admin panel routes (no locale)
└── console.php   → Console commands

bootstrap/
└── app.php       → Route loading configuration
```

## Summary

✅ **Admin routes:** `/admin/*` (no locale)  
✅ **User routes:** `/{locale}/*` (with locale)  
✅ **Payment callbacks:** `/payment/*` (no locale)  
✅ **Guest checkout:** Enabled on order routes  
✅ **Locale support:** `id` (Indonesian) and `en` (English)

**Result:** Clear separation between admin and user routing, proper locale handling, and working payment callbacks.
