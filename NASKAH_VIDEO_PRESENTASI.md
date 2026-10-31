# NASKAH & PANDUAN PEREKAMAN VIDEO PRESENTASI UAS
## WEB APPLICATION DEVELOPMENT (WAD05 / WAD04) - UNIVERSITAS CAKRAWALA
### APLIKASI: INVENTOHUB (WAREHOUSE INVENTORY MANAGEMENT SYSTEM)
**Penyaji:** Halimah Sukmawaty (NIM: `25120300037`)  
**Durasi Total Target:** 11 - 13 Menit (Maksimal Batas Ujian: 15 Menit)  
**Tools Perekaman:** Loom, OBS Studio, Google Meet Record, atau Zoom (Share Screen + Wajah di Sudut Kamera).

---

## ⏱️ RUNDOWN & PEMBAGIAN WAKTU VIDEO

| Segmen Waktu | Topik Presentasi | Hal yang Ditampilkan di Layar |
| :--- | :--- | :--- |
| **00:00 - 01:15** | Pembukaan, Salam, Identitas Diri & Pengantar Proyek | Slide Judul / Halaman Depan Laporan PDF |
| **01:15 - 03:30** | Arsitektur Sistem MVC & Perancangan Relasi Basis Data (ERD) | Diagram ERD & Diagram Alur MVC di Laporan |
| **03:30 - 07:45** | Demonstrasi Langsung Fitur Aplikasi (Live Demo) | Peramban Browser (`http://localhost:8000` / Live App) |
| **07:45 - 11:30** | Pemaparan Jawaban 3 Esai Teknis (CPMK 1 - 5) | Menampilkan Ringkasan Poin Esai di Layar |
| **11:30 - 12:45** | Strategi Deployment Cloud, Kesimpulan & Penutup | Halaman Dashboard / Repositori GitHub Proyek |

---

## 🎙️ NASKAH LENGKAP KATA-DEMI-KATA (SCRIPT READ-ALOUD)

---

### BAGIAN 1: PEMBUKAAN & IDENTITAS DIRI (00:00 - 01:15)
*(Layar menampilkan: Judul Proyek, Nama Mahasiswa, Logo Universitas Cakrawala)*

> *"Assalamu’alaikum Warahmatullahi Wabarakatuh,*  
> *Selamat pagi/siang/sore kepada Bapak Wahyu Ari selaku Dosen Pengampu mata kuliah Web Application Development, Bapak Dyas selaku Ketua Program Studi Ilmu Komputer, serta seluruh Tim Penguji Ujian Akhir Semester Universitas Cakrawala.*
>
> *Perkenalkan, nama saya **Halimah Sukmawaty**, mahasiswi Program Studi S1 Ilmu Komputer, kelas **WAD05**.*
>
> *Pada kesempatan kali ini, saya akan mempresentasikan serta mendemonstrasikan luaran proyek Ujian Akhir Semester saya yang berjudul: **InventoHub - Warehouse Inventory Management System**.*
>
> *InventoHub merupakan aplikasi web dinamis full-stack berbasis server-side dengan arsitektur Model-View-Controller (MVC) yang dibangun menggunakan Laravel Framework dan basis data relasional MySQL. Aplikasi ini dirancang khusus untuk mendigitalkan operasional pergudangan, mulai dari manajemen master produk, klasifikasi kategori, peringatan dini stok menipis, hingga pencatatan transaksi mutasi barang secara akurat dan aman."*

---

### BAGIAN 2: PERANCANGAN ARSITEKTUR MVC & BASIS DATA RELASIONAL (01:15 - 03:30)
*(Layar menampilkan: Diagram ERD dan Diagram Flowchart MVC)*

> *"Baik, kita masuk ke pembahasan Capaian Pembelajaran CPMK 1, yaitu perancangan sistem dan basis data.*
>
> *Seperti yang dapat Bapak/Ibu penguji lihat pada layar, basis data InventoHub dirancang dengan 4 tabel relasional utama yang telah memenuhi normalisasi ketiga (3NF):*
> 1. *Pertama, tabel **`users`**: menyimpan kredensial pengguna, password yang terenkripsi aman menggunakan Bcrypt, serta kolom peran (`role`) untuk membedakan otorisasi antara **Administrator** dan **Staff Gudang**.*
> 2. *Kedua, tabel **`categories`**: merupakan tabel master klasifikasi barang dengan kode unik seperti Elektronik, Aksesoris, dan Jaringan.*
> 3. *Ketiga, tabel **`products`**: menyimpan katalog barang inventaris dengan atribut SKU unik, nama barang, harga beli, harga jual, stok fisik saat ini, serta ambang batas stok minimum.*
> 4. *Keempat, tabel **`transactions`**: mencatat seluruh riwayat mutasi stok masuk (`in`) dan keluar (`out`), yang terhubung secara relasional ke tabel pengguna sebagai penanggung jawab dan tabel produk yang dimutasikan.*
>
> *Seluruh tabel ini diikat dengan integritas referensial Foreign Key dan batasan `ON DELETE RESTRICT` pada kategori, guna mencegah anomali penghapusan data master yang masih memiliki keterkaitan produk aktif.*
>
> *Dari sisi arsitektur, aplikasi mengadopsi standar **Model-View-Controller (MVC)**:*
> * Permintaan HTTP dari browser pertama-tama disaring oleh **Routing** dan **Middleware** untuk otorisasi peran dan proteksi CSRF.
> * Kemudian **Controller** mengeksekusi logika bisnis dan validasi input.
> * Data diproses melalui **Model Eloquent ORM** dengan teknik *parameterized query* untuk berkomunikasi dengan basis data MySQL.
> * Terakhir, hasilnya dirender secara dinamis melalui **Blade View** dengan antarmuka modern dan responsif berbasis Tailwind CSS."*

