-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2025 at 02:27 PM
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
  `title` varchar(100) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `bidang` varchar(100) DEFAULT NULL,
  `tipe` varchar(50) DEFAULT NULL,
  `gaji` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `pertanyaan` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `perusahaan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lowongan`
--

INSERT INTO `lowongan` (`id`, `perusahaan_id`, `title`, `lokasi`, `bidang`, `tipe`, `gaji`, `deskripsi`, `pertanyaan`, `logo`, `banner`, `perusahaan`) VALUES
(8, 9, 'Automation Programmer', 'Jakarta', 'Developer/Programmer (Teknologi Informasi & Komunikasi)', 'Full-Time', '4.000.000 - 6.000.000', '-Work with our Operations team to plan automation scripts on manual process and data management.\r\n-Create automation scripts using Python or other means for data scraping, data manipulation, listing optimization, research & analysis, and search engine emulation purposes.\r\n-Present findings and deliver potential improvements for process and productivity optimization.\r\n-Research, interpret & analyze patterns and trends in large data sets.\r\n-Identify problems and implement solutions in a timely manner.\r\n-Prepare and present reports regularly.', 'Which of the following types of qualifications do you have?\r\nWhat\'s your expected monthly basic salary?\r\nHow many years\' experience do you have as a programmer?\r\nWhich of the following programming languages are you experienced in?\r\nHow many years\' experience do you have using SQL queries?\r\nHow would you rate your English language skills?\r\nWhich of the following languages are you fluent in?', '1749382227_a868bcb8fbb284f4e8301904535744d488ea93c1.jpeg', '1749382227_a868bcb8fbb284f4e8301904535744d488ea93c1 (1).jpeg', 'PT Mindo Small Business Solutions'),
(9, 9, 'Finance Staff', 'Jakarta', 'Perpajakan (Akuntansi)', 'Part-Time', '6.000.000 - 9.000.000', '-Bachelor’s degree in Accounting, Finance, or Economics\r\n-Minimum 3 years of relevant experience in Finance (General Accounting, Cost Accounting, or similar role)\r\n-Proficient in financial/accounting software (e.g., Accurate)\r\n-Strong knowledge of financial procedures, reporting, and reconciliation\r\n-Able to work under tight deadlines and manage multiple priorities\r\n-High attention to detail, integrity, and a proactive attitude\r\n-Available to join immediately / ASAP', 'Berapa gaji pokok bulanan yang Anda harapkan?\r\nJenis kualifikasi apa yang Anda miliki?\r\nBerapa tahun pengalaman Anda sebagai Staf Keuangan?\r\nBerapa tahun pengalaman Anda dalam perpajakan?\r\nProduk Microsoft Office apa yang Anda kuasai?\r\nPerangkat lunak akuntansi apa yang Anda kuasai?', '1749382902_a868bcb8fbb284f4e8301904535744d488ea93c1.jpeg', '1749382902_a868bcb8fbb284f4e8301904535744d488ea93c1 (1).jpeg', 'PT Mindo Small Business Solutions'),
(10, 9, 'IT Programmer', 'Jakarta', 'Developer/Programmer (Teknologi Informasi & Komunikasi)', 'Remote', '10.000.000 - 15.000.000', '-Develop and programming web application as per specification and requirement for IT Programmer Analyst.\r\n-Design and create database relational model for use in development of Web Application.\r\n-Testing of application and database for web application develop.\r\n-Analyst requirements from IT Programmer Analyst and Business Requirement to be made in web application.\r\n-Maintain and develop existing applications.', 'Kualifikasi apa yang Anda miliki?\r\nBerapa gaji pokok bulanan yang Anda harapkan?\r\nBahasa pemrograman apa yang Anda kuasai?\r\nBerapa tahun pengalaman Anda sebagai programmer teknologi informasi?\r\nBahasa apa yang Anda kuasai?\r\nApakah Anda bersedia pindah untuk posisi ini?', '1749383088_a868bcb8fbb284f4e8301904535744d488ea93c1.jpeg', '1749383088_a868bcb8fbb284f4e8301904535744d488ea93c1 (1).jpeg', 'PT Mindo Small Business Solutions'),
(11, 11, 'Administrator', 'Riau', 'Asisten Administratif (Administrasi & Dukungan Perkantoran)', 'Full-Time', '2.000.000 - 4.000.000', '-Pendidikan minimal Diploma.\r\n-Memiliki pengalaman kerja di bidang farmasi atau FMCG.\r\n-Memahami dan mampu mengoperasikan Microsoft Excel, terutama VLOOKUP\r\n-Pribadi yang rapih, teliti dan resik dalam bekerja\r\n-Bersedia ditempatkan di Kawasan GIIC, Cikarang.\r\n-Posisi ini akan berada di bawah kontrak outsourcing.', 'Berapa gaji pokok bulanan yang Anda harapkan?\r\nJenis kualifikasi apa yang Anda miliki?\r\nProduk Microsoft Office mana yang pernah Anda gunakan?', '1749383460_PTLestari.jpg', '1749383460_PTLestari.jpg', 'PT Sumber Indah Lestari (DAN+DAN)'),
(12, 11, 'Tooling Engineer', 'Bandung', 'Teknik Mesin (Teknik)', 'Part-Time', '15.000.000 - 20.000.000', '-Mengembangkan dan menyediakan peralatan/alat yang ditransfer/alat cadangan untuk mendukung dari tahap pengembangan mainan baru hingga tahap produksi\r\n-Memastikan peralatan berjalan sesuai yang tercantum dalam rencana peralatan\r\n-Meninjau desain peralatan dan komponen untuk modifikasi apa pun\r\n-Melakukan uji coba cetakan, memverifikasi, dan merilis dokumen persetujuan untuk produksi.', 'Berapa gaji pokok bulanan yang Anda harapkan?\r\nBerapa tahun pengalaman Anda sebagai Insinyur Perkakas?\r\nBagaimana Anda menilai kemampuan bahasa Inggris Anda?\r\nBahasa mana yang Anda kuasai dengan lancar?\r\nPerangkat lunak CAD mana yang Anda kuasai?\r\nProduk Microsoft Office mana yang Anda kuasai?\r\nBerapa lama Anda harus memberi tahu atasan Anda saat ini?\r\nApakah Anda bersedia menjalani pemeriksaan latar belakang sebelum bekerja?', '1749383815_PTLestari.jpg', '1749383815_PTLestari.jpg', 'PT Sumber Indah Lestari (DAN+DAN)'),
(13, 9, 'Freelance Social Media Admin', 'Tangerang', 'Komunikasi Pemasaran (Pemasaran & Komunikasi)', 'Freelance', '3.500.000 - 3.750.000', '-Minimal SMA, diploma, lebih disukai dengan gelar D3/S1 di bidang Komunikasi, Pemasaran, atau bidang terkait.\r\n-Minimal 1-2 tahun pengalaman sebagai Admin Media Sosial atau pemasaran digital (pengalaman informal mengelola akun pribadi/bisnis juga dipertimbangkan).\r\n-Mahir dalam platform media sosial (Instagram, TikTok, Facebook, dll.) dan mengikuti tren terkini di setiap platform.\r\n-Mampu membuat konten visual dan tertulis yang menarik (copywriting, caption, storytelling).\r\n-Mampu merencanakan konten, menulis caption, dan menjadwalkan postingan\r\n-Memahami metrik media sosial (tingkat keterlibatan, jangkauan, tayangan) dan kemampuan menganalisis kinerja konten.\r\n-Familiar dengan Meta Business Suite, Hootsuite, atau alat serupa merupakan nilai tambah\r\n-Kreatif, inovatif, dan mampu menghasilkan ide konten yang disesuaikan dengan target audiens.\r\n-Keterampilan komunikasi yang kuat, responsif, dan mampu memenuhi tenggat waktu.\r\n-Berorientasi pada detail dengan keterampilan manajemen waktu yang sangat baik.\r\n-Berbasis di Jakarta atau bersedia bekerja jarak jauh dengan komunikasi yang baik.\r\n-Bekerja secara hybrid (1x/minggu ke kantor) akan menjadi nilai tambah.', 'Berapa gaji pokok bulanan yang Anda harapkan?\r\nBerapa tahun pengalaman Anda sebagai Administrator Media Sosial?\r\nApakah Anda berpengalaman dalam penulisan naskah dan pembuatan konten?', '1749383998_a868bcb8fbb284f4e8301904535744d488ea93c1.jpeg', '1749383998_a868bcb8fbb284f4e8301904535744d488ea93c1 (1).jpeg', 'PT Mindo Small Business Solutions'),
(14, 11, '5-star Hotel Internship', 'Riau', 'Manajemen (Hospitaliti & Pariwisata)', 'Freelance', '4.000.000 - 5.000.000', '-Saat ini terdaftar atau baru saja lulus dari sekolah/universitas yang terkait dengan perhotelan atau pariwisata\r\n-Keterampilan komunikasi bahasa Inggris yang baik dan percaya diri dalam peran yang berhadapan dengan tamu\r\n-Berorientasi pada detail, bersemangat dalam pelayanan, dan bersemangat untuk belajar di lingkungan yang mewah\r\n-Positif, profesional, dan mudah beradaptasi—siap untuk berkembang dalam lingkungan yang serba cepat\r\n-Sikap ramah dan menarik dengan minat yang tulus pada orang dan budaya global\r\n-Bonus jika Anda memiliki pengalaman paruh waktu di hotel, kafe, atau restoran—tunjukkan kepada kami bahwa Anda memiliki keramahtamahan dalam DNA Anda.', 'Kualifikasi apa yang Anda miliki?\r\nBahasa apa yang Anda kuasai dengan lancar?\r\nBagaimana Anda menilai kemampuan bahasa Inggris Anda?\r\nApakah Anda bersedia pindah untuk posisi ini?', '1749384292_PTLestari.jpg', '1749384292_PTLestari.jpg', 'PT Sumber Indah Lestari (DAN+DAN)'),
(15, 11, 'Customer Service - Service Center', 'Tangerang', 'Layanan Konsumen – Berhadapan dengan Konsumen (Call Center & Layanan Konsumen)', 'Remote', '1.000.000 - 3.000.000', '-Bertindak sebagai titik kontak pertama bagi pelanggan yang datang langsung, serta melalui telepon, email, dan obrolan\r\n-Memahami kebutuhan pelanggan, memberikan informasi yang akurat, dan menyelesaikan pertanyaan atau keluhan secara profesional\r\n-Menangani permintaan layanan menyeluruh, mulai dari pendaftaran hingga penyelesaian dan tindak lanjut layanan\r\n-Menjual silang dan menjual lebih banyak layanan dan produk yang relevan dengan kebutuhan pelanggan\r\n-Memastikan dokumentasi yang jelas dan akurat tentang interaksi pelanggan dan hasil layanan dalam sistem\r\n-Berkolaborasi dengan departemen lain untuk menyelesaikan masalah teknis atau operasional secara efisien\r\n-Menjaga area konter layanan yang rapi, ramah, dan profesional\r\n-Menangani penagihan, pembayaran, dan transaksi tunai dasar dengan integritas\r\n-Memenuhi standar tingkat layanan dan mengikuti prosedur perusahaan secara konsisten', 'Berapa gaji bulanan yang kamu inginkan?\r\nKualifikasi mana yang kamu miliki?\r\nHow many years\' experience do you have as a Customer Service Role?\r\nProduk Microsoft Office apa saja di bawah ini yang bisa kamu gunakan?\r\nBahasa apa saja di bawah ini yang fasih kamu gunakan?\r\nApakah kamu berpengalaman di bidang pelayanan pelanggan?\r\nBerapa lama waktu yang kamu butuhkan untuk memberi tahu perusahaanmu saat ini?\r\nApakah kamu bersedia menjalani pemeriksaan latar belakang prakerja?', '1749384537_PTLestari.jpg', '1749384537_PTLestari.jpg', 'PT Sumber Indah Lestari (DAN+DAN)');

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
(13, 8, 'Tomas Becket', '2004-12-24', 'tomas@gmail.com', '01823497973', '6845751fe28a6_syllabus.pdf', '6845751fe2b86_syllabus.pdf', 'Saya mau kerja disini', '2025-06-08 11:33:51', 10),
(14, 15, 'Dennis Don', '2025-06-08', 'denis@gmail.com', '01823497973', '68457f7f6dceb_RPLBO_15_-_MVC_Pattern-No_Thread.pdf', '68457f7f6e036_bab14.pdf', '', '2025-06-08 12:18:07', 12);

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
(9, 'budiono@staff.com', '$2y$10$p37q0B2Ie0tea.kNrK3m.Od96TxVN/Vx9J552AHYZwCvZsAYqXwVq', '2025-06-08 11:23:54', 'Budiono', 'default.png'),
(10, 'tomas@gmail.com', '$2y$10$dRYhjjHsl7oQGlqRRSbG3.QoXCZVt.pNfNluVt1njEQWjZ.EFq646', '2025-06-08 11:32:07', 'Tomas', 'default.png'),
(11, 'staff2@staff.com', '$2y$10$VDKKND/glEo3C7711.dHKOZZbIgWlRsKSPALXCcQxiREY/cFI49c6', '2025-06-08 11:47:33', 'Staff 2', 'default.png'),
(12, 'denis@gmail.com', '$2y$10$FSBO/3yYlH7I2xCQAmtpyO5MdU3g/UznEcEGDwpvkxPMPdF/EG7Je', '2025-06-08 12:12:21', 'Denis', 'default.png');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `pelamar`
--
ALTER TABLE `pelamar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
