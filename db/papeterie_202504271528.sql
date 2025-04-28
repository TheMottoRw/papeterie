-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 27, 2025 at 03:27 PM
-- Server version: 8.0.41-0ubuntu0.22.04.1
-- PHP Version: 8.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `papeterie`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `delete_status` tinyint NOT NULL DEFAULT '0',
  `delete_reason` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `delete_date` datetime DEFAULT NULL,
  `doneby` int DEFAULT NULL,
  `regdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `phone`, `delete_status`, `delete_reason`, `delete_date`, `doneby`, `regdate`) VALUES
(1, 'Roger', '0786459523', 0, NULL, NULL, NULL, '2025-04-26 14:53:43'),
(2, 'Gerard', '0789693253', 0, NULL, NULL, NULL, '2025-04-26 16:14:12'),
(3, 'Karim', '078986532', 0, NULL, NULL, NULL, '2025-04-26 16:14:44'),
(4, 'Karamuzi', '078942153', 0, NULL, NULL, NULL, '2025-04-26 16:15:40');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int NOT NULL,
  `expense_name` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `comment` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `expense_name`, `reason`, `price`, `comment`, `created_at`) VALUES
(1, 'Bike', 'Transport facilitation to deliver books', '1500.00', 'was needed urgently', '2025-04-09 12:07:23'),
(2, 'Electricity', 'Monthly', '10000.00', 'this is well recorded', '2025-04-11 15:29:14');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `invoice_id` int NOT NULL,
  `invoice_clientid` int NOT NULL,
  `invoice_identifier` varchar(50) NOT NULL,
  `paid` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `remain` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `total_amount` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `total_profit` int NOT NULL DEFAULT '0',
  `delete_status` tinyint(1) NOT NULL DEFAULT '0',
  `delete_reason` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `doneby` int DEFAULT NULL,
  `delete_date` datetime DEFAULT NULL,
  `regdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`invoice_id`, `invoice_clientid`, `invoice_identifier`, `paid`, `remain`, `total_amount`, `total_profit`, `delete_status`, `delete_reason`, `doneby`, `delete_date`, `regdate`) VALUES
(1, 1, 'INV250426-1', '0', '0', '0', 0, 0, NULL, NULL, NULL, '2025-04-26 14:54:18'),
(2, 1, 'INV250426-2', '0', '0', '0', 0, 0, NULL, NULL, NULL, '2025-04-26 14:54:22'),
(3, 2, 'INV250426-3', '0', '0', '0', 0, 0, NULL, NULL, NULL, '2025-04-26 16:14:12'),
(4, 3, 'INV250426-4', '0', '165', '165', 105, 1, NULL, NULL, NULL, '2025-04-26 16:14:44'),
(5, 4, 'INV250426-5', '0', '-100', '-100', 0, 1, NULL, NULL, NULL, '2025-04-26 16:15:40'),
(6, 2, 'INV250426-6', '425', '110', '535', 235, 0, NULL, NULL, NULL, '2025-04-26 16:16:01');

-- --------------------------------------------------------

--
-- Table structure for table `invoicesdetails`
--

CREATE TABLE `invoicesdetails` (
  `invoicedt_id` int NOT NULL,
  `invoice_id` int NOT NULL,
  `item_id` int NOT NULL,
  `buying_price` int NOT NULL,
  `selling_price` int NOT NULL,
  `quantity` int NOT NULL,
  `total_price` int NOT NULL,
  `profit` int NOT NULL,
  `status` enum('sold','refunded','deleted') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'sold',
  `doneby` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoicesdetails`
--

INSERT INTO `invoicesdetails` (`invoicedt_id`, `invoice_id`, `item_id`, `buying_price`, `selling_price`, `quantity`, `total_price`, `profit`, `status`, `doneby`, `created_at`) VALUES
(1, 6, 3, 100, 110, 2, 220, 20, 'sold', 0, '2025-04-26 17:33:58'),
(2, 6, 3, 100, 110, 2, 220, 20, 'deleted', 0, '2025-04-26 17:34:16'),
(3, 6, 1, 20, 55, 3, 165, 105, 'sold', 0, '2025-04-26 17:35:33'),
(4, 6, 1, 20, 50, 1, 50, 30, 'sold', 0, '2025-04-26 18:35:23'),
(5, 6, 1, 20, 50, 1, 50, 30, 'sold', 0, '2025-04-26 18:36:25'),
(6, 6, 1, 20, 50, 1, 50, 30, 'sold', 0, '2025-04-26 18:37:10'),
(7, 5, 1, 20, 50, 2, 100, 60, 'deleted', 0, '2025-04-27 12:34:39'),
(8, 4, 1, 20, 50, 2, 100, 60, 'deleted', 0, '2025-04-27 12:37:35'),
(9, 4, 1, 20, 65, 1, 65, 45, 'deleted', 0, '2025-04-27 12:37:59');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `buying_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `buying_price`, `selling_price`, `quantity`, `created_at`) VALUES
(1, 'Pen Bic', '20.00', '50.00', 97, '2025-04-09 11:52:56'),
(3, 'Book', '100.00', '110.00', 74, '2025-04-11 15:20:29'),
(4, 'Flash disk', '3000.00', '4000.00', 10, '2025-04-11 15:38:10');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int NOT NULL,
  `invoice_id` int NOT NULL,
  `paid` int NOT NULL,
  `delete_status` tinyint NOT NULL DEFAULT '0',
  `delete_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `doneby` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `invoice_id`, `paid`, `delete_status`, `delete_date`, `doneby`, `created_at`) VALUES
(1, 6, 425, 0, '2025-04-27 12:43:35', 0, '2025-04-26 18:38:59'),
(2, 6, 45, 0, '2025-04-27 13:00:39', 0, '2025-04-27 13:00:39');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int NOT NULL,
  `item_id` int NOT NULL,
  `buying_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `item_id`, `buying_price`, `selling_price`, `quantity`, `total_price`, `profit`, `created_at`) VALUES
(1, 1, '20.00', '50.00', 3, '150.00', '90.00', '2025-04-09 11:17:51'),
(2, 3, '100.00', '120.00', 20, '2400.00', '400.00', '2025-04-11 13:22:16');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_name`, `price`, `created_at`) VALUES
(1, 'Printing service', '150.00', '2025-04-09 12:03:06'),
(3, 'Photography and video graphy', '300.00', '2025-04-09 14:08:07'),
(4, 'Identity Card Copy', '550.00', '2025-04-09 14:08:21'),
(5, 'Print', '1000.00', '2025-04-11 15:25:17'),
(6, 'PRINT PHOTO', '9000.00', '2025-04-11 15:28:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Asua', 'asua@yopmail.com', '$2y$10$tWrYtuS3vBtlL114EhY5gOS.qJG8ODXvlQZSNlVhlKErr4uBsnVYq', '2025-04-09 09:23:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `invoices` (`invoice_id`),
  ADD KEY `clients` (`invoice_clientid`);

--
-- Indexes for table `invoicesdetails`
--
ALTER TABLE `invoicesdetails`
  ADD PRIMARY KEY (`invoicedt_id`),
  ADD KEY `invoices` (`invoice_id`),
  ADD KEY `items` (`item_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `invoicesdetails`
--
ALTER TABLE `invoicesdetails`
  MODIFY `invoicedt_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
