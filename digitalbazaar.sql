-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2026 at 03:53 PM
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
-- Database: `digitalbazaar`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(2, 'admin', '123');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `original_price` decimal(10,2) NOT NULL,
  `discount_percentage` int(11) DEFAULT 0,
  `discounted_price` decimal(10,2) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `original_price`, `discount_percentage`, `discounted_price`, `quantity`, `created_at`) VALUES
(1, 3, 63, 1200.00, 20, 960.00, 3, '2026-01-31 08:36:30'),
(2, 3, 60, 5400.00, 50, 2700.00, 1, '2026-02-06 12:42:19'),
(3, 1, 60, 5400.00, 50, 5400.00, 3, '2026-02-09 12:39:43'),
(4, 1, 62, 1500.00, 0, 1500.00, 4, '2026-02-15 07:40:19'),
(5, 1, 61, 9999.00, 0, 9999.00, 1, '2026-02-18 04:58:45'),
(6, 1, 63, 1200.00, 0, 1200.00, 3, '2026-04-02 03:59:55'),
(7, 1, 49, 32999.00, 20, 26399.20, 1, '2026-04-03 11:03:07');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `status`, `created_at`) VALUES
(1, 'Mobiles', 'All Smartphones', 1, '2025-12-27 06:07:25'),
(2, 'Laptops', 'All Laptops', 1, '2025-12-27 06:07:25'),
(3, 'Accessories', 'Mobile & Laptop Accessories', 1, '2025-12-27 06:07:25'),
(4, 'TV', NULL, 1, '2026-01-01 03:33:08'),
(5, 'AC', NULL, 1, '2026-01-01 03:33:29');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(2, 'anshul', 'ahdave1573@gmail.com', 'Hello', '2025-12-28 10:32:31'),
(3, 'anshul', 'ahdave1573@gmail.com', 'Hello', '2026-01-05 06:49:58'),
(4, 'anshul', 'ahdave1573@gmail.com', 'hello\r\n', '2026-02-16 08:34:29');

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `discount_percentage` int(11) DEFAULT 0,
  `original_price` decimal(10,2) DEFAULT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `product_id`, `title`, `description`, `discount_percentage`, `original_price`, `discount_price`, `image`, `start_date`, `end_date`, `active`, `created_at`) VALUES
