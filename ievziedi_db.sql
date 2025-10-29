-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2025 at 10:58 AM
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
-- Database: `ievziedi_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `boats`
--

CREATE TABLE `boats` (
  `id` int(11) NOT NULL,
  `Routes` varchar(50) NOT NULL,
  `Price` int(11) NOT NULL,
  `Distance(km)` int(11) NOT NULL,
  `Duration (h)` int(11) NOT NULL,
  `Rental day` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cottages`
--

CREATE TABLE `cottages` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price_per_night` int(11) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hot_tub`
--

CREATE TABLE `hot_tub` (
  `id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `duration_h` int(11) NOT NULL,
  `rental_day` int(11) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `main_services`
--

CREATE TABLE `main_services` (
  `id` int(11) NOT NULL,
  `boat_id` int(11) DEFAULT NULL,
  `sup_id` int(11) DEFAULT NULL,
  `cottage_id` int(11) DEFAULT NULL,
  `sauna_id` int(11) DEFAULT NULL,
  `hot_tub_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sauna`
--

CREATE TABLE `sauna` (
  `id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `duration_h` int(11) NOT NULL,
  `rental_day` int(11) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sup_boards`
--

CREATE TABLE `sup_boards` (
  `id` int(11) NOT NULL,
  `routes` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `distance_km` int(11) NOT NULL,
  `duration_h` int(11) NOT NULL,
  `rental_day` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `boats`
--
ALTER TABLE `boats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cottages`
--
ALTER TABLE `cottages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hot_tub`
--
ALTER TABLE `hot_tub`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `main_services`
--
ALTER TABLE `main_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `boat_id` (`boat_id`),
  ADD KEY `sup_id` (`sup_id`),
  ADD KEY `cottage_id` (`cottage_id`),
  ADD KEY `sauna_id` (`sauna_id`),
  ADD KEY `hot_tub_id` (`hot_tub_id`);

--
-- Indexes for table `sauna`
--
ALTER TABLE `sauna`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sup_boards`
--
ALTER TABLE `sup_boards`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `boats`
--
ALTER TABLE `boats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cottages`
--
ALTER TABLE `cottages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hot_tub`
--
ALTER TABLE `hot_tub`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `main_services`
--
ALTER TABLE `main_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sauna`
--
ALTER TABLE `sauna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sup_boards`
--
ALTER TABLE `sup_boards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `main_services`
--
ALTER TABLE `main_services`
  ADD CONSTRAINT `main_services_ibfk_1` FOREIGN KEY (`boat_id`) REFERENCES `boats` (`id`),
  ADD CONSTRAINT `main_services_ibfk_2` FOREIGN KEY (`sup_id`) REFERENCES `sup_boards` (`id`),
  ADD CONSTRAINT `main_services_ibfk_3` FOREIGN KEY (`cottage_id`) REFERENCES `cottages` (`id`),
  ADD CONSTRAINT `main_services_ibfk_4` FOREIGN KEY (`sauna_id`) REFERENCES `sauna` (`id`),
  ADD CONSTRAINT `main_services_ibfk_5` FOREIGN KEY (`hot_tub_id`) REFERENCES `hot_tub` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
