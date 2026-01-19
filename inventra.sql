-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 19, 2026 at 02:47 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventra`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Workstations', 'Unit komputasi performa tinggi yang dioptimalkan untuk pengembangan perangkat lunak, rendering kompleks, dan alur kerja kreatif intensif.', NULL, NULL),
(2, 'Photography', 'Sistem pencitraan canggih, lensa optik, dan perlengkapan pendukung produksi visual untuk dokumentasi fidelitas tinggi.', NULL, NULL),
(3, 'Peripherals', 'Antarmuka perangkat keras dan perangkat output visual yang dirancang untuk meningkatkan produktivitas serta ergonomi stasiun kerja.', NULL, NULL),
(4, 'Audio & Sound System', 'Perangkat perekaman audio profesional dan sistem reproduksi suara untuk kebutuhan produksi multimedia serta studio.', NULL, NULL),
(5, 'Mobile Testing Units', 'Perangkat seluler dan tablet yang didedikasikan untuk pengujian aplikasi lintas platform dan verifikasi antarmuka pengguna.', NULL, NULL),
(6, 'Studio Lighting', 'Sistem pencahayaan terkontrol, panel LED, dan modifikator cahaya untuk mendukung konsistensi kualitas produksi visual di studio.', NULL, NULL),
(7, 'Creative Display Solutions', 'Monitor referensi dengan akurasi warna tinggi dan solusi docking station untuk integrasi ekosistem kerja multi-display.', NULL, NULL);

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
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `quantity_repair` int NOT NULL DEFAULT '0',
  `quantity_broken` int NOT NULL DEFAULT '0',
  `condition` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Good',
  `room` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Gudang Utama',
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `category_id`, `quantity`, `quantity_repair`, `quantity_broken`, `condition`, `room`, `description`, `image`, `created_at`, `updated_at`) VALUES
(1, 'MacBook Pro M3 Max 16\"', 1, 17, 0, 1, 'Good', 'Development Lab', 'Space Black, 64GB RAM, 1TB SSD', 'items/FGi3IUH43d31UBlG1Ob2DYx7tWkZgx0BPIo4izxs.jpg', NULL, NULL),
(2, 'Sony A7 IV Mirrorless', 2, 6, 0, 0, 'Good', 'Creative Studio', 'Body Only with 4K Video support', 'items/SvYYrtRiO7fEghXEd97lPUO6eXC77OZ9AyunVeSc.jpg', NULL, NULL),
(3, 'Keychron Q1 Pro', 3, 20, 0, 2, 'Good', 'Open Space Office', 'Mechanical Keyboard Wireless Carbon Grey', 'items/0nYdQCyItU5yRLnt2363L0sVEAkvvswWaICg2Ue4.jpg', NULL, NULL),
(5, 'Mac Studio M2 Ultra', 1, 5, 0, 0, 'Good', 'Open Space Office', NULL, 'items/31TmfiTh06RLXbDkYN9GFPJHhSNKA54njTj3oJPB.jpg', NULL, NULL),
(6, 'ASUS ROG Zephyrus G16', 1, 5, 0, 0, 'Good', 'Open Space Office', NULL, 'items/hTwMkATGNBR8zwt0OBT6ONFsCnjKjXTsgGqHl55C.jpg', NULL, NULL),
(7, 'Canon EOS R5', 2, 10, 0, 0, 'Good', 'Creative Studio', NULL, 'items/W1Z2Ljq5y99CBb8aTQl14vKEpzWEn4O4z1LPz8Rm.jpg', NULL, NULL),
(8, 'Lensa Sony FE 24-70mm f/2.8 GM II', 2, 1, 1, 0, 'Good', 'Creative Studio', NULL, 'items/XwVOVcGlOwoMA5cH8Kw73MK5Aq0OE71aFXI2tPqE.jpg', NULL, NULL),
(9, 'Tripod Manfrotto 055', 2, 17, 0, 0, 'Good', 'Creative Studio', NULL, 'items/wKYHuz0cATSoqSJeyQZ5fKe58AuEBe2Mjf3YyKAJ.jpg', NULL, NULL),
(11, 'Logitech MX Master 3S', 3, 30, 0, 0, 'Good', 'Open Space Office', NULL, 'items/WaH6dQpqCwGwQ6OHRlMtInD47e7ybkU08emOeBaT.jpg', NULL, NULL),
(12, 'Wacom Intuos Pro Large', 3, 3, 0, 0, 'Good', 'Open Space Office', NULL, 'items/4LIb8f7ozYOd5Mbvlp6L2Kb5ZPdVrhSaliODr9Co.jpg', NULL, NULL),
(13, 'Apple Magic Trackpad', 3, 2, 0, 0, 'Good', 'Open Space Office', NULL, 'items/bPiK7CfuyvCcnjQd0cCB25LgIf8TApCiIcu75jtP.jpg', NULL, NULL),
(14, 'Microphone Shure SM7B', 4, 2, 0, 0, 'Good', 'Creative Studio', NULL, 'items/SGY2Y5BOwqaq8B3dAQZzBBV8GgPvw4L1dVds4ZDx.jpg', NULL, NULL),
(15, 'Focusrite Scarlett 4i4', 4, 2, 0, 0, 'Good', 'Creative Studio', NULL, 'items/GNR6JYNkEtfCa8JAGPlIf3i9dfrgL2JCsHWm8AWD.jpg', NULL, NULL),
(16, 'Headphone Beyerdynamic DT 990 Pro', 4, 0, 1, 0, 'Good', 'Creative Studio', NULL, 'items/X4ynr6taCzradRgOdZkIILyBWs6EYuIeViHw3VZq.jpg', NULL, NULL),
(17, 'Yamaha HS5 Studio Monitor', 4, 1, 0, 0, 'Good', 'Creative Studio', NULL, 'items/phBAAbfgtNCWofB5ofuFItT0AN3pd88VM6sLd6cD.jpg', NULL, NULL),
(18, 'iPhone 15 Pro Max', 5, 2, 0, 0, 'Good', 'Open Space Office', NULL, 'items/pstlzDh2D8HbB2ncWHXj5hwuVyhhPdcbCfXKVwEY.jpg', NULL, NULL),
(19, 'Google Pixel 8 Pro', 5, 2, 0, 0, 'Good', 'Open Space Office', NULL, 'items/sghbrRqpwwo3Or91DiS8mAwJ8EmMWNRKJzh2VAcg.jpg', NULL, NULL),
(20, 'Samsung Galaxy S24 Ultra', 5, 2, 0, 0, 'Good', 'Open Space Office', NULL, 'items/3mnXa4FRZkGPHUZCcag3xW3fBRKq9vGCRSv9Y2g9.jpg', NULL, NULL),
(21, 'Aputure LS 600d Pro', 6, 2, 0, 0, 'Good', 'Creative Studio', NULL, 'items/UZNlu629xwzq7TE53kYjVJn3TDGzzwUlBpnb9hqW.jpg', NULL, NULL),
(22, 'Godox QR-P90 Softbox', 6, 2, 0, 1, 'Good', 'Creative Studio', NULL, 'items/M9inpeCTFdjUC7cHV9t2hEV1S5JvhHAzdY9hQLwz.jpg', NULL, NULL),
(23, 'Nanlite Pavotube III', 6, 4, 1, 0, 'Good', 'Creative Studio', NULL, 'items/csSdLmW9nxzHC9uRV9Ki2DlQaTezYMgkIOQ1JHyV.jpg', NULL, NULL);

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
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `quantity_borrowed` int NOT NULL DEFAULT '1',
  `loan_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`id`, `item_id`, `user_id`, `status`, `approved_by`, `due_date`, `quantity_borrowed`, `loan_date`) VALUES
