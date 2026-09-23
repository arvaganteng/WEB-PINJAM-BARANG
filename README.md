<p align="center">
  <a href="https://github.com/arvaganteng/WEB-PINJAM-BARANG">
    <img src="public/images/logo.png" alt="PT Nusantara Digital Express Logo" width="480">
  </a>
</p>

<h1 align="center">🏢 Sistem Peminjaman Barang & Inventaris Enterprise</h1>
<p align="center">
  <strong>PT NUSANTARA DIGITAL EXPRESS</strong><br>
  <em>Cepat • Aman • Terpercaya</em>
</p>

<p align="center">
  Platform manajemen inventaris internal dan alur peminjaman aset perusahaan berbasis web yang modern, aman, dan efisien.
</p>

<p align="center">
  <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11"></a>
  <a href="https://www.php.net/"><img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+"></a>
  <a href="https://mysql.com"><img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL"></a>
  <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge" alt="License"></a>
  <a href="https://github.com/arvaganteng/WEB-PINJAM-BARANG/stargazers"><img src="https://img.shields.io/badge/PRs-Welcome-brightgreen.svg?style=for-the-badge" alt="PRs Welcome"></a>
</p>

---

## 📌 Tentang Sistem (System Overview)

**Sistem Peminjaman Barang & Inventaris PT Nusantara Digital Express** adalah solusi enterprise terpadu yang dirancang untuk mengelola siklus peminjaman aset perusahaan (seperti perangkat elektronik, alat AV, operasional, dan furnitur kantor). Sistem ini membantu merapikan pencatatan stok, meminimalisir risiko kehilangan barang, serta menyediakan alur persetujuan transparan antara pengaju (Customer/Karyawan) dan Admin Inventaris.

---

## ✨ Fitur-Fitur Utama (Key Features)

### 👨‍💼 Panel Manajemen Admin
- 📊 **Dashboard Analitik & Real-time Metrics**: Visualisasi total barang, transaksi aktif, pengajuan pending, dan statistik denda.
- 📦 **Katalog & Manajemen Stok Aset**: Olah data barang (CRUD), penentuan kategori, kondisi fisik (Baik/Rusak), serta harga sewa harian.
- 🛡️ **Alur Persetujuan Bertingkat (Multi-Stage Approval)**:
  - Verifikasi pengajuan peminjaman (*Approve* / *Reject*).
  - Konfirmasi pembayaran & pelepasan barang (*Release Item*).
  - Persetujuan perpanjangan masa pinjam.
- 🔄 **Verifikasi Pengembalian & Denda**: Pengecekan kelengkapan barang dan perhitungan denda otomatis untuk keterlambatan atau kerusakan.
- 👥 **Manajemen Pengguna & Hak Akses**: Manajemen status akun pengguna (Aktif/Nonaktif) dan verifikasi profil.
- 📄 **Cetak Laporan PDF & Rekapitulasi**: Ekspor rekap transaksi dan laporan aset siap cetak menggunakan DomPDF.
- 📝 **System Activity Audit Logs**: Catatan riwayat aktivitas operasional untuk transparansi audit internal.

### 👤 Panel Pengguna (Customer / Karyawan)
- 🔍 **Katalog & Pencarian Barang Interaktif**: Eksplorasi barang berdasarkan kategori, stok tersedia, dan rincian spesifikasi.
- 🛒 **Pengajuan Peminjaman & Otentikasi OTP**: Formulir pengajuan interaktif dengan kalkulasi otomatis total biaya dan verifikasi OTP demi keamanan.
- 📋 **Pelacakan Status Real-Time**: Pemantauan status transaksi secara langsung (*Menunggu*, *Disetujui*, *Dipinjam*, *Selesai*).
- ⏱️ **Pengajuan Perpanjangan Durasi**: Pengajuan perpanjangan waktu pinjam langsung dari dashboard pengguna.
- 🧾 **Download Invoice & Bukti Pinjam PDF**: Pengunduhan faktur resmi transaksi dan bukti pengembalian.
- ⭐ **Ulasan & Rating Barang**: Memberikan penilaian kondisi barang pasca-pengembalian.
- 🔔 **Sistem Notifikasi Terpusat**: Pemberitahuan otomatis saat ada perubahan status peminjaman atau penagihan.

