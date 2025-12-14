<div align="center">
  <h1>🍽️ Ulu Coffee</h1>
  <p>Sistem Manajemen Restoran & Pemesanan Online Modern</p>
  
  <img src="https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/TailwindCSS-4.0-38B2AC?style=for-the-badge&logo=tailwind-css" alt="TailwindCSS">
  <img src="https://img.shields.io/badge/Alpine.js-3.15-8BC0D0?style=for-the-badge&logo=alpine.js" alt="Alpine.js">
  <img src="https://img.shields.io/badge/Midtrans-Payment-0056B3?style=for-the-badge" alt="Midtrans">
</div>

## 📋 Tentang Project

Ulu Coffee adalah sistem manajemen restoran modern yang dibangun dengan Laravel 12. Aplikasi ini menyediakan solusi lengkap untuk mengelola operasional restoran, mulai dari pemesanan menu online, manajemen keranjang, pembayaran digital dengan Midtrans, hingga dashboard administrasi untuk pemilik dan kasir.

### ✨ Fitur Utama

#### 🛒 Fitur Pelanggan

-   **Menu Katalog**: Menampilkan daftar menu dengan kategori (Business Lunch, Dessert, dll)
-   **WOK Builder**: Sistem kustomisasi menu dengan pilihan bahan
-   **Keranjang Belanja**: Sistem keranjang untuk pengguna login dan guest
-   **Checkout & Pembayaran**: Integrasi dengan Midtrans (QRIS, Bank Transfer)
-   **Voucher Diskon**: Sistem voucher dengan tipe fixed dan percentage
-   **Riwayat Pesanan**: Melihat status dan riwayat pemesanan
-   **Autentikasi**: Login, registrasi, dan Google OAuth

#### 🎛️ Fitur Administratif

-   **Dashboard Admin**: Monitoring dan pengelolaan sistem
-   **Dashboard Kasir**: Manajemen pesanan dan update status
-   **Dashboard Owner**: Kontrol penuh operasional restoran
-   **Manajemen Produk**: CRUD menu dengan upload gambar
-   **Manajemen Kategori**: Organisasi menu per kategori
-   **Manajemen Voucher**: Buat dan kelola voucher promosi
-   **Update Status Pesanan**: Tracking status pesanan real-time

## 🏗️ Arsitektur Teknologi

### Backend

-   **Framework**: Laravel 12
-   **Database**: SQLite (default), dapat diubah ke MySQL/PostgreSQL
-   **Authentication**: Laravel Breeze + Google OAuth (Socialite)
-   **Payment Gateway**: Midtrans Integration
-   **Queue System**: Laravel Queues untuk background processing

### Frontend

-   **CSS Framework**: TailwindCSS 4.0
-   **JavaScript**: Alpine.js 3.15 untuk interaktivitas
-   **Build Tool**: Vite 7.0
-   **Template Engine**: Blade

## 🚀 Instalasi & Setup

### Persyaratan Sistem

-   PHP 8.2 atau lebih tinggi
-   Composer
-   Node.js dan NPM
-   Database (SQLite, MySQL, atau PostgreSQL)

### Langkah-langkah Instalasi

1. **Clone Repository**

    ```bash
    git clone <repository-url>
    cd ulu-cafe
    ```

2. **Install Dependencies**

    ```bash
    composer install
    npm install
    ```

3. **Setup Environment**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Konfigurasi Database**
   Edit file `.env`:

    ```env
    DB_CONNECTION=sqlite
    # DB_HOST=127.0.0.1
    # DB_PORT=3306
    # DB_DATABASE=ulu_cafe
    # DB_USERNAME=root
    # DB_PASSWORD=
    ```

5. **Konfigurasi Midtrans**
   Tambahkan ke file `.env`:

    ```env
    MIDTRANS_SERVER_KEY=your_server_key
    MIDTRANS_CLIENT_KEY=your_client_key
    MIDTRANS_IS_PRODUCTION=false
    MIDTRANS_IS_SANITIZED=true
    MIDTRANS_IS_3DS=true
    ```

6. **Setup Google OAuth (Opsional)**
   Tambahkan ke file `.env`:

    ```env
    GOOGLE_CLIENT_ID=your_google_client_id
    GOOGLE_CLIENT_SECRET=your_google_client_secret
    GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback
    ```

7. **Run Migration**

    ```bash
    php artisan migrate
    ```

8. **Build Assets**

    ```bash
    npm run build
    ```

