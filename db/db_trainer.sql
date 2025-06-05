-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20250210.12023785fe
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 26, 2025 at 09:11 AM
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
  `judul` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `penulis` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_terbit` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_ebook` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover_image` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ebooks`
--

INSERT INTO `ebooks` (`id`, `judul`, `deskripsi`, `penulis`, `tahun_terbit`, `file_ebook`, `cover_image`, `created_at`, `updated_at`) VALUES
(1, 'Perancangan UI/UX Pada Website Parentify Menggunakan Metode Design Thinking', 'Buku ini membahas perancangan UI/UX website Parentify menggunakan metode Design Thinking. Proses dimulai dari memahami kebutuhan pengguna hingga pengujian solusi, dengan tujuan menciptakan antarmuka yang intuitif dan pengalaman pengguna yang optimal. Cocok bagi yang tertarik pada desain digital berbasis kebutuhan pengguna.', 'Muhammad Faturrahman Putra', '2024', 'ebooks/jML6H6Z99rIKutQFBGjFOEecyrlXVWZSYH7gdvOX.pdf', 'covers/683064bca578f.png', '2025-05-23 05:06:20', '2025-05-23 05:06:20'),
(2, 'Bahasa Indonesia Kelas XI', 'Buku Bahasa Indonesia Kelas XI disusun untuk membantu siswa memahami, menguasai, dan menerapkan keterampilan berbahasa secara lisan maupun tulisan sesuai dengan Kurikulum Merdeka. Materi dalam buku ini mencakup berbagai teks seperti teks anekdot, eksplanasi, prosedur kompleks, ulasan, dan debat, yang dilengkapi dengan latihan, contoh soal, serta kegiatan proyek untuk mengasah kemampuan berpikir kritis dan kreatif siswa. Buku ini juga dirancang agar siswa mampu berkomunikasi secara efektif dalam kehidupan sehari-hari dan lingkungan akademik.', 'Suherli, Maman Suyarman, Aji septiaji, Istiqomah', '2017', 'ebooks/AYNbrOrpKEX8vn0YVW42s2JLi5uaJjsZXpDp4Pwq.pdf', 'covers/68306eac352f3.png', '2025-05-23 05:48:44', '2025-05-23 05:48:44'),
(3, 'Perancangan UI/UX Pada Website Parentify Menggunakan Metode Design Thinking', 'dsada', 'Muhammad Faturrahman Putra', '2025', 'ebooks/GVCLF9AVkOb3gkxShLgxqyKDFlS9Etk86LPfUirG.pdf', 'covers/6830755a4c8ba.png', '2025-05-23 06:17:14', '2025-05-23 06:17:14'),
(4, 'Cara Orangtua Menyikapi Anak Introvert dan Ekstrovert', 'sadasdasda', 'Ayudia Bing Slamet, Ditto Percussion', '1998', 'ebooks/EqSOgzE3d9Ixq34dPt5bZPZswSR648NQBEnQMzZo.pdf', 'covers/6830758c64ed3.jpeg', '2025-05-23 06:18:04', '2025-05-23 06:18:04'),
(5, 'PENGUMUMAN ROLL OVER HKK50_BBJ/HKK5U_BBJ TGL 28 APRIL 2025', 'qwertyuiop', 'Muhammad Faturrahman Putra', '2017', 'ebooks/yvy3VsqfQVwPulrgcwqloe07TsAzh4KnYIjY4k49.pdf', 'covers/683075a8a0eec.jpg', '2025-05-23 06:18:32', '2025-05-23 06:18:32'),
(6, 'Mengatasi Tantangan Parenting di Era Digital', '999999', 'Muhammad Faturrahman Putra', '2025', 'ebooks/jU8tLvWgRjtokdyi5ndrChQrmuopuzIks6xLd4bP.pdf', 'covers/683075ea00b05.jpg', '2025-05-23 06:19:38', '2025-05-23 06:19:38'),
(7, 'Transaksi Rifan Financindo Berjangka Tembus 530.000 Lot, Nasabah Baru Hampir Sentuh 1000 Nasabah', '000000000000000000000000', 'Ayudia Bing Slamet, Ditto Percussion', '1998', 'ebooks/KSrFlZ3SprNUZ0H06u5JoEjuhwyLX5wd9rHMdokl.pdf', 'covers/6830761418adc.jpg', '2025-05-23 06:20:20', '2025-05-23 06:20:20'),
(8, 'Transaksi Rifan Financindo Berjangka Tembus 530.000 Lot, Nasabah Baru Hampir Sentuh 1000 Nasabah', '123456789', 'Suherli, Maman Suyarman, Aji septiaji, Istiqomah', '2025', 'ebooks/nxRzwzo2bhSAoClP3ODYiAWbYedkq0LFK4hIfBKh.pdf', 'covers/6830763ec819f.jpg', '2025-05-23 06:21:02', '2025-05-23 06:21:02'),
(9, '12312312', '999999', 'Muhammad Faturrahman Putra', '2025', 'ebooks/uE1sXKdtAIBIqsgAelSHz66EXoQzLRAPIbT56ltj.pdf', 'covers/6830767210382.jpg', '2025-05-23 06:21:54', '2025-05-23 06:21:54'),
(10, 'Transaksi Rifan Financindo Berjangka Tembus 530.000 Lot, Nasabah Baru Hampir Sentuh 1000 Nasabah', '8888', 'Muhammad Faturrahman Putra', '2025', 'ebooks/YtaBsZl8hYNHcqzHJFrRT3GLniNhMqSv90qxnh6j.pdf', 'covers/6830769e7025f.jpg', '2025-05-23 06:22:38', '2025-05-23 06:22:38');

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
(8, '0001_01_01_000000_create_users_table', 1),
(9, '0001_01_01_000001_create_cache_table', 1),
(10, '0001_01_01_000002_create_jobs_table', 1),
(11, '2025_05_23_083718_create_ebooks_table', 1),
(21, '2025_05_26_051917_create_quizzes_table', 2),
(22, '2025_05_26_064328_create_questions_table', 2),
(23, '2025_05_26_065242_create_user_quiz_results_table', 2);

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
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` bigint UNSIGNED NOT NULL,
  `quiz_id` bigint UNSIGNED NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_a` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_b` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_c` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_d` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `correct_option` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `Status` enum('public','private') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
('3gENoZWPtfTsv5bbuP40CWFSYfD1PcxbjEUnmj2b', 1, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/17.5 Mobile/15A5370a Safari/602.1', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibjdHRFh4RURmQ2tjREtCaUgyNUttdU9RdkFRZDNOeUVkSnpJVE05YyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM1OiJodHRwOi8vbm1fZWR1a2FzaS50ZXN0L2Vib29rP3BhZ2U9MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1748250584);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` enum('KTP','SIM','Paspor','KITAS') COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_id` varchar(17) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_tlp` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('Admin','Trainer (Internal)','Trainer (Eksternal)') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Trainer (Eksternal)',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `type_id`, `no_id`, `no_tlp`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Faturrahman Ptra', 'faturrahman86.fr@gmail.com', 'KTP', '123123123123123', '+6287778376988', 'Admin', NULL, '$2y$12$2U5t4LDU4pB3/Xn1ys4/Nuy416t3HwjFPqqFABMFDLemZ1MU0xSAq', NULL, '2025-05-23 05:05:19', '2025-05-23 05:05:19'),
(2, 'Laura Shakira Aisyah Putri', 'laurashakira9@gmail.com', 'KTP', '31713123123131231', '+6287778376989', 'Trainer (Eksternal)', NULL, '$2y$12$tX8iX7C448uqeQRYhJbYje4oibvtZp7cOHWk8ASz0vqknfNJV8ZVW', NULL, '2025-05-23 07:13:38', '2025-05-23 07:13:38'),
(4, 'Muhammad Daffa Fahcreza', 'superadmin@example.com', 'KTP', '21312312312', '+6287778376911', 'Trainer (Eksternal)', NULL, '$2y$12$snLy9xS65jkOGVHkk5AcLutCC0H7s9Sy/Wp/KiCKK/dwMtvo/KfTu', NULL, '2025-05-23 10:35:14', '2025-05-23 10:35:14');

-- --------------------------------------------------------

--
-- Table structure for table `user_quiz_results`
--

CREATE TABLE `user_quiz_results` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `quiz_id` bigint UNSIGNED NOT NULL,
  `score` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questions_quiz_id_foreign` (`quiz_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`);

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
  ADD UNIQUE KEY `users_no_id_unique` (`no_id`),
  ADD UNIQUE KEY `users_no_tlp_unique` (`no_tlp`);

--
-- Indexes for table `user_quiz_results`
--
ALTER TABLE `user_quiz_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_quiz_results_user_id_foreign` (`user_id`),
  ADD KEY `user_quiz_results_quiz_id_foreign` (`quiz_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ebooks`
--
ALTER TABLE `ebooks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_quiz_results`
--
ALTER TABLE `user_quiz_results`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_quiz_results`
--
ALTER TABLE `user_quiz_results`
  ADD CONSTRAINT `user_quiz_results_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_quiz_results_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
