-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2026 at 05:13 AM
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
-- Database: `shopping_cart`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `cat_id` varchar(10) NOT NULL,
  `cat_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_name`) VALUES
('C00001', 'Men'),
('C00002', 'Women'),
('C00003', 'Children'),
('C00004', 'Accessories');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `favorite_id` int(11) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `product_id` varchar(10) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `shipment_status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_price`, `status`, `payment_method`, `order_date`, `shipment_status`) VALUES
(83, 'U002', 114.00, 'Pending', NULL, '2026-04-16 00:09:41', 'Pending'),
(84, 'U002', 57.00, 'Paid', 'PayPal', '2026-04-16 00:33:25', 'Processing'),
(85, 'U002', 114.00, 'Paid', 'PayPal', '2026-04-16 00:42:49', 'Shipped'),
(86, 'U001', 242.00, 'Paid', 'PayPal', '2026-04-16 00:52:04', 'Delivered'),
(87, 'U001', 28.50, 'Pending', NULL, '2026-04-16 00:54:35', 'Pending'),
(88, 'U001', 149.50, 'Paid', 'PayPal', '2026-04-16 02:59:32', 'Pending'),
(89, 'U001', 523.36, 'Paid', 'PayPal', '2026-04-16 03:18:41', 'Pending'),
(90, 'U002', 57.00, 'Pending', NULL, '2026-04-16 15:15:37', 'Pending'),
(91, 'U003', 265.30, 'Pending', NULL, '2026-04-16 23:07:37', 'Pending'),
(92, 'U001', 32.00, 'Pending', NULL, '2026-04-16 23:10:58', 'Pending'),
(93, 'U003', 28.50, 'Paid', 'PayPal', '2026-04-16 23:48:18', 'Delivered'),
(94, 'U003', 85.50, 'Cancelled', 'PayPal', '2026-04-19 10:34:31', 'Cancelled'),
(95, 'U003', 96.00, 'Paid', 'PayPal', '2026-04-19 10:51:24', 'Processing'),
(96, 'U003', 128.00, 'Pending', NULL, '2026-04-19 10:52:16', 'Pending'),
(97, 'U003', 57.00, 'Paid', 'PayPal', '2026-04-19 10:56:12', 'Shipped'),
(98, 'U003', 85.50, 'Cancelled', NULL, '2026-04-19 10:56:36', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_id` int(11) NOT NULL,
  `variant_id` varchar(10) NOT NULL,
  `unit` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_id`, `variant_id`, `unit`, `unit_price`) VALUES
