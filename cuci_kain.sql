-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 27, 2025 at 01:03 PM
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
-- Database: `cuci_kain`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id_detail` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `id_layanan` int(11) NOT NULL,
  `jumlah` decimal(5,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id_detail`, `id_pesanan`, `id_layanan`, `jumlah`, `subtotal`) VALUES
(3, 6, 2, 123.00, 3075000.00),
(4, 1, 2, 10.20, 255000.00),
(5, 5, 1, 12.00, 240000.00),
(6, 4, 2, 0.49, 12250.00),
(7, 3, 1, 1.30, 26000.00),
(8, 2, 1, 1.00, 20000.00);

-- --------------------------------------------------------

--
-- Table structure for table `layanan`
--

CREATE TABLE `layanan` (
  `id_layanan` int(11) NOT NULL,
  `nama_layanan` varchar(100) NOT NULL,
  `harga` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `layanan`
--

INSERT INTO `layanan` (`id_layanan`, `nama_layanan`, `harga`) VALUES
(1, 'Kiloan (Reguler)', 20000.00),
(2, 'Kiloan (Express)', 25000.00),
(3, 'Kiloan (Super Express)', 30000.00),
(4, 'Satuan', 5000.00);

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `status` varchar(50) DEFAULT 'Menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `id_user`, `tanggal`, `status`) VALUES
(1, 6, '2025-06-25', 'Selesai'),
(2, 6, '2025-06-25', 'Diproses'),
(3, 6, '2025-06-25', 'Diproses'),
(4, 1, '2025-06-25', 'Dibatalkan'),
(5, 13, '2025-07-25', 'Diproses'),
(6, 12, '2025-08-12', 'Diproses');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama`, `alamat`, `no_hp`, `username`, `password`, `role`) VALUES
(1, 'Muhammad Yusufa', 'Limbungan', '089653373859', 'Yusuf', 'dd2eb170076a5dec97cdbbbbff9a4405', 'admin'),
(6, 'Muyu', 'Limbungana', '123123123', 'WOOPA', '$2y$10$405g6ez55iF9/xBvDXGFsORqqzm/wR3VkLtNtmvgJWnaaXadYG8ZC', 'admin'),
(7, 'ROOPA', 'PRAMUKAS', '089653373859', 'ROOPA', '$2y$10$Y8amJMFHo9X3APFAKWFJCejC4IH0uzZSPsM8m1bS5ZsJizi5stNe.', 'user'),
(12, 'tommy', 'dfewf', '1234', 'UDEEN', 'yammeh', 'admin'),
(13, 'yammeh', '1grgw', 'gwergrg', 'yeameh', '$2y$10$Mcir7hn4.Xyxsyi7TZK3FuaZs4CvyCGaLpS1MUKaVTgeHXcJpua9q', 'user');

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_laporan_detail_pesanan`
-- (See below for the actual view)
--
CREATE TABLE `vw_laporan_detail_pesanan` (
`id_detail` int(11)
,`id_pesanan` int(11)
,`id_layanan` int(11)
,`jumlah` decimal(5,2)
,`subtotal` decimal(10,2)
,`tanggal_pesanan` date
,`tahun_pesanan` int(4)
,`bulan_pesanan` int(2)
,`nama_layanan` varchar(100)
);

-- --------------------------------------------------------

--
-- Structure for view `vw_laporan_detail_pesanan`
--
DROP TABLE IF EXISTS `vw_laporan_detail_pesanan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_laporan_detail_pesanan`  AS SELECT `dp`.`id_detail` AS `id_detail`, `dp`.`id_pesanan` AS `id_pesanan`, `dp`.`id_layanan` AS `id_layanan`, `dp`.`jumlah` AS `jumlah`, `dp`.`subtotal` AS `subtotal`, `p`.`tanggal` AS `tanggal_pesanan`, year(`p`.`tanggal`) AS `tahun_pesanan`, month(`p`.`tanggal`) AS `bulan_pesanan`, `l`.`nama_layanan` AS `nama_layanan` FROM ((`detail_pesanan` `dp` join `pesanan` `p` on(`dp`.`id_pesanan` = `p`.`id_pesanan`)) join `layanan` `l` on(`dp`.`id_layanan` = `l`.`id_layanan`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_layanan` (`id_layanan`);

--
-- Indexes for table `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id_layanan`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `idx_pesanan_tanggal` (`tanggal`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id_layanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`),
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id_layanan`);

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
