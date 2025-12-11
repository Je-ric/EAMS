-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2025 at 01:51 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `employee_attendance`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `emp_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `emp_id`, `date`, `time_in`, `time_out`, `created_at`, `updated_at`) VALUES
(1, 2, '2025-10-20', '14:53:00', '18:45:00', '2025-10-20 06:53:36', '2025-10-22 10:45:10'),
(8, 1, '2025-10-21', '17:11:00', NULL, '2025-10-21 09:11:24', '2025-10-21 09:22:26'),
(9, 3, '2025-10-21', '17:11:35', NULL, '2025-10-21 09:11:35', '2025-10-21 09:11:35'),
(13, 1, '2025-10-22', '09:01:13', '17:46:46', '2025-10-22 01:01:13', '2025-10-22 09:46:46'),
(14, 2, '2025-10-22', '17:46:36', NULL, '2025-10-22 09:46:36', '2025-10-22 09:46:36'),
(15, 4, '2025-10-22', '18:39:47', '18:44:38', '2025-10-22 10:39:47', '2025-10-22 10:44:38'),
(16, 3, '2025-10-22', '18:44:30', NULL, '2025-10-22 10:44:30', '2025-10-22 10:44:30'),
(17, 2, '2025-10-21', '09:46:00', '19:47:00', '2025-10-22 11:46:24', '2025-10-22 11:46:33'),
(18, 1, '2025-10-24', '21:01:53', '21:33:44', '2025-10-24 13:01:53', '2025-10-24 13:33:44'),
(19, 2, '2025-10-24', '21:02:10', '21:33:59', '2025-10-24 13:02:10', '2025-10-24 13:33:59'),
(20, 4, '2025-10-24', '21:02:19', '21:34:05', '2025-10-24 13:02:19', '2025-10-24 13:34:05'),
(21, 7, '2025-10-24', '21:02:24', '21:34:11', '2025-10-24 13:02:24', '2025-10-24 13:34:11'),
(22, 3, '2025-10-24', '21:34:21', NULL, '2025-10-24 13:34:21', '2025-10-24 13:34:21');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `emp_pic` varchar(255) DEFAULT NULL,
  `position` varchar(50) NOT NULL,
  `login_provider` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `emp_pic`, `position`, `login_provider`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'employees/1Lc8mVcEwKlrcyXuHWvkKPrl75jiWeVwwMwUrYZs.png', 'Founder', NULL, 2, '2025-10-20 06:52:57', '2025-10-20 06:52:57'),
(2, 'employees/B2PqCkujVtaMAaeR0yRQ0qpmGxgDaXIXJJrVQIAX.png', 'Senior Executive Director', NULL, 3, '2025-10-20 06:53:13', '2025-10-20 20:06:07'),
(3, 'employees/18XkD4Shefc0wGSrOHwKDThaLSe8DnvnJkGNmsR6.png', 'Corporate Secretary', NULL, 4, '2025-10-20 06:53:28', '2025-10-20 06:53:28'),
(4, 'employees/N3Z8LZaxhD4BOPctOlh0ZY2z9tQP0tggSlAsXtTe.png', 'Developer', 'google', 5, '2025-10-22 10:27:51', '2025-10-22 10:39:37'),
(7, 'https://platform-lookaside.fbsbx.com/platform/profilepic/?asid=1483303652906717&width=1920&ext=1763902749&hash=AT-JWVCzDyPCpytNzPzVK8b8', 'Employee', 'facebook', 8, '2025-10-24 12:59:05', '2025-10-24 12:59:05');

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
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_20_063849_update_users_table_add_role', 1),
(5, '2025_10_20_063923_create_employees_table', 1),
(6, '2025_10_20_063938_create_attendance_table', 1),
(7, '2025_10_22_180406_add_password_and_provider_to_employees_table', 2),
(8, '2025_10_22_191557_drop_password_from_employees_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('bhzXmart06Zy45mYR9LipRxJit5romafKvW2vqGo', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWEw2ODVRWUJJN0d3T1phSUE4VWJNd2hBU3dqYmJGNjVIVnBsNzZ2ViI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1761374439),
('hnsw8tzaBRFEPUrzTJKPOPq4GeV9AcGAEiOk8Ags', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibjZXQTBUMlk4TVFnWTJQaEt3blZRVG5PeXBUaVVCNTNxWkY3M3hGYyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1762171521);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','employee') NOT NULL DEFAULT 'employee',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@eams.com', NULL, '$2y$12$mYIeB8whpSbAZ7hGKg7td.AVXGAFK5lmJv2MeatziuqYMoRhL/gvS', 'admin', NULL, NULL, '2025-10-20 06:52:14'),
(2, 'Jeric Dela Cruz', 'jericjdelacruz@gmail.com', NULL, '$2y$12$Ac8lYljnIETxucXNaF0f.O97pqi.s7dX9gl.YPBCumUExghIgP2ZC', 'employee', NULL, '2025-10-20 06:52:57', '2025-10-20 19:19:24'),
(3, 'Melgie Alata', 'melgie@gmail.com', NULL, '$2y$12$CIA1/ltNPE7WiH9RIhXzUOmCeC5ad.l6g7TvNk/vpUAnRlrO1.9ha', 'employee', NULL, '2025-10-20 06:53:13', '2025-10-20 20:06:07'),
(4, 'Jozen Agustin', 'jozenagustin1@gmail.com', NULL, '$2y$12$ZW/cSvbEtxdYTMAU/4fWOeao.1yY9u0x9.V.CVPxcDGv8RnvowoDq', 'employee', NULL, '2025-10-20 06:53:28', '2025-10-20 06:53:28'),
(5, 'Jeric Juyamag Dela Cruz', 'delacruz.jeric.j@gmail.com', NULL, '$2y$12$h09JZnYg3dyP2fpdexL2AOJtSR0C1UA3a/gI10o2ck9EZuykxJA6y', 'employee', NULL, '2025-10-22 10:27:51', '2025-10-24 13:01:34'),
(8, 'Jeric Dela Cruz', 'cirejd22@gmail.com', NULL, '$2y$12$.db3TKnsG4g.Kwt/.LQg7uskUjdjvsSYdAl74IS1pxTHTIsKre0Gi', 'employee', NULL, '2025-10-24 12:59:05', '2025-10-24 13:01:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendance_emp_id_foreign` (`emp_id`);

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
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employees_user_id_foreign` (`user_id`);

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
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_emp_id_foreign` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
