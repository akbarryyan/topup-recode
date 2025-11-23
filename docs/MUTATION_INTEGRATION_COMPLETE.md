# Mutation Integration Summary

## ✅ Completed Features

### 1. Game Order Integration

**File**: `app/Http/Controllers/GameOrderController.php`

**Features:**

-   Balance validation before purchase
-   Stock checking
-   Automatic balance deduction
-   Mutation recording with type='debit'
-   Transaction ID generation (GAME-{timestamp}-{random})
-   Stock decrement for limited items
-   Error handling with DB rollback

**Mutation Metadata:**

```php
[
    'trxid' => 'GAME-1234567890-1234',
    'game' => 'Mobile Legends',
    'service_code' => 'ML_100_DM',
    'user_id' => '12345678',
    'zone_id' => '1234',
]
```

**API Endpoint:**

```
POST /order/game
Authentication: Required

Request Body:
{
    "game": "Mobile Legends",
    "service_code": "ML_100_DM",
    "account_fields": {
        "user_id": "12345678",
        "zone_id": "1234"
    },
    "whatsapp": "6281234567890"
}

Response:
{
    "success": true,
    "message": "Pesanan berhasil dibuat",
    "data": {
        "trxid": "GAME-1234567890-1234",
        "status": "success",
        "service_name": "Mobile Legends 100 Diamond",
        "price": 15000,
        "balance_after": 85000
    }
}
```

---

### 2. Prepaid Order Integration

**File**: `app/Http/Controllers/PrepaidOrderController.php`

**Features:**

-   Balance validation before purchase
-   Stock checking
-   Automatic balance deduction
-   Mutation recording with type='debit'
-   Transaction ID generation (PREPAID-{timestamp}-{random})
-   Stock decrement for limited items
-   Error handling with DB rollback

**Mutation Metadata:**

```php
[
    'trxid' => 'PREPAID-1234567890-1234',
    'brand' => 'Telkomsel',
    'service_code' => 'TSEL_50K',
    'phone_number' => '081234567890',
]
```

**API Endpoint:**

```
POST /order/prepaid
Authentication: Required

Request Body:
{
    "brand": "Telkomsel",
    "service_code": "TSEL_50K",
    "phone_number": "081234567890",
    "whatsapp": "6281234567890"
}

Response:
{
    "success": true,
    "message": "Pesanan berhasil dibuat",
    "data": {
        "trxid": "PREPAID-1234567890-1234",
        "status": "success",
        "service_name": "Telkomsel 50.000",
        "price": 51000,
        "balance_after": 34000
    }
}
```

---

### 3. Mutation Filter & Search

**File**: `app/Http/Controllers/ProfileController.php`

**Query Parameters:**

-   `type`: Filter by mutation type (credit/debit)
-   `search`: Search in description or notes
-   `date_from`: Filter from date (YYYY-MM-DD)
-   `date_to`: Filter to date (YYYY-MM-DD)
-   `page`: Pagination page number

**Example URLs:**

```
# Show all mutations (paginated)
GET /profile?tab=mutations

# Filter by credit only
GET /profile?tab=mutations&type=credit

# Search for "Top Up"
GET /profile?tab=mutations&search=Top%20Up

# Date range filter
GET /profile?tab=mutations&date_from=2025-11-01&date_to=2025-11-30

# Combined filters
GET /profile?tab=mutations&type=debit&search=Mobile&date_from=2025-11-01
```

**Implementation:**

```php
// Build mutations query with filters
$mutationsQuery = Mutation::forUser($user->id);

// Filter by type
if ($request->filled('type') && in_array($request->type, ['credit', 'debit'])) {
    $mutationsQuery->where('type', $request->type);
}

// Search by description
if ($request->filled('search')) {
    $search = $request->search;
    $mutationsQuery->where(function($query) use ($search) {
        $query->where('description', 'like', "%{$search}%")
              ->orWhere('notes', 'like', "%{$search}%");
    });
}

// Filter by date range
if ($request->filled('date_from')) {
    $mutationsQuery->whereDate('created_at', '>=', $request->date_from);
}

if ($request->filled('date_to')) {
    $mutationsQuery->whereDate('created_at', '<=', $request->date_to);
}

// Paginate results
$mutations = $mutationsQuery
    ->orderBy('created_at', 'desc')
    ->paginate(20)
    ->withQueryString();
```

---

### 4. View Updates

**File**: `resources/views/profile/index.blade.php`

**New Filter Form:**

-   Search input with icon
-   Type dropdown (Semua/Masuk/Keluar)
-   Date from input
-   Date to input
-   Filter button (submit)
-   Reset button (clear filters)

**Pagination:**