(22, 49, 'Xiaomi 138 cm (55 inch) FX Pro QLED Ultra HD 4K Smart Fire TV L55MB-FPIN', NULL, 20, 32999.00, 26399.20, '1775193410_0536c61e50e5.webp', '2026-04-03 10:46:00', '2026-04-11 10:46:00', 1, '2026-04-03 05:16:50');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `pincode` varchar(20) DEFAULT NULL,
  `category` varchar(100) NOT NULL,
  `total_amount` int(11) NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `payment_status` varchar(20) DEFAULT 'Pending',
  `order_status` varchar(20) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_id`, `user_id`, `address`, `city`, `pincode`, `category`, `total_amount`, `payment_method`, `payment_status`, `order_status`, `created_at`) VALUES
(1, 'ORD1766994668', 1, NULL, NULL, NULL, 'Laptops', 23361, 'COD', 'Pending', 'Pending', '2025-12-29 07:51:08'),
(2, 'ORD1766994838', 1, NULL, NULL, NULL, 'Laptops', 23361, 'COD', 'Pending', 'Completed', '2025-12-29 07:53:58'),
(3, 'ORD1767234460', 3, NULL, NULL, NULL, '', 1000, 'COD', 'Pending', 'Processing', '2026-01-01 02:27:40'),
(4, 'ORD1767237801', 3, NULL, NULL, NULL, 'Mobiles', 1759989, 'COD', 'Pending', 'Pending', '2026-01-01 03:23:21'),
(5, 'ORD1767237815', 3, NULL, NULL, NULL, 'Earphones', 24999, 'COD', 'Pending', 'Pending', '2026-01-01 03:23:35'),
(6, 'ORD1767237873', 3, NULL, NULL, NULL, 'Earphones', 24999, 'COD', 'Pending', 'Pending', '2026-01-01 03:24:33'),
(7, 'ORD1767237915', 3, NULL, NULL, NULL, 'Mobiles', 154000, 'COD', 'Pending', 'Completed', '2026-01-01 03:25:15'),
(8, 'ORD1767339552', 3, NULL, NULL, NULL, 'TV', 42998, 'COD', 'Pending', 'Pending', '2026-01-02 07:39:12'),
(9, 'ORD1767510205', 3, NULL, NULL, NULL, 'Accessories', 1500, 'COD', 'Pending', 'Pending', '2026-01-04 07:03:25'),
(10, 'ORD1767595079', 3, NULL, NULL, NULL, 'Accessories', 1500, 'COD', 'Pending', 'Processing', '2026-01-05 06:37:59'),
(11, 'ORD1767595993', 1, NULL, NULL, NULL, 'Accessories', 1500, 'COD', 'Pending', 'Pending', '2026-01-05 06:53:13'),
(12, 'ORD1767838497', 1, NULL, NULL, NULL, 'Accessories', 5400, 'COD', 'Pending', 'Completed', '2026-01-08 02:14:57'),
(13, 'ORD1767839979', 1, NULL, NULL, NULL, 'Accessories', 29997, 'COD', 'Pending', 'Completed', '2026-01-08 02:39:39'),
(14, 'ORD1767933857', 1, NULL, NULL, NULL, 'Accessories', 1500, 'COD', 'Pending', 'Completed', '2026-01-09 04:44:17'),
(15, 'ORD1768202441', 1, NULL, NULL, NULL, 'Accessories', 1500, 'COD', 'Pending', 'Pending', '2026-01-12 07:20:41'),
(16, 'ORD1768442758', 1, NULL, NULL, NULL, 'Accessories', 1200, 'COD', 'Pending', 'Pending', '2026-01-15 02:05:58'),
(17, 'ORD1768442983', 1, NULL, NULL, NULL, 'Accessories', 1500, 'COD', 'Pending', 'Pending', '2026-01-15 02:09:43'),
(18, 'ORD1768443150', 1, NULL, NULL, NULL, 'Accessories', 1500, 'COD', 'Pending', 'Pending', '2026-01-15 02:12:30'),
(19, 'ORD1768444413', 1, NULL, NULL, NULL, 'Accessories', 1500, 'COD', 'Pending', 'Pending', '2026-01-15 02:33:33'),
(20, 'ORD1768447405959', 3, NULL, NULL, NULL, 'Accessories', 1500, 'COD', 'Pending', 'Pending', '2026-01-15 03:23:25'),
(21, 'ORD1768912499619', 3, NULL, NULL, NULL, 'Accessories', 1500, 'COD', 'Pending', 'Pending', '2026-01-20 12:34:59'),
(22, 'ORD1769048503394', 3, NULL, NULL, NULL, 'Accessories', 1500, 'COD', 'Pending', 'Processing', '2026-01-22 02:21:43'),
(23, 'ORD1769846655785', 3, NULL, NULL, NULL, 'Accessories', 9999, 'COD', 'Pending', 'Pending', '2026-01-31 08:04:15'),
(24, 'ORD1769848602472', 3, NULL, NULL, NULL, 'Accessories', 3420, 'COD', 'Pending', 'Pending', '2026-01-31 08:36:42'),
(25, 'ORD1769848817130', 3, NULL, NULL, NULL, 'Accessories', 960, 'COD', 'Pending', 'Pending', '2026-01-31 08:40:17'),
(26, 'ORD1770294967521', 3, NULL, NULL, NULL, 'TV', 21499, 'COD', 'Pending', 'Pending', '2026-02-05 12:36:07'),
(27, 'ORD1770294997733', 3, NULL, NULL, NULL, 'TV', 21499, 'COD', 'Pending', 'Pending', '2026-02-05 12:36:37'),
(28, 'ORD1770381749441', 3, NULL, NULL, NULL, 'Accessories', 2700, 'COD', 'Pending', 'Pending', '2026-02-06 12:42:29'),
(29, 'ORD1770639926188', 1, NULL, NULL, NULL, 'Accessories', 2700, 'COD', 'Pending', 'Completed', '2026-02-09 12:25:26'),
(30, 'ORD1771141248249', 1, 'demo ', 'Rajkot', '360007', 'Accessories', 1500, 'UPI', 'Pending', 'Pending', '2026-02-15 07:40:48'),
(31, 'ORD1771151801834', 1, '150 Ft Ring Road\r\n', 'RaJkot', '360007', 'Accessories', 1500, 'COD', 'Pending', 'Pending', '2026-02-15 10:36:41'),
(32, 'ORD1771230939913', 1, 'hello', 'RaJkot', '360007', 'Accessories', 5400, 'UPI', 'Pending', 'Pending', '2026-02-16 08:35:39'),
(33, 'ORD1771390760972', 1, 'ygg', 'Rajkot', '360007', 'Accessories', 19998, 'COD', 'Pending', 'Pending', '2026-02-18 04:59:20'),
(34, 'ORD1775102424725', 1, 'Rakot', 'RaJkot', '360007', 'Accessories', 2400, 'COD', 'Pending', 'Cancelled', '2026-04-02 04:00:24'),
(35, 'ORD1775102546543', 1, 'kjfewklfjewf', 'Rajkot', '360007', 'Accessories', 1200, 'UPI', 'Pending', 'Pending', '2026-04-02 04:02:26'),
(36, 'ORD1775104442107', 1, 'ugh', 'Rajkot', '360007', 'Accessories', 1200, 'COD', 'Pending', 'Pending', '2026-04-02 04:34:02'),
(37, 'ORD1775104757356', 1, 'kljkjkl', 'Rajkot', '360007', 'Accessories', 1500, 'COD', 'Pending', 'Pending', '2026-04-02 04:39:17'),
(38, 'ORD1775105086241', 1, 'f;oekfo;aekdfsa', 'Rajkot', '360007', 'Accessories', 1500, 'DEBIT', 'Pending', 'Pending', '2026-04-02 04:44:46'),
(39, 'ORD1775214108230', 1, 'Rajkot\r\n', 'Rajkot', '360007', 'Accessories', 5400, 'UPI', 'Pending', 'Completed', '2026-04-03 11:01:48'),
(40, 'ORD1775214203102', 1, 'Home', 'Rajkot', '360007', 'TV', 26399, 'COD', 'Pending', 'Processing', '2026-04-03 11:03:23');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `discount_percentage` int(11) DEFAULT NULL,
  `category` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `original_price`, `discount_percentage`, `category`, `price`, `quantity`, `created_at`) VALUES
(1, 'ORD1766994668', 9, 'Pav Bhaji', NULL, NULL, 'Laptops', 23361, 1, '2025-12-29 07:51:08'),
(2, 'ORD1766994838', 9, 'Pav Bhaji', NULL, NULL, 'Laptops', 23361, 1, '2025-12-29 07:53:58'),
(3, 'ORD1767234460', 8, 'ice_cream', NULL, NULL, '', 1000, 1, '2026-01-01 02:27:40'),
(4, 'ORD1767237801', 26, 'iPhone 16 Pro Max', NULL, NULL, 'Mobiles', 159999, 11, '2026-01-01 03:23:21'),
(5, 'ORD1767237815', 32, 'Sony WF-1000XM5', NULL, NULL, 'Earphones', 24999, 1, '2026-01-01 03:23:35'),
(6, 'ORD1767237873', 32, 'Sony WF-1000XM5', NULL, NULL, 'Earphones', 24999, 1, '2026-01-01 03:24:33'),
(7, 'ORD1767237915', 17, 'iPhone 17 Pro 512 GB', NULL, NULL, 'Mobiles', 154000, 1, '2026-01-01 03:25:15'),
(8, 'ORD1767339552', 52, 'Xiaomi 108 cm (43 inch) FX Ultra HD 4K Smart LED Fire TV L43MB-FIN', NULL, NULL, 'TV', 21499, 2, '2026-01-02 07:39:12'),
(9, 'ORD1767510205', 62, 'havit H2002d Gaming Headsets ', NULL, NULL, 'Accessories', 1500, 1, '2026-01-04 07:03:25'),
(10, 'ORD1767595079', 62, 'havit H2002d Gaming Headsets ', NULL, NULL, 'Accessories', 1500, 1, '2026-01-05 06:37:59'),
(11, 'ORD1767595993', 62, 'havit H2002d Gaming Headsets ', NULL, NULL, 'Accessories', 1500, 1, '2026-01-05 06:53:13'),
(12, 'ORD1767838497', 60, 'JBL Tune 770NC Wireless Over Ear ANC Headphones with Mic', NULL, NULL, 'Accessories', 5400, 1, '2026-01-08 02:14:57'),
(13, 'ORD1767839979', 61, 'Marshall Major IV Wireless Bluetooth On Ear Headphone with Mic (Black)', NULL, NULL, 'Accessories', 9999, 3, '2026-01-08 02:39:39'),
(14, 'ORD1767933857', 62, 'havit H2002d Gaming Headsets ', NULL, NULL, 'Accessories', 1500, 1, '2026-01-09 04:44:17'),
(15, 'ORD1768202441', 62, 'havit H2002d Gaming Headsets ', NULL, NULL, 'Accessories', 1500, 1, '2026-01-12 07:20:41'),
(16, 'ORD1768442758', 63, 'PTron Dynamo Power 20000mAh 22.5W Super Fast Charging', NULL, NULL, 'Accessories', 1200, 1, '2026-01-15 02:05:58'),
(17, 'ORD1768442983', 62, 'havit H2002d Gaming Headsets ', NULL, NULL, 'Accessories', 1500, 1, '2026-01-15 02:09:43'),
(18, 'ORD1768443150', 62, 'havit H2002d Gaming Headsets ', NULL, NULL, 'Accessories', 1500, 1, '2026-01-15 02:12:30'),
(19, 'ORD1768444413', 62, 'havit H2002d Gaming Headsets ', NULL, NULL, 'Accessories', 1500, 1, '2026-01-15 02:33:33'),
(20, 'ORD1768444781', 62, 'havit H2002d Gaming Headsets ', NULL, NULL, 'Accessories', 1500, 1, '2026-01-15 02:39:41'),
(21, 'ORD1768444792', 62, 'havit H2002d Gaming Headsets ', NULL, NULL, 'Accessories', 1500, 1, '2026-01-15 02:39:52'),
(22, 'ORD1768447405959', 62, 'havit H2002d Gaming Headsets ', NULL, NULL, 'Accessories', 1500, 1, '2026-01-15 03:23:25'),
(23, 'ORD1768912499619', 62, 'havit H2002d Gaming Headsets ', NULL, NULL, 'Accessories', 1500, 1, '2026-01-20 12:34:59'),
(24, 'ORD1769048503394', 62, 'havit H2002d Gaming Headsets ', NULL, NULL, 'Accessories', 1500, 1, '2026-01-22 02:21:43'),
(25, 'ORD1769846655785', 61, 'Marshall Major IV Wireless Bluetooth On Ear Headphone with Mic (Black)', 9999.00, 0, 'Accessories', 9999, 1, '2026-01-31 08:04:15'),
(26, 'ORD1769848602472', 62, 'havit H2002d Gaming Headsets ', 1500.00, 0, 'Accessories', 1500, 1, '2026-01-31 08:36:42'),
(27, 'ORD1769848602472', 63, 'PTron Dynamo Power 20000mAh 22.5W Super Fast Charging', 1200.00, 20, 'Accessories', 960, 2, '2026-01-31 08:36:42'),
(28, 'ORD1769848817130', 63, 'PTron Dynamo Power 20000mAh 22.5W Super Fast Charging', 1200.00, 20, 'Accessories', 960, 1, '2026-01-31 08:40:17'),
(29, 'ORD1770294967521', 54, 'Xiaomi 108 cm (43 inch) FX Ultra HD 4K Smart LED Fire TV L43MB-FIN', 21499.00, 0, 'TV', 21499, 1, '2026-02-05 12:36:07'),
(30, 'ORD1770294997733', 54, 'Xiaomi 108 cm (43 inch) FX Ultra HD 4K Smart LED Fire TV L43MB-FIN', 21499.00, 0, 'TV', 21499, 1, '2026-02-05 12:36:37'),
(31, 'ORD1770381749441', 60, 'JBL Tune 770NC Wireless Over Ear ANC Headphones with Mic', 5400.00, 50, 'Accessories', 2700, 1, '2026-02-06 12:42:29'),
(32, 'ORD1770639926188', 60, 'JBL Tune 770NC Wireless Over Ear ANC Headphones with Mic', 5400.00, 50, 'Accessories', 2700, 1, '2026-02-09 12:25:26'),
(33, 'ORD1771141248249', 62, 'havit H2002d Gaming Headsets ', 1500.00, 0, 'Accessories', 1500, 1, '2026-02-15 07:40:48'),
(34, 'ORD1771151801834', 62, 'havit H2002d Gaming Headsets ', 1500.00, 0, 'Accessories', 1500, 1, '2026-02-15 10:36:41'),
(35, 'ORD1771230939913', 60, 'JBL Tune 770NC Wireless Over Ear ANC Headphones with Mic', 5400.00, 0, 'Accessories', 5400, 1, '2026-02-16 08:35:39'),
(36, 'ORD1771390760972', 61, 'Marshall Major IV Wireless Bluetooth On Ear Headphone with Mic (Black)', 9999.00, 0, 'Accessories', 9999, 2, '2026-02-18 04:59:20'),
(37, 'ORD1775102424725', 63, 'PTron Dynamo Power 20000mAh 22.5W Super Fast Charging', 1200.00, 0, 'Accessories', 1200, 2, '2026-04-02 04:00:24'),
(38, 'ORD1775102546543', 63, 'PTron Dynamo Power 20000mAh 22.5W Super Fast Charging', 1200.00, 0, 'Accessories', 1200, 1, '2026-04-02 04:02:26'),
(39, 'ORD1775104442107', 63, 'PTron Dynamo Power 20000mAh 22.5W Super Fast Charging', 1200.00, 0, 'Accessories', 1200, 1, '2026-04-02 04:34:02'),
(40, 'ORD1775104757356', 62, 'havit H2002d Gaming Headsets ', 1500.00, 0, 'Accessories', 1500, 1, '2026-04-02 04:39:17'),
(41, 'ORD1775105086241', 62, 'havit H2002d Gaming Headsets ', 1500.00, 0, 'Accessories', 1500, 1, '2026-04-02 04:44:46'),
(42, 'ORD1775214108230', 60, 'JBL Tune 770NC Wireless Over Ear ANC Headphones with Mic', 5400.00, 0, 'Accessories', 5400, 1, '2026-04-03 11:01:48'),
(43, 'ORD1775214203102', 49, 'Xiaomi 138 cm (55 inch) FX Pro QLED Ultra HD 4K Smart Fire TV L55MB-FPIN', 32999.00, 20, 'TV', 26399, 1, '2026-04-03 11:03:23');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `selling_price` decimal(10,2) DEFAULT NULL,
  `image` varchar(150) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `price`, `description`, `selling_price`, `image`, `category`) VALUES
(11, 1, 'Samsung Galaxy S25', NULL, '5G (12GB RAM + 256GB Storage) Navy\r\n', 68250.00, '1767235034_m2.jpg', 'Mobiles'),
(12, 1, 'Samsung Galaxy A55 5G ', NULL, 'Awesome Navy, 8GB RAM, 128GB Storage | AI | Metal Frame | 50 MP Main Camera (OIS) | Super HDR Video| Nightography | IP67 | Corning Gorilla Glass Victus+ | sAMOLED Display\r\n', 23990.00, '1767235174_m3.jpg', 'Mobiles'),
(13, 1, 'Samsung Galaxy M06 5G', NULL, ' Sage Green, 4GB RAM, 128 GB Storage | MediaTek Dimensity 6300 | AnTuTu Score 422K+ | 12 5G Bands| 25W Fast Charging | 4 Gen of OS Upgrades | Without Charger\r\n', 8999.00, '1767235271_m4.jpg', 'Mobiles'),
(14, 1, 'Samsung Galaxy Z Fold7 5G ', NULL, 'Smartphone with Galaxy AI (JetBlack, 12GB RAM, 256GB Storage), Ultra Sleek Design with 200MP Camera, Powerful Snapdragon 8 Elite, Google Gemini\r\n', 174999.00, '1767235361_m5.jpg', 'Mobiles'),
(15, 1, 'Apple iPhone 15 (128 GB) - Black', NULL, 'Apple iPhone 15 (128 GB) - Black\r\n', 55999.00, '1767235405_i1.jpg', 'Mobiles'),
(16, 1, 'iPhone 16e 128 GB', NULL, 'Built for Apple Intelligence, A18 Chip, Supersized Battery Life, 48MP Fusion. Camera, 15.40 cm (6.1″) Super Retina XDR Display; Black\r\n', 55999.00, '1767235667_i2.jpg', 'Mobiles'),
(17, 1, 'iPhone 17 Pro 512 GB', NULL, ' 15.93 cm (6.3″) Display with Promotion up to 120Hz, A19 Pro Chip, Breakthrough Battery Life, Pro Fusion Camera System with Center Stage Front Camera; Deep Blue\r\n', 154000.00, '1767235721_i3.jpg', 'Mobiles'),
(18, 1, 'Motorola Edge 60 Fusion 5G Smartphone', NULL, '6.67\" 120Hz pOLED Display, 50MP OIS Camera, Android 14, Dimensity 7030 Processor, 68W Fast Charging (Pantone Amazonite, 8GB + 256GB Storage)\r\n', 21500.00, '1767235780_mo1.jpg', 'Mobiles'),
(19, 1, 'Motorola Edge 50 Fusion 5G ', NULL, 'Marshmallow Blue, 12GB RAM, 256GB Storage', 20500.00, '1767235822_mo2.jpg', 'Mobiles'),
(20, 2, 'HP Smartchoice Victus, AMD Ryzen 5 8645HS', NULL, '6GB RTX 3050, 31 Tops, 16GB DDR5(Upgradeable) 512GB SSD, 144Hz, 300nits, FHD, 15.6\"/39.6cm, Win11, M365* Office24, Blue, 2.3kg, fb3009AX, AI Gaming Laptop\r\n', 60990.00, '1767235896_p1.jpg', 'Laptops'),
(21, 2, 'HP 15, AMD Ryzen 5 7430U', NULL, '16GB DDR4, 512GB SSD FHD, Anti-Glare, Micro-Edge,15.6\'\'/39.6cm, Win11, M365 Basic(1yr)* Office24, Silver,1.59kg, fc0389AU, AMD Radeon, FHD Camera w/Shutter, Backlit Laptop', 31990.00, '1767235952_p2.jpg', 'Mobiles'),
(22, 2, 'HP 15, Intel Core Ultra 5 125H ', NULL, '16GB DDR5, 1TB SSD FHD, IPS, 15.6\'\'/39.6cm, Win11, M365 Basic(1yr)*Office24, Silver, 1.65kg, fd1354TU, Intel Arc Graphics, FHD Camera w/Shutter, AI Powered Laptop\r\n', 61990.00, '1767235995_p3.jpg', 'Laptops'),
(23, 2, 'HP 15, 13th Gen Intel Core i5-1334U', NULL, '16GB DDR4, 1TB SSD FHD, Anti-Glare, Micro-Edge, 15.6\'\'/39.6cm, Win11, M365(1yr)* Office24, Silver, 1.59kg, FD0552TU, Iris Xe, FHD Camera w/Shutter, Backlit Laptop\r\n', 56990.00, '1767236275_p4.jpg', 'Laptops'),
(24, 1, 'Samsung Galaxy S25 Ultra 5G', NULL, 'AI Smartphone (Titanium WhiteSilver, 12GB RAM...)', 129000.00, '1767237705_m1.jpg', 'Mobiles'),
(26, 1, 'iPhone 16 Pro Max', NULL, 'Apple Flagship A19 Chip 256GB Storage Smartphone', 159999.00, '1767237649_i4.jpg', 'Mobiles'),
(27, 1, 'OnePlus 13 Pro', NULL, 'Snapdragon Gen 4 Flagship Performance Smartphone', 89999.00, '1767237560_o1.jpg', 'Mobiles'),
(28, 2, 'HP Pavilion Gaming Laptop', NULL, 'Ryzen 7 | 16GB RAM | 512GB SSD | RTX Graphics', 89999.00, '1767237450_h11.jpg', 'Laptops'),
(29, 2, 'Dell Inspiron 15', NULL, 'Intel i7 | 16GB RAM | 1TB SSD | Backlit Keyboard', 76999.00, '1767237363_d1.jpg', 'Laptops'),
(31, 3, 'Boat Airdopes 441', NULL, 'Bluetooth Earbuds with Deep Bass', 2999.00, '1767237261_e3.jpg', 'Earphones'),
(32, 3, 'Sony WF-1000XM5', NULL, 'Premium Noise Cancelling Earbuds', 24999.00, '1767237225_e2.jpg', 'Earphones'),
(33, 3, 'AirPods Pro 2', NULL, 'Apple ANC Wireless Earbuds', 26999.00, '1767237132_e1.jpg', 'Earphones'),
(44, 4, 'Samsung 80 cm (32 inches) HD Smart LED TV UA32H4550FUXXL', NULL, '', 12990.00, '1767339125_t1.jpg', 'TV'),
(45, 4, 'Xiaomi 138 cm (55 inch) FX Pro QLED Ultra HD 4K Smart Fire TV L55MB-FPIN', NULL, '', 34990.00, '1767339175_t2.jpg', 'TV'),
(46, 4, 'Samsung 108 cm (43 inches) Crystal 4K Vista Pro Ultra HD Smart LED TV UA43UE86AFULXL', NULL, '', 36990.00, '1767339207_t3.jpg', 'TV'),
(47, 4, 'LG 108 cm (43 inches) UA82 Series 4K Ultra HD Smart webOS LED TV 43UA82006LA', NULL, '', 28990.00, '1767339246_t4.jpg', 'TV'),
(48, 4, 'LG 80 cms (32 inches) LR570 Series Smart webOS LED TV 32LR570B6LA', NULL, '', 13990.00, '1767339275_t5.jpg', 'TV'),
(49, 4, 'Xiaomi 138 cm (55 inch) FX Pro QLED Ultra HD 4K Smart Fire TV L55MB-FPIN', NULL, '', 32999.00, '1767339306_t6.webp', 'TV'),
(50, 4, 'Xiaomi 80 cm (32 inch) A HD Ready Smart Google LED TV L32MB-AIN', NULL, '', 12999.00, '1767339333_t7.webp', 'TV'),
(51, 4, 'Sony 108 cm (43 inches) BRAVIA 2M2 Series 4K Ultra HD Smart LED Google TV K-43S22BM2', NULL, '', 38490.00, '1767339359_t8.webp', 'TV'),
(53, 4, 'Sony 139 cm (55 inches) BRAVIA 2M2 Series 4K Ultra HD Smart LED Google TV K-55S25BM2', NULL, '', 57990.00, '1767339428_t10.webp', 'TV'),
(54, 4, 'Xiaomi 108 cm (43 inch) FX Ultra HD 4K Smart LED Fire TV L43MB-FIN', NULL, '', 21499.00, '1767352562_t9.jpg', 'TV'),
(55, 5, 'Haier 1.5 Ton 3 Star Triple Inverter Smart Split AC ', NULL, '5125 Watts, Copper, Wi-Fi, 4-Way Swing, 7 in 1 Convertible, Frost Self Clean, HD Filter, Cools at 60°C - HSU18K-PYFR3BN-INV, White\r\n', 34000.00, '1767352643_c1.jpg', 'AC'),
(56, 5, 'Cruise Limited Edition 1.5 Ton 3 Star Black Inverter Split AC', NULL, '5200W, Copper, Heavy Duty, 4-in-1 Convertible Cooling, 4-Way Swing, PM 2.5 Filter, Anti-Rust Tech, CWCVBL-VP3F183BL, Piano Black\r\n', 28000.00, '1767352686_c2.jpg', 'AC'),
(57, 5, 'Godrej 1.5 Ton 3 Star, 5 Years Comprehensive Warranty, AI powered', NULL, ' 5-In-1 Convertible Cooling, 4 Way Air Swing, Wood Finish, Inverter Split AC (Copper, AC 1.5T SIC 18VTC3 WYB TK, Teak Wood)\r\n', 32500.00, '1767352745_c3.webp', 'AC'),
(58, 5, 'Godrej 1.5 Ton 5 Star, 5 Years Comprehensive Warranty,AI Powered', NULL, ' 5 in1 Convertible Cooling, Self Clean, Inverter Split AC (Copper, 2025 Model, Heavy Duty Cooling at 52°C, AC 1.5T EI 18I5T WZR,White)\r\np-34990\r\n\r\n', 34990.00, '1767352795_c4.jpg', 'AC'),
(59, 5, 'Voltas 183V Vectra CAW 1.5 ton 3 star inverter Split AC', NULL, '|4-IN-1 Adjustable mode|Energy Efficient|High ambient Cooling-cools even at 52°C|Anti dust filter with Anti-microbial coating|Copper Coil| White\r\n', 34990.00, '1767352835_c5.jpg', 'AC'),
(60, 3, 'JBL Tune 770NC Wireless Over Ear ANC Headphones with Mic', NULL, 'Upto 70 Hrs Battery, Speed Charge : 5 min Charge Gives up to 3H of Playback, Customized EQ, Google Fast Pair, Dual Pairing, BT 5.3 (Black)\r\n', 5400.00, '1767509952_h2.jpg', 'Accessories'),
(61, 3, 'Marshall Major IV Wireless Bluetooth On Ear Headphone with Mic (Black)', NULL, '', 9999.00, '1767509979_h3.jpg', 'Accessories'),
(62, 3, 'havit H2002d Gaming Headsets ', NULL, 'for PS4, PS5, PC, Xbox Series X|S, Xbox One Controller Gaming Headphones with Mic - 53mm Drivers - Durable Aluminum Frame\r\n', 1500.00, '1767510036_h4.jpg', 'Accessories'),
(63, 3, 'PTron Dynamo Power 20000mAh 22.5W Super Fast Charging', NULL, 'USB Type-C Input Power Bank with Quick Charge & 20W Power Delivery, Built-in Charging Cables, 4 Outputs, Type-C Input/Output Port (Black)\r\n', 1200.00, '1767510108_po1.jpg', 'Accessories');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_as` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role_as`, `created_at`) VALUES
(1, 'Anshul Dave', 'ahdave1573@gmail.com', '8849919418', '$2y$10$lzpuk74GL.5PYw1.xvRroeiJl6dWkG0ps5sY9wJ1VPTFDRT5Cnq9O', 0, '2025-12-26 11:37:49'),
(2, 'demo', 'demo@gmail.com', '8849919418', '$2y$10$Pd37x9Os59LWygbg2AlWK.rV5JBsQBNilGtcriQxtZIrl34hIdO/G', 0, '2025-12-26 11:45:29'),
(3, 'Jadav Jay', 'jay@gmail.com', '9081734816', '$2y$10$BuFIWSKPe0P5kL2JN.ps/.kUen0.rM68wS.UMtgm/PtxPKSe9QKU6', 0, '2025-12-27 05:53:00'),
(4, 'pizza', 'pizaa@gmail.com', '1591591592', '$2y$10$eV3TJJFGP.I0dr3Lves/Ru2QiGxvpvMA1iZWhWDIeTKbES8E.VDFi', 0, '2026-04-03 05:05:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
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
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
