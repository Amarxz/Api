-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2025 at 12:33 PM
-- Server version: 11.4.7-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpus_mobile`
--

-- --------------------------------------------------------

--
-- Table structure for table `bukus`
--

CREATE TABLE `bukus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `penulis` varchar(255) NOT NULL,
  `kategori_id` bigint(20) UNSIGNED NOT NULL,
  `stok_total` int(11) NOT NULL,
  `stok_saat_ini` int(11) NOT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `tanggal_publikasi` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bukus`
--

INSERT INTO `bukus` (`id`, `judul`, `penulis`, `kategori_id`, `stok_total`, `stok_saat_ini`, `cover`, `publisher`, `tanggal_publikasi`, `created_at`, `updated_at`) VALUES
(2, 'Binaaa', 'bimoo', 1, 20, 18, NULL, 'Gramedia', '2000-12-20', '2025-06-05 00:23:00', '2025-06-07 16:48:53');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategoris`
--

CREATE TABLE `kategoris` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategoris`
--

INSERT INTO `kategoris` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 'IPA', '2025-05-18 09:36:38', '2025-06-04 20:32:28'),
(5, 'china', '2025-06-06 08:02:01', '2025-06-06 08:02:01'),
(7, 'AYAM', '2025-06-06 08:09:21', '2025-06-06 08:09:21'),
(8, 'Horror', '2025-06-07 16:50:30', '2025-06-07 16:50:30');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_05_18_145000_create_kategoris_table', 1),
(6, '2025_05_18_145058_create_bukus_table', 1),
(7, '2025_05_18_145200_create_pemesanans_table', 1),
(8, '2025_05_18_145205_create_peminjamen_table', 1),
(9, '2025_05_18_145211_create_notifikasis_table', 1),
(10, '2025_06_06_132156_add_cover_to_bukus_table', 2),
(11, '2014_10_12_100000_create_password_resets_table', 3),
(12, '2025_06_06_145315_add_status_to_peminjamans_table', 3),
(13, '2025_06_07_232819_add_remember_token_to_users_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `notifikasis`
--

CREATE TABLE `notifikasis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `pesan` text NOT NULL,
  `dibaca` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifikasis`
--

INSERT INTO `notifikasis` (`id`, `user_id`, `pesan`, `dibaca`, `created_at`, `updated_at`) VALUES
(4, 5, 'HAI TAMPAN', 0, '2025-06-06 08:58:06', '2025-06-06 08:58:06'),
(5, 2, 'HI MANUSIA', 0, '2025-06-06 09:01:23', '2025-06-06 09:01:23'),
(7, 5, 'Udah malem', 0, '2025-06-06 09:25:03', '2025-06-06 09:25:03');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('112@gmail.com', '$2y$12$I1mycXXCIauXhtD9Ui0fDOMs1AEpNcVSxXQLkmni4FQiVuLQMdaCm', '2025-06-06 19:38:35'),
('losdor10@gmail.com', '$2y$12$6ZTHgg.GKHJlz5BiEm6mVOa4Aanw9iy00avivPqbHzknn7Tl2uWCO', '2025-06-07 17:15:41');

-- --------------------------------------------------------

--
-- Table structure for table `pemesanans`
--

CREATE TABLE `pemesanans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `buku_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_pesan` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pemesanans`
--

INSERT INTO `pemesanans` (`id`, `user_id`, `buku_id`, `tanggal_pesan`, `created_at`, `updated_at`) VALUES
(1, 5, 2, '2025-06-05', '2025-06-05 03:11:26', '2025-06-05 03:11:26'),
(2, 2, 2, '2025-06-06', '2025-06-06 08:33:20', '2025-06-06 08:33:20'),
(3, 2, 2, '2025-06-06', '2025-06-06 08:37:44', '2025-06-06 08:37:44');

-- --------------------------------------------------------

--
-- Table structure for table `peminjamans`
--

CREATE TABLE `peminjamans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `buku_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'dipinjam',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `peminjamans`
--

INSERT INTO `peminjamans` (`id`, `user_id`, `buku_id`, `tanggal_pinjam`, `tanggal_kembali`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 2, '2025-06-05', '2025-06-10', 'dipinjam', '2025-06-05 00:36:25', '2025-06-05 01:01:01'),
(2, 5, 2, '2025-06-05', '2025-06-13', 'dipinjam', '2025-06-05 02:51:19', '2025-06-05 02:51:19'),
(3, 5, 2, '2025-06-05', '2025-06-12', 'dipinjam', '2025-06-05 02:55:27', '2025-06-05 02:55:27'),
(4, 5, 2, '2025-06-07', '2025-06-21', 'dipinjam', '2025-06-07 16:48:53', '2025-06-07 16:48:53');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'api_token', '92d23d857ff23e28812fe5c919bb88008857ab0b70abf330a7841fcca9a41bec', '[\"*\"]', NULL, NULL, '2025-05-18 09:32:17', '2025-05-18 09:32:17'),
(2, 'App\\Models\\User', 1, 'api_token', '5ca5659f7d77bffc71bd4b69b4f42e06c3034cd546fff8d4573ff58f1eff6b63', '[\"*\"]', NULL, NULL, '2025-05-18 09:33:10', '2025-05-18 09:33:10'),
(3, 'App\\Models\\User', 1, 'api_token', 'fbf740b380b63ea07db5a3f971d32960dbebd5d750956fe7c0b7b88b1bcae486', '[\"*\"]', NULL, NULL, '2025-05-18 09:37:52', '2025-05-18 09:37:52'),
(4, 'App\\Models\\User', 2, 'api_token', '13c81204418691cc6734ada3c595e156949963ddaccedf4bfedbb63f71e9579a', '[\"*\"]', NULL, NULL, '2025-06-03 14:58:39', '2025-06-03 14:58:39'),
(10, 'App\\Models\\User', 3, 'api_token', '595fc68502581e128398633732343c355d85c3d7d8e4858664322a03987cbc54', '[\"*\"]', NULL, NULL, '2025-06-03 15:45:55', '2025-06-03 15:45:55'),
(12, 'App\\Models\\User', 1, 'api_token', '27bb39e8a2229b57430b0e8699806a22a4fb75b291cdf78bcf75c07aba5340fe', '[\"*\"]', NULL, NULL, '2025-06-04 19:48:27', '2025-06-04 19:48:27'),
(13, 'App\\Models\\User', 1, 'api_token', 'deb11e414dc57fa1d090340bd6a557d2aca474d751b72e1fb7313ee6ba4a2883', '[\"*\"]', NULL, NULL, '2025-06-04 19:48:37', '2025-06-04 19:48:37'),
(14, 'App\\Models\\User', 1, 'api_token', 'f71f0e1e8120416183416f1249d5c9c0e1b7e5bd7719c990688a66573c185995', '[\"*\"]', '2025-06-04 20:18:56', NULL, '2025-06-04 19:54:57', '2025-06-04 20:18:56'),
(41, 'App\\Models\\User', 1, 'api_token', 'e1f6feff05970c156f3f91d1d2cc766c0d64f1c0ee520a29953fb9ed06ca8343', '[\"*\"]', '2025-06-05 01:30:41', NULL, '2025-06-05 01:01:33', '2025-06-05 01:30:41'),
(42, 'App\\Models\\User', 4, 'api_token', '0d4756d4b134e38a52cb0b48e6f61d95f179d7118242f9aadaf86903fe87c159', '[\"*\"]', NULL, NULL, '2025-06-05 01:19:32', '2025-06-05 01:19:32'),
(46, 'App\\Models\\User', 5, 'api_token', '7a4deee48a120addf136a22a2a0c5452fd8fba474b0c6bdfc911dcd72c98e608', '[\"*\"]', NULL, NULL, '2025-06-05 02:35:39', '2025-06-05 02:35:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `role` enum('admin','anggota') NOT NULL DEFAULT 'anggota',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `telepon`, `role`, `created_at`, `updated_at`, `remember_token`) VALUES
(2, 'M Bimo', 'losdor10@gmail.com', '$2y$12$DNs1a8F5u6pc/Cn6.pxIEOQaHw61jNn08vFLuCKWJWdI.MieHXDnm', NULL, 'anggota', '2025-06-03 14:58:39', '2025-06-03 14:58:39', NULL),
(3, 'M Bimo', 'admin2@example.com', '$2y$12$ZAYukOeNsbcx1lEHMc14w.G.EueCiM5hDXHFoiGPgYCiLUl5D/c3a', NULL, 'admin', '2025-06-03 15:45:55', '2025-06-03 15:45:55', NULL),
(4, 'M Bimo', '112@gmail.com', '$2y$12$WgUCsGMO8obY7J9F.L0qQuJVIiwPz5FmUS/KUzPjOb5AgeoWGdFYm', NULL, 'admin', '2025-06-05 01:19:32', '2025-06-05 01:19:32', NULL),
(5, 'Naila cantik', 'nn@gmail.com', '$2y$12$QrTHDl8AyEFBByn6its3e.KKfz/j56NKX7MFuzDEVezKcqlY0eiFW', NULL, 'anggota', '2025-06-05 02:35:39', '2025-06-05 02:35:39', NULL),
(6, 'M bimo', '113@gmail.com', '$2y$12$coO5XXlOxQTcOgVYnhT5pOWq.bWSifonp95p6KbqSHyWE/P7tGoee', NULL, 'anggota', '2025-06-07 16:35:17', '2025-06-07 16:35:17', NULL),
(7, 'Manusia Ikan', '114@gmail.com', '$2y$12$gS8jL6wHxvAoDGwsTPoYpeK8qODbUJpGpHFiaMs4qbHjHFANaDSMS', NULL, 'anggota', '2025-06-07 16:58:10', '2025-06-07 16:58:10', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bukus`
--
ALTER TABLE `bukus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bukus_kategori_id_foreign` (`kategori_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `kategoris`
--
ALTER TABLE `kategoris`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifikasis`
--
ALTER TABLE `notifikasis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifikasis_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pemesanans`
--
ALTER TABLE `pemesanans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pemesanans_user_id_foreign` (`user_id`),
  ADD KEY `pemesanans_buku_id_foreign` (`buku_id`);

--
-- Indexes for table `peminjamans`
--
ALTER TABLE `peminjamans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peminjamans_user_id_foreign` (`user_id`),
  ADD KEY `peminjamans_buku_id_foreign` (`buku_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

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
-- AUTO_INCREMENT for table `bukus`
--
ALTER TABLE `bukus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategoris`
--
ALTER TABLE `kategoris`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `notifikasis`
--
ALTER TABLE `notifikasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pemesanans`
--
ALTER TABLE `pemesanans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `peminjamans`
--
ALTER TABLE `peminjamans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bukus`
--
ALTER TABLE `bukus`
  ADD CONSTRAINT `bukus_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategoris` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifikasis`
--
ALTER TABLE `notifikasis`
  ADD CONSTRAINT `notifikasis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pemesanans`
--
ALTER TABLE `pemesanans`
  ADD CONSTRAINT `pemesanans_buku_id_foreign` FOREIGN KEY (`buku_id`) REFERENCES `bukus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemesanans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `peminjamans`
--
ALTER TABLE `peminjamans`
  ADD CONSTRAINT `peminjamans_buku_id_foreign` FOREIGN KEY (`buku_id`) REFERENCES `bukus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjamans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
