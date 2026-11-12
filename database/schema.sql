-- =========================================================================
-- BARU: RE-STRUKTURISASI DATABASE DENGAN SCHEMA FIXED DARI USER
-- =========================================================================

CREATE DATABASE IF NOT EXISTS db_perkebunan_nyapah_banyu;
USE db_perkebunan_nyapah_banyu;

SET FOREIGN_KEY_CHECKS = 0;

-- Drop existing tables to ensure a clean install
DROP TABLE IF EXISTS log_aktivitas;
DROP TABLE IF EXISTS operasional;
DROP TABLE IF EXISTS sdm;
DROP TABLE IF EXISTS operasional_sdm;
DROP TABLE IF EXISTS tanam;
DROP TABLE IF EXISTS pengiriman;
DROP TABLE IF EXISTS pembibitan;
DROP TABLE IF EXISTS perawatan;
DROP TABLE IF EXISTS panen;
DROP TABLE IF EXISTS jadwal;
DROP TABLE IF EXISTS area_kebun;
DROP TABLE IF EXISTS pegawai;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS komoditas;
DROP TABLE IF EXISTS status;
DROP TABLE IF EXISTS perlengkapan;
DROP TABLE IF EXISTS peralatan;

-- 1. TABEL KOMODITAS
CREATE TABLE komoditas (
    id_komoditas BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_komoditas VARCHAR(100) NOT NULL,
    keterangan TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. TABEL USER
CREATE TABLE users (
    id_user BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Owner', 'Pengurus') NOT NULL DEFAULT 'Pengurus',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. TABEL PEGAWAI
CREATE TABLE pegawai (
    id_pegawai BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_user BIGINT UNSIGNED NOT NULL,
    nama_pegawai VARCHAR(100) NOT NULL,
    jabatan VARCHAR(50) NOT NULL,
    no_tlp VARCHAR(15) NULL,
    alamat TEXT NULL,
    status ENUM('Aktif', 'Tidak Aktif') NOT NULL DEFAULT 'Aktif',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. TABEL AREA KEBUN
CREATE TABLE area_kebun (
    id_area BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_area VARCHAR(100) NOT NULL,
    id_komoditas BIGINT UNSIGNED NOT NULL,
    luas_lahan DECIMAL(10,2) NOT NULL,
    jumlah_tanaman INT NOT NULL,
    id_pegawai BIGINT UNSIGNED NOT NULL,
    status_area ENUM('Aktif', 'Tidak Aktif') NOT NULL DEFAULT 'Aktif',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_komoditas) REFERENCES komoditas(id_komoditas) ON DELETE RESTRICT,
    FOREIGN KEY (id_pegawai) REFERENCES pegawai(id_pegawai) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. TABEL JADWAL
CREATE TABLE jadwal (
    id_jadwal BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_komoditas BIGINT UNSIGNED NOT NULL,
    id_area BIGINT UNSIGNED NOT NULL,
    jenis_kegiatan ENUM('panen', 'perawatan', 'pengiriman', 'pembibitan') NOT NULL,
    frekuensi INT NOT NULL,
    keterangan TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_komoditas) REFERENCES komoditas(id_komoditas) ON DELETE CASCADE,
    FOREIGN KEY (id_area) REFERENCES area_kebun(id_area) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. TABEL STATUS
CREATE TABLE status (
    id_status BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_status VARCHAR(100) NOT NULL,
    keterangan TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. TABEL PERLENGKAPAN
CREATE TABLE perlengkapan (
    id_perlengkapan BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_perlengkapan VARCHAR(100) NOT NULL,
    keterangan TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. TABEL PERALATAN
CREATE TABLE peralatan (
    id_peralatan BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_peralatan VARCHAR(100) NOT NULL,
    keterangan TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. TABEL PANEN
CREATE TABLE panen (
    id_panen BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_jadwal BIGINT UNSIGNED NOT NULL,
    id_area BIGINT UNSIGNED NOT NULL,
    id_komoditas BIGINT UNSIGNED NOT NULL,
    id_peralatan BIGINT UNSIGNED NOT NULL,
    id_perlengkapan BIGINT UNSIGNED NOT NULL,
    id_status BIGINT UNSIGNED NOT NULL,
    tanggal DATE NOT NULL,
    gross_panen DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    netto_panen DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    keterangan TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_jadwal) REFERENCES jadwal(id_jadwal) ON DELETE RESTRICT,
    FOREIGN KEY (id_area) REFERENCES area_kebun(id_area) ON DELETE CASCADE,
    FOREIGN KEY (id_komoditas) REFERENCES komoditas(id_komoditas) ON DELETE RESTRICT,
    FOREIGN KEY (id_peralatan) REFERENCES peralatan(id_peralatan) ON DELETE RESTRICT,
    FOREIGN KEY (id_perlengkapan) REFERENCES perlengkapan(id_perlengkapan) ON DELETE RESTRICT,
    FOREIGN KEY (id_status) REFERENCES status(id_status) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. TABEL PERAWATAN
CREATE TABLE perawatan (
    id_perawatan BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_jadwal BIGINT UNSIGNED NOT NULL,
    id_area BIGINT UNSIGNED NOT NULL,
    id_komoditas BIGINT UNSIGNED NOT NULL,
    id_pegawai BIGINT UNSIGNED NOT NULL,
    id_peralatan BIGINT UNSIGNED NOT NULL,
    id_perlengkapan BIGINT UNSIGNED NOT NULL,
    jenis_perawatan ENUM('Pemupukan', 'Obat Hama', 'Pemupukan dan Obat Hama') NOT NULL,
    tanggal_perawatan DATE NOT NULL,
    keterangan TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_jadwal) REFERENCES jadwal(id_jadwal) ON DELETE RESTRICT,
    FOREIGN KEY (id_area) REFERENCES area_kebun(id_area) ON DELETE CASCADE,
    FOREIGN KEY (id_komoditas) REFERENCES komoditas(id_komoditas) ON DELETE RESTRICT,
    FOREIGN KEY (id_pegawai) REFERENCES pegawai(id_pegawai) ON DELETE RESTRICT,
    FOREIGN KEY (id_peralatan) REFERENCES peralatan(id_peralatan) ON DELETE RESTRICT,
    FOREIGN KEY (id_perlengkapan) REFERENCES perlengkapan(id_perlengkapan) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. TABEL PEMBIBITAN
CREATE TABLE pembibitan (
    id_pembibitan BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_jadwal BIGINT UNSIGNED NOT NULL,
    id_komoditas BIGINT UNSIGNED NOT NULL,
    id_pegawai BIGINT UNSIGNED NOT NULL,
    id_peralatan BIGINT UNSIGNED NOT NULL,
    id_perlengkapan BIGINT UNSIGNED NOT NULL,
    tanggal_pembibitan DATE NOT NULL,
    jumlah_bibit INT NOT NULL DEFAULT 0,
    jumlah_penyulaman INT NOT NULL DEFAULT 0,
    keterangan TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_jadwal) REFERENCES jadwal(id_jadwal) ON DELETE RESTRICT,
    FOREIGN KEY (id_komoditas) REFERENCES komoditas(id_komoditas) ON DELETE RESTRICT,
    FOREIGN KEY (id_pegawai) REFERENCES pegawai(id_pegawai) ON DELETE RESTRICT,
    FOREIGN KEY (id_peralatan) REFERENCES peralatan(id_peralatan) ON DELETE RESTRICT,
    FOREIGN KEY (id_perlengkapan) REFERENCES perlengkapan(id_perlengkapan) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. TABEL PENGIRIMAN
CREATE TABLE pengiriman (
    id_pengiriman BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_panen BIGINT UNSIGNED NOT NULL,
    id_jadwal BIGINT UNSIGNED NOT NULL,
    id_komoditas BIGINT UNSIGNED NOT NULL,
    id_status BIGINT UNSIGNED NOT NULL,
    tanggal_pengiriman DATE NOT NULL,
    jumlah_dikirim DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    harga_jual DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    total_penjualan DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    keterangan TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_panen) REFERENCES panen(id_panen) ON DELETE CASCADE,
    FOREIGN KEY (id_jadwal) REFERENCES jadwal(id_jadwal) ON DELETE RESTRICT,
    FOREIGN KEY (id_komoditas) REFERENCES komoditas(id_komoditas) ON DELETE RESTRICT,
    FOREIGN KEY (id_status) REFERENCES status(id_status) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. TABEL TANAM
CREATE TABLE tanam (
    id_tanam BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_area BIGINT UNSIGNED NOT NULL,
    id_peralatan BIGINT UNSIGNED NOT NULL,
    id_perlengkapan BIGINT UNSIGNED NOT NULL,
    tanggal_tanam DATE NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_area) REFERENCES area_kebun(id_area) ON DELETE CASCADE,
    FOREIGN KEY (id_peralatan) REFERENCES peralatan(id_peralatan) ON DELETE RESTRICT,
    FOREIGN KEY (id_perlengkapan) REFERENCES perlengkapan(id_perlengkapan) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. TABEL OPERASIONAL SDM
CREATE TABLE operasional_sdm (
    id_upah BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_jadwal BIGINT UNSIGNED NOT NULL,
    id_area BIGINT UNSIGNED NOT NULL,
    id_komoditas BIGINT UNSIGNED NOT NULL,
    nama_pekerja VARCHAR(100) NOT NULL,
    jenis_pekerjaan VARCHAR(50) NOT NULL,
    jumlah_hari INT NOT NULL DEFAULT 0,
    tarif_harian DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    total_upah DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    tanggal_bayar DATE NOT NULL,
    bukti_pembayaran VARCHAR(255) NULL,
    keterangan TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_jadwal) REFERENCES jadwal(id_jadwal) ON DELETE RESTRICT,
    FOREIGN KEY (id_area) REFERENCES area_kebun(id_area) ON DELETE CASCADE,
    FOREIGN KEY (id_komoditas) REFERENCES komoditas(id_komoditas) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================================================================
-- SEEDING DATA AWAL (SEEDER DUMMY)
-- =========================================================================

-- Seed Komoditas
INSERT INTO komoditas (id_komoditas, nama_komoditas, keterangan) VALUES
(1, 'kopi', 'Komoditas Kopi Arabika & Robusta'),
(2, 'sawit', 'Komoditas Kelapa Sawit TBS'),
(3, 'lada', 'Komoditas Lada Putih & Hitam');

-- Seed Users (Password: 'password' yang di-hash)
INSERT INTO users (id_user, username, email, password, role) VALUES 
(1, 'owner', 'owner@nyapahbanyu.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Owner'),
(2, 'sawit', 'sawit@nyapahbanyu.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pengurus'),
(3, 'kopi', 'kopi@nyapahbanyu.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pengurus'),
(4, 'lada', 'lada@nyapahbanyu.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pengurus');

-- Seed Pegawai
INSERT INTO pegawai (id_pegawai, id_user, nama_pegawai, jabatan, no_tlp, alamat, status) VALUES
(1, 1, 'Haji Banyu (Owner)', 'Owner', '08123456789', 'Lampung Utara', 'Aktif'),
(2, 2, 'Ahmad (Sawit)', 'Pengurus Lahan Sawit', '08123456780', 'Lampung Utara', 'Aktif'),
(3, 3, 'Budi (Kopi)', 'Pengurus Lahan Kopi', '08123456781', 'Lampung Utara', 'Aktif'),
(4, 4, 'Siti (Lada)', 'Pengurus Lahan Lada', '08123456782', 'Lampung Utara', 'Aktif');

-- Seed Area Kebun
INSERT INTO area_kebun (id_area, nama_area, id_komoditas, luas_lahan, jumlah_tanaman, id_pegawai, status_area) VALUES
(1, 'Kebun Kopi Blok A', 1, 5.5, 5500, 3, 'Aktif'),
(2, 'Kebun Sawit Blok B', 2, 12.0, 1600, 2, 'Aktif'),
(3, 'Kebun Lada Blok C', 3, 2.5, 3000, 4, 'Aktif');

-- Seed Status
INSERT INTO status (id_status, nama_status, keterangan) VALUES
(1, 'proses', 'Pengiriman Sedang Diproses'),
(2, 'terkirim', 'Pengiriman Sudah Sampai'),
(3, 'batal', 'Pengiriman Dibatalkan');

-- Seed Perlengkapan & Peralatan
INSERT INTO perlengkapan (id_perlengkapan, nama_perlengkapan, keterangan) VALUES
(1, 'Perlengkapan Standar', 'Alat pelindung diri, keranjang, pupuk');
INSERT INTO peralatan (id_peralatan, nama_peralatan, keterangan) VALUES
(1, 'Peralatan Standar', 'Egrek, cangkul, gunting pangkas');

-- Seed Tanam ( Planting Date )
INSERT INTO tanam (id_tanam, id_area, id_peralatan, id_perlengkapan, tanggal_tanam) VALUES
(1, 1, 1, 1, '2023-01-15'),
(2, 2, 1, 1, '2022-03-10'),
(3, 3, 1, 1, '2024-08-01');

-- Seed Jadwal
INSERT INTO jadwal (id_jadwal, id_komoditas, id_area, jenis_kegiatan, frekuensi, keterangan) VALUES
(1, 1, 1, 'panen', 12, 'Jadwal Panen Kopi Tahunan'),
(2, 2, 2, 'panen', 24, 'Jadwal Panen Sawit Rutin'),
(3, 3, 3, 'panen', 12, 'Jadwal Panen Lada'),
(4, 1, 1, 'perawatan', 24, 'Pemeliharaan Kopi'),
(5, 2, 2, 'perawatan', 24, 'Pemeliharaan Sawit'),
(6, 3, 3, 'perawatan', 24, 'Pemeliharaan Lada'),
(7, 1, 1, 'pembibitan', 4, 'Pembibitan Kopi'),
(8, 2, 2, 'pembibitan', 4, 'Pembibitan Sawit'),
(9, 3, 3, 'pembibitan', 4, 'Pembibitan Lada'),
(10, 1, 1, 'pengiriman', 12, 'Pengiriman Kopi'),
(11, 2, 2, 'pengiriman', 12, 'Pengiriman Sawit'),
(12, 3, 3, 'pengiriman', 12, 'Pengiriman Lada');

-- Seed Panen
INSERT INTO panen (id_panen, id_jadwal, id_area, id_komoditas, id_peralatan, id_perlengkapan, id_status, tanggal, gross_panen, netto_panen, keterangan) VALUES
(1, 1, 1, 1, 1, 1, 2, '2026-04-10', 1500.00, 1420.00, 'Robusta, Premium'),
(2, 2, 2, 2, 1, 1, 2, '2026-04-18', 8500.00, 8300.00, 'Validated'),
(3, 1, 1, 1, 1, 1, 2, '2026-05-12', 1200.00, 1150.00, 'Arabika, Premium');

-- Seed Perawatan
INSERT INTO perawatan (id_perawatan, id_jadwal, id_area, id_komoditas, id_pegawai, id_peralatan, id_perlengkapan, jenis_perawatan, tanggal_perawatan, keterangan) VALUES
(1, 4, 1, 1, 3, 1, 1, 'Pemupukan dan Obat Hama', '2026-04-05', 'Biaya: 3200000 | Nota: nota_pupuk_kopi.jpg | Pupuk: 120.00 | Obat: 15.00 | Sulam: 15'),
(2, 5, 2, 2, 2, 1, 1, 'Pemupukan dan Obat Hama', '2026-04-12', 'Biaya: 8750000 | Nota: nota_sawit_operasional.png | Pupuk: 450.00 | Obat: 50.00 | Sulam: 5'),
(3, 6, 3, 3, 4, 1, 1, 'Pemupukan dan Obat Hama', '2026-05-02', 'Biaya: 1500000 | Nota: nota_lada_bibit.jpg | Pupuk: 45.00 | Obat: 8.00 | Sulam: 50');

-- Seed Pengiriman
INSERT INTO pengiriman (id_pengiriman, id_panen, id_jadwal, id_komoditas, id_status, tanggal_pengiriman, jumlah_dikirim, harga_jual, total_penjualan, keterangan) VALUES
(1, 2, 11, 2, 2, '2026-04-19', 8300.00, 2600.00, 21580000.00, 'Tujuan: PT Sawit Lampung Sejahtera | Kendaraan: Colt Diesel BE 8312 FG | Supir: Supriyadi'),
(2, 3, 10, 1, 1, '2026-05-13', 1150.00, 45000.00, 51750000.00, 'Tujuan: Gudang Kopi Bandar Lampung | Kendaraan: L300 BE 9011 CO | Supir: Kadek');

-- Seed Pembibitan
INSERT INTO pembibitan (id_pembibitan, id_jadwal, id_komoditas, id_pegawai, id_peralatan, id_perlengkapan, tanggal_pembibitan, jumlah_bibit, jumlah_penyulaman, keterangan) VALUES
(1, 8, 2, 2, 1, 1, '2026-01-10', 500, 0, 'Kondisi: sangat_baik | Lokasi: Rumah Semai Utara'),
(2, 7, 1, 3, 1, 1, '2026-02-15', 1200, 0, 'Kondisi: baik | Lokasi: Bedengan Blok A'),
(3, 9, 3, 4, 1, 1, '2026-03-01', 400, 0, 'Kondisi: kurang_baik | Lokasi: Greenhouse Barat');

-- Seed Operasional SDM
INSERT INTO operasional_sdm (id_upah, id_jadwal, id_area, id_komoditas, nama_pekerja, jenis_pekerjaan, jumlah_hari, tarif_harian, total_upah, tanggal_bayar, bukti_pembayaran, keterangan) VALUES
(1, 2, 2, 2, 'Slamet', 'Pemanen Sawit', 6, 120000.00, 720000.00, '2026-04-18', NULL, 'Gaji panen sawit'),
(2, 4, 1, 1, 'Yanto', 'Pemupuk Blok A', 3, 100000.00, 300000.00, '2026-04-05', NULL, 'Gaji pemeliharaan blok kopi'),
(3, 6, 3, 3, 'Dewi', 'Penyemprot Lada', 2, 90000.00, 180000.00, '2026-05-02', NULL, 'Gaji pemeliharaan blok lada');

SET FOREIGN_KEY_CHECKS = 1;
