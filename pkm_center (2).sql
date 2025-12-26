-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 25, 2025 at 04:58 PM
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
-- Table structure for table `kelompok`
--

CREATE TABLE `kelompok` (
  `code` varchar(255) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `skema` varchar(255) NOT NULL,
  `dosen` varchar(255) DEFAULT NULL,
  `ketua` varchar(255) DEFAULT NULL,
  `anggota` longtext,
  `accdospem` tinyint(1) DEFAULT NULL,
  `acckaprodi` tinyint(1) DEFAULT NULL,
  `lulus` tinyint(1) DEFAULT NULL,
  `laporanSatu` varchar(255) DEFAULT NULL,
  `laporanDua` varchar(255) DEFAULT NULL,
  `laporanTiga` varchar(255) DEFAULT NULL,
  `komentarLaporanSatu` longtext,
  `komentarLaporanDua` longtext,
  `komentarLaporanTiga` longtext,
  `revisiSatu` varchar(255) DEFAULT NULL,
  `revisiDua` varchar(255) DEFAULT NULL,
  `revisiTiga` varchar(255) DEFAULT NULL,
  `finalProject` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kelompok`
--

INSERT INTO `kelompok` (`code`, `judul`, `nama`, `skema`, `dosen`, `ketua`, `anggota`, `accdospem`, `acckaprodi`, `lulus`, `laporanSatu`, `laporanDua`, `laporanTiga`, `komentarLaporanSatu`, `komentarLaporanDua`, `komentarLaporanTiga`, `revisiSatu`, `revisiDua`, `revisiTiga`, `finalProject`) VALUES
('2b28eb', 'peningkatan efesiensi figma', 'kelompok hore', 'PKM Karsa Cipta (KC)', '0027026701', '2203015047', '[\"2203015048\",\"2203015049\",\"2203015040\",\"2203015042\"]', 1, NULL, NULL, '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `nomor_induk` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jenis_kelamin` varchar(255) DEFAULT NULL,
  `semester` varchar(255) DEFAULT NULL,
  `program_studi` varchar(255) DEFAULT NULL,
  `fakultas` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `no_telpon` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL,
  `code` longtext,
  `kuota` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`nomor_induk`, `nama`, `jenis_kelamin`, `semester`, `program_studi`, `fakultas`, `alamat`, `email`, `no_telpon`, `password`, `foto`, `role`, `code`, `kuota`) VALUES
