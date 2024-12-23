-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2024 at 06:58 AM
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
(12, 25, 'Andi Kyud', 'Screenshot 2024-12-18 004716.png', 0, 0, 0),
(13, 37, 'Ali', 'warung_6767abc4c6e851.32068192.png', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `ID_Keranjang` int(11) NOT NULL,
  `ID_Menu` int(11) NOT NULL,
  `ID_Pengguna` int(11) NOT NULL,
  `Jumlah_Pesanan` int(11) NOT NULL,
  `Subtotal_Harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keranjang`
--

INSERT INTO `keranjang` (`ID_Keranjang`, `ID_Menu`, `ID_Pengguna`, `Jumlah_Pesanan`, `Subtotal_Harga`) VALUES
(1, 5, 23, 5, 0),
(2, 4, 23, 4, 0),
(3, 4, 35, 1, 0),
(4, 5, 35, 1, 0);

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
(4, '12', 'Tupai Goreng', 'enak', 10000.00, 'Screenshot 2024-12-18 004716.png', 10),
(5, '12', 'Bintang goreng', 'Hitam', 0.00, 'Screenshot 2024-11-08 225141.png', 10);

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
  `foto_profile` varchar(255) DEFAULT 'profile.jpeg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_pengguna`, `username`, `email`, `password`, `kontak`, `role`, `status`, `nama`, `foto_profile`) VALUES
(1, 'admin@UIIKANTIN', 'UIIKantin@TapEat', '$2y$10$7aOHEXovF/hbIiUiJ6Mq6uUUM9KP0scofhmryx6avXhi5oEjH7Zkm', 0, 'admin', 'nonaktif', 'Bocil Ngoding', '0'),
(23, 'Shihab', 'Shihab@gmail.com', '$2y$10$fwfa5IrT/29VFcAvCti.IOkHM5cs.DD.ZajAUgiiHlgYgRlbep/z6', 222, 'buyer', 'nonaktif', 'Shihab', '0'),
(25, 'Andi', 'Andi@gmail.com', '$2y$10$YyoQJjjzfHL/M4RoB/4GvemnPyj30mW0czCRGa/03WEsi7J.ZCXzS', 123, 'seller', 'nonaktif', 'Bocil Ngoding', '0'),
(35, 'ccc', 'TES1@gmail.com', '$2y$10$9Ut3Lofk6WCmtfjD3hKzY.JNNqgEASiSaJom0QBghxi.LSpOT4YiW', 123, 'buyer', 'aktif', 'Bocil Ngoding', '0'),
(37, 'aaa', 'TES1@gmail.com', '$2y$10$jmHsRAhFXCmC62IntNWj7OIij3QhPRTh.4P5DhBW24qVWEez3tLou', 123, 'seller', 'nonaktif', 'Bocil Ngoding', '0');

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
  MODIFY `ID_Warung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `ID_Keranjang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `ID_Menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