---

## 🛠️ Arsitektur & Teknologi (Tech Stack)

| Komponen | Teknologi |
| :--- | :--- |
| **Backend Framework** | [Laravel 11](https://laravel.com/) (PHP 8.2+) |
| **Database** | MySQL / MariaDB |
| **Frontend UI** | Blade Templating, Responsive Vanilla CSS & JavaScript |
| **PDF Generator** | DomPDF (`dompdf/dompdf`) |
| **Otentikasi & Keamanan** | Custom Role Middleware, Session Auth & OTP Verification |
| **Version Control** | Git & GitHub Repository |

---

## 📁 Struktur Direktori Project (Directory Structure)

```text
WEB-PINJAM-BARANG/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Controller manajemen admin (Items, Borrowings, Reports, Fines)
│   │   │   ├── Customer/       # Controller pengguna (Catalog, Borrowings, Profile)
│   │   │   └── AuthController  # Otentikasi, Login, Register, & OTP
│   │   └── Middleware/         # Middleware Role (Admin & Customer)
│   └── Models/                 # Eloquent Models (User, Item, Category, Borrowing, ReturnRecord, ActivityLog)
├── database/
│   ├── migrations/             # Skema dan struktur tabel database
│   └── seeders/                # Seeder data awal (Admin, Customer, Kategori, Barang)
├── public/
│   ├── images/                 # Asset logo (logo.png) & gambar produk
│   └── storage/                # Symlink direktori media
├── resources/
│   └── views/                  # Blade templates (Admin, Customer, Auth, Email, Invoices)
└── routes/
    └── web.php                 # Rute utama aplikasi
```

---

## 🚀 Panduan Instalasi & Jalankan (Getting Started)

### 1. Prasyarat Sistem
- PHP `>= 8.2`
- Composer `>= 2.x`
- Node.js & NPM
- Database Server (MySQL via Laragon / XAMPP)

### 2. Langkah-Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/arvaganteng/WEB-PINJAM-BARANG.git
cd WEB-PINJAM-BARANG

# 2. Install dependensi PHP & JavaScript
composer install
npm install

# 3. Konfigurasi Environment File
cp .env.example .env

# 4. Generate App Encryption Key
php artisan key:generate

# 5. Konfigurasi Database pada file .env
# DB_DATABASE=pinjam_barang
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Jalankan Migrasi & Database Seeder
php artisan migrate --seed

# 7. Buat Symbolic Link Storage
php artisan storage:link

# 8. Jalankan Server Development & Asset Build
php artisan serve
npm run dev
```

Aplikasi siap diakses pada browser melalui URL: `http://127.0.0.1:8000`

---

## 🔑 Akun Akses Demo (Default Credentials)

Gunakan akun berikut setelah menjalankan perintah `php artisan migrate --seed`:

| Role | Email | Password | Akses URL |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@nde.co.id` | `password` | `/admin/login` |
| **Customer / User** | `budi@example.com` | `password` | `/login` |

---

## 📤 Cara Update Perubahan ke GitHub (Push to GitHub)

Untuk memperbarui tampilan `README.md` dan logo baru di repository GitHub Anda, jalankan perintah git berikut pada terminal:

```bash
git add README.md public/images/logo.png
git commit -m "docs: update README with PT Nusantara Digital Express branding & logo"
git push origin main
```

---

## 📄 Lisensi (License)

Dikembangkan untuk **PT Nusantara Digital Express** di bawah lisensi [MIT License](LICENSE).

<p align="center">
  <sub>PT Nusantara Digital Express &copy; 2026. All rights reserved.</sub>
</p>
