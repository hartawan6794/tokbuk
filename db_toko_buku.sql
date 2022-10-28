-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2022 at 05:59 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_toko_buku`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(5, '2022-10-19-141937', 'App\\Database\\Migrations\\TblUserBiodata', 'default', 'App', 1666512082, 1),
(6, '2022-10-19-142944', 'App\\Database\\Migrations\\TblAlamat', 'default', 'App', 1666512083, 1),
(8, '2022-10-24-102929', 'App\\Database\\Migrations\\TblToko', 'default', 'App', 1666607729, 2),
(9, '2022-10-20-071912', 'App\\Database\\Migrations\\Tbluser', 'default', 'App', 1666624699, 3),
(10, '2022-10-27-012434', 'App\\Database\\Migrations\\AlterDropEmail', 'default', 'App', 1666834131, 4),
(17, '2022-10-27-160802', 'App\\Database\\Migrations\\TblKategori', 'default', 'App', 1666887085, 6),
(22, '2022-10-27-074548', 'App\\Database\\Migrations\\TblProduk', 'default', 'App', 1666892451, 7);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_alamat`
--

CREATE TABLE `tbl_alamat` (
  `id_alamat` smallint(6) NOT NULL,
  `id_user_bio` smallint(6) DEFAULT NULL,
  `provinsi` varchar(100) NOT NULL,
  `kabupaten` varchar(100) NOT NULL,
  `kecamatan` varchar(100) NOT NULL,
  `kelurahan` varchar(150) NOT NULL,
  `alamat_rumah` varchar(200) NOT NULL,
  `postalcode` varchar(20) DEFAULT NULL,
  `status` varchar(2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kategori`
--

CREATE TABLE `tbl_kategori` (
  `id_kategori` tinyint(4) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `id_product` smallint(6) NOT NULL,
  `id_toko` smallint(6) NOT NULL,
  `judul_buku` varchar(255) NOT NULL,
  `nm_penerbit` varchar(255) NOT NULL,
  `nm_penulis` varchar(255) DEFAULT NULL,
  `tahun_terbit` varchar(4) DEFAULT NULL,
  `jml_halaman` smallint(6) DEFAULT NULL,
  `deskripsi_buku` varchar(255) DEFAULT NULL,
  `id_kategori` tinyint(4) NOT NULL,
  `stok` smallint(6) DEFAULT NULL,
  `harga_buku` decimal(10,0) DEFAULT NULL,
  `imgproduct1` varchar(255) DEFAULT NULL,
  `imgproduct2` varchar(255) DEFAULT NULL,
  `imgproduct3` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_toko`
--

CREATE TABLE `tbl_toko` (
  `id_toko` smallint(6) NOT NULL,
  `id_user_bio` smallint(6) NOT NULL,
  `nm_toko` varchar(20) NOT NULL,
  `alamat_toko` varchar(255) NOT NULL,
  `telpon` varchar(15) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id_user` smallint(6) NOT NULL,
  `username` varchar(20) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `password` varchar(32) DEFAULT NULL,
  `role` tinyint(2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id_user`, `username`, `status`, `password`, `role`, `created_at`, `updated_at`) VALUES
(5, 'admin', 10, '21232f297a57a5a743894a0e4a801fc3', 0, '2022-10-24 04:33:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_biodata`
--

CREATE TABLE `tbl_user_biodata` (
  `id_user_bio` smallint(6) NOT NULL,
  `nik_user` varchar(20) NOT NULL,
  `nm_user` varchar(100) NOT NULL,
  `email_user` varchar(100) NOT NULL,
  `gender` enum('1','2') DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `tempat_lahir` varchar(150) DEFAULT NULL,
  `telpon` varchar(15) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `imguser` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_user_biodata`
--

INSERT INTO `tbl_user_biodata` (`id_user_bio`, `nik_user`, `nm_user`, `email_user`, `gender`, `tanggal_lahir`, `tempat_lahir`, `telpon`, `alamat`, `imguser`, `created_at`, `updated_at`) VALUES
(11, 'admin', 'admin', 'admin@test.com', '1', '0001-01-01', '-', '080808080808', '-', NULL, '2022-10-24 04:33:02', '2022-10-26 19:25:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_alamat`
--
ALTER TABLE `tbl_alamat`
  ADD PRIMARY KEY (`id_alamat`),
  ADD KEY `tbl_alamat_id_user_bio_foreign` (`id_user_bio`);

--
-- Indexes for table `tbl_kategori`
--
ALTER TABLE `tbl_kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`id_product`),
  ADD KEY `tbl_product_id_toko_foreign` (`id_toko`),
  ADD KEY `tbl_product_id_kategori_foreign` (`id_kategori`);

--
-- Indexes for table `tbl_toko`
--
ALTER TABLE `tbl_toko`
  ADD PRIMARY KEY (`id_toko`),
  ADD KEY `tbl_toko_id_user_bio_foreign` (`id_user_bio`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `tbl_user_biodata`
--
ALTER TABLE `tbl_user_biodata`
  ADD PRIMARY KEY (`id_user_bio`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tbl_alamat`
--
ALTER TABLE `tbl_alamat`
  MODIFY `id_alamat` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_kategori`
--
ALTER TABLE `tbl_kategori`
  MODIFY `id_kategori` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `id_product` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_toko`
--
ALTER TABLE `tbl_toko`
  MODIFY `id_toko` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id_user` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_user_biodata`
--
ALTER TABLE `tbl_user_biodata`
  MODIFY `id_user_bio` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_alamat`
--
ALTER TABLE `tbl_alamat`
  ADD CONSTRAINT `tbl_alamat_id_user_bio_foreign` FOREIGN KEY (`id_user_bio`) REFERENCES `tbl_user_biodata` (`id_user_bio`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD CONSTRAINT `tbl_product_id_kategori_foreign` FOREIGN KEY (`id_kategori`) REFERENCES `tbl_kategori` (`id_kategori`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_product_id_toko_foreign` FOREIGN KEY (`id_toko`) REFERENCES `tbl_toko` (`id_toko`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_toko`
--
ALTER TABLE `tbl_toko`
  ADD CONSTRAINT `tbl_toko_id_user_bio_foreign` FOREIGN KEY (`id_user_bio`) REFERENCES `tbl_user_biodata` (`id_user_bio`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
