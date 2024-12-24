-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2024 at 04:01 AM
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
-- Database: `tap&eat`
--

-- --------------------------------------------------------

--
-- Table structure for table `kantin`
--

CREATE TABLE `kantin` (
  `ID_Warung` int(11) NOT NULL,
  `ID_Pengguna` int(11) NOT NULL,
  `Nama_Warung` varchar(255) NOT NULL,
  `Gambar_Warung` varchar(255) DEFAULT NULL,
  `rating` float NOT NULL,
  `Total_Order` int(11) NOT NULL,
  `Total_Hasil` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kantin`
--

INSERT INTO `kantin` (`ID_Warung`, `ID_Pengguna`, `Nama_Warung`, `Gambar_Warung`, `rating`, `Total_Order`, `Total_Hasil`) VALUES
(14, 38, 'Ahmad', 'kantinahmad.jpg', 0, 0, 0),
(15, 39, 'Bude Macan', 'kantinbude.jpg', 0, 0, 0),
(16, 40, 'Bu Siti', 'kantinsiti.jpg', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `ID_Keranjang` int(11) NOT NULL,
  `ID_Menu` int(11) NOT NULL,
  `ID_Pengguna` int(11) NOT NULL,
  `Jumlah_Pesanan` int(11) NOT NULL,
  `Subtotal_Harga` int(11) NOT NULL,
  `status` enum('checkout','belum checkout','diterima','ditolak','dibuat','selesai') NOT NULL DEFAULT 'belum checkout'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `ID_Menu` int(11) NOT NULL,
  `ID_Warung` varchar(10) NOT NULL,
  `Nama_Menu` varchar(255) NOT NULL,
  `Deskripsi_Menu` varchar(100) NOT NULL,
  `Harga_Menu` decimal(10,2) NOT NULL,
  `Gambar_Menu` varchar(255) DEFAULT NULL,
  `Stok_Menu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`ID_Menu`, `ID_Warung`, `Nama_Menu`, `Deskripsi_Menu`, `Harga_Menu`, `Gambar_Menu`, `Stok_Menu`) VALUES
(7, '13', 'Ayam', 'digoreng', 12000.00, 'Screenshot (2).png', 0),
(8, '14', 'Mie Ayam', 'Menggunakan Ayam Jago', 15000.00, 'mie ayam.jpg', 0),
(9, '14', 'Bakso ', 'Menggunakan Sapi Madura', 16000.00, 'bakso.jpg', 0),
(10, '15', 'Ayam Bakar', 'Dibakar menggunakan api', 17000.00, 'ayam bakar.webp', 0),
(11, '15', 'Gado-Gado', 'Gado-Gado bukan pecel', 12000.00, 'gado-gado.jpeg', 0),
(12, '16', 'Nasi Goreng', 'Tidak digoreng', 10000.00, 'nasi goreng.jpeg', 0),
(13, '16', 'Nasi Kuning', 'Bukan nasi golkar', 12000.00, 'nasi kuning.webp', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_pengguna` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `kontak` int(100) NOT NULL,
  `role` enum('admin','seller','buyer') NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'nonaktif',
  `nama` varchar(255) NOT NULL DEFAULT 'Bocil Ngoding',
  `foto_profile` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_pengguna`, `username`, `email`, `password`, `kontak`, `role`, `status`, `nama`, `foto_profile`) VALUES
(1, 'admin@UIIKANTIN', 'UIIKantin@TapEat', '$2y$10$7aOHEXovF/hbIiUiJ6Mq6uUUM9KP0scofhmryx6avXhi5oEjH7Zkm', 0, 'admin', 'nonaktif', 'Bocil Ngoding', NULL),
(38, 'Ahmad', 'Ahmad@gmail.com', '$2y$10$1qvRtzg.eJKbt1HA2khj1OtMj0WHbWZQ21Vx13fSyIimfbBnpWfV2', 2147483647, 'seller', 'nonaktif', 'Bocil Ngoding', NULL),
(39, 'Bude', 'Bude@gmail.com', '$2y$10$PhyWYXN02CLa09278B.VW.hQFMTxSUtQcwIbsd5ffHTD/6tdLqISG', 2147483647, 'seller', 'nonaktif', 'Bocil Ngoding', NULL),
(40, 'Siti', 'Siti@gmail.com', '$2y$10$FeyWuWzn/Tw69fKwSKWg4O0YovjICVnXYjLHqNKyNnJLZuRTEajj.', 2147483647, 'seller', 'nonaktif', 'Bocil Ngoding', NULL),
(43, 'Shihab', 'Shihab@gmail.com', '$2y$10$5dfqCwDb0wK2xIt2bzebbe7wb8hsZADHK9pFy3DmaFpiiuZ8Eq2ce', 2147483647, 'buyer', 'nonaktif', 'Shihab', 'account.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kantin`
--
ALTER TABLE `kantin`
  ADD PRIMARY KEY (`ID_Warung`),
  ADD KEY `ID_Pengguna` (`ID_Pengguna`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`ID_Keranjang`),
  ADD KEY `ID_Menu` (`ID_Menu`),
  ADD KEY `ID_Pesanan` (`ID_Pengguna`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`ID_Menu`),
  ADD KEY `ID_Warung` (`ID_Warung`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kantin`
--
ALTER TABLE `kantin`
  MODIFY `ID_Warung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `ID_Keranjang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `ID_Menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
