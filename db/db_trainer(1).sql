-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 12, 2025 at 09:45 AM
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
-- Table structure for table `ebooks`
--

CREATE TABLE `ebooks` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ebooks`
--

INSERT INTO `ebooks` (`id`, `title`, `slug`, `deskripsi`, `cover`, `file`, `created_at`, `updated_at`) VALUES
(1, 'Legalitas dan Keamanan Bertransaksi PBK', 'legalitas-dan-keamanan-bertransaksi-pbk', 'eBook \"Legalitas dan Keamanan Bertransaksi PBK\" membahas secara ringkas dan jelas mengenai kerangka hukum serta aspek keamanan dalam Perdagangan Berjangka Komoditi (PBK) di Indonesia. Buku ini dirancang untuk memberikan pemahaman kepada masyarakat, investor, dan pelaku usaha mengenai regulasi yang berlaku, peran lembaga pengawas, legalitas pialang, serta cara mengenali dan menghindari praktik ilegal seperti penipuan dan investasi bodong. Dengan penjelasan yang mudah dipahami dan disertai contoh kasus nyata, eBook ini menjadi panduan praktis untuk bertransaksi secara aman dan sesuai aturan di pasar berjangka.', 'uploads/cover/1749690809_E-Book Legalitas dan Keamanan-gambar-0.jpg', 'uploads/ebook/1749690809_E-Book Legalitas dan Keamanan.pdf', '2025-06-11 18:13:29', '2025-06-11 18:13:29');

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
(7, '2025_06_05_021945_create_post_test_results_table', 1);

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
(2, 1, '<p><img class=\"img-fluid\" style=\"display: block; margin-left: auto; margin-right: auto;\" src=\"https://eu-images.contentstack.com/v3/assets/blt73dfd92ee49f59a6/blt3544c7666336b346/66686415cfa97251e6c87f88/MDP-3296_1200x675_2.png\" alt=\"\" width=\"598\" height=\"336\"></p>\r\n<p>&nbsp;</p>\r\n<p>Gambar di atas menunjukkan pola candlestick yang muncul setelah tren penurunan. Pola ini dikenal sebagai:</p>', 'Doji', 'Bearish Engulfing', 'Bullish Engulfing', 'Shooting Star', 'C', '2025-06-11 21:16:14', '2025-06-11 21:49:28'),
(3, 1, '<p><img class=\"img-fluid\" style=\"display: block; margin-left: auto; margin-right: auto;\" src=\"https://images.squarespace-cdn.com/content/v1/5acda2f4c258b4bd2d14dca2/e9e0c8bd-6791-4421-affa-2c17aa21f47c/candlestick+hammer+for+bullish+reversal.jpg\" alt=\"\" width=\"458\" height=\"282\"></p>\r\n<p>&nbsp;</p>\r\n<p>Gambar di atas menunjukkan sebuah pola candlestick yang muncul setelah tren penurunan. Pola ini dikenal dengan nama:</p>', 'Inverted Hammer', 'Shooting Star', 'Hanging Man', 'Hammer', 'D', '2025-06-11 21:27:10', '2025-06-11 21:27:10');

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
(7, 2, 1, 1, 100, '2025-06-12 01:41:16', '2025-06-12 01:41:16');

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
(1, 1, 'Legalitas dan Keamanan Bertransaksi PBK 2', 20, '2025-06-11 18:55:51', '2025-06-11 19:22:36');

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
('OfEgd8wYe9r3TqnhZTCXPrUZnOjaGDQIHbEciTjY', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoieUVRQ01RbE9uRE14QzE3U0o5cWpzdFNwMkFrZTlKVDQ0b01ka2dVOSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjY4OiJodHRwOi8vbm1fZWR1a2FzaS50ZXN0L2Vib29rL2xlZ2FsaXRhcy1kYW4ta2VhbWFuYW4tYmVydHJhbnNha3NpLXBiayI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1749721257);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('Pria','Wanita') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `warga_negara` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_id` varchar(17) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_tlp` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pekerjaan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('Admin','Trainer (Eksternal)') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Trainer (Eksternal)',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `warga_negara`, `alamat`, `no_id`, `no_tlp`, `pekerjaan`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Faturrahman Ptra', 'faturrahman86.fr@gmail.com', 'Pria', 'Jakarta', '2003-06-28', NULL, 'Jalan Kompleks Griya Bukit Jaya 49', NULL, '087778376988', 'Programmer', 'Admin', NULL, '$2y$12$GpR.5G5oSeIDjqwkSWXZyO.f1ef6TY3thbqlJi5LkL/0lBf5g1IO.', NULL, '2025-06-11 18:10:13', '2025-06-11 18:10:13'),
(2, 'Laura Shakira Aisyah Putri', 'laurashakira9@gmail.com', 'Wanita', 'Jakarta', '2002-08-09', 'Indonesia', 'Jalan Kompleks Griya Bukit Jaya N8/14, Tlajung Udik, Gunung Putri, Kab. Bogor', '123123123123123', '087778376989', 'Guru/Dosen', 'Trainer (Eksternal)', NULL, '$2y$12$I/X2.pGpoA.6qm0BfhruOOGEpun1Es2D8ciNxQR6HlSnd8JUcvFt.', NULL, '2025-06-11 20:27:34', '2025-06-11 20:35:57');

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
-- Indexes for table `ebooks`
--
ALTER TABLE `ebooks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ebooks_slug_unique` (`slug`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
  ADD UNIQUE KEY `users_no_tlp_unique` (`no_tlp`),
  ADD UNIQUE KEY `users_no_id_unique` (`no_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ebooks`
--
ALTER TABLE `ebooks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `post_tests`
--
ALTER TABLE `post_tests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `post_test_results`
--
ALTER TABLE `post_test_results`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `post_test_sessions`
--
ALTER TABLE `post_test_sessions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

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
