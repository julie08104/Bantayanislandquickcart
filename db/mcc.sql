-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 08, 2024 at 05:47 AM
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
-- Database: `mcc`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `verification_code` varchar(50) NOT NULL,
  `forgot_password_code` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `firstname`, `lastname`, `phone`, `address`, `email`, `password_hash`, `is_verified`, `verification_code`, `forgot_password_code`, `created_at`) VALUES
(2, 'test', 'test', '09123456789', 'Madridejos Cebu', 'customer1@gmail.com', '$2y$10$aUzbTwzqoF/kjRKd88jmFeY9OdI7u2LOYLykdjVeOYNZjiQQCdjWC', 1, '', '', '2024-09-06 08:06:09'),
(4, 'test22', 'test22', '09123456789', 'Madridejos Cebu', 'customer2@gmail.com', '$2y$10$kXTc3iQyd7d8VUqVgQgezOEhwo7svGYH5wjjnbOsijKxIFKGRQZnS', 0, '3a423e69a2182f21f9a3af02a38c8478', '', '2024-09-06 13:42:00');

-- --------------------------------------------------------

--
-- Table structure for table `raiders`
--

CREATE TABLE `raiders` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `vehicle_type` varchar(150) NOT NULL,
  `vehicle_number` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `verification_code` varchar(50) NOT NULL,
  `forgot_password_code` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `raiders`
--

INSERT INTO `raiders` (`id`, `firstname`, `lastname`, `phone`, `address`, `vehicle_type`, `vehicle_number`, `email`, `password_hash`, `is_verified`, `verification_code`, `forgot_password_code`, `created_at`) VALUES
(4, 'test', 'test', '09123456789', 'Madridejos Cebu', 'Motor', 'Motor1', 'test@gmail.com', '$2y$10$Udmtw6hiYM.b92QuF4Yg8.09B5FkkxJYvMr7YtV6WMPpcOhRZgUS.', 0, 'e28a3f0be5c80429a4a5230d7eee0a1a', '', '2024-09-06 13:37:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `verification_code` varchar(50) DEFAULT NULL,
  `forgot_password_code` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `is_verified`, `verification_code`, `forgot_password_code`, `created_at`) VALUES
(6, 'Mang Jose', 'mang061894@gmail.com', '$2y$10$z.nW1Ly3Ilh2Fqwc2ADNbew4pctalynnZquKaotI3h8iTMs5WZhYW', 1, NULL, NULL, '2024-09-06 05:38:27'),
(10, 'test', 'test@test.com', '$2y$10$BPq4LnwJvnTrg/vyYnc77uPUpZ6W8s.w.0jiEStVc8z2AypY4hjuO', 0, '2e49483b8a449d164f3bc8ba905a9c7c', NULL, '2024-09-06 13:31:26'),
(11, 'test2', 'test2@gmail.com', '$2y$10$/L1hzjtEyZARljiH7CIIzOef4uom1kBZulvz.ngtCsi3dLSVZBtke', 0, 'c3351c6310b5edf9679b2a2714136727', NULL, '2024-09-06 13:31:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `raiders`
--
ALTER TABLE `raiders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

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
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `raiders`
--
ALTER TABLE `raiders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
