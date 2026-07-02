-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 01, 2026 at 05:57 PM
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
-- Database: `warmindo_connect`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `username`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin', '$2y$12$6c8IGfdRb994lmbXjDndOOtloLqdGpvgdG7wxoXyU6FQfUNzr0Nzu', '2026-06-16 09:05:30', '2026-06-16 09:05:30');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Makanan', '2026-06-16 08:16:09', '2026-06-16 08:16:09', NULL),
(2, 'Minuman', '2026-06-16 08:16:09', '2026-06-16 08:16:09', NULL),
(3, 'Cemilan', '2026-06-16 08:16:09', '2026-06-16 08:16:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(11) UNSIGNED NOT NULL,
  `category_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `category_id`, `name`, `description`, `price`, `image_path`, `is_available`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 'Nutrisasi', 'minuman segar cocok untuk panas2 an', 5000.00, '1781618899_1f4cd922c781f1474aff.jpg', 1, '2026-06-16 14:08:19', '2026-06-16 14:08:19', NULL),
(2, 1, 'Nasi Goreng', 'nasi goreng telur dengan ciri khas jawa dimasak dengan arang', 15000.00, '1781618948_f013e6dd9b3822f0a377.jpg', 1, '2026-06-16 14:09:08', '2026-06-16 14:09:08', NULL),
(3, 3, 'Omelet', 'kelezatan makanan luar negeri', 10000.00, '1781618984_65dee5bfd803c87435e8.jpg', 1, '2026-06-16 14:09:44', '2026-06-16 14:09:44', NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-06-16-081230', 'App\\Database\\Migrations\\Categories', 'default', 'App', 1781597769, 1),
(2, '2026-06-16-081231', 'App\\Database\\Migrations\\Menus', 'default', 'App', 1781597769, 1),
(3, '2026-06-16-081231', 'App\\Database\\Migrations\\Orders', 'default', 'App', 1781597789, 2),
(4, '2026-06-16-081232', 'App\\Database\\Migrations\\OrderItems', 'default', 'App', 1781597789, 2),
(5, '2026-06-16-090401', 'App\\Database\\Migrations\\CreateAdminsTable', 'default', 'App', 1781600730, 3),
(6, '2026-06-16-092019', 'App\\Database\\Migrations\\AddSessionIdToOrders', 'default', 'App', 1781601644, 4),
(7, '2026-06-30-000000', 'App\\Database\\Migrations\\AddDeletedAtToTables', 'default', 'App', 1782776690, 5);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) UNSIGNED NOT NULL,
  `session_id` varchar(50) DEFAULT NULL,
  `table_number` varchar(10) NOT NULL,
  `status` enum('pending','cooking','ready','completed') NOT NULL DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `session_id`, `table_number`, `status`, `total_amount`, `created_at`, `updated_at`) VALUES
(1, NULL, '1', 'pending', 20000.00, '2026-07-01 15:41:28', '2026-07-01 15:41:28'),
(2, NULL, '1', 'pending', 10000.00, '2026-07-01 15:44:33', '2026-07-01 15:44:33');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) UNSIGNED NOT NULL,
  `order_id` int(11) UNSIGNED NOT NULL,
  `transaction_id` int(11) UNSIGNED DEFAULT NULL,
  `menu_id` int(11) UNSIGNED NOT NULL,
  `qty` int(5) NOT NULL,
  `price_at_order` decimal(10,2) NOT NULL,
  `payment_status` enum('unpaid','paid') NOT NULL DEFAULT 'unpaid',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `transaction_id`, `menu_id`, `qty`, `price_at_order`, `payment_status`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 2, 1, 15000.00, 'paid', '2026-07-01 15:41:28', '2026-07-01 15:41:43'),
(2, 1, NULL, 1, 1, 5000.00, 'paid', '2026-07-01 15:41:28', '2026-07-01 15:41:43'),
(3, 2, 2, 3, 1, 10000.00, 'paid', '2026-07-01 15:44:33', '2026-07-01 15:44:42');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) UNSIGNED NOT NULL,
  `order_id` int(11) UNSIGNED NOT NULL,
  `payment_method` enum('qris','cash') NOT NULL DEFAULT 'qris',
  `payment_mode` enum('single','split') NOT NULL DEFAULT 'single',
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('paid','unpaid') NOT NULL DEFAULT 'unpaid',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `order_id`, `payment_method`, `payment_mode`, `amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'cash', 'single', 20000.00, 'paid', '2026-07-01 15:41:28', '2026-07-01 15:41:43'),
(2, 2, 'cash', 'single', 10000.00, 'paid', '2026-07-01 15:44:33', '2026-07-01 15:44:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menus_category_id_foreign` (`category_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_menu_id_foreign` (`menu_id`),
  ADD KEY `order_items` (`transaction_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_order_id_foreign` (`order_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `order_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