---

### BAGIAN 3: DEMONSTRASI LANGSUNG FITUR APLIKASI (03:30 - 07:45)
*(Layar menampilkan: Browser yang membuka aplikasi InventoHub)*

> *"Sekarang, mari kita beralih ke demonstrasi fungsionalitas aplikasi secara langsung.*
>
> *(1) **Halaman Login & Autentikasi:**  
> *Pertama, kita berada di halaman login. Di sini saya telah menyediakan dua akun demo: Administrator dan Staff Gudang. Mari kita masuk terlebih dahulu sebagai **Administrator** menggunakan email `admin@inventohub.com` dan kata sandi `password123`. Klik Masuk.*
>
> *(2) **Dashboard Analitik & Alert Stok:**  
> *Begitu berhasil login, kita langsung disambut oleh halaman Dashboard. Di sini terdapat 4 kartu metrik utama:*
> * *Total produk SKU yang terdaftar,*
> * *Total kategori master aktif,*
> * *Total riwayat transaksi mutasi, dan*
> * *Kalkulasi otomatis total nilai valuasi aset barang yang tersimpan di gudang dalam format Rupiah.*
>
> *Di bawahnya, terdapat fitur penting yaitu **Peringatan Stok Menipis**. Sistem secara otomatis mendeteksi produk yang jumlah stok fisiknya berada di bawah atau sama dengan batas minimum stok, seperti Lenovo ThinkPad dan Keychron Keyboard ini. Fitur ini mempermudah tim logistik untuk segera melakukan tindakan restock barang.*
>
> *(3) **Manajemen Katalog Produk (CRUD):**  
> *Sekarang mari kita buka menu **Katalog Produk**. Di sini ditampilkan tabel data produk yang dilengkapi fitur pencarian instan dan filter kategori. Mari kita coba mengetikkan 'Laptop' pada kolom pencarian, maka sistem langsung memfilter data terkait.*
> *Selanjutnya, mari kita coba fitur **Tambah Produk Baru**. Saya masukkan SKU `SKU-MON-002`, pilih kategori Elektronik, nama produk 'Monitor Dell 27 Inch 4K', harga beli Rp 3.500.000, harga jual Rp 4.200.000, stok awal 10 unit, dan batas minimum 3 unit. Klik 'Simpan Produk'.*
> *Terlihat notifikasi hijau muncul bahwa produk berhasil ditambahkan, dan data baru langsung masuk ke dalam katalog.*
>
> *(4) **Pencatatan Mutasi Stok (ACID Transaction):**  
> *Sekarang mari kita uji fitur pencatatan mutasi stok pada menu **Mutasi Stok**. Klik 'Catat Mutasi Stok Baru'.*
> *Misalkan hari ini kita menerima restock barang dari supplier: pilih nomor surat jalan, tipe 'Masuk', pilih produk Lenovo ThinkPad, jumlah 10 unit, catatan 'Penerimaan PO Vendor'. Klik simpan.*
> *Sistem secara instan mencatat riwayat transaksi dan secara otomatis menambahkan stok fisik Lenovo ThinkPad dari 4 unit menjadi 14 unit, sehingga statusnya di dashboard kini keluar dari daftar stok menipis.*
>
> *Sistem ini juga memiliki validasi keamanan bisnis: jika staf mencoba mengeluarkan barang keluar melebihi stok yang ada, backend akan menolak transaksi tersebut dan menampilkan pesan kesalahan bahwa stok tidak mencukupi, menjaga agar stok gudang tidak pernah menjadi minus.*
>
> *(5) **Otorisasi Role-Based Access Control (RBAC):**  
> *Menu **Kategori Barang** hanya dapat diakses dan dikelola oleh Administrator. Jika kita logout dan login kembali sebagai Staff Gudang (`staff@inventohub.com`), maka hak akses administratif akan dibatasi secara ketat oleh Middleware `CheckRole`."*

---

### BAGIAN 4: PEMAPARAN JAWABAN ESAI TEKNIS CPMK 1 - 5 (07:45 - 11:30)
*(Layar menampilkan: Ringkasan Jawaban Esai pada Laporan)*