(83, 'P10003', 4, 28.50),
(84, 'P10003', 2, 28.50),
(85, 'P10003', 4, 28.50),
(86, 'P10003', 4, 28.50),
(86, 'P10006', 4, 32.00),
(87, 'P10003', 1, 28.50),
(88, 'P10003', 3, 28.50),
(88, 'P10004', 2, 32.00),
(89, 'P10004', 4, 32.00),
(89, 'P10007', 6, 45.90),
(89, 'P10010', 4, 29.99),
(90, 'P10001', 2, 28.50),
(91, 'P10001', 1, 28.50),
(91, 'P10064', 3, 25.00),
(91, 'P10069', 2, 35.00),
(91, 'P10070', 2, 45.90),
(92, 'P10004', 1, 32.00),
(93, 'P10003', 1, 28.50),
(94, 'P10001', 3, 28.50),
(95, 'P10005', 3, 32.00),
(96, 'P10006', 4, 32.00),
(97, 'P10003', 2, 28.50),
(98, 'P10001', 3, 28.50);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` varchar(10) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `cat_id` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `description`, `photo`, `cat_id`) VALUES
('P20001', 'Green T-shirt', 'A classic, breathable cotton t-shirt in Green.', '69dfc2f8ba679.jpg', 'C00001'),
('P20002', 'Black T-shirt', 'A classic, breathable cotton t-shirt in Black.', 'm_tshirt_blk.png', 'C00001'),
('P20003', 'Navy Blue T-shirt', 'A classic, breathable cotton t-shirt in Navy Blue.', 'm_tshirt_nvb.png', 'C00001'),
('P20004', 'Brown T-shirt', 'A classic, breathable cotton t-shirt in Brown.', 'm_tshirt_brown.png', 'C00001'),
('P20005', 'White/Green T-shirt', 'A classic, breathable cotton t-shirt in White/Green.', 'm_tshirt_wg.png', 'C00001'),
('P20006', 'White/Blue T-shirt', 'A classic, breathable cotton t-shirt in White/Blue.', 'm_tshirt_wb.png', 'C00001'),
('P20007', 'White/Red/Blue T-shirt', 'A classic, breathable cotton t-shirt in White/Red/Blue.', 'm_tshirt_wrb.png', 'C00001'),
('P20008', 'Jeans Jacket', 'Versatile outerwear featuring a durable finish in Jeans style.', 'm_jacket_jeans.png', 'C00001'),
('P20009', 'White/Green Jacket', 'Versatile outerwear featuring a durable finish in White/Green.', 'm_jacket_wg.png', 'C00001'),
('P20010', 'Red Jacket', 'Versatile outerwear featuring a durable finish in Red.', 'm_jacket_red.png', 'C00001'),
('P20011', 'Green/Plaid Jacket', 'Versatile outerwear featuring a durable finish in Green/Plaid.', 'm_jacket_gp.png', 'C00001'),
('P20012', 'Light Beige Jacket', 'Versatile outerwear featuring a durable finish in Light Beige.', 'm_jacket_lb.png', 'C00001'),
('P20013', 'Brown Jacket', 'Versatile outerwear featuring a durable finish in Brown.', 'm_jacket_brown.png', 'C00001'),
('P20014', 'Dark Grey Joggers', 'Premium fleece joggers in Dark Grey.', 'm_joggers_dg.png', 'C00001'),
('P20015', 'Khaki Joggers', 'Premium fleece joggers in Khaki.', 'm_joggers_khaki.png', 'C00001'),
('P20016', 'Sage Green Joggers', 'Premium fleece joggers in Sage Green.', 'm_joggers_sg.png', 'C00001'),
('P20017', 'Light Grey Joggers', 'Premium fleece joggers in Light Grey.', 'm_joggers_lg.png', 'C00001'),
('P20018', 'Navy Blue Joggers', 'Premium fleece joggers in Navy Blue.', 'm_joggers_nb.png', 'C00001'),
('P20019', 'Beige Joggers', 'Premium fleece joggers in Beige.', 'm_joggers_beige.png', 'C00001'),
('P20020', 'Olive Green Joggers', 'Premium fleece joggers in Olive Green.', 'm_joggers_og.png', 'C00001'),
('P20021', 'Sand Joggers', 'Premium fleece joggers in Sand.', 'm_joggers_sand.png', 'C00001'),
('P20022', 'White Tops', 'Elegant and stylish tops in White.', 'w_tops_white.png', 'C00002'),
('P20023', 'Black Tops', 'Elegant and stylish tops in Black.', 'w_tops_black.png', 'C00002'),
('P20024', 'Beige Tops', 'Elegant and stylish tops in Beige.', 'w_tops_beige.png', 'C00002'),
('P20025', 'Light Blue Tops', 'Elegant and stylish tops in Light Blue.', 'w_tops_lb.png', 'C00002'),
('P20026', 'Dusty Pink Tops', 'Elegant and stylish tops in Dusty Pink.', 'w_tops_dp.png', 'C00002'),
('P20027', 'Navy Blue Tops', 'Elegant and stylish tops in Navy Blue.', 'w_tops_nb.png', 'C00002'),
('P20028', 'Pink Tops', 'Elegant and stylish tops in Pink.', 'w_tops_pink.png', 'C00002'),
('P20029', 'Mustard Yellow Tops', 'Elegant and stylish tops in Mustard Yellow.', 'w_tops_my.png', 'C00002'),
('P20030', 'Blue Sleepwear', 'Ultra-soft pajamas in Blue.', 'w_sleepware_blue.png', 'C00002'),
('P20031', 'Pink Sleepwear', 'Ultra-soft pajamas in Pink.', 'w_sleepware_pink.png', 'C00002'),
('P20032', 'White/Pink Sleepwear', 'Ultra-soft pajamas in White/Pink.', 'w_sleepware_wp.png', 'C00002'),
('P20033', 'Peanuts Sleepwear', 'Ultra-soft pajamas featuring Peanuts design.', 'w_sleepware_peanuts.png', 'C00002'),
('P20034', 'Pink Ribbon Sleepwear', 'Ultra-soft pajamas with Pink Ribbon.', 'w_sleepware_prb.png', 'C00002'),
('P20035', 'Coffee Time Sleepwear', 'Ultra-soft pajamas with Coffee Time design.', 'w_sleepware_ct.png', 'C00002'),
('P20036', 'Red Sleepwear', 'Ultra-soft pajamas in Red.', 'w_sleepware_red.png', 'C00002'),
('P20037', 'Egg Plant Sleepwear', 'Ultra-soft pajamas in Egg Plant color.', 'w_sleepware_ep.png', 'C00002'),
('P20038', 'Red Floral Dress', 'Tailored dress with Red Floral patterns.', 'w_dresses_rf.png', 'C00002'),
('P20039', 'Blue Polka Dot Dress', 'Tailored dress with Blue Polka Dots.', 'w_dresses_bpd.png', 'C00002'),
('P20040', 'Pink Dress', 'Tailored dress in Pink.', 'w_dresses_pink.png', 'C00002'),
('P20041', 'Black Dress', 'Tailored dress in Black.', 'w_dresses_black.png', 'C00002'),
('P20042', 'Brown Dress', 'Tailored dress in Brown.', 'w_dresses_brown.png', 'C00002'),
('P20043', 'White Dress', 'Tailored dress in White.', 'w_dresses_white.png', 'C00002'),
('P20044', 'Black/Flower Dress', 'Tailored dress with Black/Flower pattern.', 'w_dresses_bf.png', 'C00002'),
('P20045', 'Blue Dress', 'Tailored dress in Blue.', 'w_dresses_blue.png', 'C00002'),
('P20046', 'Black Pants', 'High-quality trousers in Black.', 'w_pants_black.png', 'C00002'),
('P20047', 'Blue Pants', 'High-quality trousers in Blue.', 'w_pants_blue.png', 'C00002'),
('P20048', 'Yellow Pants', 'High-quality trousers in Yellow.', 'w_pants_yellow.png', 'C00002'),
('P20049', 'Pink Pants', 'High-quality trousers in Pink.', 'w_pants_pink.png', 'C00002'),
('P20050', 'Blue Jeans', 'High-quality denim in Blue.', 'w_pants_jeans.png', 'C00002'),
('P20051', 'Dark Blue Jeans', 'High-quality denim in Dark Blue.', 'w_pants_bjeans.png', 'C00002'),
('P20052', 'White Pants', 'High-quality trousers in White.', 'w_pants_white.png', 'C00002'),
('P20053', 'White/Black Pattern Pants', 'High-quality trousers in White/Black Pattern.', 'w_pants_wbbox.png', 'C00002'),
('P20054', 'Yellow Kids Tops', 'Playful yellow tops for kids.', 'c_tops_yellow.png', 'C00003'),
('P20055', 'White/Blue Striped Kids Tops', 'Playful striped tops for kids.', 'c_tops_wb.png', 'C00003'),
('P20056', 'Red/Silver Kids Tops', 'Playful red/silver tops for kids.', 'c_tops_rs.png', 'C00003'),
('P20057', 'Blue Kids Sleepwear', 'Cozy blue sleep sets for kids.', 'c_sleepwear_blue.png', 'C00003'),
('P20058', 'Peach Kids Dress', 'Adorable peach dress for kids.', 'c_dress_peach.png', 'C00003'),
('P20059', 'Blue Floral Kids Dress', 'Adorable floral dress for kids.', 'c_dress_blue.png', 'C00003'),
('P20060', 'Pink/Blue Plaid Skirt', 'Durable plaid skirt for children.', 'c_skirt_pb.png', 'C00003'),
('P20061', 'Red Skirt', 'Durable red skirt for children.', 'c_skirt_red.png', 'C00003'),
('P20062', 'Brown Kids Shorts', 'Durable brown shorts for children.', 'c_shorts_brown.png', 'C00003'),
('P20063', 'Blue Kids Shorts', 'Durable blue shorts for children.', 'c_shorts_blue.png', 'C00003'),
('P20064', 'Black Wallet', 'A sleek and functional Black leather wallet.', 'a_wallet_black.png', 'C00004'),
('P20065', 'Blue Wallet', 'A sleek and functional Blue leather wallet.', 'a_wallet_blue.png', 'C00004'),
('P20066', 'Black Sunglasses', 'Fashion-forward sunglasses with Black frames.', 'a_sunglasses_black.png', 'C00004'),
('P20067', 'Beige Sunglasses', 'Fashion-forward sunglasses with Beige frames.', 'a_sunglasses_beige.png', 'C00004'),
('P20068', 'Black Backpack', 'Spacious and ergonomic Black backpack.', 'a_backpack_black.png', 'C00004'),
('P20069', 'Blue Backpack', 'Spacious and ergonomic Blue backpack.', 'a_backpack_blue.png', 'C00004'),
('P20070', 'Navy NY Cap', 'Classic adjustable Navy NY baseball cap.', 'a_caps_ny.png', 'C00004'),
('P20071', 'Light Blue Cap', 'Classic adjustable Light Blue baseball cap.', 'a_caps_blue.png', 'C00004'),
('P20072', 'Black Shoes', 'Comfortable footwear in Black.', 'a_shoes_black.png', 'C00004'),
('P20073', 'Brown Shoes', 'Comfortable footwear in Brown.', 'a_shoes_brown.png', 'C00004'),
('P20074', 'Pink Ribbon Sleepwear', 'Ultra-soft pajamas with Pink Ribbon.', 'w_sleepware_prb.png', 'C00002'),
('P20075', 'Coffee Time Sleepwear', 'Ultra-soft pajamas with Coffee Time design.', 'w_sleepware_ct.png', 'C00002'),
('P20076', 'Egg Plant Sleepwear', 'Ultra-soft pajamas in Egg Plant color.', 'w_sleepware_ep.png', 'C00002');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `review_id` int(11) NOT NULL,
  `product_id` varchar(10) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `rating` int(1) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `review` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_reviews`