-   Shows item range (e.g., "Menampilkan 1 - 20 dari 45 data")
-   Previous/Next buttons with disabled state
-   Current page indicator
-   Query string preservation

**Tab Activation:**

-   Supports URL query parameter `?tab=mutations`
-   JavaScript automatically opens correct tab
-   Useful for redirects after filtering

**Features:**

-   Real-time validation
-   Responsive design
-   Empty state with icon
-   Color-coded amounts (green for credit, red for debit)
-   Badge for mutation type
-   Formatted currency display

---

## Routes Added

```php
// In routes/web.php
Route::middleware('auth')->group(function () {
    // Game Order
    Route::post('/order/game', [GameOrderController::class, 'store'])
        ->name('order.game.store');

    // Prepaid Order
    Route::post('/order/prepaid', [PrepaidOrderController::class, 'store'])
        ->name('order.prepaid.store');
});
```

---

## Complete Transaction Flow

### 1. Top-Up (Credit)

```
User → Top-Up Form → DuitkuService → Duitku Payment
                                           ↓
                                      User Pays
                                           ↓
                                   Duitku Callback
                                           ↓
                               TopUpController::callback()
                                           ↓
                        ┌──────────────────┴────────────────────┐
                        ↓                                       ↓
              Update Transaction                      Update User Balance
                (status: paid)                         (+amount)
                        ↓                                       ↓
                        └──────────────────┬────────────────────┘
                                           ↓
                               Mutation::record()
                                    (type: credit)
```

### 2. Game Purchase (Debit)

```
User → Game Order Form → GameOrderController::store()
                                    ↓
                        Check Balance & Stock
                                    ↓
                        Create GameTransaction
                                    ↓
                        Update User Balance
                               (-amount)
                                    ↓
                        Mutation::record()
                            (type: debit)
                                    ↓
                        Decrement Stock
                                    ↓
                        Process with Provider
                                    ↓
                        Return Success
```

### 3. Prepaid Purchase (Debit)

```
User → Prepaid Order Form → PrepaidOrderController::store()
                                       ↓
                           Check Balance & Stock
                                       ↓
                           Create PrepaidTransaction
                                       ↓
                           Update User Balance
                                  (-amount)
                                       ↓
                           Mutation::record()
                               (type: debit)
                                       ↓
                           Decrement Stock
                                       ↓
                           Process with Provider
                                       ↓
                           Return Success
```

---

## Testing Guide

### Test Mutation Recording

**1. Test Top-Up (Credit):**

```bash
# Create a top-up transaction and pay via Duitku
# Mutation will be automatically recorded on payment success
```

**2. Test Game Order (Debit):**

```bash
# Using cURL or Postman
curl -X POST http://localhost:8000/order/game \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "game": "Mobile Legends",
    "service_code": "ML_100_DM",
    "account_fields": {
      "user_id": "12345678",
      "zone_id": "1234"
    }
  }'
```

**3. Test Prepaid Order (Debit):**

```bash
# Using cURL or Postman
curl -X POST http://localhost:8000/order/prepaid \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "brand": "Telkomsel",
    "service_code": "TSEL_50K",
    "phone_number": "081234567890"
  }'
```

**4. Test Filters:**

```bash
# Via browser or HTTP client
GET /profile?tab=mutations&type=credit
GET /profile?tab=mutations&type=debit
GET /profile?tab=mutations&search=Mobile
GET /profile?tab=mutations&date_from=2025-11-01&date_to=2025-11-30
```

**5. Verify Database:**

```bash
php artisan tinker

# Check mutations
\App\Models\Mutation::latest()->take(5)->get(['id', 'type', 'description', 'amount']);

# Check user balance
\App\Models\User::find(1)->balance;

# Count by type
\App\Models\Mutation::credit()->count();
\App\Models\Mutation::debit()->count();
```

---

## Error Handling

### Insufficient Balance

```json
{
    "success": false,
    "message": "Saldo tidak mencukupi. Silakan top up terlebih dahulu.",
    "data": {
        "required_balance": 15000,
        "current_balance": 5000,
        "shortage": 10000
    }
}
```

### Out of Stock

```json
{
    "success": false,
    "message": "Stok produk tidak tersedia."
}
```

### Service Not Found

```json
{
    "success": false,
    "message": "Service not found or unavailable"
}
```

---

## Next Steps (Optional Enhancements)

1. **Webhook Integration**: Connect with real game/prepaid providers
2. **Mutation Export**: PDF/Excel download functionality
3. **Mutation Analytics**: Charts for income/expense trends
4. **Admin Panel**: View and manage all user mutations
5. **Refund System**: Create reverse mutations for refunds
6. **Scheduled Reports**: Daily/weekly mutation summaries via email
