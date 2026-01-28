<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="200" alt="Laravel Logo">
    </a>
</p>

<h1 align="center">Sistem Manajemen Kos (INNA KOS)</h1>

<p align="center">
    Aplikasi berbasis web untuk mempermudah pengelolaan data penyewa, kamar, dan pembayaran kos secara digital.
    <br />
    <strong>Project Skripsi - Teknologi Informasi</strong>
</p>

<p align="center">
    <a href="#"><img src="https://img.shields.io/badge/Framework-Laravel-FF2D20?style=flat-square&logo=laravel" alt="Laravel"></a>
    <a href="#"><img src="https://img.shields.io/badge/Language-PHP_8.x-777BB4?style=flat-square&logo=php" alt="PHP"></a>
    <a href="#"><img src="https://img.shields.io/badge/Database-MySQL-4479A1?style=flat-square&logo=mysql" alt="MySQL"></a>
    <a href="#"><img src="https://img.shields.io/badge/Payment-Midtrans-0063A5?style=flat-square" alt="Midtrans"></a>
</p>

---

## 📖 Tentang Aplikasi

**Sistem Manajemen Kos** adalah aplikasi yang dibangun untuk membantu pemilik kos dalam mengelola operasional bisnis kos secara efisien. Aplikasi ini menggantikan pencatatan manual dengan sistem terintegrasi yang mencakup pemesanan kamar, pembayaran otomatis, dan pelaporan keuangan.

Project ini dikembangkan sebagai syarat kelulusan Skripsi pada program studi Teknologi Informasi.

## 🚀 Fitur Utama

### 👤 User (Pencari Kos)
* **Pencarian Kamar:** Melihat daftar kamar tersedia lengkap dengan fasilitas dan harga.
* **Booking Online:** Melakukan pemesanan kamar secara *real-time*.
* **Pembayaran Digital:** Terintegrasi dengan **Midtrans** untuk pembayaran via Transfer Bank, E-Wallet (GoPay/OVO), dll.
* **Riwayat Transaksi:** Melihat status pembayaran dan histori pemesanan.
* **Cetak Kwitansi:** Bukti pembayaran otomatis.

### 🛡️ Admin (Pemilik/Pengelola)
* **Dashboard Statistik:** Ringkasan pendapatan bulanan, jumlah penyewa aktif, dan kamar kosong.
* **Manajemen Data Master:** Kelola data Kamar, Tipe Kamar, dan User.
* **Validasi Pembayaran:** Verifikasi manual (jika perlu) dan monitoring status transaksi Midtrans.
* **Laporan Keuangan:** Export laporan pendapatan dalam format **PDF**.
* **WhatsApp Gateway:** (Opsional) Notifikasi otomatis ke penyewa via WhatsApp Service.

## 🛠️ Teknologi yang Digunakan

* **Backend:** Laravel Framework (PHP)
* **Frontend:** Blade Templating, Bootstrap / Tailwind CSS
* **Database:** MySQL
* **Payment Gateway:** Midtrans API
* **Services:** WhatsApp API Integration (untuk notifikasi)

## 💻 Cara Instalasi (Localhost)

Ikuti langkah berikut untuk menjalankan project ini di komputer Anda:

1.  **Clone Repository**
    ```bash
    git clone [https://github.com/AhmadDuwiSetianto/Sistem-Manajemen-Kos.git](https://github.com/AhmadDuwiSetianto/Sistem-Manajemen-Kos.git)
    cd Sistem-Manajemen-Kos
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install && npm run build
    ```

3.  **Konfigurasi Environment**
    Duplikat file `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```
    Buka file `.env` dan sesuaikan konfigurasi database serta API Key Midtrans Anda:
    ```env
    DB_DATABASE=nama_database_anda
    
    MIDTRANS_SERVER_KEY=isi_server_key_anda
    MIDTRANS_CLIENT_KEY=isi_client_key_anda
    ```

4.  **Generate Key & Migrasi Database**
    ```bash
    php artisan key:generate
    php artisan migrate --seed
    ```

5.  **Jalankan Server**
    ```bash
    php artisan serve
    ```
    Buka browser dan akses: `http://localhost:8000`

## 📄 Lisensi

Aplikasi ini bersifat open-source di bawah lisensi [MIT license](https://opensource.org/licenses/MIT).

---
<p align="center">
    Dibuat dengan ❤️ oleh <strong>Ahmad Duwi Setianto</strong>
</p>
