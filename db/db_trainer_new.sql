-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 11, 2025 at 08:37 AM
-- Server version: 8.0.30
-- PHP Version: 8.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_trainer`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('newsmaker23_cache_17ba0791499db908433b80f37c5fbc89b870084b', 'i:2;', 1750930373),
('newsmaker23_cache_17ba0791499db908433b80f37c5fbc89b870084b:timer', 'i:1750930373;', 1750930373),
('newsmaker23_cache_b1d5781111d84f7b3fe45a0852e59758cd7a87e5', 'i:1;', 1750754609),
('newsmaker23_cache_b1d5781111d84f7b3fe45a0852e59758cd7a87e5:timer', 'i:1750754609;', 1750754609),
('newsmaker23_cache_faturrahman86.fr@gmail.com|127.0.0.1', 'i:1;', 1752116619),
('newsmaker23_cache_faturrahman86.fr@gmail.com|127.0.0.1:timer', 'i:1752116619;', 1752116619);

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
-- Table structure for table `certificate_awards`
--

CREATE TABLE `certificate_awards` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `batch_number` int NOT NULL,
  `average_score` double NOT NULL,
  `certificate_uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `awarded_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ebooks`
--

CREATE TABLE `ebooks` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `folder_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ebooks`
--

INSERT INTO `ebooks` (`id`, `title`, `slug`, `deskripsi`, `cover`, `file`, `folder_id`, `created_at`, `updated_at`) VALUES
(125, 'Legalitas dan Keamanan 1', 'legalitas-dan-keamanan-1', 'eBook \"Legalitas dan Keamanan\" membahas pentingnya pemahaman aspek hukum dan keamanan di era digital, di mana informasi dan teknologi berkembang pesat. Melalui buku ini, pembaca diajak mengenal dasar-dasar legalitas, perlindungan data, serta risiko keamanan yang sering diabaikan baik dalam kehidupan pribadi maupun bisnis. Dengan bahasa yang sederhana dan contoh kasus yang relevan, eBook ini memberikan panduan praktis untuk membangun kesadaran hukum dan menjaga keamanan informasi di tengah tantangan dunia modern.', 'uploads/cover/1752127615_E-Book Legalitas dan Keamanan-gambar-0.jpg', 'uploads/ebook/1752127615_E-Book Legalitas dan Keamanan.pdf', 1, '2025-07-09 23:06:55', '2025-07-09 23:19:33'),
(126, 'Pengenalan PBK', 'pengenalan-pbk', 'Perdagangan Berjangka Komoditi (PBK) adalah suatu kegiatan jual beli kontrak berjangka atas komoditas tertentu, seperti emas, minyak, atau mata uang, yang diperdagangkan di bursa berjangka. PBK memungkinkan para pelaku pasar untuk melakukan lindung nilai (hedging) terhadap fluktuasi harga, sekaligus memberikan peluang investasi dengan potensi keuntungan maupun risiko yang tinggi. Sebagai instrumen keuangan yang diatur oleh Badan Pengawas Perdagangan Berjangka Komoditi (Bappebti), PBK dijalankan dalam kerangka hukum yang ketat untuk menjaga keamanan, transparansi, dan integritas pasar.', 'uploads/cover/1752132919_E-Book Pengenalan PBK-gambar-0.jpg', 'uploads/ebook/1752132919_E-Book Pengenalan PBK.pdf', 1, '2025-07-10 00:35:19', '2025-07-10 00:35:19');

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
-- Table structure for table `folder_ebooks`
--

