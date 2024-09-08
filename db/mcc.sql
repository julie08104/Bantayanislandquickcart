-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 08, 2024 at 10:45 AM
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
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `raider_id` int(11) NOT NULL,
  `assigned_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`id`, `order_id`, `raider_id`, `assigned_at`) VALUES
(10, 1, 5, '2024-09-08 15:10:22');

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
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `status` enum('pending','assigned','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending',
  `instruction` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `status`, `instruction`, `created_at`, `updated_at`) VALUES
(1, 2, 'completed', '1 dark caramel coffee', '2024-09-08 14:15:11', '2024-09-08 15:35:26'),
(2, 2, 'pending', '1 pizza in albertos', '2024-09-08 15:27:56', NULL);

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
(4, 'test', 'test', '09123456789', 'Madridejos Cebu', 'Motor', 'Motor1', 'test@gmail.com', '$2y$10$Udmtw6hiYM.b92QuF4Yg8.09B5FkkxJYvMr7YtV6WMPpcOhRZgUS.', 0, 'e28a3f0be5c80429a4a5230d7eee0a1a', '', '2024-09-06 13:37:19'),
(5, 'john', 'doe', '09123456789', 'Madridejos Cebu', 'Motor', 'Motor12', 'raider2@gmail.com', '$2y$10$Dpuk/Bj0tE3P3R41biTgV.GMF95DTGJVZzKYRJW7oIKR3c1gflCTC', 1, '', '', '2024-09-08 07:09:48');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `order_id`, `customer_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 2, 4, 'delivered on time', '2024-09-08 16:08:41');

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
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `raider_id` (`raider_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `raiders`
--
ALTER TABLE `raiders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

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
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `raiders`
--
ALTER TABLE `raiders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignments_ibfk_2` FOREIGN KEY (`raider_id`) REFERENCES `raiders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
