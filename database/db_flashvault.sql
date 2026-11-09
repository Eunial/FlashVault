-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 09, 2026 at 12:08 AM
-- Server version: 8.4.3
-- PHP Version: 8.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_flashvault`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_cards`
--

CREATE TABLE `tb_cards` (
  `id_kartu` int UNSIGNED NOT NULL,
  `id_deck` int UNSIGNED NOT NULL,
  `pertanyaan` text NOT NULL,
  `jawaban` text NOT NULL,
  `interval_hari` int UNSIGNED NOT NULL DEFAULT '0',
  `jadwal_review` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_categories`
--

CREATE TABLE `tb_categories` (
  `id_kategori` int UNSIGNED NOT NULL,
  `nama_kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_categories`
--

INSERT INTO `tb_categories` (`id_kategori`, `nama_kategori`) VALUES
(4, 'Backend'),
(5, 'Data Science & ML'),
(3, 'Frontend'),
(6, 'Lainnya'),
(2, 'Mobile Development'),
(1, 'Web Development');

-- --------------------------------------------------------

--
-- Table structure for table `tb_decks`
--

CREATE TABLE `tb_decks` (
  `id_deck` int UNSIGNED NOT NULL,
  `id_user` int UNSIGNED NOT NULL,
  `id_kategori` int UNSIGNED NOT NULL,
  `nama_deck` varchar(100) NOT NULL,
  `deskripsi` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_review_logs`
--

CREATE TABLE `tb_review_logs` (
  `id_review` int UNSIGNED NOT NULL,
  `id_user` int UNSIGNED NOT NULL,
  `id_kartu` int UNSIGNED NOT NULL,
  `rating` enum('sulit','lumayan','mudah') NOT NULL,
  `reviewed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_users`
--

CREATE TABLE `tb_users` (
  `id_user` int UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_cards`
--
ALTER TABLE `tb_cards`
  ADD PRIMARY KEY (`id_kartu`),
  ADD KEY `fk_cards_deck` (`id_deck`);

--
-- Indexes for table `tb_categories`
--
ALTER TABLE `tb_categories`
  ADD PRIMARY KEY (`id_kategori`),
  ADD UNIQUE KEY `uq_categories_nama` (`nama_kategori`);

--
-- Indexes for table `tb_decks`
--
ALTER TABLE `tb_decks`
  ADD PRIMARY KEY (`id_deck`),
  ADD KEY `fk_decks_user` (`id_user`),
  ADD KEY `fk_decks_kategori` (`id_kategori`);

--
-- Indexes for table `tb_review_logs`
--
ALTER TABLE `tb_review_logs`
  ADD PRIMARY KEY (`id_review`),
  ADD KEY `fk_review_user` (`id_user`),
  ADD KEY `fk_review_kartu` (`id_kartu`);

--
-- Indexes for table `tb_users`
--
ALTER TABLE `tb_users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `uq_users_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_cards`
--
ALTER TABLE `tb_cards`
  MODIFY `id_kartu` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_categories`
--
ALTER TABLE `tb_categories`
  MODIFY `id_kategori` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_decks`
--
ALTER TABLE `tb_decks`
  MODIFY `id_deck` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_review_logs`
--
ALTER TABLE `tb_review_logs`
  MODIFY `id_review` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_users`
--
ALTER TABLE `tb_users`
  MODIFY `id_user` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_cards`
--
ALTER TABLE `tb_cards`
  ADD CONSTRAINT `fk_cards_deck` FOREIGN KEY (`id_deck`) REFERENCES `tb_decks` (`id_deck`) ON DELETE CASCADE;

--
-- Constraints for table `tb_decks`
--
ALTER TABLE `tb_decks`
  ADD CONSTRAINT `fk_decks_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `tb_categories` (`id_kategori`) ON DELETE RESTRICT,
  ADD CONSTRAINT `fk_decks_user` FOREIGN KEY (`id_user`) REFERENCES `tb_users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `tb_review_logs`
--
ALTER TABLE `tb_review_logs`
  ADD CONSTRAINT `fk_review_kartu` FOREIGN KEY (`id_kartu`) REFERENCES `tb_cards` (`id_kartu`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_review_user` FOREIGN KEY (`id_user`) REFERENCES `tb_users` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
