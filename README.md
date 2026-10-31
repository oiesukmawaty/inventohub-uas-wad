# InventoHub - Warehouse Inventory Management System
> **Tugas Akhir Semester (UAS) - Web Application Development (WAD05 / WAD04)**  
> **Program Studi S1 Ilmu Komputer - Universitas Cakrawala**

---

## 📌 Identitas Mahasiswa
* **Nama Lengkap:** Halimah Sukmawaty
* **NIM:** [NIM Mahasiswa]
* **Program Studi:** S1 Ilmu Komputer
* **Mata Kuliah:** Web Application Development (WAD05)
* **Dosen Pengampu:** Wahyu Ari
* **Ketua Program Studi:** Dyas
* **Institusi:** Universitas Cakrawala
* **Tahun Akademik:** Semester Ganjil 2026/2027

---

## 📖 Ringkasan Proyek
**InventoHub** adalah sistem aplikasi web dinamis *full-stack* operasional berbasis arsitektur *server-side* **Model-View-Controller (MVC)** yang dirancang untuk mengelola rantai pasok dan inventaris pergudangan secara *real-time*, akurat, dan terstruktur.

Aplikasi ini memenuhi seluruh capaian pembelajaran **CPMK 1 sampai CPMK 5**:
1. **Perancangan Relasional & Arsitektur MVC (CPMK 1):** Skema basis data 4 tabel relasional (`users`, `categories`, `products`, `transactions`) yang ternormalisasi dengan integritas referensial *Foreign Key*, *Cascading*, serta *Unique Constraints*.
2. **Implementasi Backend & Operasi CRUD Penuh (CPMK 2 & CPMK 3):** Dibangun menggunakan Laravel Framework dengan Eloquent ORM, mencakup operasi Create, Read, Update, Delete produk, kategori, dan pencatatan transaksi mutasi stok dengan integritas ACID (*Database Transactions*).
3. **Keamanan Web & Manajemen Sesi (CPMK 4):**
   * Proteksi **CSRF (Cross-Site Request Forgery)** pada seluruh permintaan form HTTP POST/PUT/DELETE via `@csrf`.
   * Sanitasi data masukan dari serangan **XSS (Cross-Site Scripting)** menggunakan `strip_tags()` dan sintaks Blade `{{ }}` (*HTML entity encoding*).
   * Pencegahan **SQL Injection** secara menyeluruh melalui *Parameterized Queries & Prepared Statements* bawaan Eloquent ORM.
   * Autentikasi aman dengan algoritma *hashing* **Bcrypt** dan proteksi pembajakan sesi melalui `session()->regenerate()`.
   * **Role-Based Access Control (RBAC):** Pemisahan hak akses antara `admin` (akses penuh termasuk CRUD master kategori) dan `staff` (operasional katalog dan mutasi stok).
4. **Kesiapan Deployment Cloud (CPMK 5):** Konfigurasi environment siap rilis ke platform cloud (Railway, Render, VPS) dengan variabel terisolasi.

---

## 🔑 Akun Demo (Credentials)

Untuk pengujian fungsionalitas dan penilaian, gunakan kredensial berikut:

| Peran (Role) | Email Login | Password | Hak Akses Utama |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@inventohub.com` | `password123` | Akses penuh dashboard, manajemen kategori master, CRUD produk, mutasi stok. |
| **Staff Gudang** | `staff@inventohub.com` | `password123` | Akses dashboard, melihat katalog produk, melakukan transaksi barang masuk/keluar. |

---

## 🛠️ Kebutuhan Sistem (System Requirements)
* PHP >= 8.2
* Composer >= 2.0
* MySQL Server >= 8.0 (atau MariaDB >= 10.4)
* Web Browser modern (Google Chrome, Firefox, Microsoft Edge)

---

## 🚀 Panduan Instalasi di Komputer Lokal

Ikuti langkah-langkah berikut untuk menjalankan sistem di lingkungan lokal:

### 1. Kloning Repositori
```bash
git clone https://github.com/[username-anda]/inventohub-uas-wad.git
cd inventohub-uas-wad
```

### 2. Instal Dependensi Composer
```bash
composer install
```

### 3. Konfigurasi Environment File
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Buka file `.env` dan sesuaikan koneksi database MySQL lokal Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Setup Database & Seeding Data
Buat database baru di MySQL dengan nama `inventory_db`, kemudian import skema dan data awal menggunakan salah satu dari dua cara berikut:

* **Opsi A (Menggunakan file SQL langsung):**
  Import file `database/inventory_system.sql` melalui phpMyAdmin, DBeaver, HeidiSQL, atau terminal MySQL:
  ```bash
  mysql -u root -p inventory_db < database/inventory_system.sql
  ```

* **Opsi B (Menggunakan Artisan Migration & Seeder):**
  ```bash
  php artisan migrate --seed
  ```

### 6. Menjalankan Server Lokal
Jalankan development server Laravel:
```bash
php artisan serve
```
Aplikasi kini aktif dan dapat diakses di browser melalui:  
👉 **`http://127.0.0.1:8000`**