--

INSERT INTO `product_reviews` (`review_id`, `product_id`, `user_id`, `rating`, `review`, `created_at`) VALUES
(1, 'P20067', 'U001', 5, '', '2026-04-14 16:13:41'),
(2, 'P20001', 'U002', 5, 'Very Nice Customer Service', '2026-04-15 11:35:35'),
(3, 'P20002', 'U001', 5, 'Good Quality', '2026-04-15 15:14:09'),
(4, 'P20023', 'U003', 5, 'Good Service', '2026-04-16 14:49:45'),
(5, 'P20001', 'A003', 3, 'Fast Delivery', '2026-04-16 15:59:04');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `variant_id` varchar(10) NOT NULL,
  `product_id` varchar(10) NOT NULL,
  `size` varchar(10) DEFAULT NULL,
  `colour` varchar(30) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `price` decimal(10,2) NOT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`variant_id`, `product_id`, `size`, `colour`, `stock_quantity`, `price`, `photo`) VALUES
('P10001', 'P20001', 'S', 'Green', 45, 28.50, '69dfc2f8ba679.jpg'),
('P10002', 'P20001', 'M', 'Green', 32, 28.50, '69dfc2f8ba679.jpg'),
('P10003', 'P20001', 'L', 'Green', 18, 28.50, '69dfc2f8ba679.jpg'),
('P10004', 'P20002', 'S', 'Black', 50, 32.00, 'm_tshirt_blk.png'),
('P10005', 'P20002', 'M', 'Black', 12, 32.00, 'm_tshirt_blk.png'),
('P10006', 'P20002', 'L', 'Black', 25, 32.00, 'm_tshirt_blk.png'),
('P10007', 'P20003', 'S', 'Navy Blue', 60, 45.90, 'm_tshirt_nvb.png'),
('P10008', 'P20003', 'M', 'Navy Blue', 44, 45.90, 'm_tshirt_nvb.png'),
('P10009', 'P20003', 'L', 'Navy Blue', 31, 45.90, 'm_tshirt_nvb.png'),
('P10010', 'P20004', 'S', 'Brown', 20, 29.99, 'm_tshirt_brown.png'),
('P10011', 'P20004', 'M', 'Brown', 15, 29.99, 'm_tshirt_brown.png'),
('P10012', 'P20004', 'L', 'Brown', 10, 29.99, 'm_tshirt_brown.png'),
('P10013', 'P20005', 'S', 'White/Green', 55, 24.50, 'm_tshirt_wg.png'),
('P10014', 'P20005', 'M', 'White/Green', 38, 24.50, 'm_tshirt_wg.png'),
('P10015', 'P20005', 'L', 'White/Green', 22, 24.50, 'm_tshirt_wg.png'),
('P10016', 'P20006', 'S', 'White/Blue', 40, 35.00, 'm_tshirt_wb.png'),
('P10017', 'P20006', 'M', 'White/Blue', 29, 35.00, 'm_tshirt_wb.png'),
('P10018', 'P20006', 'L', 'White/Blue', 14, 35.00, 'm_tshirt_wb.png'),
('P10019', 'P20007', 'S', 'White/Red/Blue', 65, 22.00, 'm_tshirt_wrb.png'),
('P10020', 'P20007', 'M', 'White/Red/Blue', 50, 22.00, 'm_tshirt_wrb.png'),
('P10021', 'P20007', 'L', 'White/Red/Blue', 33, 22.00, 'm_tshirt_wrb.png'),
('P10022', 'P20008', 'S', 'Jeans', 15, 50.00, 'm_jacket_jeans.png'),
('P10023', 'P20008', 'M', 'Jeans', 18, 50.00, 'm_jacket_jeans.png'),
('P10024', 'P20008', 'L', 'Jeans', 12, 50.00, 'm_jacket_jeans.png'),
('P10025', 'P20009', 'S', 'White/Green', 20, 120.00, 'm_jacket_wg.png'),
('P10026', 'P20009', 'M', 'White/Green', 15, 120.00, 'm_jacket_wg.png'),
('P10027', 'P20009', 'L', 'White/Green', 10, 120.00, 'm_jacket_wg.png'),
('P10028', 'P20010', 'S', 'Red', 25, 89.90, 'm_jacket_red.png'),
('P10029', 'P20010', 'M', 'Red', 22, 89.90, 'm_jacket_red.png'),
('P10030', 'P20010', 'L', 'Red', 14, 89.90, 'm_jacket_red.png'),
('P10031', 'P20011', 'S', 'Green/Plaid', 30, 65.00, 'm_jacket_gp.png'),
('P10032', 'P20011', 'M', 'Green/Plaid', 28, 65.00, 'm_jacket_gp.png'),
('P10033', 'P20011', 'L', 'Green/Plaid', 19, 65.00, 'm_jacket_gp.png'),
('P10034', 'P20012', 'S', 'Light Beige', 40, 55.00, 'm_jacket_lb.png'),
('P10035', 'P20012', 'M', 'Light Beige', 35, 55.00, 'm_jacket_lb.png'),
('P10036', 'P20012', 'L', 'Light Beige', 25, 55.00, 'm_jacket_lb.png'),
('P10037', 'P20013', 'S', 'Brown', 15, 75.50, 'm_jacket_brown.png'),
('P10038', 'P20013', 'M', 'Brown', 12, 75.50, 'm_jacket_brown.png'),
('P10039', 'P20013', 'L', 'Brown', 10, 75.50, 'm_jacket_brown.png'),
('P10040', 'P20014', 'S', 'Dark Grey', 50, 55.00, 'm_joggers_dg.png'),
('P10041', 'P20014', 'M', 'Dark Grey', 45, 55.00, 'm_joggers_dg.png'),
('P10042', 'P20014', 'L', 'Dark Grey', 30, 55.00, 'm_joggers_dg.png'),
('P10043', 'P20015', 'S', 'Khaki', 40, 62.50, 'm_joggers_khaki.png'),
('P10044', 'P20015', 'M', 'Khaki', 35, 62.50, 'm_joggers_khaki.png'),
('P10045', 'P20015', 'L', 'Khaki', 25, 62.50, 'm_joggers_khaki.png'),
('P10046', 'P20016', 'S', 'Sage Green', 30, 49.90, 'm_joggers_sg.png'),
('P10047', 'P20016', 'M', 'Sage Green', 28, 49.90, 'm_joggers_sg.png'),
('P10048', 'P20016', 'L', 'Sage Green', 15, 49.90, 'm_joggers_sg.png'),
('P10049', 'P20017', 'S', 'Light Grey', 60, 45.00, 'm_joggers_lg.png'),
('P10050', 'P20017', 'M', 'Light Grey', 55, 45.00, 'm_joggers_lg.png'),
('P10051', 'P20017', 'L', 'Light Grey', 40, 45.00, 'm_joggers_lg.png'),
('P10052', 'P20018', 'S', 'Navy Blue', 35, 58.00, 'm_joggers_nb.png'),
('P10053', 'P20018', 'M', 'Navy Blue', 30, 58.00, 'm_joggers_nb.png'),
('P10054', 'P20018', 'L', 'Navy Blue', 20, 58.00, 'm_joggers_nb.png'),
('P10055', 'P20019', 'S', 'Beige', 45, 52.00, 'm_joggers_beige.png'),
('P10056', 'P20019', 'M', 'Beige', 40, 52.00, 'm_joggers_beige.png'),
('P10057', 'P20019', 'L', 'Beige', 25, 52.00, 'm_joggers_beige.png'),
('P10058', 'P20020', 'S', 'Olive Green', 30, 65.00, 'm_joggers_og.png'),
('P10059', 'P20020', 'M', 'Olive Green', 25, 65.00, 'm_joggers_og.png'),
('P10060', 'P20020', 'L', 'Olive Green', 15, 65.00, 'm_joggers_og.png'),
('P10061', 'P20021', 'S', 'Sand', 55, 59.90, 'm_joggers_sand.png'),
('P10062', 'P20021', 'M', 'Sand', 50, 59.90, 'm_joggers_sand.png'),
('P10063', 'P20021', 'L', 'Sand', 35, 59.90, 'm_joggers_sand.png'),
('P10064', 'P20022', 'S', 'White', 70, 25.00, 'w_tops_white.png'),
('P10065', 'P20022', 'M', 'White', 65, 25.00, 'w_tops_white.png'),
('P10066', 'P20022', 'L', 'White', 45, 25.00, 'w_tops_white.png'),
('P10067', 'P20023', 'S', 'Black', 80, 35.00, 'w_tops_black.png'),
('P10068', 'P20023', 'M', 'Black', 75, 35.00, 'w_tops_black.png'),
('P10069', 'P20023', 'L', 'Black', 50, 35.00, 'w_tops_black.png'),
('P10070', 'P20024', 'S', 'Beige', 40, 45.90, 'w_tops_beige.png'),
('P10071', 'P20024', 'M', 'Beige', 35, 45.90, 'w_tops_beige.png'),
('P10072', 'P20024', 'L', 'Beige', 20, 45.90, 'w_tops_beige.png'),
('P10073', 'P20025', 'S', 'Light Blue', 55, 38.00, 'w_tops_lb.png'),
('P10074', 'P20025', 'M', 'Light Blue', 50, 38.00, 'w_tops_lb.png'),
('P10075', 'P20025', 'L', 'Light Blue', 30, 38.00, 'w_tops_lb.png'),
('P10076', 'P20026', 'S', 'Dusty Pink', 60, 28.00, 'w_tops_dp.png'),
('P10077', 'P20026', 'M', 'Dusty Pink', 55, 28.00, 'w_tops_dp.png'),
('P10078', 'P20026', 'L', 'Dusty Pink', 35, 28.00, 'w_tops_dp.png'),
('P10079', 'P20027', 'S', 'Navy Blue', 45, 42.00, 'w_tops_nb.png'),
('P10080', 'P20027', 'M', 'Navy Blue', 40, 42.00, 'w_tops_nb.png'),
('P10081', 'P20027', 'L', 'Navy Blue', 25, 42.00, 'w_tops_nb.png'),
('P10082', 'P20028', 'S', 'Pink', 30, 55.00, 'w_tops_pink.png'),
('P10083', 'P20028', 'M', 'Pink', 25, 55.00, 'w_tops_pink.png'),
('P10084', 'P20028', 'L', 'Pink', 15, 55.00, 'w_tops_pink.png'),
('P10085', 'P20029', 'S', 'Mustard Yellow', 50, 32.50, 'w_tops_my.png'),
('P10086', 'P20029', 'M', 'Mustard Yellow', 45, 32.50, 'w_tops_my.png'),
('P10087', 'P20029', 'L', 'Mustard Yellow', 30, 32.50, 'w_tops_my.png'),
('P10088', 'P20030', 'S', 'Blue', 40, 45.00, 'w_sleepware_blue.png'),
('P10089', 'P20030', 'M', 'Blue', 35, 45.00, 'w_sleepware_blue.png'),
('P10090', 'P20030', 'L', 'Blue', 20, 45.00, 'w_sleepware_blue.png'),
('P10091', 'P20031', 'S', 'Pink', 30, 55.00, 'w_sleepware_pink.png'),
('P10092', 'P20031', 'M', 'Pink', 25, 55.00, 'w_sleepware_pink.png'),
('P10093', 'P20031', 'L', 'Pink', 15, 55.00, 'w_sleepware_pink.png'),
('P10094', 'P20032', 'S', 'White/Pink', 50, 42.50, 'w_sleepware_wp.png'),
('P10095', 'P20032', 'M', 'White/Pink', 45, 42.50, 'w_sleepware_wp.png'),
('P10096', 'P20032', 'L', 'White/Pink', 30, 42.50, 'w_sleepware_wp.png'),
('P10097', 'P20033', 'S', 'Peanuts', 60, 38.00, 'w_sleepware_peanuts.png'),
('P10098', 'P20033', 'M', 'Peanuts', 55, 38.00, 'w_sleepware_peanuts.png'),
('P10099', 'P20033', 'L', 'Peanuts', 40, 38.00, 'w_sleepware_peanuts.png'),
('P10100', 'P20074', 'S', 'Pink Ribbon', 45, 29.90, 'w_sleepware_prb.png'),
('P10101', 'P20074', 'M', 'Pink Ribbon', 40, 29.90, 'w_sleepware_prb.png'),
('P10102', 'P20074', 'L', 'Pink Ribbon', 25, 29.90, 'w_sleepware_prb.png'),
('P10103', 'P20075', 'S', 'Coffee Time', 35, 48.00, 'w_sleepware_ct.png'),
('P10104', 'P20075', 'M', 'Coffee Time', 30, 48.00, 'w_sleepware_ct.png'),
('P10105', 'P20075', 'L', 'Coffee Time', 20, 48.00, 'w_sleepware_ct.png'),
('P10106', 'P20036', 'S', 'Red', 55, 35.00, 'w_sleepware_red.png'),
('P10107', 'P20036', 'M', 'Red', 50, 35.00, 'w_sleepware_red.png'),
('P10108', 'P20036', 'L', 'Red', 35, 35.00, 'w_sleepware_red.png'),
('P10109', 'P20076', 'S', 'Egg Plant', 25, 59.90, 'w_sleepware_ep.png'),
('P10110', 'P20076', 'M', 'Egg Plant', 20, 59.90, 'w_sleepware_ep.png'),
('P10111', 'P20076', 'L', 'Egg Plant', 10, 59.90, 'w_sleepware_ep.png'),
('P10112', 'P20038', 'S', 'Red Floral', 30, 65.00, 'w_dresses_rf.png'),
('P10113', 'P20038', 'M', 'Red Floral', 25, 65.00, 'w_dresses_rf.png'),
('P10114', 'P20038', 'L', 'Red Floral', 15, 65.00, 'w_dresses_rf.png'),
('P10115', 'P20039', 'S', 'Blue Polka Dot', 40, 58.50, 'w_dresses_bpd.png'),
('P10116', 'P20039', 'M', 'Blue Polka Dot', 35, 58.50, 'w_dresses_bpd.png'),
('P10117', 'P20039', 'L', 'Blue Polka Dot', 20, 58.50, 'w_dresses_bpd.png'),
('P10118', 'P20040', 'S', 'Pink', 20, 89.90, 'w_dresses_pink.png'),
('P10119', 'P20040', 'M', 'Pink', 15, 89.90, 'w_dresses_pink.png'),
('P10120', 'P20040', 'L', 'Pink', 10, 89.90, 'w_dresses_pink.png'),
('P10121', 'P20041', 'S', 'Black', 35, 72.00, 'w_dresses_black.png'),
('P10122', 'P20041', 'M', 'Black', 30, 72.00, 'w_dresses_black.png'),
('P10123', 'P20041', 'L', 'Black', 15, 72.00, 'w_dresses_black.png'),
('P10124', 'P20042', 'S', 'Brown', 45, 62.00, 'w_dresses_brown.png'),
('P10125', 'P20042', 'M', 'Brown', 40, 62.00, 'w_dresses_brown.png'),
('P10126', 'P20042', 'L', 'Brown', 25, 62.00, 'w_dresses_brown.png'),
('P10127', 'P20043', 'S', 'White', 50, 55.00, 'w_dresses_white.png'),
('P10128', 'P20043', 'M', 'White', 45, 55.00, 'w_dresses_white.png'),
('P10129', 'P20043', 'L', 'White', 30, 55.00, 'w_dresses_white.png'),
('P10130', 'P20044', 'S', 'Black/Flower', 55, 49.90, 'w_dresses_bf.png'),
('P10131', 'P20044', 'M', 'Black/Flower', 50, 49.90, 'w_dresses_bf.png'),
('P10132', 'P20044', 'L', 'Black/Flower', 35, 49.90, 'w_dresses_bf.png'),
('P10133', 'P20045', 'S', 'Blue', 40, 68.00, 'w_dresses_blue.png'),
('P10134', 'P20045', 'M', 'Blue', 35, 68.00, 'w_dresses_blue.png'),
('P10135', 'P20045', 'L', 'Blue', 20, 68.00, 'w_dresses_blue.png'),
('P10136', 'P20046', 'S', 'Black', 60, 45.00, 'w_pants_black.png'),
('P10137', 'P20046', 'M', 'Black', 55, 45.00, 'w_pants_black.png'),
('P10138', 'P20046', 'L', 'Black', 40, 45.00, 'w_pants_black.png'),
('P10139', 'P20047', 'S', 'Blue', 50, 42.00, 'w_pants_blue.png'),
('P10140', 'P20047', 'M', 'Blue', 45, 42.00, 'w_pants_blue.png'),
('P10141', 'P20047', 'L', 'Blue', 30, 42.00, 'w_pants_blue.png'),
('P10142', 'P20048', 'S', 'Yellow', 40, 48.50, 'w_pants_yellow.png'),
('P10143', 'P20048', 'M', 'Yellow', 35, 48.50, 'w_pants_yellow.png'),
('P10144', 'P20048', 'L', 'Yellow', 20, 48.50, 'w_pants_yellow.png'),
('P10145', 'P20049', 'S', 'Pink', 55, 46.00, 'w_pants_pink.png'),
('P10146', 'P20049', 'M', 'Pink', 50, 46.00, 'w_pants_pink.png'),
('P10147', 'P20049', 'L', 'Pink', 35, 46.00, 'w_pants_pink.png'),
('P10148', 'P20050', 'S', 'Blue Jeans', 30, 55.00, 'w_pants_jeans.png'),
('P10149', 'P20050', 'M', 'Blue Jeans', 25, 55.00, 'w_pants_jeans.png'),
('P10150', 'P20050', 'L', 'Blue Jeans', 15, 55.00, 'w_pants_jeans.png'),
('P10151', 'P20051', 'S', 'Dark Blue Jeans', 35, 55.00, 'w_pants_bjeans.png'),
('P10152', 'P20051', 'M', 'Dark Blue Jeans', 30, 55.00, 'w_pants_bjeans.png'),
('P10153', 'P20051', 'L', 'Dark Blue Jeans', 20, 55.00, 'w_pants_bjeans.png'),
('P10154', 'P20052', 'S', 'White', 45, 44.90, 'w_pants_white.png'),
('P10155', 'P20052', 'M', 'White', 40, 44.90, 'w_pants_white.png'),
('P10156', 'P20052', 'L', 'White', 25, 44.90, 'w_pants_white.png'),
('P10157', 'P20053', 'S', 'White/Black Pattern', 40, 49.00, 'w_pants_wbbox.png'),
('P10158', 'P20053', 'M', 'White/Black Pattern', 35, 49.00, 'w_pants_wbbox.png'),
('P10159', 'P20053', 'L', 'White/Black Pattern', 20, 49.00, 'w_pants_wbbox.png'),
('P10160', 'P20054', 'S', 'Yellow', 50, 18.00, 'c_tops_yellow.png'),
('P10161', 'P20054', 'M', 'Yellow', 45, 18.00, 'c_tops_yellow.png'),
('P10162', 'P20054', 'L', 'Yellow', 30, 18.00, 'c_tops_yellow.png'),
('P10163', 'P20055', 'S', 'White/Blue Striped', 60, 19.50, 'c_tops_wb.png'),
('P10164', 'P20055', 'M', 'White/Blue Striped', 55, 19.50, 'c_tops_wb.png'),
('P10165', 'P20055', 'L', 'White/Blue Striped', 40, 19.50, 'c_tops_wb.png'),
('P10166', 'P20056', 'S', 'Red/Silver', 55, 20.00, 'c_tops_rs.png'),
('P10167', 'P20056', 'M', 'Red/Silver', 50, 20.00, 'c_tops_rs.png'),
('P10168', 'P20056', 'L', 'Red/Silver', 35, 20.00, 'c_tops_rs.png'),
('P10169', 'P20057', 'S', 'Blue', 40, 25.00, 'c_sleepwear_blue.png'),
('P10170', 'P20057', 'M', 'Blue', 35, 25.00, 'c_sleepwear_blue.png'),
('P10171', 'P20057', 'L', 'Blue', 20, 25.00, 'c_sleepwear_blue.png'),
('P10172', 'P20058', 'S', 'Peach', 30, 35.00, 'c_dress_peach.png'),
('P10173', 'P20058', 'M', 'Peach', 25, 35.00, 'c_dress_peach.png'),
('P10174', 'P20058', 'L', 'Peach', 15, 35.00, 'c_dress_peach.png'),
('P10175', 'P20059', 'S', 'Blue Floral', 40, 32.00, 'c_dress_blue.png'),
('P10176', 'P20059', 'M', 'Blue Floral', 35, 32.00, 'c_dress_blue.png'),
('P10177', 'P20059', 'L', 'Blue Floral', 20, 32.00, 'c_dress_blue.png'),
('P10178', 'P20060', 'S', 'Pink/Blue Plaid', 50, 22.00, 'c_skirt_pb.png'),
('P10179', 'P20060', 'M', 'Pink/Blue Plaid', 45, 22.00, 'c_skirt_pb.png'),
('P10180', 'P20060', 'L', 'Pink/Blue Plaid', 30, 22.00, 'c_skirt_pb.png'),
('P10181', 'P20061', 'S', 'Red', 40, 24.00, 'c_skirt_red.png'),
('P10182', 'P20061', 'M', 'Red', 35, 24.00, 'c_skirt_red.png'),
('P10183', 'P20061', 'L', 'Red', 20, 24.00, 'c_skirt_red.png'),
('P10184', 'P20062', 'S', 'Brown', 60, 15.00, 'c_shorts_brown.png'),
('P10185', 'P20062', 'M', 'Brown', 55, 15.00, 'c_shorts_brown.png'),
('P10186', 'P20062', 'L', 'Brown', 40, 15.00, 'c_shorts_brown.png'),
('P10187', 'P20063', 'S', 'Blue', 70, 15.00, 'c_shorts_blue.png'),
('P10188', 'P20063', 'M', 'Blue', 65, 15.00, 'c_shorts_blue.png'),
('P10189', 'P20063', 'L', 'Blue', 50, 15.00, 'c_shorts_blue.png'),
('P10190', 'P20064', NULL, 'Black', 25, 35.00, 'a_wallet_black.png'),
('P10191', 'P20065', NULL, 'Blue', 18, 35.00, 'a_wallet_blue.png'),
('P10192', 'P20066', NULL, 'Black', 30, 85.00, 'a_sunglasses_black.png'),
('P10193', 'P20067', NULL, 'Beige', 22, 85.00, 'a_sunglasses_beige.png'),
('P10194', 'P20068', NULL, 'Black', 15, 65.00, 'a_backpack_black.png'),
('P10195', 'P20069', NULL, 'Blue', 12, 65.00, 'a_backpack_blue.png'),
('P10196', 'P20070', 'M', 'Navy NY', 40, 25.00, 'a_caps_ny.png'),
('P10197', 'P20070', 'L', 'Navy NY', 35, 25.00, 'a_caps_ny.png'),
('P10198', 'P20071', 'M', 'Light Blue', 30, 22.00, 'a_caps_blue.png'),
('P10199', 'P20071', 'L', 'Light Blue', 25, 22.00, 'a_caps_blue.png'),
('P10200', 'P20072', 'S', 'Black', 20, 95.00, 'a_shoes_black.png'),
('P10201', 'P20072', 'M', 'Black', 18, 95.00, 'a_shoes_black.png'),
('P10202', 'P20072', 'L', 'Black', 12, 95.00, 'a_shoes_black.png'),
('P10203', 'P20073', 'S', 'Brown', 15, 88.00, 'a_shoes_brown.png'),
('P10204', 'P20073', 'M', 'Brown', 12, 88.00, 'a_shoes_brown.png'),
('P10205', 'P20073', 'L', 'Brown', 10, 88.00, 'a_shoes_brown.png');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` varchar(10) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `failed_attempts` int(11) DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'Member'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `email`, `password_hash`, `failed_attempts`, `locked_until`, `photo`, `role`) VALUES
('A001', 'Jaclyn', 'jaclyn@gmail.com', '$2y$10$HO9mAaL4sPBp7N0Ju98Gj.D/unX8vG.8SJuyxki.LmS/AV1xiFzFe', 0, NULL, 'default_user.jpg', 'Admin'),
('A002', 'Xiang', 'xiang@gmail.com', '$2y$10$0SeP3CzwYQMPxZi35qQJwORBwyikU1U495F38N8m2ovW.zXMx0S4.', 0, NULL, 'default_user.jpg', 'Admin'),
('A003', 'Admin demo', '321@gmail.com', '$2y$10$gs/iXOiJRi6v0rGPV2GwSuG1xPcPeb/ql9j1DzxBdRKMc/lKgH4g.', 0, NULL, '69e1072e4a426.jpg', 'Admin'),
('U001', 'Alfred Tan', 'alfredtan@gmail.com', '$2y$10$j2BnnJKTbLCSNbKhNLngJOOEgePLf4DC3J9ayqpp.aLXViyefAJR.', 0, NULL, 'default_user.jpg', 'Member'),
('U002', 'Ben Wong', 'benw@gmail.com', '$2y$10$GQ6T6Kk60oZvvLtu9E5JL.3RZm6jGzmwvBeW6jFx9iR/2VcxuQ8S6', 0, NULL, 'default_user.jpg', 'Member'),
('U003', 'User Demo', '123@gmail.com', '$2y$10$4iTzStKXWwYoEIjRVo.k5OUX7mC6P7jtCZEP3CE14zdEBNWBzz7YO', 0, NULL, '69dfe9b2196e0.jpg', 'Member');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`favorite_id`),
  ADD KEY `fk_favorites_user` (`user_id`),
  ADD KEY `fk_favorites_product` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_id`,`variant_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `fk_reviews_product` (`product_id`),
  ADD KEY `fk_reviews_user` (`user_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`variant_id`),
  ADD KEY `fk_variants_product` (`product_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `fk_favorites_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_favorites_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `fk_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `fk_variants_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