CREATE TABLE `folder_ebooks` (
  `id` bigint UNSIGNED NOT NULL,
  `folder_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `folder_ebooks`
--

INSERT INTO `folder_ebooks` (`id`, `folder_name`, `Deskripsi`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Test Nama Materi 1', 'Test Deskripsi Materi', 'test-nama-materi-1', '2025-07-09 20:35:40', '2025-07-09 21:13:38'),
(3, 'Test Nama Materi 2', 'Test Nama Materi 2', 'test-nama-materi-2', '2025-07-09 23:49:10', '2025-07-09 23:49:10');

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
(4, '2025_06_05_011604_create_ebooks_table', 1),
(5, '2025_06_05_021833_create_post_test_sessions_table', 1),
(6, '2025_06_05_021931_create_post_tests_table', 1),
(7, '2025_06_05_021945_create_post_test_results_table', 1),
(8, '2025_06_19_064834_create_certificate_awards_table', 2),
(9, '2025_07_10_010646_create_folder_ebooks_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('ayatindonesia.ai@gmail.com', '$2y$12$G0VbbIr.ZeoofuUH7lnmV.omcnEEYPO.1tou9DSIBM0mbz2.mf0py', '2025-06-24 01:27:19'),
('faturrahman86.fg@gmail.com', '$2y$12$2vuhG.qbtf75AGYCEUD/GO2mOfjR/wgnpRkiHdeEG69.HFQ9ACRK2', '2025-06-16 18:49:09'),
('laurashakira9@gmail.com', '$2y$12$usuSCXHGyizZvEREztMvJOzcTV.8wL6VYSKZXxlFt4Au6DL1Xf2Da', '2025-06-24 01:29:26'),
('test@example.com', '$2y$12$eeZFPBGlZbU41MKUXpJ87.VKQuaj2P.JmWVYLLcLOMsz0yvx.BwXu', '2025-06-24 00:21:28');

-- --------------------------------------------------------

--
-- Table structure for table `post_tests`
--

CREATE TABLE `post_tests` (
  `id` bigint UNSIGNED NOT NULL,
  `session_id` bigint UNSIGNED NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_a` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_b` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_c` text COLLATE utf8mb4_unicode_ci,
  `option_d` text COLLATE utf8mb4_unicode_ci,
  `correct_option` enum('A','B','C','D') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_tests`
--

INSERT INTO `post_tests` (`id`, `session_id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`, `created_at`, `updated_at`) VALUES
(71, 34, '<p>Pertanyaan percobaan</p>', '1', '2', '3', '4', 'B', '2025-07-09 23:39:17', '2025-07-09 23:39:17'),
(72, 34, '<p>1</p>', '1', '2', '3', '4', 'C', '2025-07-09 23:42:19', '2025-07-09 23:42:19'),
(73, 35, '<p>Test soal</p>', '1', '1', '1', '1', 'D', '2025-07-10 01:52:03', '2025-07-10 01:52:03'),
(74, 35, '<p>1</p>', '1', '1', '1', '1', 'C', '2025-07-10 01:52:14', '2025-07-10 01:52:14');

-- --------------------------------------------------------

--
-- Table structure for table `post_test_results`
--

CREATE TABLE `post_test_results` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `ebook_id` bigint UNSIGNED NOT NULL,
  `session_id` bigint UNSIGNED NOT NULL,
  `score` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_test_results`
--

INSERT INTO `post_test_results` (`id`, `user_id`, `ebook_id`, `session_id`, `score`, `created_at`, `updated_at`) VALUES
(29, 2, 125, 34, 100, '2025-07-10 02:50:30', '2025-07-10 02:50:30'),
(30, 2, 126, 35, 100, '2025-07-10 02:50:53', '2025-07-10 02:50:53');

-- --------------------------------------------------------

--
-- Table structure for table `post_test_sessions`
--

CREATE TABLE `post_test_sessions` (
  `id` bigint UNSIGNED NOT NULL,
  `ebook_id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_test_sessions`
--

INSERT INTO `post_test_sessions` (`id`, `ebook_id`, `title`, `duration`, `created_at`, `updated_at`) VALUES
(34, 125, 'Legalitas dan Keamanan', 20, '2025-07-09 23:29:33', '2025-07-09 23:29:33'),
(35, 126, 'Test', 10, '2025-07-10 01:46:48', '2025-07-10 01:46:48');

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
('Hi6C6M5gMP159BGo8NGbO9dENhG7iAQs6xQ8HB4O', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo5OntzOjY6Il90b2tlbiI7czo0MDoiWW9Ia2s5NkNWTUl4bG43ZE9JYXA5RG5OUmlQeFdZenpFajVoNG1HWCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjU4OiJodHRwOi8vbm1fZWR1a2FzaS50ZXN0L3NlcnRpZmlrYXQvdW5kdWgvdGVzdC1uYW1hLW1hdGVyaS0xIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjI0OiJxdWl6XzM1X3F1ZXN0aW9uc191c2VyXzIiO2E6Mjp7aTowO2k6NzM7aToxO2k6NzQ7fXM6MjU6InF1aXpfMzVfc3RhcnRfdGltZV91c2VyXzIiO086MjU6IklsbHVtaW5hdGVcU3VwcG9ydFxDYXJib24iOjM6e3M6NDoiZGF0ZSI7czoyNjoiMjAyNS0wNy0xMCAwODo1MzoyMS45MjE5OTEiO3M6MTM6InRpbWV6b25lX3R5cGUiO2k6MztzOjg6InRpbWV6b25lIjtzOjM6IlVUQyI7fXM6MjQ6InF1aXpfMzRfcXVlc3Rpb25zX3VzZXJfMiI7YToyOntpOjA7aTo3MjtpOjE7aTo3MTt9czoyNToicXVpel8zNF9zdGFydF90aW1lX3VzZXJfMiI7TzoyNToiSWxsdW1pbmF0ZVxTdXBwb3J0XENhcmJvbiI6Mzp7czo0OiJkYXRlIjtzOjI2OiIyMDI1LTA3LTEwIDA4OjU3OjMzLjIyOTI3NCI7czoxMzoidGltZXpvbmVfdHlwZSI7aTozO3M6ODoidGltZXpvbmUiO3M6MzoiVVRDIjt9fQ==', 1752141274);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('Pria','Wanita') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempat_lahir` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `warga_negara` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `no_tlp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('Admin','Trainer (Eksternal)') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Trainer (Eksternal)',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `warga_negara`, `alamat`, `no_tlp`, `pekerjaan`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Muhammad Faturrahman', 'Admin@newsmaker23.id', 'Pria', 'Jakarta', '2003-06-28', NULL, 'Jalan Kompleks Griya Bukit Jaya 49', '087778376988', 'Programmer', 'Admin', '2025-06-18 22:45:13', '$2y$12$GpR.5G5oSeIDjqwkSWXZyO.f1ef6TY3thbqlJi5LkL/0lBf5g1IO.', NULL, '2025-06-11 18:10:13', '2025-06-18 22:45:13'),
(2, 'Laura Shakira Aisyah Putri', 'laurashakira9@gmail.com', 'Wanita', 'Jakarta', '2002-08-09', 'Indonesia', 'Jalan Kompleks Griya Bukit Jaya N8/14, Tlajung Udik, Gunung Putri, Kab. Bogor', '087778376989', 'Guru/Dosen', 'Trainer (Eksternal)', '2025-06-16 20:35:37', '$2y$12$I/X2.pGpoA.6qm0BfhruOOGEpun1Es2D8ciNxQR6HlSnd8JUcvFt.', NULL, '2025-06-11 20:27:34', '2025-07-10 00:49:30'),
(3, 'Muhammad Daffa Fahcreza', 'Admin@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Admin', NULL, '$2y$12$nKwx/KhpvkEqUPxmxWIQkOXLpQHGUd.53Ps0.oagPOf0pja9FI0oS', NULL, '2025-06-12 20:17:49', '2025-06-12 20:17:49'),
(5, 'Kresno', 'kresno@gmail.com', 'Pria', 'Jakarta', '2025-06-08', 'Indonesia', 'Depok', '0821223131', 'Programmer', 'Trainer (Eksternal)', NULL, '$2y$12$ln6AO55U1ghYGNyqyOirMO074TQ4yU2wDs2tgWZB.dpGzdbfJeOtK', NULL, '2025-06-12 22:49:13', '2025-06-12 22:53:18'),
(6, 'Test User', 'ayatindonesia.ai@gmail.com', 'Pria', 'Bogor', '2007-06-13', NULL, 'Jalan Kompleks Griya Bukit Jaya 49', '087778376933', 'Arsitek', 'Trainer (Eksternal)', '2025-06-24 01:08:19', '$2y$12$KUYzLOG1xPO3oGnIx3nGoujXd1JmOD6awql95wIg/5wHTDIsR9n0K', 'VpFpv0LNDxRGRgl8d4s2OCMexlYVgAvJLgdPR3wZSCIdYooKKXy5DnEv2qRC', '2025-06-12 23:03:27', '2025-06-24 01:09:44'),
(7, 'Test User', 'test@example.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Trainer (Eksternal)', '2025-06-19 01:27:41', '$2y$12$ebuhmukMVIEMi9LNyE91C.zCwY9VRzBn69u8/fLHaweqlxPMAmuj6', 'OflnVLdavA', '2025-06-19 01:27:41', '2025-06-19 01:27:41'),
(9, 'Faturrahman Ptra', 'johndoe@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Trainer (Eksternal)', '2025-06-23 19:32:56', '$2y$12$oVTWX8.VKIK3NXGe/HGf1.57q1fiNfWHMRwIGui.cYLdlAovdRmeu', NULL, '2025-06-23 19:32:22', '2025-06-23 19:32:56'),
(10, 'Faturrahman Ptra', 'faturrahman86.fr@gmail.com', 'Pria', 'Jakarta', '2007-06-17', 'Indonesia', 'Jalan Kompleks Griya Bukit Jaya 49', '087778376911', 'Arsitek', 'Trainer (Eksternal)', '2025-06-24 01:42:29', '$2y$12$tGo0KUSVnuft8iK23ZVX9OUBqRxC/o2CKc0Xh/1quMJhqcIMDskYi', '4rDER6UTWdIaHx91wlIgIadhsoiXkwBgMs6kZnvXzjohtFUP4p2yFapkytym', '2025-06-24 01:42:04', '2025-06-24 02:29:23'),
(11, 'Test', 'akunbackup2816@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Trainer (Eksternal)', '2025-06-26 02:32:09', '$2y$12$kdmaEzNszGDScDYHMydQ/.dj4aOHs5TiQzqipKKO.I.j60YzSpNHK', NULL, '2025-06-24 02:25:26', '2025-06-26 02:32:09'),
(12, 'Faturrahman Ptra', 'faturrahman86.fg@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Trainer (Eksternal)', NULL, '$2y$12$64/JUcGYxl0h2bJXF27xHO/.Oj6ttWOZEfOxZhlys9Mxog19yG2E.', NULL, '2025-07-06 20:23:06', '2025-07-06 20:23:06');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `certificate_awards`
--
ALTER TABLE `certificate_awards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `certificate_awards_user_id_batch_number_unique` (`user_id`,`batch_number`),
  ADD UNIQUE KEY `certificate_awards_certificate_uuid_unique` (`certificate_uuid`);

--
-- Indexes for table `ebooks`
--
ALTER TABLE `ebooks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ebooks_slug_unique` (`slug`),
  ADD KEY `folder_id` (`folder_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `folder_ebooks`
--
ALTER TABLE `folder_ebooks`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `post_tests`
--
ALTER TABLE `post_tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_tests_session_id_foreign` (`session_id`);

--
-- Indexes for table `post_test_results`
--
ALTER TABLE `post_test_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_test_results_user_id_foreign` (`user_id`),
  ADD KEY `post_test_results_ebook_id_foreign` (`ebook_id`),
  ADD KEY `post_test_results_session_id_foreign` (`session_id`);

--
-- Indexes for table `post_test_sessions`
--
ALTER TABLE `post_test_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_test_sessions_ebook_id_foreign` (`ebook_id`);

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
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_no_tlp_unique` (`no_tlp`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `certificate_awards`
--
ALTER TABLE `certificate_awards`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ebooks`
--
ALTER TABLE `ebooks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `folder_ebooks`
--
ALTER TABLE `folder_ebooks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `post_tests`
--
ALTER TABLE `post_tests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `post_test_results`
--
ALTER TABLE `post_test_results`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `post_test_sessions`
--
ALTER TABLE `post_test_sessions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certificate_awards`
--
ALTER TABLE `certificate_awards`
  ADD CONSTRAINT `certificate_awards_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ebooks`
--
ALTER TABLE `ebooks`
  ADD CONSTRAINT `folder_id` FOREIGN KEY (`folder_id`) REFERENCES `folder_ebooks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_tests`
--
ALTER TABLE `post_tests`
  ADD CONSTRAINT `post_tests_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `post_test_sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_test_results`
--
ALTER TABLE `post_test_results`
  ADD CONSTRAINT `post_test_results_ebook_id_foreign` FOREIGN KEY (`ebook_id`) REFERENCES `ebooks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_test_results_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `post_test_sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_test_results_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_test_sessions`
--
ALTER TABLE `post_test_sessions`
  ADD CONSTRAINT `post_test_sessions_ebook_id_foreign` FOREIGN KEY (`ebook_id`) REFERENCES `ebooks` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