---

## 🗄️ Struktur Skema Basis Data (ERD Summary)

Aplikasi memiliki 4 tabel berelasi:
* **`users`**: Menyimpan data pengguna, kredensial password terenkripsi bcrypt, dan peran (`admin` / `staff`).
* **`categories`**: Menyimpan kategori klasifikasi barang (Relasi `1 to Many` ke `products`).
* **`products`**: Menyimpan katalog master barang, SKU unik, stok fisik, batas minimum stok, dan harga.
* **`transactions`**: Menyimpan riwayat mutasi stok masuk (`in`) dan keluar (`out`), terhubung ke `users` (pencatat) dan `products` (barang termutasi).

---

## 📂 Struktur Direktori MVC Proyek
```
uas_wad_halimah/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php        # Autentikasi, sesi, registrasi, login, logout
│   │   │   ├── CategoryController.php    # CRUD kategori barang (Admin Only)
│   │   │   ├── DashboardController.php   # Metrik KPI, kalkulasi valuasi stok, alert stok
│   │   │   ├── ProductController.php     # CRUD inventaris produk, filter, dan search
│   │   │   └── TransactionController.php  # Mutasi stok in/out dengan ACID transactions
│   │   └── Middleware/
│   │       └── CheckRole.php             # Enforcer otorisasi Role-Based Access Control
│   └── Models/
│       ├── Category.php                  # Model Kategori & relasi hasMany Products
│       ├── Product.php                   # Model Produk & kalkulasi low stock
│       ├── Transaction.php               # Model Transaksi mutasi & relasi belongsTo
│       └── User.php                      # Model Autentikasi User & helper role
├── database/
│   └── inventory_system.sql              # Skema DDL MySQL lengkap + Seeder data awal
├── resources/
│   └── views/
│       ├── auth/                         # Template login & register
│       ├── categories/                   # Template CRUD kategori
│       ├── dashboard/                    # Template dashboard analitik
│       ├── layouts/                      # Layout utama Tailwind CSS responsif
│       ├── products/                     # Template CRUD produk
│       └── transactions/                 # Template log dan form mutasi stok
├── routes/
│   └── web.php                           # Definisi rute MVC & proteksi middleware
├── .env.example                          # Template konfigurasi environment
├── composer.json                         # Dependensi framework Laravel
└── README.md                             # Dokumentasi resmi proyek
```

---

## 🔒 Fitur Keamanan yang Diimplementasikan
1. **Pencegahan SQL Injection:** Seluruh manipulasi data menggunakan Query Binding & Eloquent ORM.
2. **Proteksi CSRF:** Token verifikasi otomatis disertakan di setiap form POST/PUT/DELETE.
3. **Pencegahan XSS:** Input data dibersihkan dari tag HTML berbahaya dengan `strip_tags()` dan di-escape otomatis saat dirender di antarmuka Blade.
4. **Session Fixation Defense:** Token session di-regenerasi saat login (`$request->session()->regenerate()`) dan dibersihkan total saat logout.
5. **Enkripsi Kredensial:** Password dienkripsi dengan standar industri `bcrypt` via `Hash::make()`.
6. **Integritas Transaksi ACID:** Pengurangan dan penambahan stok produk dibungkus dalam blok `DB::transaction()` untuk menghindari inkonsistensi data jika terjadi kegagalan sistem.

---
*Dibuat dengan dedikasi untuk penyelesaian Ujian Akhir Semester Web Application Development - Universitas Cakrawala.*
