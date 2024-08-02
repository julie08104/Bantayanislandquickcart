-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2024 at 09:10 AM
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
-- Database: `ample`
--

-- --------------------------------------------------------

--
-- Table structure for table `catagory`
--

CREATE TABLE `catagory` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `company` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `username`, `password`, `name`, `lastname`, `company`, `address`, `contact`, `email`, `created_at`, `updated_at`) VALUES
(27, NULL, NULL, 'jonhn', 'doe', 'mcc', 'mccc', '11112', 'dsad@gmail.com', '2024-07-18 05:21:10', '2024-07-18 22:17:07'),
(0, NULL, NULL, 'tae', 'tae', 'kota', 'wewss', '2121212121', 'ano@gmail.com', '2024-07-23 16:30:57', '2024-07-23 16:30:57');

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

CREATE TABLE `expense` (
  `id` int(11) NOT NULL,
  `ex_date` date NOT NULL,
  `expense_for` varchar(50) NOT NULL,
  `amount` float(15,2) NOT NULL DEFAULT 0.00,
  `expense_cat` int(11) NOT NULL,
  `ex_description` text NOT NULL,
  `added_by` int(11) DEFAULT NULL,
  `added_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_category`
--

CREATE TABLE `expense_category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `added_by` int(11) NOT NULL,
  `added_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `factory_products`
--

CREATE TABLE `factory_products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_id` varchar(50) NOT NULL,
  `brand_name` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `quantity` int(10) NOT NULL,
  `alert_quantity` int(4) NOT NULL,
  `product_expense` float(15,2) NOT NULL DEFAULT 0.00,
  `sell_price` float(15,2) NOT NULL DEFAULT 0.00,
  `added_by` int(11) NOT NULL,
  `last_update_at` date NOT NULL,
  `added_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `invoice_number` varchar(100) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(50) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `sub_total` float(15,2) NOT NULL DEFAULT 0.00,
  `discount` float(15,2) NOT NULL DEFAULT 0.00,
  `pre_cus_due` float(15,2) NOT NULL DEFAULT 0.00,
  `net_total` float(15,2) NOT NULL DEFAULT 0.00,
  `paid_amount` float(15,2) NOT NULL DEFAULT 0.00,
  `due_amount` float(15,2) NOT NULL DEFAULT 0.00,
  `payment_type` varchar(20) NOT NULL,
  `return_status` varchar(30) NOT NULL DEFAULT 'no',
  `last_update` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_details`
--

CREATE TABLE `invoice_details` (
  `id` int(11) NOT NULL,
  `invoice_no` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `price` float(15,2) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `id` int(11) NOT NULL,
  `member_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT '',
  `address` text NOT NULL,
  `con_num` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `total_due` decimal(10,2) DEFAULT 0.00,
  `reg_date` date NOT NULL,
  `update_by` int(11) NOT NULL,
  `total_buy` decimal(10,2) DEFAULT 0.00,
  `total_paid` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`id`, `member_id`, `name`, `company`, `address`, `con_num`, `email`, `total_due`, `reg_date`, `update_by`, `total_buy`, `total_paid`) VALUES
(1, 'C1721028073', 'dsadas', 'fgfdgdf', 'dsdasdasdas', '09252252', 'fernandezanjerald@gmail.com', 0.00, '2002-02-02', 1, 0.00, 0.00),
(2, 'C1721028076', 'dsadas', 'fdgfdgd', 'dsdasdasdas', '09252252', 'fernandezanjerald@gmail.com', 0.00, '2002-02-02', 1, 0.00, 0.00),
(3, '1', 'jhonmark', 'gdfgfd', 'adfsada', '09292437307', 'jhonmarkcueva14@gmail.com', 500.00, '0000-00-00', 0, 200.00, 300.00),
(4, 'C1721029507', 'dsada', '', 'sadsadasd', '09252252', 'fernandezanjerald@gmail.com', 0.00, '0002-02-02', 1, 0.00, 0.00),
(5, 'C1721029826', 'dsada', '', 'dsadasdadsasdadasd', '09252252', 'gelardfernandez@gmail.com', 0.00, '2020-02-02', 1, 0.00, 0.00),
(6, 'C1721031211', 'cxvcxv', '', '2dsfds', '09252252', 'gelardfernandez@gmail.com', 0.00, '5252-02-02', 1, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `paymethode`
--

CREATE TABLE `paymethode` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `added_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_id` varchar(50) DEFAULT NULL,
  `brand_name` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) DEFAULT NULL,
  `product_source` varchar(20) DEFAULT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `quantity` int(10) DEFAULT 0,
  `alert_quantity` int(3) DEFAULT NULL,
  `buy_price` float(15,2) DEFAULT NULL,
  `sell_price` float(15,2) DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `last_update_at` date NOT NULL,
  `added_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_payment`
--

CREATE TABLE `purchase_payment` (
  `id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_amount` float(15,2) NOT NULL DEFAULT 0.00,
  `payment_type` varchar(20) DEFAULT NULL,
  `pay_description` text NOT NULL,
  `added_by` int(11) DEFAULT NULL,
  `last_update` date DEFAULT NULL,
  `added_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_products`
--

CREATE TABLE `purchase_products` (
  `id` int(11) NOT NULL,
  `purchase_subtotal` decimal(10,2) DEFAULT NULL,
  `purchase_net_total` decimal(10,2) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `riders`
--

CREATE TABLE `riders` (
  `rider_id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `vehicle_type` varchar(50) DEFAULT NULL,
  `license_number` varchar(50) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `date_joined` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_rides` int(11) DEFAULT 0,
  `rating` decimal(2,1) DEFAULT 0.0,
  `payment_method` varchar(50) DEFAULT NULL,
  `alert_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riders`
--

INSERT INTO `riders` (`rider_id`, `username`, `password`, `name`, `lastname`, `gender`, `address`, `contact_number`, `email`, `vehicle_type`, `license_number`, `status`, `date_joined`, `total_rides`, `rating`, `payment_method`, `alert_quantity`) VALUES
(0, 'wawa', '123456', 'wawa', 'wawa', 'Male', 'wawwa', '21212121', 'wa@sds', 'dsd', 'dsds', 'Active', '2024-07-21 18:37:02', 0, 0.0, 'utang', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `invoice_no` varchar(100) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `product_quantity` int(11) DEFAULT NULL,
  `product_price` float(15,2) DEFAULT NULL,
  `product_total` float(15,2) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `sale_date` date DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `last_update` date DEFAULT NULL,
  `added_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliar`
--

CREATE TABLE `suppliar` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `supplier_address` text NOT NULL,
  `supplier_contact` varchar(255) DEFAULT NULL,
  `supplier_email` varchar(255) DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `added_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `verification_code` varchar(50) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `user_role` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `first_name`, `last_name`, `picture`, `address`, `verification_code`, `middle_name`, `user_role`, `created_at`) VALUES
(19, 'john', 'doe@gmail.com', '$2y$10$HpxtapfeVDl3.soGar2PbuN0IUa.YLGIxiv67Obqg/mrPhojOXWVy', 'jonh', 'owmntnu', NULL, 'theetn mad cebu', '123456', 'maru', NULL, '2024-07-20 22:12:00');

-- --------------------------------------------------------

--
-- Table structure for table `your_table_name`
--

CREATE TABLE `your_table_name` (
  `id` int(11) NOT NULL,
  `column1` varchar(255) NOT NULL,
  `column2` varchar(255) NOT NULL,
  `alert_quantity` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `catagory`
--
ALTER TABLE `catagory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_category`
--
ALTER TABLE `expense_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `factory_products`
--
ALTER TABLE `factory_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_details`
--
ALTER TABLE `invoice_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_no` (`invoice_no`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paymethode`
--
ALTER TABLE `paymethode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_payment`
--
ALTER TABLE `purchase_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_products`
--
ALTER TABLE `purchase_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliar`
--
ALTER TABLE `suppliar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `your_table_name`
--
ALTER TABLE `your_table_name`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `catagory`
--
ALTER TABLE `catagory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_category`
--
ALTER TABLE `expense_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `factory_products`
--
ALTER TABLE `factory_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_details`
--
ALTER TABLE `invoice_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `paymethode`
--
ALTER TABLE `paymethode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_payment`
--
ALTER TABLE `purchase_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_products`
--
ALTER TABLE `purchase_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliar`
--
ALTER TABLE `suppliar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `your_table_name`
--
ALTER TABLE `your_table_name`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
