-- ================================================================
-- UAS Web Application Development - Universitas Cakrawala
-- Nama Mahasiswa : Halimah Sukmawaty
-- Mata Kuliah    : Web Application Development (WAD05)
-- Studi Kasus    : Sistem Manajemen Inventaris Gudang (InventoHub)
-- Database Engine: MySQL / MariaDB (InnoDB)
-- ================================================================

CREATE DATABASE IF NOT EXISTS `db_inventaris_gudang` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_inventaris_gudang`;

-- --------------------------------------------------------
-- 1. Tabel: users (Autentikasi & Otorisasi Pengguna)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `transactions`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'staff') NOT NULL DEFAULT 'staff',
  `remember_token` VARCHAR(100) NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 2. Tabel: categories (Master Kategori Barang)
-- --------------------------------------------------------
CREATE TABLE `categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `code` VARCHAR(20) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 3. Tabel: products (Master Data Produk & Stok)
-- --------------------------------------------------------
CREATE TABLE `products` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` BIGINT UNSIGNED NOT NULL,
  `sku` VARCHAR(50) NOT NULL UNIQUE,
  `name` VARCHAR(150) NOT NULL,
  `purchase_price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `selling_price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `stock` INT NOT NULL DEFAULT 0,
  `min_stock` INT NOT NULL DEFAULT 5,
  `unit` VARCHAR(20) NOT NULL DEFAULT 'Unit',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 4. Tabel: transactions (Riwayat Transaksi Stok Masuk/Keluar)
-- --------------------------------------------------------
CREATE TABLE `transactions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `type` ENUM('in', 'out') NOT NULL,
  `quantity` INT NOT NULL,
  `reference_number` VARCHAR(50) NOT NULL,
  `notes` TEXT NULL,
  `transaction_date` DATE NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_transactions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_transactions_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- DATA SEEDERS AWAL (DUMMY DATA TESTING)
-- Password default: password123 (bcrypt hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi)
-- ================================================================

-- Users
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Administrator Gudang', 'admin@invento.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(2, 'Halimah Sukmawaty (Staff)', 'halimah.sukmawaty@cakrawala.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff');

-- Categories
INSERT INTO `categories` (`id`, `name`, `code`, `description`) VALUES
(1, 'Elektronik & Gadget', 'CAT-ELK', 'Komponen komputer, periferal, dan perangkat elektronik'),
(2, 'Alat Tulis Kantor', 'CAT-ATK', 'Perlengkapan operasional kantor, kertas, dan map'),
(3, 'Perabotan Gudang', 'CAT-PRB', 'Palet kayu, rak besi penyimpanan, kardus packing'),
(4, 'Keamanan & APD', 'CAT-APD', 'Helm keselamatan, rompi reflektor, sarung tangan heavy duty');

-- Products
INSERT INTO `products` (`id`, `category_id`, `sku`, `name`, `purchase_price`, `selling_price`, `stock`, `min_stock`, `unit`) VALUES
(1, 1, 'PRD-ELK-001', 'Barcode Scanner Wireless 2.4G', 350000.00, 480000.00, 45, 10, 'Unit'),
(2, 1, 'PRD-ELK-002', 'Thermal Label Printer 80mm', 750000.00, 950000.00, 18, 5, 'Unit'),
(3, 2, 'PRD-ATK-001', 'Kertas HVS A4 80gr Rim', 45000.00, 55000.00, 120, 20, 'Rim'),
(4, 2, 'PRD-ATK-002', 'Lakban Coklat Heavy Duty 48mm', 12000.00, 18000.00, 4, 15, 'Roll'),
(5, 3, 'PRD-PRB-001', 'Palet Plastik Heavy Duty 120x100', 220000.00, 290000.00, 60, 10, 'Pcs'),
(6, 4, 'PRD-APD-001', 'Helm Proyek Safety Standar SNI', 65000.00, 95000.00, 25, 8, 'Pcs'),
(7, 4, 'PRD-APD-002', 'Sarung Tangan Karet Nitrile Box', 50000.00, 75000.00, 3, 10, 'Box');

-- Transactions
INSERT INTO `transactions` (`id`, `user_id`, `product_id`, `type`, `quantity`, `reference_number`, `notes`, `transaction_date`) VALUES
(1, 1, 1, 'in', 50, 'TRX-IN-20261001-001', 'Penerimaan barang dari supplier PT Logistik Prima', '2026-10-01'),
(2, 2, 1, 'out', 5, 'TRX-OUT-20261005-001', 'Pengeluaran untuk tim inventory gudang A', '2026-10-05'),
(3, 1, 3, 'in', 150, 'TRX-IN-20261010-002', 'Restock stok ATK kantor pusat', '2026-10-10'),
(4, 2, 3, 'out', 30, 'TRX-OUT-20261012-002', 'Distribusi ke divisi logistik & admin', '2026-10-12'),
(5, 1, 4, 'in', 20, 'TRX-IN-20261015-003', 'Pengadaan lakban packaging', '2026-10-15'),
(6, 2, 4, 'out', 16, 'TRX-OUT-20261020-003', 'Pemakaian packaging batch 42', '2026-10-20');