(1, 3, 2, 'returned', NULL, '2026-01-11', 1, '2026-01-09 22:56:00'),
(2, 23, 2, 'returned', NULL, '2026-01-13', 1, '2026-01-11 17:42:55'),
(3, 23, 2, 'returned', NULL, '2026-01-19', 1, '2026-01-11 17:43:06'),
(4, 20, 2, 'rejected', NULL, '2026-01-11', 1, '2026-01-10 00:10:23'),
(5, 19, 2, 'returned', NULL, '2026-01-21', 1, '2026-01-11 21:49:19'),
(8, 14, 6, 'returned', NULL, '2026-01-14', 1, '2026-01-12 21:55:52'),
(9, 17, 2, 'returned', NULL, '2026-01-17', 1, '2026-01-15 20:25:50'),
(10, 22, 6, 'returned', NULL, '2026-01-15', 1, '2026-01-16 01:25:05'),
(11, 16, 4, 'returned', NULL, '2026-01-18', 1, '2026-01-16 21:12:04'),
(12, 21, 7, 'returned', NULL, '2026-01-24', 1, '2026-01-16 22:58:49'),
(13, 17, 2, 'returned', NULL, '2026-01-20', 1, '2026-01-18 23:31:52'),
(14, 11, 2, 'returned', NULL, '2026-01-21', 2, '2026-01-19 00:07:40'),
(15, 8, 6, 'returned', NULL, '2026-01-21', 1, '2026-01-19 01:14:31'),
(16, 22, 6, 'returned', NULL, '2026-01-20', 1, '2026-01-19 01:40:48');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_logs`
--

CREATE TABLE `maintenance_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `repair_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `payment_status` enum('pending','paid','waived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `damage_note` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `technician_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date NOT NULL,
  `estimated_finish` date DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `status` enum('pending','ongoing','fixed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `maintenance_logs`
--

INSERT INTO `maintenance_logs` (`id`, `item_id`, `repair_cost`, `payment_status`, `damage_note`, `technician_name`, `start_date`, `estimated_finish`, `completion_date`, `status`, `cost`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 1, 0.00, 'paid', 'layar pecah', 'jaya computer', '2026-01-07', '2026-01-08', '2026-01-09', 'fixed', 0.00, NULL, '2026-01-15 23:38:07', NULL),
(2, 20, 0.00, 'paid', 'lcd pecah', 'jaya computer', '2026-01-12', '2026-01-13', '2026-01-12', 'fixed', 120000.00, NULL, '2026-01-15 23:38:12', NULL),
(3, 1, 0.00, 'paid', 'layar pecah', 'jaya computer', '2026-01-13', '2026-01-14', '2026-01-13', 'fixed', 100.00, NULL, '2026-01-15 23:38:16', NULL),
(4, 2, 250000.00, 'paid', 'ganti baterai', 'jaya computer', '2026-01-16', NULL, '2026-01-16', 'fixed', 0.00, '2026-01-15 20:03:53', '2026-01-15 23:38:22', NULL),
(5, 3, 250000.00, 'paid', 'keyboard rusak - beli baru', 'Top Sell', '2026-01-16', '2026-01-17', '2026-01-16', 'fixed', 0.00, '2026-01-15 20:05:10', '2026-01-16 21:17:18', NULL),
(6, 17, 520000.00, 'paid', 'adlah', NULL, '2026-01-16', NULL, '2026-01-16', 'fixed', 0.00, '2026-01-15 20:37:54', '2026-01-16 21:17:23', 2),
(7, 23, 2.00, 'pending', 'php artisan optimize:clear', NULL, '2026-01-19', NULL, NULL, 'ongoing', 0.00, '2026-01-18 23:04:55', '2026-01-18 23:04:55', 2),
(8, 17, 100000.00, 'paid', 'p', NULL, '2026-01-19', NULL, '2026-01-19', 'fixed', 0.00, '2026-01-18 23:43:07', '2026-01-19 00:52:57', 2),
(9, 16, 200000.00, 'pending', 'kabel putus', NULL, '2026-01-19', NULL, NULL, 'ongoing', 0.00, '2026-01-18 23:59:55', '2026-01-18 23:59:55', 4),
(10, 21, 150000.00, 'paid', 'case hilang', NULL, '2026-01-19', NULL, '2026-01-19', 'fixed', 0.00, '2026-01-19 00:46:15', '2026-01-19 00:51:25', 7),
(11, 16, 0.00, 'pending', 'kabel putus', 'jaya computer', '2026-01-20', NULL, NULL, 'ongoing', 0.00, '2026-01-19 00:52:35', '2026-01-19 00:52:35', 4),
(12, 22, 500000.00, 'pending', 'beli baru', 'Top Sell', '2026-01-19', NULL, NULL, 'ongoing', 0.00, '2026-01-19 01:13:10', '2026-01-19 01:13:10', 6),
(13, 8, 100000.00, 'pending', 'akak', 'Top Sell', '2026-01-19', NULL, NULL, 'ongoing', 0.00, '2026-01-19 01:35:03', '2026-01-19 01:35:03', 6);

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
(4, '2025_11_24_044627_create_categories_table', 1),
(5, '2025_11_24_044630_create_items_table', 1),
(6, '2025_11_24_045830_create_loans_table', 1),
(7, '2025_11_24_045902_create_activity_logs_table', 1),
(8, '2025_12_08_120053_add_role_to_users_table', 1),
(9, '2025_12_25_071444_create_locations_table', 1),
(10, '2025_12_25_071846_add_location_id_to_items_table', 1),
(11, '2025_12_26_063654_simplify_items_location', 1),
(12, '2025_12_28_094425_add_avatar_to_users_table', 1),
(13, '2025_12_28_102047_add_image_to_items_table', 1),
(14, '2025_12_31_042842_add_condition_counts_to_items_table', 1),
(15, '2026_01_01_080722_add_repair_and_broken_to_items_table', 1),
(16, '2026_01_02_082859_add_due_date_to_loans_table', 1),
(17, '2026_01_04_040508_add_quantity_borrowed_to_loans_table', 1),
(18, '2026_01_04_043529_add_approved_by_to_loans_table', 1),
(19, '2026_01_06_071825_create_maintenance_logs_table', 1),
(20, '2026_01_10_054916_add_loan_date_to_loans_table', 2),
(21, '2026_01_13_064159_add_payment_status_to_maintenance_logs_table', 3),
(22, '2026_01_15_060235_rename_issue_detail_in_maintenance_logs_table', 4),
(23, '2026_01_15_064847_final_fix_maintenance_logs', 5),
(24, '2026_01_19_052540_add_condition_columns_to_items_table', 6);

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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('6pVY2pCX8VtawJalMMp257Drkn2dUcjLChOogkfA', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaGRzNmZGZWhFMDRZV1p1YnlLSjhmbzltQXlrWlI4TWViRGo5QUx6MiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9sb2Fucz9zdGF0dXM9cGVuZGluZyI7czo1OiJyb3V0ZSI7czoxNzoiYWRtaW4ubG9hbnMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1768829464),
('QOcIAyGltua0YfJbnTTRh6gkoPfz3YRo2AzR6ijp', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQm1CbzlvQ1REVksweGtMbHR2U1VNOVN6R2lOOGNXVHdJRzRYd20waiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9tYWludGVuYW5jZSI7czo1OiJyb3V0ZSI7czoyMzoiYWRtaW4ubWFpbnRlbmFuY2UuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1768812386),
('wHAOoBep3lTsdKcVYoBS0jCjrSf7afd0PmUgg2Jc', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYWM2TjhuNUNTckZUVGl6Q1N3dXI3Uml6akpCWEVkU2VXaUl0enlJViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC91c2VyL2xvYW5zIjtzOjU6InJvdXRlIjtzOjE2OiJ1c2VyLmxvYW5zLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDt9', 1768830082);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `avatar`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'SuperAdmin1', 'admin@mail.com', 'avatars/3Mo76t8bgXYToGjdLtTJ2phrFWzUnqiCk8KM1vqk.jpg', NULL, '$2y$12$62zY2gPcakPTcyOQ3hBW6OgdLafXgft2eMLCQKORZywi8fTnujm6C', 'admin', 'ELKoBMnyc0GdjXFFIDsTGQPMVAYTEiYYWiH5Yml8Wfu2I96nw7IjOo6i8QSy', '2026-01-06 23:43:01', '2026-01-09 00:42:01'),
(2, 'amanda', 'amanda@mail.com', 'avatars/ocHZWKnLCnEzuONaFnFYRgKVObuSWjsmIA7VKi2M.jpg', NULL, '$2y$12$4nsitKlpgPf/7vUJ9c.zJObprrzN6o7rC3mB4PnW8tYUNi2Dr623W', 'user', NULL, '2026-01-09 00:49:18', '2026-01-09 22:46:13'),
(4, 'Silviana', 'silvi@mail.com', NULL, NULL, '$2y$12$.Kg4Zbf3.IA..S/vpaAHleLtOFP9/ZFwggCHnmgGP/emfLUX0OcUq', 'user', NULL, '2026-01-11 21:55:53', '2026-01-11 21:55:53'),
(6, 'Bu Leni', 'leni@mail.com', NULL, NULL, '$2y$12$WLS7pOVC/NRVuK/2teUSeOsBGR3B6WIjD8YnqiFs35SBz/mMp22Lu', 'user', NULL, '2026-01-12 21:51:21', '2026-01-12 21:51:21'),
(7, 'daffa', 'dafanda@mail.com', NULL, NULL, '$2y$12$JGhuzPo3L3DXaiTicnjG..P6uWVMxZuig1Ly79awWfqk1JsOHUMei', 'user', NULL, '2026-01-16 22:57:29', '2026-01-19 00:21:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `items_category_id_foreign` (`category_id`);

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
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loans_item_id_foreign` (`item_id`),
  ADD KEY `loans_user_id_foreign` (`user_id`),
  ADD KEY `loans_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `maintenance_logs_item_id_foreign` (`item_id`),
  ADD KEY `maintenance_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `loans_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `maintenance_logs`
--
ALTER TABLE `maintenance_logs`
  ADD CONSTRAINT `maintenance_logs_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `maintenance_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
