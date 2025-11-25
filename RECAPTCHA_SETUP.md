# Google reCAPTCHA Setup Guide

## Langkah-langkah Setup Google reCAPTCHA

### 1. Dapatkan API Keys dari Google

1. Kunjungi [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin)
2. Login dengan akun Google Anda
3. Klik tombol **"+"** atau **"Create"** untuk membuat site baru
4. Isi form dengan informasi berikut:
   - **Label**: Nama website Anda (contoh: "Top Up Game")
   - **reCAPTCHA type**: Pilih **"reCAPTCHA v2"** → **"I'm not a robot" Checkbox**
   - **Domains**: Masukkan domain website Anda
     - Untuk development: `localhost`
     - Untuk production: `yourdomain.com` (tanpa http/https)
   - **Accept the reCAPTCHA Terms of Service**: Centang checkbox
5. Klik **"Submit"**
6. Anda akan mendapatkan:
   - **Site Key** (Public Key)
   - **Secret Key** (Private Key)

### 2. Konfigurasi di Laravel

1. Buka file `.env` di root project Anda
2. Tambahkan konfigurasi berikut (jika belum ada):

```env
# Google reCAPTCHA Configuration
RECAPTCHA_SITE_KEY=your_site_key_here
RECAPTCHA_SECRET_KEY=your_secret_key_here
```

3. Ganti `your_site_key_here` dan `your_secret_key_here` dengan keys yang Anda dapatkan dari Google

### 3. Testing

#### Development (localhost)
- Pastikan Anda sudah menambahkan `localhost` di domain list saat membuat reCAPTCHA site
- reCAPTCHA akan berfungsi normal di localhost

#### Production
- Pastikan domain production Anda sudah ditambahkan di Google reCAPTCHA Admin Console
- Update file `.env` di server production dengan keys yang sama

### 4. Cara Kerja

Setelah setup selesai, reCAPTCHA akan:
- Muncul di halaman **Login** (`/auth/login`)
- Muncul di halaman **Register** (`/auth/register`)
- User harus mencentang "I'm not a robot" sebelum submit form
- Backend akan memvalidasi response reCAPTCHA sebelum memproses login/register

### 5. Troubleshooting

#### reCAPTCHA tidak muncul
- Pastikan script Google reCAPTCHA sudah dimuat: `https://www.google.com/recaptcha/api.js`
- Cek console browser untuk error JavaScript
- Pastikan `RECAPTCHA_SITE_KEY` sudah diisi dengan benar di `.env`

#### Error "Verifikasi reCAPTCHA gagal"
- Pastikan `RECAPTCHA_SECRET_KEY` sudah diisi dengan benar di `.env`
- Pastikan domain website sesuai dengan yang didaftarkan di Google reCAPTCHA
- Cek apakah server bisa mengakses `https://www.google.com/recaptcha/api/siteverify`

#### Development Mode
- Jika `RECAPTCHA_SECRET_KEY` kosong di `.env`, validasi reCAPTCHA akan di-skip (untuk development)
- Untuk production, **WAJIB** mengisi kedua keys

### 6. Keamanan

- **JANGAN** commit file `.env` ke Git
- **JANGAN** share `RECAPTCHA_SECRET_KEY` ke publik
- `RECAPTCHA_SITE_KEY` boleh dilihat publik (ada di HTML)
- `RECAPTCHA_SECRET_KEY` harus tetap rahasia (hanya di server)

## Fitur yang Sudah Diimplementasi

✅ reCAPTCHA di halaman Login
✅ reCAPTCHA di halaman Register
✅ Validasi server-side menggunakan Google API
✅ Error handling dan pesan error dalam Bahasa Indonesia
✅ Support untuk development mode (skip validation jika key kosong)
✅ Responsive design

## File yang Dimodifikasi

1. `app/Http/Controllers/Auth/AuthenticatedSessionController.php` - Menambahkan validasi reCAPTCHA
2. `config/services.php` - Menambahkan konfigurasi reCAPTCHA
3. `resources/views/auth/login.blade.php` - Menambahkan widget reCAPTCHA
4. `resources/views/auth/register.blade.php` - Menambahkan widget reCAPTCHA
5. `.env.example` - Menambahkan template konfigurasi

## Referensi

- [Google reCAPTCHA Documentation](https://developers.google.com/recaptcha/docs/display)
- [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin)
