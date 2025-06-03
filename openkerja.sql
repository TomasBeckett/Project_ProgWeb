-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2025 at 07:04 PM
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
-- Database: `openkerja`
--

-- --------------------------------------------------------

--
-- Table structure for table `lowongan`
--

CREATE TABLE `lowongan` (
  `id` int(11) NOT NULL,
  `perusahaan_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `perusahaan` varchar(100) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `bidang` varchar(100) DEFAULT NULL,
  `tipe` varchar(50) DEFAULT NULL,
  `gaji` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `pertanyaan` text DEFAULT NULL,
  `banner_img` varchar(255) DEFAULT NULL,
  `logo_img` varchar(255) DEFAULT NULL,
  `gaji_min` int(11) DEFAULT NULL,
  `gaji_max` int(11) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lowongan`
--

INSERT INTO `lowongan` (`id`, `perusahaan_id`, `company_id`, `title`, `perusahaan`, `lokasi`, `bidang`, `tipe`, `gaji`, `deskripsi`, `pertanyaan`, `banner_img`, `logo_img`, `gaji_min`, `gaji_max`, `logo`, `banner`) VALUES
(1, 1, 1, 'IT WEB PROGRAMMER', 'PT Sumber Indah Lestari', 'Tanggerang, Banten', 'Developer/Programmer (Teknologi Informasi & Komunikasi)', 'Full time', '10.000.000-15.000.000', '-Pendidikan minimal Sarjana\r\n-Pengalaman minimal 1-2 Tahun di bidang yang relevan', '1. Berapa gaji bulanan yang diharapkan?\r\n2. Kualifikasi apa yang anda miliki?', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 3, 0, 'Test', NULL, 'Test', 'Test', 'Test', '1500000', 'Makan makan', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 4, 0, 'Testtttttt', NULL, 'Jakarta', 'Testtttt', 'Testttt', '1500000', 'Kerjaa Woeeee', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 3, 0, 'Kerupuk', NULL, 'Padang', 'Desain', 'Full-Time', '1500000', 'Masak masak', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 3, 0, 'Bukan Main', NULL, 'Riau', 'Arsitektur', 'Remote', '5000000 - 7000000', 'oe kerja saya gaji lah', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 3, 0, 'Apa Aja', 'PT Aesthetic', 'Bandung', 'Komputer', 'Part-Time', '14000000 - 19000000', 'WOKEEEE', '1. APA AJA UDAH\r\n2. TEST AJA\r\n3. OKEE', NULL, NULL, NULL, NULL, 'uploads/logo/1748969403_PTAesthetic.png', 'uploads/banner/1748969403_PTAesthetic.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `pelamar`
--

CREATE TABLE `pelamar` (
  `id` int(11) NOT NULL,
  `lowongan_id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `email` varchar(100) NOT NULL,
  `nomor_hp` varchar(20) NOT NULL,
  `cv` varchar(255) NOT NULL,
  `portofolio` varchar(255) DEFAULT NULL,
  `surat_lamaran` text DEFAULT NULL,
  `tanggal_lamar` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelamar`
--

INSERT INTO `pelamar` (`id`, `lowongan_id`, `nama`, `tanggal_lahir`, `email`, `nomor_hp`, `cv`, `portofolio`, `surat_lamaran`, `tanggal_lamar`, `user_id`) VALUES
(1, 2, 'Tomas Becket', '2025-06-16', 'siregar@gmail.com', '01823497973', '683edbdc426a4_syllabuspdf', '683edbdc42914_TUGAS-SEMINARpdf', '', '2025-06-03 11:26:20', NULL),
(2, 2, 'Siregar', '2025-06-26', 'siregar@gmail.com', '01823497973', '683ee01bd0de3_TUGAS-SEMINAR.pdf', '683ee01bde117_syllabus.pdf', '', '2025-06-03 11:44:27', NULL),
(3, 4, 'Siregar', '2025-06-17', 'siregar@gmail.com', '01823497973', '683ef7b4baa78_TUGAS-SEMINAR.pdf', '', '', '2025-06-03 13:25:08', NULL),
(4, 2, 'Siregar', '2025-06-18', 'siregar@gmail.com', '01823497973', '683efaf048e7e_syllabus.pdf', '', '', '2025-06-03 13:38:56', 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `name` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `created_at`, `name`, `photo`) VALUES
(1, 'budiono@gmail.com', '$2y$10$RI9Heauj3Y6tSjzE5HiJLeT7rQ45JVVmeNCkpBwinMHzzqaIg1iWa', '2025-06-03 06:25:51', NULL, NULL),
(3, 'test@staff.com', '$2y$10$JEKCCW3iesQ.XVAFXQC2g.cAVoDL3dFmPG.VeI2g5GGNVpsJ60sfa', '2025-06-03 09:55:49', 'Denis', 'default.png'),
(4, 'test1@staff.com', '$2y$10$e8KcmK9XN5Czk8JDpqnKe.ZWdl8jM2oRW.Gw696JeZHzSPu5.81r2', '2025-06-03 10:15:08', 'Dontol', 'default.png'),
(5, 'siregar@gmail.com', '$2y$10$JiXxbrT76jPfvvinx6G92eKdUJr6IW.dbJJ6qBg6B0kbi17srpj3.', '2025-06-03 10:57:22', 'Siregar', 'default.png'),
(6, 'putang@gmail.com', '$2y$10$AOepVmb3aqkeR86IOxSXweUabEAnxaUPJfe.t62hwiCcsqvyiY80.', '2025-06-03 11:11:35', 'Putang', 'default.png'),
(7, 'test2@gmail.com', '$2y$10$YCmxzbcrsamE63zBg06n6ufvaeXk3Vf7wf5QG8KN1cmD/Q0VXFan.', '2025-06-03 11:14:10', 'Test2', 'default.png'),
(8, 'test3@gmail.com', '$2y$10$zj3HGs7.xxmvP6o5kw3siezrcw5BUYKLOfKwRid0rBFHoKYshEKTa', '2025-06-03 11:17:45', 'TEST', 'default.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lowongan`
--
ALTER TABLE `lowongan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pelamar`
--
ALTER TABLE `pelamar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lowongan_id` (`lowongan_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lowongan`
--
ALTER TABLE `lowongan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pelamar`
--
ALTER TABLE `pelamar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pelamar`
--
ALTER TABLE `pelamar`
  ADD CONSTRAINT `pelamar_ibfk_1` FOREIGN KEY (`lowongan_id`) REFERENCES `lowongan` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
