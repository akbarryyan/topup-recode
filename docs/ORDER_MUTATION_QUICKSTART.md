# 🎮 Game & Prepaid Order + Mutation System - Quick Guide

## 📋 Overview

Sistem order game dan prepaid yang terintegrasi dengan mutation tracking untuk mencatat semua perubahan saldo user.

## 🚀 Features Implemented

### ✅ Game Orders

-   Automatic balance deduction
-   Stock management
-   Mutation recording (type: debit)
-   Transaction history
-   Error handling & validation

### ✅ Prepaid Orders

-   Automatic balance deduction
-   Stock management
-   Mutation recording (type: debit)
-   Transaction history
-   Error handling & validation

### ✅ Mutation System

-   **Filter by type** (credit/debit)
-   **Search** by description/notes
-   **Date range** filtering
-   **Pagination** (20 items per page)
-   **Real-time** balance tracking

## 🔌 API Endpoints

### Game Order

```http
POST /order/game
Content-Type: application/json
Authorization: Bearer {token}

{
  "game": "Mobile Legends",
  "service_code": "ML_100_DM",
  "account_fields": {
    "user_id": "12345678",
    "zone_id": "1234"
  }
}
```

### Prepaid Order

```http
POST /order/prepaid
Content-Type: application/json
Authorization: Bearer {token}

{
  "brand": "Telkomsel",
  "service_code": "TSEL_50K",
  "phone_number": "081234567890"
}
```

### View Mutations

```http
GET /profile?tab=mutations
GET /profile?tab=mutations&type=credit
GET /profile?tab=mutations&search=Top%20Up
GET /profile?tab=mutations&date_from=2025-11-01&date_to=2025-11-30
```

## 📊 Mutation Types

| Type       | Description  | Example                    |
| ---------- | ------------ | -------------------------- |
| **credit** | Saldo masuk  | Top-up via payment gateway |
| **debit**  | Saldo keluar | Pembelian game/prepaid     |

## 🎯 Usage Examples

### Check User Balance

```php
$user = Auth::user();
echo "Saldo: Rp " . number_format($user->balance, 0, ',', '.');
```

### Get User Mutations

```php
$mutations = Mutation::forUser($user->id)
    ->orderBy('created_at', 'desc')
    ->paginate(20);
```

### Filter Mutations

```php
// Credit only (income)
$income = Mutation::forUser($user->id)->credit()->sum('amount');

// Debit only (expense)
$expense = Mutation::forUser($user->id)->debit()->sum('amount');

// Today's transactions
$today = Mutation::forUser($user->id)
    ->whereDate('created_at', today())
    ->get();
```

## 📁 File Structure

```
app/
├── Http/Controllers/
│   ├── GameOrderController.php      # Game order processing
│   ├── PrepaidOrderController.php   # Prepaid order processing
│   ├── ProfileController.php        # Mutation display with filters
│   └── TopUpController.php          # Top-up with mutation
├── Models/
│   ├── Mutation.php                 # Mutation model
│   ├── GameTransaction.php          # Game transaction
│   └── PrepaidTransaction.php       # Prepaid transaction
└── ...

resources/views/profile/
└── index.blade.php                  # Profile with mutations tab

routes/
└── web.php                          # Order routes

docs/
├── MUTATION_SYSTEM.md               # Detailed documentation
└── MUTATION_INTEGRATION_COMPLETE.md # Integration guide
```

## 🧪 Testing

### Test Game Order

```bash
php artisan tinker

# Create test order
$user = \App\Models\User::find(1);
$service = \App\Models\GameService::where('is_active', true)->first();

# Check balance before
echo "Before: Rp " . number_format($user->balance, 0, ',', '.');

# Simulate order (use API endpoint in real scenario)
# ...

# Check balance after
echo "After: Rp " . number_format($user->fresh()->balance, 0, ',', '.');

# Check mutation
$mutation = \App\Models\Mutation::latest()->first();
echo "Mutation: {$mutation->type} - {$mutation->description} - Rp " . number_format($mutation->amount, 0, ',', '.');
```

### Test Filters

1. Open browser: `http://localhost:8000/profile?tab=mutations`
2. Try filters:
    - Type: Select "Masuk" or "Keluar"
    - Search: Enter "Top Up" or game name
    - Date: Select date range
3. Click "Filter" button
4. Verify results

## 🔍 Database Queries

```sql
-- All mutations for user
SELECT * FROM mutations WHERE user_id = 1 ORDER BY created_at DESC;

-- Credit transactions only
SELECT * FROM mutations WHERE user_id = 1 AND type = 'credit';

-- Debit transactions only
SELECT * FROM mutations WHERE user_id = 1 AND type = 'debit';

-- Sum by type
SELECT type, SUM(amount) as total FROM mutations WHERE user_id = 1 GROUP BY type;

-- Today's mutations
SELECT * FROM mutations WHERE user_id = 1 AND DATE(created_at) = CURDATE();
```

## ⚠️ Important Notes

1. **Always use DB transactions** when creating orders to ensure data consistency
2. **Validate balance** before processing orders
3. **Record mutation** immediately after balance update
4. **Check stock** before allowing purchase
5. **Log all transactions** for audit purposes

## 📚 Documentation

-   [MUTATION_SYSTEM.md](./MUTATION_SYSTEM.md) - Complete mutation system documentation
-   [MUTATION_INTEGRATION_COMPLETE.md](./MUTATION_INTEGRATION_COMPLETE.md) - Integration details and testing guide

## 🎉 Status

**All features implemented and ready for testing!**

-   ✅ Game order with mutation recording
-   ✅ Prepaid order with mutation recording
-   ✅ Filter by type (credit/debit)
-   ✅ Search functionality
-   ✅ Date range filter
-   ✅ Pagination
-   ✅ Error handling
-   ✅ Balance validation
-   ✅ Stock management

## 🔜 Next Steps

1. Test all endpoints with real data
2. Connect with actual game/prepaid providers
3. Add mutation export (PDF/Excel)
4. Implement admin mutation management
5. Add analytics dashboard
