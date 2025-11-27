# SetLocale Middleware Fix

## Issue

When accessing admin routes like `http://localhost:8000/admin/login`, the SetLocale middleware was redirecting to `http://localhost:8000/id/admin/login` or `http://localhost:8000/en/admin/login`, causing 404 errors.

## Root Cause

The `SetLocale` middleware was applying to ALL routes, including admin routes which should NOT have locale prefixes.

## Solution

Updated `app/Http/Middleware/SetLocale.php` to exclude specific path prefixes from locale handling.

## Changes Made

### Before

```php
public function handle(Request $request, Closure $next)
{
    $locale = $request->segment(1);
    $availableLocales = ['en', 'id'];

    if (in_array($locale, $availableLocales)) {
        App::setLocale($locale);
        Session::put('locale', $locale);
    } else {
        // Always redirects to locale-prefixed URL
        $sessionLocale = Session::get('locale', 'id');
        if ($request->path() !== 'locale/en' && ...) {
            return redirect('/' . $sessionLocale . '/' . $path);
        }
    }
    return $next($request);
}
```

### After

```php
public function handle(Request $request, Closure $next)
{
    $locale = $request->segment(1);
    $availableLocales = ['en', 'id'];

    // Paths excluded from locale handling
    $excludedPaths = [
        'admin',      // Admin panel
        'payment',    // Payment callbacks
        'locale',     // Locale switching
        'invoices',   // Invoice redirect
        'api',        // API endpoints
    ];

    // Skip locale handling for excluded paths
    $firstSegment = $request->segment(1);
    if (in_array($firstSegment, $excludedPaths)) {
        return $next($request);
    }

    // Apply locale handling only for user routes
    if (in_array($locale, $availableLocales)) {
        App::setLocale($locale);
        Session::put('locale', $locale);
    } else {
        $sessionLocale = Session::get('locale', 'id');
        return redirect('/' . $sessionLocale . '/' . $path);
    }
    return $next($request);
}
```

## Excluded Paths

The following path prefixes are excluded from locale handling:

| Path Prefix | Purpose                   | Example URLs                                    |
| ----------- | ------------------------- | ----------------------------------------------- |
| `/admin`    | Admin panel routes        | `/admin/login`, `/admin/dashboard`              |
| `/payment`  | Payment gateway callbacks | `/payment/callback`, `/payment/duitku/callback` |
| `/locale`   | Locale switching route    | `/locale/en`, `/locale/id`                      |
| `/invoices` | Invoice redirect          | `/invoices`                                     |
| `/api`      | API endpoints             | `/api/transaction/status/{id}`                  |

## Testing Results

### ✅ Admin Routes (No Locale)

```
http://localhost:8000/admin              → Works (Dashboard)
http://localhost:8000/admin/login        → Works (Login page)
http://localhost:8000/admin/users        → Works (User management)
```

### ✅ User Routes (With Locale)

```
http://localhost:8000/                   → Redirects to /id/
http://localhost:8000/id/                → Works (Homepage Indonesian)
http://localhost:8000/en/                → Works (Homepage English)
http://localhost:8000/id/auth/login      → Works (User login)
http://localhost:8000/en/profile         → Works (User profile)
```

### ✅ Payment Routes (No Locale)

```
http://localhost:8000/payment/callback   → Works (Callback handler)
http://localhost:8000/invoices           → Works (Invoice redirect)
```

## Benefits

✅ Admin panel accessible without locale prefix  
✅ Payment callbacks work properly  
✅ User routes still use locale prefix  
✅ No more unwanted redirects  
✅ Cleaner URL structure

## Related Files

-   `app/Http/Middleware/SetLocale.php` - Updated middleware
-   `routes/web.php` - User routes with locale
-   `routes/admin.php` - Admin routes without locale
-   `bootstrap/app.php` - Middleware registration

## Cache Clear Commands

After updating middleware, always clear cache:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```