9. **Start Development Server**

    ```bash
    # Untuk development lengkap
    composer run dev

    # Atau jalankan secara manual
    php artisan serve
    npm run dev
    ```

## 📁 Struktur Project

```
ulu-cafe/
├── app/
│   ├── Http/Controllers/          # Controller aplikasi
│   │   ├── AuthController.php    # Autentikasi & Google OAuth
│   │   ├── CartController.php    # Manajemen keranjang
│   │   ├── OrderController.php   # Proses pesanan & pembayaran
│   │   ├── ProductController.php # CRUD produk
│   │   └── ...
│   └── Models/                    # Model Eloquent
│       ├── Product.php           # Model produk
│       ├── Order.php             # Model pesanan
│       ├── CartItem.php          # Model item keranjang
│       ├── Voucher.php           # Model voucher
│       └── ...
├── database/
│   ├── migrations/               # Database migrations
│   └── seeders/                  # Database seeders
├── resources/
│   ├── views/                    # Blade templates
│   │   ├── auth/                 # Halaman autentikasi
│   │   ├── cart/                 # Halaman keranjang
│   │   ├── checkout/             # Halaman checkout
│   │   ├── dashboard/            # Dashboard admin/kasir/owner
│   │   └── ...
│   ├── css/                      # File CSS
│   └── js/                       # File JavaScript
├── routes/
│   └── web.php                   # Route definition
└── ...
```

## 🎯 Penggunaan Aplikasi

### Flow Pelanggan

1. **Browse Menu**: Pelanggan melihat daftar menu di halaman utama
2. **Add to Cart**: Tambahkan menu ke keranjang (guest atau user login)
3. **Checkout**: Isi informasi pemesanan dan pilih metode pembayaran
4. **Payment**: Dapatkan QR code atau VA number dari Midtrans
5. **Order Confirmation**: Cek status pembayaran dan konfirmasi pesanan

### Flow Admin/Kasir/Owner

1. **Login**: Masuk ke dashboard sesuai role
2. **Manage Menu**: Tambah, edit, atau hapus menu dan kategori
3. **Manage Vouchers**: Buat voucher promosi
4. **Track Orders**: Monitor status pesanan
5. **Update Status**: Ubah status pesanan (pending → processing → completed)

## 🔧 Konfigurasi Lanjutan

### Payment Methods

Aplikasi mendukung:

-   **QRIS**: Pembayaran via QR code (GoPay, OVO, dll)
-   **Bank Transfer**: Transfer ke virtual account (BCA, BNI, BRI, Mandiri)

### User Roles

-   **Customer**: Pengguna biasa yang bisa memesan
-   **Cashier**: Kasir yang bisa mengelola pesanan
-   **Owner**: Pemilik restoran dengan akses penuh
-   **Admin**: Administrator sistem

### Voucher Types

-   **Fixed**: Diskon dengan jumlah tetap (Rp 10.000)
-   **Percentage**: Diskon persentase (10% dari total)

## 🐛 Troubleshooting

### Common Issues

1. **Midtrans SSL Error**: Sudah dikonfigurasi untuk development (SSL verification disabled)
2. **Google OAuth Error**: Pastikan redirect URL benar di Google Console
3. **Asset Not Loading**: Jalankan `npm run build` untuk build assets
4. **Database Connection**: Check konfigurasi database di `.env`

### Development Tips

-   Gunakan `php artisan tinker` untuk debugging database
-   Check logs di `storage/logs/laravel.log`
-   Gunakan `php artisan queue:work` untuk processing queue

## 📝 Development Commands

```bash
# Setup cepat
composer run setup

# Development dengan auto-reload
composer run dev

# Run tests
composer run test

# Build untuk production
npm run build

# Clear cache
php artisan optimize:clear
```

## 🔐 Security Notes

-   Project ini menggunakan Laravel's built-in security features
-   Validasi input untuk semua form submissions
-   CSRF protection untuk semua POST requests
-   Password hashing dengan bcrypt
-   Midtrans sandbox untuk development

## 🤝 Kontribusi

Project ini bersifat private. Untuk kontribusi:

1. Fork repository
2. Buat branch feature (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buka Pull Request

## 👥 Tim Pengembang

-   **Developer**: [Deni Setiya]
-   **Version**: 1.0.0
-   **Last Updated**: December 2025

---

<div align="center">
  <p>Dibuat dengan ❤️ untuk Ulu Coffee</p>
  <p>© 2025 Ulu Coffee. All rights reserved.</p>
</div>
