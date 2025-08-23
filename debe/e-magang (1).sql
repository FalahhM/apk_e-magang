-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 23, 2025 at 03:25 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e-magang`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensis`
--

CREATE TABLE `absensis` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `tanggal` date NOT NULL,
  `status` enum('hadir','izin','sakit','alfa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'alfa',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absensis`
--

INSERT INTO `absensis` (`id`, `mahasiswa_id`, `user_id`, `tanggal`, `status`, `keterangan`, `created_at`, `updated_at`) VALUES
(113, 28, 91, '2025-08-25', 'hadir', 'Hadir melaksanakan magang', '2025-08-25 01:09:19', '2025-08-25 01:09:19');

-- --------------------------------------------------------

--
-- Table structure for table `contact_person`
--

CREATE TABLE `contact_person` (
  `id` bigint UNSIGNED NOT NULL,
  `namecp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emailcp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emailcp_verified_at` timestamp NULL DEFAULT NULL,
  `nohpcp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_person`
--

INSERT INTO `contact_person` (`id`, `namecp`, `emailcp`, `emailcp_verified_at`, `nohpcp`, `jabatan`, `user_id`, `created_at`, `updated_at`) VALUES
(43, 'Nita', 'nita@gmail.com', NULL, '02423432', 'PIC', 89, '2025-08-21 10:55:19', '2025-08-21 10:55:19');

-- --------------------------------------------------------

--
-- Table structure for table `dospems`
--

CREATE TABLE `dospems` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `pengajuan_id` bigint UNSIGNED DEFAULT NULL,
  `nama_dospem` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dospems`
--

INSERT INTO `dospems` (`id`, `user_id`, `pengajuan_id`, `nama_dospem`, `email`, `no_telp`, `created_at`, `updated_at`) VALUES
(12, 90, 84, 'Sri Mulyati', 'mizugt4@gmail.com', NULL, '2025-08-21 11:40:55', '2025-08-21 13:59:58');

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
-- Table structure for table `laporan_magangs`
--

CREATE TABLE `laporan_magangs` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED DEFAULT NULL,
  `pengajuan_id` bigint UNSIGNED DEFAULT NULL,
  `tanggal_kegiatan` date DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `foto_dokumentasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_kegiatan_magang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nama_pembimbing_lapangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laporan_magangs`
--

INSERT INTO `laporan_magangs` (`id`, `mahasiswa_id`, `pengajuan_id`, `tanggal_kegiatan`, `keterangan`, `foto_dokumentasi`, `file_kegiatan_magang`, `created_at`, `updated_at`, `nama_pembimbing_lapangan`) VALUES
(35, 28, NULL, '2025-08-25', 'buat web', 'foto_kegiatan/pu1Ic9D75tNzCNQvJOHWhEfMob3zxZjC6WrBpIlI.png', NULL, '2025-08-25 01:30:45', '2025-08-25 01:37:47', 'Pak Budi');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswas`
--

CREATE TABLE `mahasiswas` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `nama_mahasiswa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nim` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jurusan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `judul_penelitian` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dospem_id` bigint UNSIGNED DEFAULT NULL,
  `pengajuan_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mahasiswas`
--

INSERT INTO `mahasiswas` (`id`, `user_id`, `nama_mahasiswa`, `email`, `nim`, `jurusan`, `judul_penelitian`, `dospem_id`, `pengajuan_id`, `created_at`, `updated_at`) VALUES
(28, 91, 'Falah Mubarak', 'falahmubarak98@gmail.com', '21111033', 'Sistem Informasi', 'Sistem Informasi E-Magang', 12, 84, '2025-08-21 11:40:55', '2025-08-21 13:59:58');

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
(41, '2014_10_12_000000_create_users_table', 1),
(42, '2014_10_12_100000_create_password_resets_table', 1),
(43, '2019_08_19_000000_create_failed_jobs_table', 1),
(44, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(45, '2024_08_28_074014_create_contact_person_table', 1),
(46, '2024_09_09_065530_make_pengajuan_tables', 1),
(47, '2024_09_09_070106_create_table_mahasiswas', 1),
(48, '2024_09_12_070623_add_status_to_pengajuans_table', 2),
(49, '2024_09_17_024235_add_email_verification_token_to_users_table', 3),
(50, '2024_09_23_015121_add_mulai_sampai_tanggal_to_pengajuans_table', 4),
(51, '2024_09_23_015421_remove_mulai_sampai_tanggal_from_mahasiswas_table', 4),
(52, '2024_09_26_005125_add_kabag_to_pengajuans_table', 5),
(53, '2024_09_26_010939_add_nama_kabag_to_pengajuans_table', 6),
(54, '2024_09_26_013817_add_cetak_timestamp_to_pengajuans_table', 7),
(55, '2024_09_26_072102_add_cetak_terima_timestamp_to_pengajuans_table', 8),
(56, '2024_09_26_072747_add_balasan_terima_to_pengajuans_table', 9),
(57, '2024_09_26_090526_add_no_surat_terima_to_pengajuans_table', 10),
(58, '2024_10_06_103920_add_cetak_tolak_timestamp_to_pengajuans_table', 11),
(59, '2024_10_06_104118_add_balasan_tolak_to_pengajuans_table', 12),
(60, '2024_10_06_104259_add_no_surat_tolak_to_pengajuans_table', 13),
(61, '2024_10_06_104826_add_alasan_tolak_to_pengajuans_table', 14),
(62, '2025_07_15_052447_create_absensis_table', 15),
(63, '2025_07_15_053134_create_laporan_magang_table', 16),
(64, '2025_07_15_055917_create_laporans_table', 17),
(65, '2025_07_28_100427_add_user_id_to_mahasiswas_table', 17),
(66, '2025_07_28_101047_add_asal_kampus_to_absensis_table', 18),
(67, '2025_07_28_101551_add_user_id_to_absensis_table', 19),
(68, '2025_07_28_101901_remove_asal_kampus_from_absensis_table', 20),
(69, '2025_07_21_132850_add_email_to_mahasiswas_table', 21),
(70, '2025_07_21_140348_create_laporan_magang_table', 22),
(71, '2025_07_23_030229_add_email_to_mahasiswas_table', 23),
(72, '2025_07_23_032625_create_laporan_magangs_table', 24),
(73, '2025_08_08_041206_add_mahasiswa_id_to_laporan_magang_table', 25),
(74, '2025_08_01_065013_add_jumlah_edit_to_laporan_magangs_table', 26),
(75, '2025_08_01_071739_add_kirim_fields_to_laporan_magangs_table', 27),
(76, '2025_08_10_111055_add_dokumentasi_to_laporan_magangs_table', 28),
(77, '2025_08_10_120836_add_keterangan_to_absensis_table', 29),
(79, '2025_08_11_001650_add_file_ttd_to_laporan_magangs_table', 30),
(80, '2025_08_11_152334_add_nama_pembimbing_lapangan_to_laporan_magangs_table', 31),
(81, '2025_08_12_122647_add_email_dospem_to_mahasiswas_table', 32),
(82, '2025_08_12_222347_create_dospems_table', 33),
(83, '2025_08_12_222516_update_mahasiswas_table_change_dospem_to_dospem_id', 33),
(84, '2025_08_12_223635_remove_email_dospem_from_mahasiswas', 34),
(85, '2025_08_13_014700_add_foreign_to_user_id_in_dospems', 35),
(86, '2025_08_14_020017_add_pengajuan_id_to_dospems_table', 36),
(87, '2025_08_14_105830_judul_penelitian_to_mahasiswas_table', 37),
(88, '2025_08_16_091950_create_penilaian_magangs_table', 38),
(89, '2025_08_16_092057_update_laporan_magangs_table', 39);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengajuans`
--

CREATE TABLE `pengajuans` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `no_surat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `perihal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_surat` date NOT NULL,
  `dokumen_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dokumen_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mulai_tanggal` date DEFAULT NULL,
  `sampai_tanggal` date DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nama_kabag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Hery Kurniawan',
  `cetak_timestamp` timestamp NULL DEFAULT NULL,
  `cetakTerima_timestamp` timestamp NULL DEFAULT NULL,
  `balasanTerima` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `noSuratTerima` int DEFAULT NULL,
  `cetakTolak_timestamp` timestamp NULL DEFAULT NULL,
  `balasanTolak` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `noSuratTolak` int DEFAULT NULL,
  `alasanTolak` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengajuans`
--

INSERT INTO `pengajuans` (`id`, `mahasiswa_id`, `user_id`, `no_surat`, `perihal`, `tanggal_surat`, `dokumen_name`, `dokumen_file`, `mulai_tanggal`, `sampai_tanggal`, `status`, `created_at`, `updated_at`, `nama_kabag`, `cetak_timestamp`, `cetakTerima_timestamp`, `balasanTerima`, `noSuratTerima`, `cetakTolak_timestamp`, `balasanTolak`, `noSuratTolak`, `alasanTolak`) VALUES
(84, NULL, 89, 'NH1234351', 'Pengajuan Magang', '2025-08-21', '1755776454_2.+JSITP+Vol+4+No+1+-+Maysila.pdf', 'uploads/1755776454_2.+JSITP+Vol+4+No+1+-+Maysila.pdf', '2025-08-25', '2025-09-05', 'Diterima', '2025-08-21 11:40:55', '2025-08-21 14:00:04', 'Hery Kurniawan', NULL, '2025-08-21 13:59:58', 'Surat_Balasan_Terima_84.pdf', 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penilaian_magangs`
--

CREATE TABLE `penilaian_magangs` (
  `id` bigint UNSIGNED NOT NULL,
  `pengajuan_id` bigint UNSIGNED DEFAULT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `integritas` int NOT NULL DEFAULT '0',
  `ketepatan_waktu` int NOT NULL DEFAULT '0',
  `keahlian` int NOT NULL DEFAULT '0',
  `teamwork` int NOT NULL DEFAULT '0',
  `komunikasi` int NOT NULL DEFAULT '0',
  `teknologi` int NOT NULL DEFAULT '0',
  `pengembangan_diri` int NOT NULL DEFAULT '0',
  `total_nilai` decimal(5,2) NOT NULL DEFAULT '0.00',
  `predikat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah_kirim` int NOT NULL DEFAULT '0',
  `jumlah_edit` int NOT NULL DEFAULT '0',
  `terakhir_kirim_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
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
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `no_telp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verification_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','kampus','mahasiswa','dospem') COLLATE utf8mb4_unicode_ci DEFAULT 'admin',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `alamat`, `email_verified_at`, `no_telp`, `password`, `email_verification_token`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(18, 'Admin', 'admin@gmail.com', 'paal 10', '2024-09-16 20:05:36', '985979494', '$2y$10$u6FMQw1JfOHBZzSeS.2mkuuoklBw/J6nnfRyYYBcbrFnXhclF.rJ.', NULL, 'admin', NULL, '2024-09-16 20:05:36', '2024-09-16 20:05:36'),
(89, 'Universitas Nurdin Hamzah', 'mlbbla33@gmail.com', 'Sipin', '2025-08-21 10:58:45', '078374832987', '$2y$10$5ZHXWmyu.HP3FmQsBTmofeddJvN1dT23OvjdpIE0szUH0XWJCdIk2', NULL, 'kampus', NULL, '2025-08-21 10:55:19', '2025-08-21 10:58:45'),
(90, 'Sri Mulyati', 'mizugt4@gmail.com', '-', '2025-08-21 13:59:58', '-', '$2y$10$mLbLXzk4ln3Qnf8Ejtk8uuiK7wiVtM7U1X.GQrlj1fwwf0hMJCBMm', NULL, 'dospem', NULL, '2025-08-21 13:59:58', '2025-08-21 13:59:58'),
(91, 'Falah Mubarak', 'falahmubarak98@gmail.com', '-', '2025-08-21 13:59:58', '-', '$2y$10$ijAoLhL88xBx6lCQupupiuVpwVtEFbJBXjsvSzfXyBpH8.WEUpRxK', NULL, 'mahasiswa', NULL, '2025-08-21 13:59:58', '2025-08-21 13:59:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensis`
--
ALTER TABLE `absensis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `absensis_mahasiswa_id_foreign` (`mahasiswa_id`),
  ADD KEY `absensis_user_id_foreign` (`user_id`);

--
-- Indexes for table `contact_person`
--
ALTER TABLE `contact_person`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contact_person_emailcp_unique` (`emailcp`),
  ADD KEY `contact_person_user_id_foreign` (`user_id`);

--
-- Indexes for table `dospems`
--
ALTER TABLE `dospems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dospems_user_id_foreign` (`user_id`),
  ADD KEY `dospems_pengajuan_id_foreign` (`pengajuan_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `laporan_magangs`
--
ALTER TABLE `laporan_magangs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `laporan_magangs_pengajuan_id_foreign` (`pengajuan_id`),
  ADD KEY `laporan_magangs_mahasiswa_id_foreign` (`mahasiswa_id`);

--
-- Indexes for table `mahasiswas`
--
ALTER TABLE `mahasiswas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mahasiswas_pengajuan_id_foreign` (`pengajuan_id`),
  ADD KEY `mahasiswas_user_id_foreign` (`user_id`),
  ADD KEY `mahasiswas_dospem_id_foreign` (`dospem_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `pengajuans`
--
ALTER TABLE `pengajuans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengajuans_user_id_foreign` (`user_id`);

--
-- Indexes for table `penilaian_magangs`
--
ALTER TABLE `penilaian_magangs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penilaian_magangs_pengajuan_id_index` (`pengajuan_id`);

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
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensis`
--
ALTER TABLE `absensis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `contact_person`
--
ALTER TABLE `contact_person`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `dospems`
--
ALTER TABLE `dospems`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporan_magangs`
--
ALTER TABLE `laporan_magangs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `mahasiswas`
--
ALTER TABLE `mahasiswas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `pengajuans`
--
ALTER TABLE `pengajuans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `penilaian_magangs`
--
ALTER TABLE `penilaian_magangs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensis`
--
ALTER TABLE `absensis`
  ADD CONSTRAINT `absensis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `contact_person`
--
ALTER TABLE `contact_person`
  ADD CONSTRAINT `contact_person_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dospems`
--
ALTER TABLE `dospems`
  ADD CONSTRAINT `dospems_pengajuan_id_foreign` FOREIGN KEY (`pengajuan_id`) REFERENCES `pengajuans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `dospems_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `laporan_magangs`
--
ALTER TABLE `laporan_magangs`
  ADD CONSTRAINT `laporan_magangs_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `laporan_magangs_pengajuan_id_foreign` FOREIGN KEY (`pengajuan_id`) REFERENCES `pengajuans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mahasiswas`
--
ALTER TABLE `mahasiswas`
  ADD CONSTRAINT `mahasiswas_dospem_id_foreign` FOREIGN KEY (`dospem_id`) REFERENCES `dospems` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `mahasiswas_pengajuan_id_foreign` FOREIGN KEY (`pengajuan_id`) REFERENCES `pengajuans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mahasiswas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pengajuans`
--
ALTER TABLE `pengajuans`
  ADD CONSTRAINT `pengajuans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penilaian_magangs`
--
ALTER TABLE `penilaian_magangs`
  ADD CONSTRAINT `penilaian_magangs_pengajuan_id_foreign` FOREIGN KEY (`pengajuan_id`) REFERENCES `pengajuans` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
