<p align="center">
  <a href="https://github.com/arvaganteng/WEB-PINJAM-BARANG">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" alt="Logo Pinjam Barang" width="320">
  </a>
</p>

<h1 align="center">📦 Web Peminjaman Barang & Inventaris Enterprise</h1>

<p align="center">
  A modern, full-featured web application for managing inventory, item borrowing requests, returns, fines, and automated PDF reporting built with Laravel.
</p>

<p align="center">
  <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel"></a>
  <a href="https://www.php.net/"><img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP"></a>
  <a href="https://mysql.com"><img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL"></a>
  <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge" alt="License"></a>
</p>

---

## 📌 Tentang Aplikasi (About The Project)

**Web Peminjaman Barang & Inventaris** adalah platform manajemen aset dan peminjaman barang terintegrasi yang dirancang untuk mempermudah proses alur transaksi pengajuan peminjaman, verifikasi admin, pelacakan stok barang secara real-time, manajemen denda keterlambatan, hingga pengeluaran bukti invoice / laporan PDF secara otomatis.

Aplikasi ini memiliki 2 hak akses utama (**Admin** & **Customer**) dengan fitur keamanan terkini seperti otentikasi OTP, audit trail (activity log), serta notifikasi terpusat.

---

## ✨ Fitur-Fitur Utama (Key Features)

### 👨‍💼 Panel Admin
- **Dashboard Analitik**: Ringkasan total barang, transaksi aktif, peminjaman pending, pengembalian, dan grafik statistik.
- **Manajemen Katalog & Stok**: CRUD barang, kategori, status kondisi (Baik/Rusak), dan penetapan tarif sewa per hari.
- **Persetujuan & Verifikasi Peminjaman**: Multi-stage approval (Approve, Reject, Confirm Payment, Release Item).
- **Pengolahan Pengembalian & Denda**: Pengecekan kondisi barang saat dikembalikan, konfirmasi denda keterlambatan / kerusakan.
- **Manajemen Pengguna (Customers)**: Kontrol akun pengguna, aktivasi/blokir status customer.
- **Laporan & PDF Export**: Cetak laporan transaksi peminjaman dan rekapitulasi data aset.
- **System Activity Logs**: Catatan riwayat aktivitas pengguna untuk transparansi & audit.

### 👤 Panel Customer
- **Katalog Barang & Pencarian**: Jelajahi barang berdasarkan kategori, ketersediaan stok, dan spesifikasi detail.
- **Pengajuan Peminjaman Interaktif**: Pilih tanggal pinjam, estimasi pengembalian, hitung otomatis total biaya sewa, serta verifikasi OTP.
- **Riwayat & Pelacakan Transaksi**: Pantau status pengajuan (Menunggu Approval, Dipinjam, Selesai, Ditolak).
- **Pengajuan Perpanjangan Durasi**: Ajukan perpanjangan sewa barang langsung dari sistem.
- **Invoice & Bukti PDF**: Download invoice transaksi dan tanda terima peminjaman fisik.
- **Ulasan & Rating Barang**: Memberikan ulasan dan ulasan barang setelah pengembalian.
- **Manajemen Profil & Keamanan**: Perbarui informasi akun dan kata sandi.

---

## 🛠️ Teknologi yang Digunakan (Tech Stack)

- **Framework**: [Laravel 11](https://laravel.com/)
- **Bahasa Pemrograman**: PHP 8.2+
- **Database**: MySQL / MariaDB
- **Frontend**: Blade Templating Engine, Vanilla CSS & Modern Responsive Design
- **Document Rendering**: DomPDF (`dompdf/dompdf`)
- **Otentikasi**: Custom Role-based Middleware, Session-based Auth & OTP System

---

## 📁 Struktur Direktori Utama (Project Structure)

```text
pinjam-barang/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Controller khusus Admin
│   │   │   ├── Customer/       # Controller khusus Customer
│   │   │   └── AuthController  # Autentikasi & OTP
│   │   └── Middleware/         # Role-based middleware
│   └── Models/                 # Eloquent Models (User, Item, Borrowing, ReturnRecord, dll)
├── database/
│   ├── migrations/             # Struktur tabel database
│   └── seeders/                # Data sampel awal
├── resources/
│   └── views/
│       ├── admin/              # Tampilan Dashboard Admin
│       ├── customer/           # Tampilan Dashboard Customer
│       └── auth/               # Halaman Login & Register
└── routes/
    └── web.php                 # Rute utama aplikasi
```

---

## 🚀 Panduan Instalasi (Installation Guide)

Ikuti langkah-langkah berikut untuk menjalankan project ini di komputer lokal Anda:

### 1. Prasyarat System
Pastikan komputer Anda sudah terinstal:
- PHP >= 8.2
- Composer
- Node.js & NPM
- Database Server (MySQL / Laragon / XAMPP)

### 2. Clone Repository
```bash
git clone https://github.com/arvaganteng/WEB-PINJAM-BARANG.git
cd WEB-PINJAM-BARANG
```

### 3. Install Dependensi PHP & Frontend
```bash
composer install
npm install
```

### 4. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Buka file `.env` dan sesuaikan pengaturan database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pinjam_barang
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Jalankan Migrasi & Database Seeder
Jalankan migrasi untuk membuat tabel beserta data awal (dummy data):
```bash
php artisan migrate --seed
```

### 7. Buat Symbolic Link Storage (Opsional jika mengunggah gambar)
```bash
php artisan storage:link
```

### 8. Jalankan Server Lokal
Jalankan perintah berikut di terminal:
```bash
php artisan serve
```
Aplikasi dapat diakses melalui browser di: `http://127.0.0.1:8000`

---

## 🔑 Akun Demo Default (Demo Credentials)

Setelah menjalankan `php artisan migrate --seed`, Anda dapat menggunakan akun demo berikut:

| Role | Email | Password | Akses URL |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@nde.co.id` | `password` | `/admin/login` |
| **Customer** | `budi@example.com` | `password` | `/login` |

---

## 📄 Lisensi (License)

Project ini dilisensikan di bawah [MIT License](LICENSE).

---

<p align="center">
  Dibuat dengan ❤️ untuk sistem manajemen inventaris dan peminjaman barang yang efisien & profesional.
</p>
