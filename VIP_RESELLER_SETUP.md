# Setup VIP Reseller API Integration

## Konfigurasi Sudah Selesai ✓

### 1. File Konfigurasi

-   ✅ `.env` - API credentials sudah dikonfigurasi
-   ✅ `config/services.php` - VIP Reseller config sudah ditambahkan
-   ✅ Migration `create_game_services_table` - Database table sudah dibuat

### 2. Model & Service

-   ✅ `app/Models/GameService.php` - Model untuk menyimpan layanan game
-   ✅ `app/Services/VipResellerService.php` - Service untuk handle API VIP Reseller

### 3. Controller & Routes

-   ✅ `app/Http/Controllers/Admin/GameServiceController.php` - CRUD controller
-   ✅ `routes/admin.php` - Routes sudah ditambahkan
-   ✅ Sidebar menu "Layanan Game" sudah ada

### 4. Views

-   ✅ `resources/views/admin/game-services/index.blade.php` - Halaman management layanan

---

## ⚠️ PENTING: Whitelist IP Address

Saat ini API mengembalikan error:

```
IP 114.5.212.161 tidak diizinkan
```

### Langkah-langkah Setup IP Whitelist:

1. **Login ke Dashboard VIP Reseller**

    - URL: https://vip-reseller.co.id/
    - Login dengan akun Anda

2. **Whitelist IP Address**

    - Masuk ke menu **Settings** atau **API Settings**
    - Tambahkan IP address berikut ke whitelist:
        ```
        114.5.212.161
        ```
    - Atau aktifkan **"Allow All IPs"** jika tersedia

3. **Verifikasi API Credentials**
    - API ID: `968EJsSc`
    - API Key: `baad6ab2dc32fd25b1a2f86505260433`
    - Sign (MD5): `9a46988cbe16225e58b4a2cda3357abb`

---

## Testing API Connection

Setelah IP sudah di-whitelist, test koneksi dengan command:

```bash
php artisan test:vip-api
```

Expected output jika berhasil:

```
Testing VIP Reseller API...

✓ API connection successful!
Message: Daftar layanan berhasil didapatkan.
Total services: 1500+

Sample data (first 3 services):
1. Mobile Legends - 14 Diamonds
   Code: ML14-S14
   Price: Rp 3,310
   Status: available
```

---

## Cara Menggunakan Sistem

### 1. Sync Layanan dari API

**Via Web Interface:**

1. Login ke admin panel
2. Buka menu **"Layanan Game"** di sidebar
3. Klik tombol **"Sync dari API"**
4. Pilih game tertentu atau sync semua game
5. Klik **"Mulai Sync"**

**Via Command Line:**

```bash
# Sync semua game
php artisan services:sync

# Sync game tertentu
php artisan services:sync --game="Mobile Legends"
```

### 2. Manage Layanan

Di halaman admin/game-services, Anda bisa:

-   ✅ Melihat semua layanan dalam DataTable (search, sort, pagination)
-   ✅ Toggle status Aktif/Nonaktif untuk setiap layanan
-   ✅ Menghapus layanan yang tidak diinginkan
-   ✅ Filter berdasarkan game

### 3. Auto Sync (Scheduled)

Tambahkan di `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Sync setiap hari jam 01:00 pagi
    $schedule->command('services:sync')->dailyAt('01:00');

    // Atau setiap 6 jam
    // $schedule->command('services:sync')->everySixHours();
}
```

Jalankan scheduler:

```bash
php artisan schedule:work
```

---

## API Endpoints Documentation

### Get Game Services

**Endpoint:** `POST https://vip-reseller.co.id/api/game-feature`

**Parameters:**
| Parameter | Type | Description | Required |
|-----------|------|-------------|----------|
| key | string | API Key Anda | Yes |
| sign | string | MD5(API_ID + API_KEY) | Yes |
| type | string | "services" | Yes |
| filter_type | string | "game" | No |
| filter_value | string | Nama game (e.g., "Mobile Legends") | No |
| filter_status | string | "available" atau "empty" | No |

**Example Response:**

```json
{
    "result": true,
    "data": [
        {
            "code": "ML14-S14",
            "game": "Mobile Legends",
            "name": "14 Diamonds ( 13 + 1 Bonus )",
            "price": {
                "basic": 3310,
                "premium": 3260,
                "special": 3235
            },
            "server": "1",
            "status": "available"
        }
    ],
    "message": "Daftar layanan berhasil didapatkan."
}
```

---

## Database Schema

### Table: `game_services`

| Column        | Type      | Description                            |
| ------------- | --------- | -------------------------------------- |
| id            | bigint    | Primary key                            |
| code          | string    | Kode unik layanan (e.g., ML14-S14)     |
| game          | string    | Nama game (e.g., Mobile Legends)       |
| name          | string    | Nama layanan (e.g., 14 Diamonds)       |
| price_basic   | integer   | Harga tier basic                       |
| price_premium | integer   | Harga tier premium                     |
| price_special | integer   | Harga tier special                     |
| server        | string    | Server ID (default: "1")               |
| status        | enum      | Status dari API: "available" / "empty" |
| is_active     | boolean   | Toggle manual admin (default: true)    |
| created_at    | timestamp | -                                      |
| updated_at    | timestamp | -                                      |

**Indexes:**

-   `code` (unique)
-   `game`
-   `status`

---

## Routes

| Method | URL                              | Name                        | Description           |
| ------ | -------------------------------- | --------------------------- | --------------------- |
| GET    | /admin/game-services             | admin.game-services.index   | List semua layanan    |
| POST   | /admin/game-services/sync        | admin.game-services.sync    | Sync dari API         |
| PATCH  | /admin/game-services/{id}/toggle | admin.game-services.toggle  | Toggle aktif/nonaktif |
| DELETE | /admin/game-services/{id}        | admin.game-services.destroy | Hapus layanan         |

---

## Next Steps

1. **Whitelist IP** di VIP Reseller dashboard ⚠️
2. Test API connection: `php artisan test:vip-api`
3. Sync layanan pertama kali via web interface
4. Lihat data di halaman admin/game-services
5. Integrate layanan ke halaman public (welcome page) untuk user

---

## Troubleshooting

### Error: "IP tidak diizinkan"

-   Whitelist IP Anda di VIP Reseller dashboard
-   Atau gunakan VPN/Proxy yang sudah ter-whitelist

### Error: "Invalid signature"

-   Cek API_ID dan API_KEY di `.env`
-   Sign harus MD5(API_ID + API_KEY)

### Error: "Connection timeout"

-   Cek koneksi internet
-   Pastikan firewall tidak block koneksi ke vip-reseller.co.id

### DataTables tidak muncul

-   Clear browser cache
-   Cek console browser untuk error JavaScript
-   Pastikan jQuery dan DataTables assets loaded

---

## Support & Documentation

-   **VIP Reseller Website:** https://vip-reseller.co.id/
-   **API Documentation:** https://vip-reseller.co.id/api-docs (jika ada)
-   **Customer Support:** Hubungi support VIP Reseller untuk whitelist IP

---

**Status Implementasi:** ✅ COMPLETED
**Perlu Action:** ⚠️ Whitelist IP Address
