-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 26, 2025 at 05:52 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pkm_center`
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
-- Table structure for table `kelompoks`
--

CREATE TABLE `kelompoks` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_kelompok` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `judul_pkm` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_pkm` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `ketua_id` bigint UNSIGNED NOT NULL,
  `dosen_pembimbing_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('draft','submitted','review','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelompok_user`
--

CREATE TABLE `kelompok_user` (
  `id` bigint UNSIGNED NOT NULL,
  `kelompok_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `posisi` enum('ketua','anggota') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'anggota',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2025_12_25_184855_create_roles_table', 1),
(4, '2025_12_25_184900_create_users_table', 1),
(5, '2025_12_25_184905_create_kelompoks_table', 1),
(6, '2025_12_25_185243_create_kelompok_user_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'mahasiswa', '2025-12-25 22:49:08', '2025-12-25 22:49:08'),
(2, 'dosen', '2025-12-25 22:49:08', '2025-12-25 22:49:08'),
(3, 'kaprodi', '2025-12-25 22:49:08', '2025-12-25 22:49:08'),
(4, 'dekan', '2025-12-25 22:49:08', '2025-12-25 22:49:08'),
(5, 'admin', '2025-12-25 22:49:08', '2025-12-25 22:49:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nim` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `program_studi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nidn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `nim`, `email`, `password`, `program_studi`, `no_hp`, `jenis_kelamin`, `nidn`, `role_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin PKM', NULL, 'admin@pkm.ac.id', '$2y$12$hIQa7cFhXp6OjIpfMv/ve.c6vOl2ifN7rS48xDDqkpjHKC149rkim', NULL, '081234567890', 'L', NULL, 5, NULL, '2025-12-25 22:49:08', '2025-12-25 22:49:08'),
(2, 'Dr. Ahmad Fauzi, M.Kom', NULL, 'dekan@ft.ac.id', '$2y$12$3O5VaGqGGZrqrWm.dN1H9O3OqeFcMppA0u.MxWd9F4ZWGNfQHbcqu', NULL, '081234567891', 'L', '0401068901', 4, NULL, '2025-12-25 22:49:08', '2025-12-25 22:49:08'),
(3, 'Dr. Siti Nurhaliza, M.T', NULL, 'kaprodi.ti@ft.ac.id', '$2y$12$5BKa7ulvey5qa4QG8q/3xO9LyQN5ZbbtlYUIj9/1WYryjBt1CgwdW', 'Teknik Informatika', '081234567892', 'P', '0402078902', 3, NULL, '2025-12-25 22:49:08', '2025-12-25 22:49:08'),
(4, 'Dr. Budi Santoso, M.Kom', NULL, 'kaprodi.si@ft.ac.id', '$2y$12$eIktwVmF.nfNgO8Bo10jl.gTMbYtxFrUlyRepxYmTb34NPanGrj9u', 'Sistem Informasi', '081234567893', 'L', '0403088903', 3, NULL, '2025-12-25 22:49:09', '2025-12-25 22:49:09'),
(5, 'Dr. Rina Wati, M.Kom', NULL, 'rina.wati@ft.ac.id', '$2y$12$1GjVzR1pbJtLxuuYMEKQ6.iafLabktrUCO2uKPupxLD/yjh5gzGqa', 'Teknik Informatika', '081234567894', 'P', '0404098904', 2, NULL, '2025-12-25 22:49:09', '2025-12-25 22:49:09'),
(6, 'M. Rizki Pratama, M.T', NULL, 'rizki.pratama@ft.ac.id', '$2y$12$ewyyg4pmZabWiTqcKtzLDOeF9KbrPidUPMHRXu9CMEkhMSrsYdCyW', 'Teknik Informatika', '081234567895', 'L', '0405108905', 2, NULL, '2025-12-25 22:49:09', '2025-12-25 22:49:09'),
(7, 'Dra. Fitri Handayani, M.Si', NULL, 'fitri.handayani@ft.ac.id', '$2y$12$AeIhQ2X/kYR/IwVehWk7DuSmpCYmxQ.SwznvPOyorazHiiyCqCcdq', 'Sistem Informasi', '081234567896', 'P', '0406118906', 2, NULL, '2025-12-25 22:49:09', '2025-12-25 22:49:09'),
(8, 'Agus Setiawan, M.Kom', NULL, 'agus.setiawan@ft.ac.id', '$2y$12$HtjVffIEa9Jg7oX99cdFJuB6mKBqGSnwFX5syCQqarl8vnlcvT9Ui', 'Teknik Informatika', '081234567897', 'L', '0407128907', 2, NULL, '2025-12-25 22:49:09', '2025-12-25 22:49:09'),
(9, 'Andi Wijaya', '2021001', 'andi.wijaya@student.ac.id', '$2y$12$zLH0Uh7v51I8rIXfWu8tDueeWfoIZylTFZ9AkKRXfBctU0cYkqnny', 'Teknik Informatika', '081234567898', 'L', NULL, 1, NULL, '2025-12-25 22:49:10', '2025-12-25 22:49:10'),
(10, 'Dewi Lestari', '2021002', 'dewi.lestari@student.ac.id', '$2y$12$fvcpjJxteAV8rUh03hwmJuTHw1Bv0czVe3Db7VvcHTWxZnhibxrCq', 'Teknik Informatika', '081234567899', 'P', NULL, 1, NULL, '2025-12-25 22:49:10', '2025-12-25 22:49:10'),
(11, 'Raka Permana', '2021003', 'raka.permana@student.ac.id', '$2y$12$NSC36EQ1VFp6CKDVCKkihucEJF.Ii6r69nnd4U9sEEI51FIfS4rFK', 'Teknik Informatika', '081234567900', 'L', NULL, 1, NULL, '2025-12-25 22:49:10', '2025-12-25 22:49:10'),
(12, 'Maya Sari', '2021004', 'maya.sari@student.ac.id', '$2y$12$.PRFFUnRnnDyr7lNSb50keUBxZSymQGcec6u2n4fIjD/RcrPgi43e', 'Sistem Informasi', '081234567901', 'P', NULL, 1, NULL, '2025-12-25 22:49:10', '2025-12-25 22:49:10'),
(13, 'Faisal Rahman', '2021005', 'faisal.rahman@student.ac.id', '$2y$12$CMcNUBU5xpjQ2H84IeNMK.ltoq3QJmGi3E66nic6QtQIy6kBN0GXK', 'Sistem Informasi', '081234567902', 'L', NULL, 1, NULL, '2025-12-25 22:49:10', '2025-12-25 22:49:10'),
(14, 'Sinta Maharani', '2021006', 'sinta.maharani@student.ac.id', '$2y$12$z/sjwzcL6Ru0Ye5s2TzBTOUdMFlD.BNCNX/p/CekLTfxgAsj3Ksy6', 'Sistem Informasi', '081234567903', 'P', NULL, 1, NULL, '2025-12-25 22:49:11', '2025-12-25 22:49:11');

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
-- Indexes for table `kelompoks`
--
ALTER TABLE `kelompoks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelompoks_ketua_id_foreign` (`ketua_id`),
  ADD KEY `kelompoks_dosen_pembimbing_id_foreign` (`dosen_pembimbing_id`);

--
-- Indexes for table `kelompok_user`
--
ALTER TABLE `kelompok_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kelompok_user_kelompok_id_user_id_unique` (`kelompok_id`,`user_id`),
  ADD KEY `kelompok_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_nim_unique` (`nim`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

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
-- AUTO_INCREMENT for table `kelompoks`
--
ALTER TABLE `kelompoks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelompok_user`
--
ALTER TABLE `kelompok_user`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kelompoks`
--
ALTER TABLE `kelompoks`
  ADD CONSTRAINT `kelompoks_dosen_pembimbing_id_foreign` FOREIGN KEY (`dosen_pembimbing_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `kelompoks_ketua_id_foreign` FOREIGN KEY (`ketua_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kelompok_user`
--
ALTER TABLE `kelompok_user`
  ADD CONSTRAINT `kelompok_user_kelompok_id_foreign` FOREIGN KEY (`kelompok_id`) REFERENCES `kelompoks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kelompok_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
