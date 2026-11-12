# 🌿 Sistem Informasi Pengelolaan Perkebunan Nyapah Banyu

> **SI Perkebunan Nyapah Banyu** adalah platform Enterprise Resource Planning (ERP) agribisnis berbasis web untuk pengelolaan operasional, rantai pasok panen, logistik pengiriman pabrik, dan penggajian SDM perkebunan multi-komoditas (*Kopi*, *Kelapa Sawit*, dan *Lada*). Dibangun dengan arsitektur **MVC (Model-View-Controller)** modern berbasis **Laravel 11**, **MySQL**, dan **Bootstrap 5.3**.

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11" />
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP Version" />
  <img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap" />
  <img src="https://img.shields.io/badge/DomPDF-PDF%20Engine-red?style=for-the-badge" alt="DomPDF" />
  <img src="https://img.shields.io/badge/Architecture-MVC%20Pattern-orange?style=for-the-badge" alt="Architecture" />
  <img src="https://img.shields.io/badge/Platform-Web%20Responsive-blue?style=for-the-badge" alt="Platform" />
  <img src="https://img.shields.io/badge/License-MIT-yellow?style=for-the-badge" alt="License" />
</p>

---

## 📌 Project Metadata

| Informasi | Keterangan |
| :--- | :--- |
| **Nama Aplikasi** | **Sistem Informasi Pengelolaan Perkebunan Nyapah Banyu** |
| **Deskripsi** | Solusi digitalisasi agribisnis untuk pemantauan blok kebun, kontrol pemeliharaan pupuk/pestisida, pencatatan panen gross/netto, penggajian SDM, logistik distribusi, dan rekapitulasi laba rugi. |
| **Komoditas Didukung**| ☕ **Kopi** (Arabika & Robusta), 🌴 **Kelapa Sawit** (TBS & Brondolan), 🌿 **Lada** (Putih & Hitam) |
| **Aktor Pengguna** | 2 Peran Utama: **Owner** (Pemilik Perkebunan) & **Pengurus Lahan** (Field Manager) |
| **Pengembang** | **Muhammad Farhan Aprilianto** |
| **Pola Arsitektur** | **Model-View-Controller (MVC) + Role-Based Access Control (RBAC)** |
| **Backend Framework**| **Laravel 11.x** (PHP ^8.2) |
| **Frontend & UI** | **Blade Template Engine, Bootstrap 5.3, FontAwesome & Chart Integration** |
| **Database Engine** | **MySQL 8.0+ / MariaDB** (Relational with Foreign Key Integrity) |
| **PDF Reporting** | **barryvdh/laravel-dompdf** |
| **Repository URL** | [github.com/MuhammadFarhanAprilianto/si-perkebunan-nyapah-banyu](https://github.com/MuhammadFarhanAprilianto/si-perkebunan-nyapah-banyu) |

---

## 🌟 Arsitektur Ekosistem & Fitur Berdasarkan Peran

```text
========================================================================================
                      SI PERKEBUNAN NYAPAH BANYU ECOSYSTEM
========================================================================================
  [ 👑 OWNER / PEMILIK ]                         [ 🚜 PENGURUS LAHAN ]
  ├── 📊 Executive Dashboard Utama               ├── 📈 Dashboard Spesifik Komoditas
  ├── 💰 Monitoring Arus Kas & Finansial         ├── 🌱 Monitoring Pembibitan & Tanam
  ├── 🗺️ Evaluasi Produktivitas Lahan            ├── 🧪 Pencatatan Pupuk & Obat Hama
  ├── 🗂️ Manajemen Master Data (User & Pegawai)  ├── 🌾 Input Transaksi Hasil Panen
  ├── 🛡️ Audit Trail (Log Aktivitas Sistem)      ├── 👥 Absensi & Penggajian SDM
  ├── ❌ Otoritas Hapus Transaksi & Data Sensitif ├── 🚚 Pencatatan Pengiriman Pabrik
  └── 📑 Rekapitulasi Laporan Terpadu & PDF      └── 📄 Unduh Laporan Operasional Lahan
========================================================================================
```

---

## 🚀 Fitur Unggulan Sistem

### 1. 📊 Executive & Analytical Dashboards
- **Executive Dashboard (Owner)**: Menampilkan metrik makro seluruh perkebunan — total luas lahan produktif, akumulasi tonase panen, total biaya operasional, dan laba kotor penjualan.
- **Commodity Dedicated Dashboards**: Halaman analitik khusus per komoditas:
  - ☕ **Dashboard Kopi**: Statistik pemeliharaan, jadwal petik merah, dan perbandingan varietas Robusta vs Arabika.
  - 🌴 **Dashboard Kelapa Sawit**: Monitoring Tandan Buah Segar (TBS), persentase brondolan lepas, dan rotasi panen.
  - 🌿 **Dashboard Lada**: Pemantauan sulaman tanaman, pemangkasan tiang panjat, dan hasil panen lada.

### 2. 🗺️ Manajemen Area Kebun & Lahan
- Pendataan blok kebun dengan atribut detail: nama blok, luas hektar, estimasi populasi tanaman, usia tanaman dalam bulan, tanggal tanam, dan estimasi periode panen raya.
- Klasifikasi status lahan dinamis: `Produktif`, `Perawatan`, dan `Replanting`.

### 3. 🌱 Pembibitan (*Nursery*) & Penyulaman
- Pencatatan pengadaan bibit varietas unggul dan monitoring kondisi bibit di rumah semai (*Rumah Semai Utara, Bedengan Blok A, Greenhouse*).
- Modul penyulaman (*re-planting*) untuk mencatat penggantian tanaman yang mati/rusak demi menjaga kerapatan populasi pohon.

### 4. 🚜 Operasional & Pemeliharaan Tanaman
- Dokumentasi pemupukan terpadu (volume kilogram pupuk yang diaplikasikan) dan penyemprotan obat hama / fungisida / pestisida.
- Pengunggahan digital bukti nota/kuitansi pembelian bahan operasional.
- Perhitungan otomatis biaya perawatan per blok kebun.

### 5. 🌾 Manajemen Hasil Panen & Estimasi Pendapatan
- Input penimbangan hasil panen kotor (*Gross Weight*), berat bersih setelah sortasi (*Netto Weight*), dan brondolan sawit.
- Integrasi otomatis dengan harga pasar per kilogram untuk kalkulasi total nilai bruto penjualan.

### 6. 🚚 Logistik & Pengiriman ke Pabrik / Pengepul
- Manajemen rantai distribusi: data pabrik tujuan, nomor polisi armada kendaraan, nama supir pengangkut, dan tanggal keberangkatan.
- Pelacakan status pengiriman secara *real-time*: `Proses`, `Terkirim`, atau `Batal`.

### 7. 👥 Manajemen SDM & Penggajian Pekerja
- Pencatatan tenaga kerja harian lepas maupun borongan (Pemanen, Pemupuk, Penyemprot, Perawat Lahan).
- Kalkulasi upah otomatis berdasarkan tarif per hari dan jumlah hari kehadiran.
- Penyimpanan bukti transfer / slip gaji pekerja untuk akuntabilitas finansial.

### 8. 🗂️ Master Data & 🛡️ Audit Trail Log
- Master data komprehensif: Pegawai, Pengguna Sistem, Komoditas, Peralatan Kerja, Perlengkapan, dan Jadwal Perawatan.
- **Log Aktivitas Pengguna (Audit Trail)**: Merekam seluruh aktivitas penting (siapa, melakukan apa, dan kapan) guna mencegah manipulasi data.

### 9. 📑 Laporan Terpadu & Ekspor PDF Otomatis
- Filter laporan multi-parameter: rentang tanggal (*Date Range*) dan pilihan komoditas (*Kopi, Sawit, Lada, atau Semua*).
- Cetak langsung (*Print View*) atau ekspor dokumen laporan siap cetak dalam format **PDF** melalui **Laravel DOMPDF**.

---

## 👥 Matriks Hak Akses Pengguna (RBAC)

| Fitur / Modul | Owner (Pemilik) | Pengurus Lahan |
| :--- | :---: | :---: |
| **Executive Dashboard & Finansial** | ✅ Akses Penuh | ✅ Ringkasan Area |
| **Dashboard Komoditas (Kopi/Sawit/Lada)** | ✅ Ya | ✅ Ya |
| **Kelola Area Lahan & Pembibitan** | ✅ CRUD Penuh | ✅ CRUD Penuh |
| **Input Data Panen & Operasional** | ✅ CRUD Penuh | ✅ Input & Edit |
| **Hapus Transaksi Panen / Biaya** | ✅ Ya | ❌ Dibatasi |
| **Kelola Upah SDM & Bukti Pembayaran** | ✅ CRUD Penuh | ✅ Input & Edit |
| **Pengiriman Hasil Kebun ke Pabrik** | ✅ CRUD Penuh | ✅ Input & Pantau |
| **Master Data (User, Pegawai, Komoditas)** | ✅ Ya | ❌ Tidak |
| **Audit Trail (Log Aktivitas Sistem)** | ✅ Ya | ❌ Tidak |
| **Ekspor & Unduh Laporan PDF** | ✅ Ya | ✅ Ya |

---

## 🛠️ Tech Stack & Dependencies

```plaintext
├── Backend Framework : Laravel 11.x
├── Runtime & Language : PHP >= 8.2 (pdo_mysql, mbstring, openssl, gd)
├── Database Engine    : MySQL 8.0+ / MariaDB
├── Web Architecture   : Model-View-Controller (MVC) + Clean Service Layer
├── UI & Styling       : Blade Engine, Bootstrap 5.3, FontAwesome Icons
├── Reporting Engine   : Barryvdh Laravel-DomPDF
├── Authentication     : Laravel Breeze Multi-Role Middleware
└── Version Control    : Git & GitHub
```

---

## 📋 Prasyarat Sistem (*System Requirements*)

Pastikan lingkungan lokal Anda memenuhi spesifikasi berikut:
- **PHP** `>= 8.2` (dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `gd`)
- **Composer** `>= 2.x`
- **MySQL / MariaDB Server** (melalui Laragon, XAMPP, atau MySQL Standalone)
- **Web Browser Modern** (Google Chrome, Microsoft Edge, Mozilla Firefox)

---

## ⚙️ Panduan Instalasi & Menjalankan Aplikasi

Ikuti panduan mudah berikut untuk menginstal dan menjalankan aplikasi di komputer lokal:

### 1. Clone Repository
```bash
git clone https://github.com/MuhammadFarhanAprilianto/si-perkebunan-nyapah-banyu.git
cd si-perkebunan-nyapah-banyu
```

### 2. Install Dependensi PHP
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
Salin file template `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Buka file `.env` dan sesuaikan koneksi database MySQL Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_perkebunan_nyapah_banyu
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Setup Database & Seeding Data
Buat database bernama `db_perkebunan_nyapah_banyu` di MySQL Anda, kemudian lakukan salah satu opsi setup berikut:

* **Opsi A (Rekomendasi - Import File SQL Lengkap)**:
  ```bash
  mysql -u root -p db_perkebunan_nyapah_banyu < database/schema.sql
  ```
* **Opsi B (Artisan Migrate & Seed)**:
  ```bash
  php artisan migrate --seed
  ```

### 6. Buat Simbolik Link Storage
```bash
php artisan storage:link
```

### 7. Jalankan Local Development Server
```bash
php artisan serve
```
Buka peramban web dan akses aplikasi di: **`http://localhost:8000`**

---

## 🔑 Akun Demo Pengujian

Setelah database diimpor, gunakan akun default berikut untuk mencoba seluruh fitur:

| Peran Akun | Email Login | Password | Cakupan Akses |
| :--- | :--- | :--- | :--- |
| **👑 Owner (Pemilik)** | `owner@nyapahbanyu.com` | `password` | Seluruh Modul, Master Data, Audit Trail & Laporan Finansial |
| **🌴 Pengurus Sawit** | `sawit@nyapahbanyu.com` | `password` | Operasional Lahan Sawit, Panen TBS, Pengiriman & SDM |
| **☕ Pengurus Kopi** | `kopi@nyapahbanyu.com` | `password` | Operasional Kebun Kopi, Panen Robusta/Arabika & SDM |
| **🌿 Pengurus Lada** | `lada@nyapahbanyu.com` | `password` | Operasional Kebun Lada, Panen Lada & SDM |

---

## 📂 Struktur Direktori Proyek

```plaintext
si-perkebunan-nyapah-banyu/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # Controller bisnis (Panen, Operasional, Laporan, SDM, dll)
│   │   ├── Middleware/        # Middleware autentikasi & kontrol hak akses (RoleMiddleware)
│   │   └── Requests/          # Form Request Validation
│   └── Models/                # Model Eloquent ORM (AreaKebun, Panen, Operasional, Sdm, dll)
├── bootstrap/                 # Kernel inisialisasi framework & cache
├── config/                    # Konfigurasi aplikasi, auth, database, dan session
├── database/
│   ├── migrations/            # Migration skema database terstruktur
│   ├── seeders/               # Database seeders untuk data awal
│   └── schema.sql             # Skema DDL & DML terpadu siap impor
├── public/                    # Entry point publik web, aset CSS, JS, dan gambar
├── resources/
│   └── views/                 # Antarmuka sistem Blade Templates
│       ├── area_kebun/        # Antarmuka Kelola Lahan & Blok Kebun
│       ├── panen/             # Antarmuka Input & Rekapitulasi Hasil Panen
│       ├── operasional/       # Antarmuka Pemeliharaan, Pupuk & Pestisida
│       ├── sdm/               # Antarmuka Penggajian & Absensi SDM
│       ├── pengiriman/        # Antarmuka Rantai Pasok Logistik Pabrik
│       ├── pembibitan/        # Antarmuka Rumah Semai Bibit
│       ├── tanam/             # Antarmuka Penyulaman Tanaman
│       ├── laporan/           # Antarmuka Rekap Laporan & Template PDF
│       └── layouts/           # Master Layout, Header, & Sidebar Navigasi
├── routes/                    # Routing aplikasi (web.php, auth.php, console.php)
├── storage/                   # Berkas log, cache, dan hasil kompilasi Blade
├── .env.example               # Template variabel environment publik
├── .gitignore                 # Konfigurasi proteksi privasi & berkas rahasia
└── LICENSE                    # Lisensi lisensi terbuka (MIT License)
```

---

## 🔒 Keamanan & Privasi Data

- **Zero Secret Leakage**: File kredensial asli `.env` dilindungi secara penuh dan tidak pernah disertakan ke repositori publik.
- **Penyembunyian Data Sensitif**: Nota fisik transaksi, slip gaji pekerja, dan berkas log internal diabaikan oleh Git guna menjamin privasi operasional.

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan portofolio pengembangan sistem informasi agribisnis dan dilisensikan di bawah [MIT License](LICENSE).

---

<p align="center">
  Developed with ❤️ by <strong><a href="https://github.com/MuhammadFarhanAprilianto">Muhammad Farhan Aprilianto</a></strong>
</p>