('0027026701', 'Dr. Ir.  Hamid Al-Jufri, MM', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$ZViGPVCoYOohJzU4cY4T2OKgIFL3boHIRCWuyBklGvP28e.mzffg.', 'dosen.jpg', '3', '[]', '4'),
('0123456789', 'Admin PKM', '', '', '', '', '', 'admin@uhamka.ac.id', '', '$2b$09$8VT9l0HfQ49DVZLvrDSs3OKWUGyVgbswr8FNiAWj1Z7r5I.K2hCRC', 'mahasiswa.png', '6', '[]', ''),
('0224028603', 'Arafat Febriandirza, S.T., M.TI., Ph.D.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$UmOvqQKd7u8FWid4LDytreNL7BJy3OgU/fc6aQARSRLfs5PQT798.', 'dosen.jpg', '3', '[]', '5'),
('0301087803', 'Hendi Saryanto, S.T., M.Eng.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$xiakzN9./eOfA/lzUdOTkuIIlfCT9X5go2m6kPucbD/XACD0lEM8S', 'dosen.jpg', '3', '[]', '5'),
('0301087904', 'Faldy Irwiensyah, S.Kom., M.TI.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$QXGb.RGk22wVLo06mzxP5.xJAR9sNhrQW8GtyBZRRKytHYC8U2eEG', 'dosen.jpg', '3', '[]', '5'),
('0301088305', 'Firman Noor Hasan, S.Kom., M.TI.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$VOXnXNvYgc6W5p7UAQd1gebMoTj5hQSU6AIzDAT/Rocebn2cdUZs.', 'dosen.jpg', '3', '[]', '5'),
('0301126901', 'Dr. Dan Mugisidi, S.T., M.Si.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$8VT9l0HfQ49DVZLvrDSs3OKWUGyVgbswr8FNiAWj1Z7r5I.K2hCRC', 'dosen.jpg', '5', '[]', ''),
('0302026504', 'E. Rizal, S.Kom., M.Kom.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$puYtRoNkXjIMxj711LIfLu6n/BRDvCb882xWsTuZpcTYW2mZr2kJS', 'dosen.jpg', '4', '[]', ''),
('0302069105', 'Nunik Pratiwi, S.T., M.Kom.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$duf7QbyBwSKna2FXonE0iOeIJwRAnwDF/pO62cyFfBt5YTIHe6MyC', 'dosen.jpg', '3', '[]', '5'),
('0303097006', 'Harry Ramza, S.T., M.T., Ph.D.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$OtW859sP7pij9oynnA3XX.eO9lRfr2fAO9MA/UzR970RuS2QKmVCa', 'dosen.jpg', '4', '[]', ''),
('0304017001', 'Rosalina, S.T., M.T.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$sxIVuatoHL.tDhUVBA8KseEfGhbWgjCG8yTwq3qq5/8.YkVf8ZKeO', 'dosen.jpg', '3', '[]', '5'),
('0304048505', 'Nuroji, S.T., M.Kom.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$EMvuWMoXa9hxJr7U9D9P/uCv.e65cKjtBQxZVKR8Gw6Eq1gpIXjtC', 'dosen.jpg', '3', '[]', '5'),
('0305046403', 'Dr. Ir. Mohammad Givi Efgivia., M.Kom', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$MB5/sPNFKoeouhN/bJMokO9QNf.gfYuNs9y7HQTZTvNA.GjjURon6', 'dosen.jpg', '3', '[]', '5'),
('0305046501', 'Ir. Rifky, S.T., M.M., M.T., IPP.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$X1IZeUdTI/BRQGC7woVMF.KDFKaXfrsl3g1ey7l4rCRs1zTK05nc6', 'dosen.jpg', '5', '[]', ''),
('0305067702', 'Oktarina Heriyani, S.Si., M.T.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$0u8cTV4HBgrvTGSjQIJVMOysWKNuqa2ug4.ppZRL/tV9Zj6UwBJnC', 'dosen.jpg', '5', '[]', ''),
('0305125701', 'Kun Fayakun, S.T., M.T.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$cicfqw5C3e9V/SHZUcEsbOPUL9nguXHMDpQAb89gcyGZzlfBxAXAW', 'dosen.jpg', '3', '[]', '5'),
('0306028502', 'Dimas Febriawan, S.Kom., M.TI.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$1PhimytSttXEAd75LghPo.BRbj.mvG6gpATQyjelXrgqWQKY4.EaO', 'dosen.jpg', '3', '[]', '5'),
('0307128301', 'Muchammad Sholeh, S.Kom, M.Kom.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$Hw3XjUX3ZWIpdnKPxCfF0O2Uc6QyEOL7MzTE31qfUJXsb4ofyGL0.', 'dosen.jpg', '3', '[]', '5'),
('0310049502', 'Nur Chalik Azhar, S.Kom., M.Kom.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$Xn7KTiv/qPy4YPWj8NAIbOfs.f1m1bF.SRy15Co5ZURyWoMYo7Va.', 'dosen.jpg', '3', '[]', '5'),
('0311087002', 'Delvis Agusman, S.T., M.Sc.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$JMVgkgs8uZMmXRJ1Ch0cy.e8Oa/3NydUV85mloUOWK3f.xQ4klOiq', 'dosen.jpg', '3', '[]', '5'),
('0311128701', 'Akhmad Rizal Dzikrillah, S.T., M.TI.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$KGLdNCRXDTIS7Uudc3BBluQOQbcsqBqaUPdtN2.pOo9UbpZMaKN7W', 'dosen.jpg', '3', '[]', '5'),
('0312028704', 'Mia Kamayani Sulaeman, S.T., M.T.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$3ipUZBvhxWe7PZolsokhweacxHqHZ2OcwwHpsn7ERNZVh90TfJA6W', 'dosen.jpg', '4', '[]', ''),
('0312126705', 'M. Mujirudin, ST., MT', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$AJw9ShaIdBMxvbTNJY81DuJooM78uxM1GUtuamtjjsmRwBUcRf34K', 'dosen.jpg', '3', '[]', '5'),
('0313028602', 'Zuhri Halim, S.Kom., M.Kom.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$2NNI3V7bUUIBLYWnjRQvde3l8rtJRypIaiujpE0nZFdvNt/At6Fqe', 'dosen.jpg', '3', '[]', '5'),
('0314098403', 'Estu Sinduningrum, S.T., M.T.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$NjPLysreTV826JLTtqUomu0dyxt9EKjX3vTyfoGi517BX9MYYt8zO', 'dosen.jpg', '3', '[]', '5'),
('0315046802', 'Pancatatva Hesti Gunawan, ST.,MT.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$LVGYJJT/y6vsX4HLRH0vkO5vhuxxsCqPua5GdjeKmcP2OfoiYB/BC', 'dosen.jpg', '3', '[]', '5'),
('0316099202', 'Isa Faqihuddin, S.Kom., M.MSI.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$OR4Cn0wNVpPxJI9fMWwmTe5L4627FEPYUj6rBRxbzh4TEJBP/sJei', 'dosen.jpg', '3', '[]', '5'),
('0319027901', 'Yos Nofendri S.Pd., MSME', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$XOFzSMX3ysA8lv0FvtsAQOlp6vfvK3rackLAaqza/Yf9wsvqTk2dK', 'dosen.jpg', '3', '[]', '5'),
('0319087101', 'Agus Fikri, S.T., M.T.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$8PUD70eehMJojDCswBD2iOEQmuoU0k/wVC6uSwOxsc7F7hy9I5flC', 'dosen.jpg', '3', '[]', '5'),
('0321089205', 'Irwansyah, S.Kom., M.Kom.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$yVOSQiMdGPgRrHtngcIpPOtoBNsLEEWOybWPzyOnG3vs53PAGf99i', 'dosen.jpg', '3', '[]', '5'),
('0322077101', 'Endy Syaiful Alim, S.T., M.T., Ph.D.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$wVmfoSnxeaDD5bdUWyo6yu621F.QwqjdF43iASlWk82M6.cY/B6ei', 'dosen.jpg', '3', '[]', '5'),
('0323027401', 'Dr. Dwi Astuti Cahyasiwi, S.T., M.T.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$eCulRCKR9s3dDy/nE9bPpOQf/zVJP0jNqZ45MzMgz6cJ6tVIC.93.', 'dosen.jpg', '3', '[]', '5'),
('0324069102', 'Riyan Ariyansah, S.T., M.T.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$BjeNeuB9RyOaQdbZBOtNBOWEOdfhc46X0y7hZ8HjXNS.TDdZrThS.', 'dosen.jpg', '4', '[]', ''),
('0325119302', 'Ade Davy Wiranata, M.Kom.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$5h0Jh205TLBn9JnHAcbbhub4/d7YIQjHHbpF3GKwbB2oExrLcAzt.', 'dosen.jpg', '3', '[]', '5'),
('0328056901', 'Arry Avorizano, S.Kom., M.Kom.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$CdwF.3IjpJtsU49HfVfl9ehgCjmy4R8TUrsyKiUBTt/EecpCG/LJa', 'dosen.jpg', '3', '[]', '5'),
('0330016001', 'Mohammad Yusuf D, Drs., M.M., M.T.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$4apTblJrYJ8uT3cQHy.nn.Neo7hG2abVOuBmJKBpWeADFeuqJXzBq', 'dosen.jpg', '5', '[]', ''),
('0330019204', 'Rahmi Imanda, S.Kom., M.Kom.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$brwsG/YNz9UcL6ZE6tGTy.D.z.n4RAtRkIpH06rt601PVFbqA.xNa', 'dosen.jpg', '3', '[]', '5'),
('0330097402', 'Emilia Roza, S.T., M.Pd., M.T.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$N2PU3xJOxAEmu58Xlv8hieHXzb7Zq0RsblE3zpBhVetSvXAa1WwlG', 'dosen.jpg', '3', '[]', '5'),
('0331017304', 'Atiqah Meutia Hilda, S.Kom., M.Kom.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$d9eSbsb7Y8RpeLhxycYX..yEGv18yz8GfTEx.rDyBuF0MXr.DAYOu', 'dosen.jpg', '3', '[]', '5'),
('2203015040', 'mark pertapen', 'Laki-Laki', '5', 'Pendidikan Biologi', 'Fakultas Keguruan dan Ilmu Pendidikan', 'Jl Cibubur 2 Blok duku', 'tedyhermawanto@gmail.com', '6285819954391', '$2b$09$SX6pqfiLjez42YMBB8wZfOr1WVQOFnKGXWD/Vw.9jdSd7IRVkBH.e', 'mahasiswa.png', '2', '[\"2b28eb\"]', ''),
('2203015042', 'luis hamilton', 'Laki-Laki', '5', 'Teknik Elektro', 'Fakultas Teknik Industri dan Informatika', 'Jl Cibubur 2 Blok duku', 'yextiuxiu@gmail.com', '6285819954391', '$2b$09$PHY7bwwQy2va0f99xWA9Ve7hdhpL.XVMoxURwjSZ5m0p8bQa2aD/C', 'mahasiswa.png', '2', '[\"2b28eb\"]', ''),
('2203015047', 'Yoga Budi Santoso', 'Laki-Laki', '5', 'Teknik Informatika', 'Fakultas Teknik Industri dan Informatika', 'Jl Cibubur 2 Blok duku', 'yogabudisantoso25@gmail.com', '6285883398981', '$2b$09$PYpxb0enq6nv5iZjN8c9iuYnIuGXEalnVNKbh.1xwIEHBB11RdzkW', 'mahasiswa.png', '1', '[\"2b28eb\"]', ''),
('2203015048', 'jamal', 'Laki-Laki', '3', 'Sistem dan Teknologi Informasi', 'Fakultas Teknik Industri dan Informatika', 'Jl Cibubur 2 Blok duku', 'yogabudisantoso25@gmail.com', '6285819954391', '$2b$09$VUFs5caeINQotTlS6E6d3u8lAM00Brn2hoV6h6/ZLU/BU111tuyhq', 'mahasiswa.png', '2', '[\"2b28eb\"]', ''),
('2203015049', 'jarwo', 'Laki-Laki', '1', 'Pendidikan Bahasa Inggris', 'Fakultas Keguruan dan Ilmu Pendidikan', 'Jl Jendral Sudirman', 'budiagustono@gmail.com', '6285819954391', '$2b$09$JWJFlxHtZu5iGTo6Kd3v/e.cezNjKIsMXiw.0nt25iQhM5fkyA77G', 'mahasiswa.png', '2', '[\"2b28eb\"]', ''),
('9990601145', 'Mohammad Haekal, Ph.D.', '', '', '', '', '', 'dosen@uhamka.ac.id', '', '$2b$09$vzEqwtHZYjRtGoA3JVgHE.EboZxkrFgWuSdMxGmzBjNAZVkS6Pjtq', 'dosen.jpg', '3', '[]', '5');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kelompok`
--
ALTER TABLE `kelompok`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`nomor_induk`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