> *"Selanjutnya, saya akan menjelaskan jawaban atas 3 pertanyaan esai teknis yang menjadi komponen penting dalam evaluasi UAS ini:*
>
> #### **Jawaban Esai 1: Alur Data, Pola MVC, dan Struktur Database**
> *Alur data dalam aplikasi dimulai dari HTTP Request pengguna yang diterima oleh router Laravel. Request terlebih dahulu divalidasi oleh layer Middleware untuk memeriksa keabsahan session dan token CSRF. Controller kemudian mengambil alih untuk memvalidasi masukan secara ketat sebelum berinteraksi dengan Model.*
> *Model Eloquent merepresentasikan tabel pada MySQL dan mengeksekusi query relasional menggunakan Prepared Statements. Integritas database 4 tabel dijaga dengan kunci relasi Foreign Key, indeks unik pada SKU dan kode kategori, serta normalisasi 3NF yang mengeliminasi redundansi data tanpa mengorbankan performa baca/tulis.*
>
> #### **Jawaban Esai 2: Logika Backend, Penanganan Error, dan Keamanan**
> *Logika backend paling krusial terletak pada pencatatan transaksi mutasi stok. Operasi ini dibungkus menggunakan **`DB::transaction()`** untuk memastikan kepatuhan prinsip **ACID**. Jika terjadi kegagalan sistem saat proses berjalan, seluruh perubahan akan di-rollback sehingga integritas data antara tabel transaksi dan stok produk tetap sinkron 100%.*
> *Terkait keamanan, sistem mengimplementasikan tiga pilar proteksi web utama:*
> 1. *Pertama, **Proteksi CSRF**: menggunakan token acak berbasis session pada seluruh formulir perubahan data.*
> 2. *Kedua, **Pencegahan XSS**: menerapkan pembersihan tag script berbahaya melalui `strip_tags()` di controller dan fitur auto-escaping HTML entities bawaan Blade saat menampilkan variabel ke layar.*
> 3. *Ketiga, **Pencegahan SQL Injection**: mengandalkan Eloquent ORM yang secara default menggunakan PDO Parameterized Binding, sehingga karakter jahat dari input pengguna tidak dapat merusak struktur query SQL.*
> *Selain itu, keamanan sesi ditingkatkan dengan meregenerasi session ID saat login guna menangkal serangan Session Fixation.*
>
> #### **Jawaban Esai 3: Tahapan Deployment, Konfigurasi Environment, dan Troubleshooting**
> *Pada aspek deployment ke lingkungan cloud (seperti Railway atau Render), aplikasi dikonfigurasi menggunakan berkas `.env` terisolasi.*
> *Di lingkungan produksi, parameter `APP_DEBUG` disetel ke nilai `false` agar rincian stack trace dan kredensial sensitif tidak bocor saat terjadi error.*
> *Web server diarahkan ke direktori `/public` sebagai Document Root agar kode inti aplikasi tidak dapat diakses langsung oleh publik.*
> *Dalam hal troubleshooting, penanganan error 500 dilakukan dengan memeriksa log pada `storage/logs/laravel.log`, memastikan permission tulis pada folder storage, serta menjalankan perintah optimasi cache seperti `php artisan config:cache`."*

---

### BAGIAN 5: KESIMPULAN & PENUTUP (11:30 - 12:45)
*(Layar menampilkan: Tampilan Repositori GitHub & Halaman Dashboard)*

> *"Sebagai kesimpulan, aplikasi **InventoHub** yang saya bangun telah berhasil memenuhi seluruh tolok ukur capaian pembelajaran mata kuliah Web Application Development:*
> * Memenuhi **CPMK 1** melalui perancangan arsitektur MVC dan ERD 4 tabel relasional yang kokoh;
> * Memenuhi **CPMK 2 dan CPMK 3** melalui implementasi backend Laravel dengan operasi CRUD penuh dan transaksi ACID;
> * Memenuhi **CPMK 4** dengan implementasi keamanan CSRF, XSS sanitization, prepared statements, hashing Bcrypt, serta RBAC;
> * Serta memenuhi **CPMK 5** dengan kesiapan konfigurasi deployment cloud publik.
>
> *Seluruh kode sumber, skema basis data `.sql`, panduan instalasi `.env.example`, dan laporan lengkap telah saya rangkum ke dalam satu dokumen pengumpulan PDF dan repositori GitHub yang dapat diakses oleh tim penguji.*
>
> *Demikian presentasi dan demonstrasi Ujian Akhir Semester dari saya. Terima kasih yang sebesar-besarnya kepada Bapak Wahyu Ari, Bapak Dyas, dan seluruh Tim Penguji atas bimbingan dan kesempatan yang diberikan. Mohon maaf apabila ada tutur kata yang kurang berkenan.*
>
> *Wabillahi taufiq wal hidayah,  
> Wassalamu’alaikum Warahmatullahi Wabarakatuh."*

