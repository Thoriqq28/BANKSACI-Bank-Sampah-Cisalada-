-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 01, 2026 at 06:33 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bank_sampah`
--

-- --------------------------------------------------------

--
-- Table structure for table `auto_debits`
--

CREATE TABLE `auto_debits` (
  `id` bigint UNSIGNED NOT NULL,
  `nasabah_id` bigint UNSIGNED NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `tanggal_eksekusi` int NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_executed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_sampah`
--

CREATE TABLE `jenis_sampah` (
  `id` bigint UNSIGNED NOT NULL,
  `kategori_id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga_beli` int NOT NULL DEFAULT '0',
  `harga_per_kg` decimal(12,2) NOT NULL DEFAULT '0.00',
  `satuan` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kg',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori_sampah`
--

CREATE TABLE `kategori_sampah` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori_sampah`
--

INSERT INTO `kategori_sampah` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(7, 'PLASTIK | Botol Plastik | 3200', '2026-07-31 00:49:15', '2026-07-31 00:49:15'),
(8, 'PLASTIK | Aqua Gelas dan Semacamnya | 3000', '2026-07-31 00:49:52', '2026-07-31 00:49:52'),
(9, 'PLASTIK | Ember | 2500', '2026-07-31 00:50:14', '2026-07-31 00:50:14'),
(10, 'KERTAS | Kardus | 1800', '2026-07-31 00:51:06', '2026-07-31 00:51:06'),
(11, 'KERTAS | Buku | 1200', '2026-07-31 00:52:04', '2026-07-31 00:52:04'),
(12, 'BESI | Kaleng | 2000', '2026-07-31 00:53:22', '2026-07-31 00:53:22'),
(13, 'BESI | Besi | 4500', '2026-07-31 00:53:43', '2026-07-31 00:53:43'),
(14, 'LOGAM | Tembaga | 180000', '2026-07-31 00:54:35', '2026-07-31 00:54:35'),
(15, 'LOGAM | Kuningan | 100000', '2026-07-31 00:55:08', '2026-07-31 00:55:08');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_07_09_000001_create_nasabah_table', 1),
(5, '2026_07_09_000002_create_saldo_nasabah_table', 1),
(6, '2026_07_09_000003_create_kategori_sampah_table', 1),
(7, '2026_07_09_000004_create_jenis_sampah_table', 1),
(8, '2026_07_09_000005_create_setoran_table', 1),
(9, '2026_07_09_000006_create_setoran_detail_table', 1),
(10, '2026_07_09_000007_create_penarikan_table', 1),
(11, '2026_07_10_081459_add_role_to_users_table', 1),
(12, '2026_07_13_003400_add_user_id_to_nasabah_table', 1),
(13, '2026_07_14_100149_add_nomor_rumah_to_nasabah_table', 1),
(14, '2026_07_16_071751_add_harga_to_jenis_sampah_table', 1),
(15, '2026_07_26_135222_add_status_to_penarikan_table', 1),
(16, '2026_07_31_044259_create_auto_debits_table', 2),
(17, '2026_07_31_095420_create_notifications_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `nasabah`
--

CREATE TABLE `nasabah` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `kode_nasabah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rt` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rw` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomor_rumah` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `saldo` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nasabah`
--

INSERT INTO `nasabah` (`id`, `user_id`, `kode_nasabah`, `nama`, `alamat`, `no_hp`, `rt`, `rw`, `nomor_rumah`, `saldo`, `created_at`, `updated_at`) VALUES
(1, 2, 'BS-0008', 'Warga Testing', 'Jl. Sukamaju No. 12', '081234567890', '00', '00', NULL, '0.00', '2026-07-26 09:18:17', '2026-07-29 20:56:50'),
(2, 3, 'BS-0009', 'adhul', 'Perumahan Saung Indah Blok C4 no 40', '0893249265', '006', '003', 'D1', '133000.00', '2026-07-26 09:18:26', '2026-07-30 08:22:34'),
(3, 4, 'BS-0010', 'ervan', 'Perumahan Saung Indah Blok C4 no 40', '0892468965', '004', '003', 'dusun 1', '112500.00', '2026-07-26 16:42:39', '2026-07-29 23:48:13'),
(4, 5, 'BS-0011', 'ijal', 'Perumahan Saung Indah Blok C4 no 40', '0879869869', '003', '006', '-', '0.00', '2026-07-30 00:19:16', '2026-07-30 00:19:16'),
(6, 10, 'BS-0013', 'adven', 'perum bhakti praja', '081284498659', '003', '002', NULL, '14000.00', '2026-07-30 21:38:19', '2026-07-30 21:54:30'),
(7, 11, 'BS-0014', 'JIVVY BERUK', 'dusun 2', '080808080808', '06', '002', NULL, '80000.00', '2026-07-30 22:28:48', '2026-07-30 22:58:48'),
(8, 12, 'BS-0015', 'Barjo', 'Cijanggot', '0895375660051', '006', '002', NULL, '40000.00', '2026-07-30 22:58:17', '2026-07-30 23:13:17');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('9e702ad0-8a73-42e2-9c1b-92406dd197ce', 'App\\Notifications\\PeringatanPotonganSaldoNotification', 'App\\Models\\Nasabah', 1, '{\"title\":\"Peringatan Pemotongan Saldo \\u26a0\\ufe0f\",\"jumlah\":15000,\"tanggal\":\"28 Juli 2026\",\"nama_layanan\":\"Biaya Administrasi Bulanan\",\"message\":\"Saldo Anda telah dipotong sebesar Rp 15.000 pada 28 Juli 2026 untuk pembayaran Biaya Administrasi Bulanan.\",\"url\":\"https:\\/\\/bling-breach-whole.ngrok-free.dev\\/nasabah\\/dashboard\"}', NULL, '2026-07-31 02:59:32', '2026-07-31 02:59:32');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penarikan`
--

CREATE TABLE `penarikan` (
  `id` bigint UNSIGNED NOT NULL,
  `nasabah_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `status` enum('pending','selesai','cancel') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `tanggal` datetime NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penarikan`
--

INSERT INTO `penarikan` (`id`, `nasabah_id`, `user_id`, `jumlah`, `status`, `tanggal`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '10000.00', 'cancel', '2026-07-26 00:00:00', 'Tarik tunai mandiri via UI', '2026-07-26 09:42:37', '2026-07-28 23:34:43'),
(2, 2, 3, '40000.00', 'selesai', '2026-07-27 00:00:00', 'Tarik Saldo Cash (Tunai)', '2026-07-26 23:22:48', '2026-07-28 23:34:33'),
(3, 3, 1, '10000.00', 'selesai', '2026-07-29 00:00:00', 'Tarik tunai mandiri via UI', '2026-07-28 23:37:26', '2026-07-28 23:41:39'),
(4, 2, 3, '15000.00', 'cancel', '2026-07-30 00:00:00', 'Tarik Saldo Cash (Tunai)', '2026-07-29 20:34:45', '2026-07-29 20:35:16'),
(5, 2, 3, '10000.00', 'selesai', '2026-07-30 00:00:00', 'Tarik Saldo Cash (Tunai)', '2026-07-29 20:51:11', '2026-07-29 20:51:31'),
(6, 2, 3, '300000.00', 'selesai', '2026-07-30 00:00:00', 'Tarik Saldo Cash (Tunai)', '2026-07-30 00:23:59', '2026-07-30 07:38:14'),
(7, 6, 10, '20000.00', 'selesai', '2026-07-31 00:00:00', 'Tarik E-Wallet (DANA - 81284498659)', '2026-07-30 21:39:55', '2026-07-30 21:40:09'),
(8, 7, 11, '20000.00', 'cancel', '2026-07-31 00:00:00', 'Tarik E-Wallet (DANA - 89625748224)', '2026-07-30 22:36:10', '2026-07-30 22:37:43'),
(9, 7, 11, '20000.00', 'selesai', '2026-07-31 00:00:00', 'Tarik Saldo Cash (Tunai)', '2026-07-30 22:37:04', '2026-07-30 22:38:09'),
(10, 8, 12, '10000.00', 'selesai', '2026-07-31 00:00:00', 'Tarik E-Wallet (DANA - 895375660051)', '2026-07-30 23:04:26', '2026-07-30 23:05:13');

-- --------------------------------------------------------

--
-- Table structure for table `saldo_nasabah`
--

CREATE TABLE `saldo_nasabah` (
  `id` bigint UNSIGNED NOT NULL,
  `nasabah_id` bigint UNSIGNED NOT NULL,
  `saldo` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `saldo_nasabah`
--

INSERT INTO `saldo_nasabah` (`id`, `nasabah_id`, `saldo`, `created_at`, `updated_at`) VALUES
(1, 2, '133000.00', '2026-07-26 09:18:26', '2026-07-31 03:29:11'),
(2, 3, '112500.00', '2026-07-26 16:42:39', '2026-07-31 03:29:11'),
(3, 1, '0.00', '2026-07-29 21:06:20', '2026-07-31 03:29:11'),
(4, 4, '0.00', '2026-07-30 00:19:16', '2026-07-31 03:29:11'),
(6, 6, '14000.00', '2026-07-30 21:38:33', '2026-07-31 03:29:11'),
(7, 7, '80000.00', '2026-07-30 22:37:43', '2026-07-31 03:29:11'),
(8, 8, '40000.00', '2026-07-30 22:58:48', '2026-07-31 03:29:11');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `setoran`
--

CREATE TABLE `setoran` (
  `id` bigint UNSIGNED NOT NULL,
  `nasabah_id` bigint UNSIGNED NOT NULL,
  `sampah_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL COMMENT 'Petugas',
  `tanggal` date NOT NULL,
  `total_berat` decimal(8,2) NOT NULL DEFAULT '0.00',
  `total_harga` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `setoran`
--

INSERT INTO `setoran` (`id`, `nasabah_id`, `sampah_id`, `user_id`, `tanggal`, `total_berat`, `total_harga`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, 1, '2026-07-26', '8.00', '20000.00', '2026-07-26 09:42:10', '2026-07-26 09:42:10'),
(2, 2, NULL, 1, '2026-07-26', '7.00', '35000.00', '2026-07-26 09:57:34', '2026-07-26 09:57:34'),
(3, 2, NULL, 1, '2026-07-26', '6.00', '12000.00', '2026-07-26 09:58:02', '2026-07-26 09:58:02'),
(4, 3, NULL, 1, '2026-07-29', '20.00', '50000.00', '2026-07-28 22:37:39', '2026-07-28 22:37:39'),
(12, 3, NULL, 1, '2026-07-30', '8.00', '20000.00', '2026-07-29 23:34:42', '2026-07-29 23:34:42'),
(17, 3, NULL, 1, '2026-07-30', '9.00', '22500.00', '2026-07-29 23:48:01', '2026-07-29 23:48:01'),
(18, 3, NULL, 1, '2026-07-30', '6.00', '30000.00', '2026-07-29 23:48:13', '2026-07-29 23:48:13'),
(19, 2, NULL, 1, '2026-07-30', '8.00', '16000.00', '2026-07-30 00:00:12', '2026-07-30 00:00:12'),
(20, 2, NULL, 1, '2026-07-30', '80.00', '400000.00', '2026-07-30 00:07:58', '2026-07-30 00:07:58'),
(21, 6, NULL, 1, '2026-07-31', '17.00', '34000.00', '2026-07-30 21:39:02', '2026-07-30 21:39:02'),
(22, 7, NULL, 1, '2026-07-31', '20.00', '100000.00', '2026-07-30 22:34:02', '2026-07-30 22:34:02'),
(23, 8, NULL, 1, '2026-07-31', '10.00', '50000.00', '2026-07-30 23:00:56', '2026-07-30 23:00:56');

-- --------------------------------------------------------

--
-- Table structure for table `setoran_detail`
--

CREATE TABLE `setoran_detail` (
  `id` bigint UNSIGNED NOT NULL,
  `setoran_id` bigint UNSIGNED NOT NULL,
  `jenis_sampah_id` bigint UNSIGNED NOT NULL,
  `berat` decimal(8,2) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nasabah',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@banksampah.com', NULL, '$2y$12$BYymXCKYf01f5zy6Hdb6EesU3mspV65RpmHxb9GhKM92HfOQBWviC', 'admin', '0Iip07BrkQ1GsGmCt1UU72Z4xiC9KP3DX65O00QVljbehdJHmmo0J48Xn1ck', '2026-07-26 09:18:17', '2026-07-26 09:18:17'),
(2, 'Warga Testing', 'warga@gmail.com', NULL, '$2y$12$2jYkJr2TXgB6m51wEz8GGeY2wlM.llp4Bw8Z6HVrhYCMGgGGezrdq', 'nasabah', NULL, '2026-07-26 09:18:17', '2026-07-26 09:18:17'),
(3, 'adhul', 'adhul@gmail.com', NULL, '$2y$12$lz.naRfNNBgOcHmfLE0Gy.10zcAm850SRps3tr85IHIaJsQDZXWr.', 'nasabah', NULL, '2026-07-26 09:18:26', '2026-07-27 06:47:55'),
(4, 'ervan', 'ervan@gmail.com', NULL, '$2y$12$8KDrcvtSFqU9H3UWP2jP/OKwc0CSXKFgon3w1x8H5Rwbw0zCeTAb.', 'nasabah', NULL, '2026-07-26 16:42:39', '2026-07-26 16:42:39'),
(5, 'ijal', 'ijal@gmail.com', NULL, '$2y$12$JYYvePmxeDWGgeSs6Yz11OzlNQDW0dKjQ9Wy95vLoTInwp2/YCfKe', 'nasabah', NULL, '2026-07-30 00:19:16', '2026-07-30 00:19:16'),
(6, 'adventhio', 'advnthio12@gmail.com', NULL, '$2y$12$35lJJlcKycPBvg/98iGELuaeqwLEk5uwAExTeIMAajLYJ1ogrlGKu', 'nasabah', NULL, '2026-07-30 20:09:40', '2026-07-30 20:09:40'),
(7, 'Nabila Rosa Harvania Widiyanto', 'nabilarosaharvaniawidi@gmail.com', NULL, '$2y$12$4h4KCjwRXfaacGh0/KUYDu8gP.R4STe1TLmJF2gB8tzFNi.ndLGKS', 'nasabah', NULL, '2026-07-30 20:46:11', '2026-07-30 20:46:11'),
(8, 'Thoriq Reva Ardian', 'thoriqra968@gmail.com', NULL, '$2y$12$4MPkpD1hVBaLLiNeNbKfHe6DjdTGj4oHRrHb5eNM.gwPAbGDIr2Lu', 'nasabah', NULL, '2026-07-30 21:08:29', '2026-07-30 21:08:29'),
(9, 'thio', 'adventhiochristoval@gmail.com', NULL, '$2y$12$oj3NiOZOW4FM81VhLwje4.nfATJx1PMAW/t8rWV1xIbrHOsp6n8XC', 'nasabah', NULL, '2026-07-30 21:25:15', '2026-07-30 21:25:15'),
(10, 'adven', 'adventhio5@gmail.com', NULL, '$2y$12$ecCvCDkTpc9lzJtN7xuMUebiwWi7TruZqHDrtnesOBdnyLJRceStq', 'nasabah', NULL, '2026-07-30 21:38:19', '2026-07-30 21:38:19'),
(11, 'JIVVY BERUK', 'eastdukati207@gmail.com', NULL, '$2y$12$fAW0.htNI5KU7eBz5du/kOeT6ZueF7BTffr5eOUI6Cu0rxmJxND.e', 'nasabah', NULL, '2026-07-30 22:28:48', '2026-07-30 22:28:48'),
(12, 'Barjo', 'asprob59@gmail.com', NULL, '$2y$12$vY7E1z4NN8FXSwHmwq/UcOkw9N9b.rLdcXxNYqMbbVv5H2NZNs.Ju', 'nasabah', NULL, '2026-07-30 22:58:17', '2026-07-30 22:58:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auto_debits`
--
ALTER TABLE `auto_debits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auto_debits_nasabah_id_foreign` (`nasabah_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jenis_sampah`
--
ALTER TABLE `jenis_sampah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jenis_sampah_kategori_id_foreign` (`kategori_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori_sampah`
--
ALTER TABLE `kategori_sampah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nasabah`
--
ALTER TABLE `nasabah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nasabah_kode_nasabah_unique` (`kode_nasabah`),
  ADD KEY `nasabah_user_id_foreign` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `penarikan`
--
ALTER TABLE `penarikan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penarikan_nasabah_id_foreign` (`nasabah_id`),
  ADD KEY `penarikan_user_id_foreign` (`user_id`);

--
-- Indexes for table `saldo_nasabah`
--
ALTER TABLE `saldo_nasabah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `saldo_nasabah_nasabah_id_foreign` (`nasabah_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `setoran`
--
ALTER TABLE `setoran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `setoran_nasabah_id_foreign` (`nasabah_id`),
  ADD KEY `setoran_sampah_id_foreign` (`sampah_id`),
  ADD KEY `setoran_user_id_foreign` (`user_id`);

--
-- Indexes for table `setoran_detail`
--
ALTER TABLE `setoran_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `setoran_detail_setoran_id_foreign` (`setoran_id`),
  ADD KEY `setoran_detail_jenis_sampah_id_foreign` (`jenis_sampah_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auto_debits`
--
ALTER TABLE `auto_debits`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_sampah`
--
ALTER TABLE `jenis_sampah`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori_sampah`
--
ALTER TABLE `kategori_sampah`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `nasabah`
--
ALTER TABLE `nasabah`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `penarikan`
--
ALTER TABLE `penarikan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `saldo_nasabah`
--
ALTER TABLE `saldo_nasabah`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `setoran`
--
ALTER TABLE `setoran`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `setoran_detail`
--
ALTER TABLE `setoran_detail`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auto_debits`
--
ALTER TABLE `auto_debits`
  ADD CONSTRAINT `auto_debits_nasabah_id_foreign` FOREIGN KEY (`nasabah_id`) REFERENCES `nasabah` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jenis_sampah`
--
ALTER TABLE `jenis_sampah`
  ADD CONSTRAINT `jenis_sampah_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_sampah` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nasabah`
--
ALTER TABLE `nasabah`
  ADD CONSTRAINT `nasabah_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penarikan`
--
ALTER TABLE `penarikan`
  ADD CONSTRAINT `penarikan_nasabah_id_foreign` FOREIGN KEY (`nasabah_id`) REFERENCES `nasabah` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `penarikan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `saldo_nasabah`
--
ALTER TABLE `saldo_nasabah`
  ADD CONSTRAINT `saldo_nasabah_nasabah_id_foreign` FOREIGN KEY (`nasabah_id`) REFERENCES `nasabah` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `setoran`
--
ALTER TABLE `setoran`
  ADD CONSTRAINT `setoran_nasabah_id_foreign` FOREIGN KEY (`nasabah_id`) REFERENCES `nasabah` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `setoran_sampah_id_foreign` FOREIGN KEY (`sampah_id`) REFERENCES `jenis_sampah` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `setoran_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `setoran_detail`
--
ALTER TABLE `setoran_detail`
  ADD CONSTRAINT `setoran_detail_jenis_sampah_id_foreign` FOREIGN KEY (`jenis_sampah_id`) REFERENCES `jenis_sampah` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `setoran_detail_setoran_id_foreign` FOREIGN KEY (`setoran_id`) REFERENCES `setoran` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
