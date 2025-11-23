# Mutation System - Documentation

## Overview

Sistem mutasi saldo untuk merekam semua perubahan balance user (top-up, pengeluaran, dll).

## Database Schema

### Table: `mutations`

| Column         | Type          | Description                                         |
| -------------- | ------------- | --------------------------------------------------- |
| id             | bigint        | Primary key                                         |
| user_id        | bigint        | Foreign key ke users                                |
| type           | enum          | `credit` (masuk) atau `debit` (keluar)              |
| amount         | decimal(15,2) | Jumlah mutasi                                       |
| balance_before | decimal(15,2) | Saldo sebelum mutasi                                |
| balance_after  | decimal(15,2) | Saldo setelah mutasi                                |
| reference_type | string        | Class name (TopUpTransaction, GameTransaction, etc) |
| reference_id   | bigint        | ID transaksi terkait                                |
| description    | string        | Deskripsi mutasi                                    |
| notes          | text          | Catatan tambahan                                    |
| metadata       | json          | Data tambahan                                       |
| created_at     | timestamp     | Waktu dibuat                                        |
| updated_at     | timestamp     | Waktu diupdate                                      |

## Model: Mutation

### Properties

-   `$fillable`: All columns except id and timestamps
-   `$casts`: amount, balance_before, balance_after as decimal:2, metadata as array

### Relations

-   `user()`: BelongsTo User
-   `reference()`: MorphTo (polymorphic to TopUpTransaction, GameTransaction, etc)

### Scopes

-   `credit()`: Filter by type = credit
-   `debit()`: Filter by type = debit
-   `forUser($userId)`: Filter by user_id

### Methods

-   `isCredit()`: Check if mutation is credit (income)
-   `isDebit()`: Check if mutation is debit (expense)
-   `getFormattedAmountAttribute()`: Get formatted amount with +/- sign
-   `getBadgeColorAttribute()`: Get badge color (emerald for credit, rose for debit)

### Static Method

```php
Mutation::record(
    userId: int,
    type: string,              // 'credit' or 'debit'
    amount: float,
    balanceBefore: float,
    balanceAfter: float,
    description: string,
    referenceType: string|null,
    referenceId: int|null,
    notes: string|null,
    metadata: array|null
)
```

## Usage Example

### Create Mutation on Top-Up Success

```php
use App\Models\Mutation;

// User top-up Rp 50.000
$user = User::find(1);
$balanceBefore = $user->balance; // 20000
$user->balance += 50000;
$balanceAfter = $user->balance; // 70000
$user->save();

// Record mutation
Mutation::record(
    userId: $user->id,
    type: 'credit',
    amount: 50000,
    balanceBefore: $balanceBefore,
    balanceAfter: $balanceAfter,
    description: 'Top Up Saldo',
    referenceType: TopUpTransaction::class,
    referenceId: $transaction->id,
    notes: 'Top up via QRIS',
    metadata: [
        'merchant_order_id' => 'TOPUP-123',
        'payment_method' => 'QRIS',
    ]
);
```

### Create Mutation on Game Purchase

```php
// User buy ML diamonds Rp 15.000
$user = User::find(1);
$balanceBefore = $user->balance; // 70000
$user->balance -= 15000;
$balanceAfter = $user->balance; // 55000
$user->save();

// Record mutation
Mutation::record(
    userId: $user->id,
    type: 'debit',
    amount: 15000,
    balanceBefore: $balanceBefore,
    balanceAfter: $balanceAfter,
    description: 'Mobile Legends 100 Diamond',
    referenceType: GameTransaction::class,
    referenceId: $gameTransaction->id,
    notes: 'Pembelian diamond berhasil',
    metadata: [
        'game' => 'Mobile Legends',
        'item' => '100 Diamond',
        'user_id' => '12345678',
    ]
);
```

## View Display

### Tab Mutasi di Profile

Menampilkan tabel dengan kolom:

-   ID
-   Tipe (badge: Masuk/Keluar)
-   Deskripsi (+ notes)
-   Jumlah (dengan warna: hijau untuk credit, merah untuk debit)
-   Saldo Sebelum
-   Saldo Setelah
-   Tanggal & Waktu

### Features

-   ✅ Real-time mutation recording
-   ✅ Polymorphic relations to transactions
-   ✅ Balance tracking (before/after)
-   ✅ Metadata storage for additional info
-   ✅ Formatted display with colors
-   ✅ Search & filter capabilities (ready for implementation)

## Integration Status

### ✅ Completed

1. Migration created and executed
2. Model with all methods and relations
3. Integration with TopUpController callback (credit type)
4. Integration with GameOrderController (debit type)
5. Integration with PrepaidOrderController (debit type)
6. ProfileController updated with filter/search/pagination
7. View updated with filter UI and pagination controls
8. User model relation added
9. Routes for game and prepaid orders added

### Filter/Search Features

-   Filter by mutation type (credit/debit)
-   Search by description or notes
-   Date range filter (from/to)
-   Pagination with 20 items per page
-   Query string preserved in pagination

### 🔄 Ready for Future

1. Export mutations to PDF/Excel
2. Mutation categories/tags
3. Admin mutation management
4. Mutation analytics/charts

## Testing

### Test Data Created

```
User ID: 1
- Mutation #1: +Rp 20.000 (Top Up via QRIS)
- Mutation #2: +Rp 100.000 (Top Up via Bank Transfer)
- Mutation #3: -Rp 5.000 (ML 100 Diamond)

Current Balance: Rp 115.000
```

### Verification

```bash
# Check mutations count
php artisan tinker --execute="echo \App\Models\Mutation::count();"

# Check user balance
php artisan tinker --execute="echo \App\Models\User::find(1)->balance;"

# Check latest mutation
php artisan tinker --execute="echo json_encode(\App\Models\Mutation::latest()->first()->toArray(), JSON_PRETTY_PRINT);"
```

## Notes

-   Always record mutation AFTER updating user balance
-   Use DB transactions to ensure data consistency
-   Store useful metadata for audit trail
-   Balance before/after helps verify accuracy
