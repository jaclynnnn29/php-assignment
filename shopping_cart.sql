-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2026 at 05:28 PM
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
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` varchar(10) NOT NULL,
  `order_id` varchar(10) DEFAULT NULL,
  `product_id` varchar(10) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `unit` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `order_id` varchar(10) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `user_id` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` varchar(10) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `cat_id` varchar(10) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `colour` varchar(30) DEFAULT NULL,
  `size` varchar(10) DEFAULT NULL,
  `photo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `cat_id`, `price`, `colour`, `size`, `photo`) VALUES
('P10001', 'T-shirt', 'C00001', 28.50, 'Green', 'S', 'm_tshirt_grn.png'),
('P10002', 'T-shirt', 'C00001', 28.50, 'Green', 'M', 'm_tshirt_grn.png'),
('P10003', 'T-shirt', 'C00001', 28.50, 'Green', 'L', 'm_tshirt_grn.png'),
('P10004', 'T-shirt', 'C00001', 32.00, 'Black', 'S', 'm_tshirt_blk.png'),
('P10005', 'T-shirt', 'C00001', 32.00, 'Black', 'M', 'm_tshirt_blk.png'),
('P10006', 'T-shirt', 'C00001', 32.00, 'Black', 'L', 'm_tshirt_blk.png'),
('P10007', 'T-shirt', 'C00001', 45.90, 'Navy Blue', 'S', 'm_tshirt_nvb.png'),
('P10008', 'T-shirt', 'C00001', 45.90, 'Navy Blue', 'M', 'm_tshirt_nvb.png'),
('P10009', 'T-shirt', 'C00001', 45.90, 'Navy Blue', 'L', 'm_tshirt_nvb.png'),
('P10010', 'T-shirt', 'C00001', 29.99, 'Brown', 'S', 'm_tshirt_brown.png'),
('P10011', 'T-shirt', 'C00001', 29.99, 'Brown', 'M', 'm_tshirt_brown.png'),
('P10012', 'T-shirt', 'C00001', 29.99, 'Brown', 'L', 'm_tshirt_brown.png'),
('P10013', 'T-shirt', 'C00001', 24.50, 'White/Green', 'S', 'm_tshirt_wg.png'),
('P10014', 'T-shirt', 'C00001', 24.50, 'White/Green', 'M', 'm_tshirt_wg.png'),
('P10015', 'T-shirt', 'C00001', 24.50, 'White/Green', 'L', 'm_tshirt_wg.png'),
('P10016', 'T-shirt', 'C00001', 35.00, 'White/Blue', 'S', 'm_tshirt_wb.png'),
('P10017', 'T-shirt', 'C00001', 35.00, 'White/Blue', 'M', 'm_tshirt_wb.png'),
('P10018', 'T-shirt', 'C00001', 35.00, 'White/Blue', 'L', 'm_tshirt_wb.png'),
('P10019', 'T-shirt', 'C00001', 22.00, 'White/Red/Blue', 'S', 'm_tshirt_wrb.png'),
('P10020', 'T-shirt', 'C00001', 22.00, 'White/Red/Blue', 'M', 'm_tshirt_wrb.png'),
('P10021', 'T-shirt', 'C00001', 22.00, 'White/Red/Blue', 'L', 'm_tshirt_wrb.png'),
('P10022', 'Jacket', 'C00001', 50.00, 'Jeans', 'S', 'm_jacket_jeans.png'),
('P10023', 'Jacket', 'C00001', 50.00, 'Jeans', 'M', 'm_jacket_jeans.png'),
('P10024', 'Jacket', 'C00001', 50.00, 'Jeans', 'L', 'm_jacket_jeans.png'),
('P10025', 'Jacket', 'C00001', 120.00, 'White/Green', 'S', 'm_jacket_wg.png'),
('P10026', 'Jacket', 'C00001', 120.00, 'White/Green', 'M', 'm_jacket_wg.png'),
('P10027', 'Jacket', 'C00001', 120.00, 'White/Green', 'L', 'm_jacket_wg.png'),
('P10028', 'Jacket', 'C00001', 89.90, 'Red', 'S', 'm_jacket_red.png'),
('P10029', 'Jacket', 'C00001', 89.90, 'Red', 'M', 'm_jacket_red.png'),
('P10030', 'Jacket', 'C00001', 89.90, 'Red', 'L', 'm_jacket_red.png'),
('P10031', 'Jacket', 'C00001', 65.00, 'Green/Plaid', 'S', 'm_jacket_gp.png'),
('P10032', 'Jacket', 'C00001', 65.00, 'Green/Plaid', 'M', 'm_jacket_gp.png'),
('P10033', 'Jacket', 'C00001', 65.00, 'Green/Plaid', 'L', 'm_jacket_gp.png'),
('P10034', 'Jacket', 'C00001', 55.00, 'Light Beige', 'S', 'm_jacket_lb.png'),
('P10035', 'Jacket', 'C00001', 55.00, 'Light Beige', 'M', 'm_jacket_lb.png'),
('P10036', 'Jacket', 'C00001', 55.00, 'Light Beige', 'L', 'm_jacket_lb.png'),
('P10037', 'Jacket', 'C00001', 75.50, 'Brown', 'S', 'm_jacket_brown.png'),
('P10038', 'Jacket', 'C00001', 75.50, 'Brown', 'M', 'm_jacket_brown.png'),
('P10039', 'Jacket', 'C00001', 75.50, 'Brown', 'L', 'm_jacket_brown.png'),
('P10040', 'Joggers', 'C00001', 55.00, 'Dark Grey', 'S', 'm_joggers_dg.png'),
('P10041', 'Joggers', 'C00001', 55.00, 'Dark Grey', 'M', 'm_joggers_dg.png'),
('P10042', 'Joggers', 'C00001', 55.00, 'Dark Grey', 'L', 'm_joggers_dg.png'),
('P10043', 'Joggers', 'C00001', 62.50, 'Khaki', 'S', 'm_joggers_khaki.png'),
('P10044', 'Joggers', 'C00001', 62.50, 'Khaki', 'M', 'm_joggers_khaki.png'),
('P10045', 'Joggers', 'C00001', 62.50, 'Khaki', 'L', 'm_joggers_khaki.png'),
('P10046', 'Joggers', 'C00001', 49.90, 'Sage Green', 'S', 'm_joggers_sg.png'),
('P10047', 'Joggers', 'C00001', 49.90, 'Sage Green', 'M', 'm_joggers_sg.png'),
('P10048', 'Joggers', 'C00001', 49.90, 'Sage Green', 'L', 'm_joggers_sg.png'),
('P10049', 'Joggers', 'C00001', 45.00, 'Light Grey', 'S', 'm_joggers_lg.png'),
('P10050', 'Joggers', 'C00001', 45.00, 'Light Grey', 'M', 'm_joggers_lg.png'),
('P10051', 'Joggers', 'C00001', 45.00, 'Light Grey', 'L', 'm_joggers_lg.png'),
('P10052', 'Joggers', 'C00001', 58.00, 'Navy Blue', 'S', 'm_joggers_nb.png'),
('P10053', 'Joggers', 'C00001', 58.00, 'Navy Blue', 'M', 'm_joggers_nb.png'),
('P10054', 'Joggers', 'C00001', 58.00, 'Navy Blue', 'L', 'm_joggers_nb.png'),
('P10055', 'Joggers', 'C00001', 52.00, 'Beige', 'S', 'm_joggers_beige.png'),
('P10056', 'Joggers', 'C00001', 52.00, 'Beige', 'M', 'm_joggers_beige.png'),
('P10057', 'Joggers', 'C00001', 52.00, 'Beige', 'L', 'm_joggers_beige.png'),
('P10058', 'Joggers', 'C00001', 65.00, 'Olive Green', 'S', 'm_joggers_og.png'),
('P10059', 'Joggers', 'C00001', 65.00, 'Olive Green', 'M', 'm_joggers_og.png'),
('P10060', 'Joggers', 'C00001', 65.00, 'Olive Green', 'L', 'm_joggers_og.png'),
('P10061', 'Joggers', 'C00001', 59.90, 'Sand', 'S', 'm_joggers_sand.png'),
('P10062', 'Joggers', 'C00001', 59.90, 'Sand', 'M', 'm_joggers_sand.png'),
('P10063', 'Joggers', 'C00001', 59.90, 'Sand', 'L', 'm_joggers_sand.png'),
('P10064', 'Tops', 'C00002', 25.00, 'White', 'S', 'w_tops_white.png'),
('P10065', 'Tops', 'C00002', 25.00, 'White', 'M', 'w_tops_white.png'),
('P10066', 'Tops', 'C00002', 25.00, 'White', 'L', 'w_tops_white.png'),
('P10067', 'Tops', 'C00002', 35.00, 'Black', 'S', 'w_tops_black.png'),
('P10068', 'Tops', 'C00002', 35.00, 'Black', 'M', 'w_tops_black.png'),
('P10069', 'Tops', 'C00002', 35.00, 'Black', 'L', 'w_tops_black.png'),
('P10070', 'Tops', 'C00002', 45.90, 'Beige', 'S', 'w_tops_beige.png'),
('P10071', 'Tops', 'C00002', 45.90, 'Beige', 'M', 'w_tops_beige.png'),
('P10072', 'Tops', 'C00002', 45.90, 'Beige', 'L', 'w_tops_beige.png'),
('P10073', 'Tops', 'C00002', 38.00, 'Light Blue', 'S', 'w_tops_lb.png'),
('P10074', 'Tops', 'C00002', 38.00, 'Light Blue', 'M', 'w_tops_lb.png'),
('P10075', 'Tops', 'C00002', 38.00, 'Light Blue', 'L', 'w_tops_lb.png'),
('P10076', 'Tops', 'C00002', 28.00, 'Dusty Pink', 'S', 'w_tops_dp.png'),
('P10077', 'Tops', 'C00002', 28.00, 'Dusty Pink', 'M', 'w_tops_dp.png'),
('P10078', 'Tops', 'C00002', 28.00, 'Dusty Pink', 'L', 'w_tops_dp.png'),
('P10079', 'Tops', 'C00002', 42.00, 'Navy Blue', 'S', 'w_tops_nb.png'),
('P10080', 'Tops', 'C00002', 42.00, 'Navy Blue', 'M', 'w_tops_nb.png'),
('P10081', 'Tops', 'C00002', 42.00, 'Navy Blue', 'L', 'w_tops_nb.png'),
('P10082', 'Tops', 'C00002', 55.00, 'Pink', 'S', 'w_tops_pink.png'),
('P10083', 'Tops', 'C00002', 55.00, 'Pink', 'M', 'w_tops_pink.png'),
('P10084', 'Tops', 'C00002', 55.00, 'Pink', 'L', 'w_tops_pink.png'),
('P10085', 'Tops', 'C00002', 32.50, 'Mustard Yellow', 'S', 'w_tops_my.png'),
('P10086', 'Tops', 'C00002', 32.50, 'Mustard Yellow', 'M', 'w_tops_my.png'),
('P10087', 'Tops', 'C00002', 32.50, 'Mustard Yellow', 'L', 'w_tops_my.png'),
('P10088', 'Sleepwear', 'C00002', 45.00, 'Blue', 'S', 'w_sleepware_blue.png'),
('P10089', 'Sleepwear', 'C00002', 45.00, 'Blue', 'M', 'w_sleepware_blue.png'),
('P10090', 'Sleepwear', 'C00002', 45.00, 'Blue', 'L', 'w_sleepware_blue.png'),
('P10091', 'Sleepwear', 'C00002', 55.00, 'Pink', 'S', 'w_sleepware_pink.png'),
('P10092', 'Sleepwear', 'C00002', 55.00, 'Pink', 'M', 'w_sleepware_pink.png'),
('P10093', 'Sleepwear', 'C00002', 55.00, 'Pink', 'L', 'w_sleepware_pink.png'),
('P10094', 'Sleepwear', 'C00002', 42.50, 'White/Pink', 'S', 'w_sleepware_wp.png'),
('P10095', 'Sleepwear', 'C00002', 42.50, 'White/Pink', 'M', 'w_sleepware_wp.png'),
('P10096', 'Sleepwear', 'C00002', 42.50, 'White/Pink', 'L', 'w_sleepware_wp.png'),
('P10097', 'Sleepwear', 'C00002', 38.00, 'Peanuts', 'S', 'w_sleepware_peanuts.png'),
('P10098', 'Sleepwear', 'C00002', 38.00, 'Peanuts', 'M', 'w_sleepware_peanuts.png'),
('P10099', 'Sleepwear', 'C00002', 38.00, 'Peanuts', 'L', 'w_sleepware_peanuts.png'),
('P10100', 'Sleepwear', 'C00002', 29.90, 'Pink Ribbon ', 'S', 'w_sleepware_prb.png'),
('P10101', 'Sleepwear', 'C00002', 29.90, 'Pink Ribbon ', 'M', 'w_sleepware_prb.png'),
('P10102', 'Sleepwear', 'C00002', 29.90, 'Pink Ribbon ', 'L', 'w_sleepware_prb.png'),
('P10103', 'Sleepwear', 'C00002', 48.00, 'Coffee Time', 'S', 'w_sleepware_ct.png'),
('P10104', 'Sleepwear', 'C00002', 48.00, 'Coffee Time', 'M', 'w_sleepware_ct.png'),
('P10105', 'Sleepwear', 'C00002', 48.00, 'Coffee Time', 'L', 'w_sleepware_ct.png'),
('P10106', 'Sleepwear', 'C00002', 35.00, 'Red', 'S', 'w_sleepware_red.png'),
('P10107', 'Sleepwear', 'C00002', 35.00, 'Red', 'M', 'w_sleepware_red.png'),
('P10108', 'Sleepwear', 'C00002', 35.00, 'Red', 'L', 'w_sleepware_red.png'),
('P10109', 'Sleepwear', 'C00002', 59.90, 'Egg Plant', 'S', 'w_sleepware_ep.png'),
('P10110', 'Sleepwear', 'C00002', 59.90, 'Egg Plant', 'M', 'w_sleepware_ep.png'),
('P10111', 'Sleepwear', 'C00002', 59.90, 'Egg Plant', 'L', 'w_sleepware_ep.png'),
('P10112', 'Dresses', 'C00002', 65.00, 'Red Floral', 'S', 'w_dresses_rf.png'),
('P10113', 'Dresses', 'C00002', 65.00, 'Red Floral', 'M', 'w_dresses_rf.png'),
('P10114', 'Dresses', 'C00002', 65.00, 'Red Floral', 'L', 'w_dresses_rf.png'),
('P10115', 'Dresses', 'C00002', 58.50, 'Blue Polka Dot', 'S', 'w_dresses_bpd.png'),
('P10116', 'Dresses', 'C00002', 58.50, 'Blue Polka Dot', 'M', 'w_dresses_bpd.png'),
('P10117', 'Dresses', 'C00002', 58.50, 'Blue Polka Dot', 'L', 'w_dresses_bpd.png'),
('P10118', 'Dresses', 'C00002', 89.90, 'Pink', 'S', 'w_dresses_pink.png'),
('P10119', 'Dresses', 'C00002', 89.90, 'Pink', 'M', 'w_dresses_pink.png'),
('P10120', 'Dresses', 'C00002', 89.90, 'Pink', 'L', 'w_dresses_pink.png'),
('P10121', 'Dresses', 'C00002', 72.00, 'Black', 'S', 'w_dresses_black.png'),
('P10122', 'Dresses', 'C00002', 72.00, 'Black', 'M', 'w_dresses_black.png'),
('P10123', 'Dresses', 'C00002', 72.00, 'Black', 'L', 'w_dresses_black.png'),
('P10124', 'Dresses', 'C00002', 62.00, 'Brown', 'S', 'w_dresses_brown.png'),
('P10125', 'Dresses', 'C00002', 62.00, 'Brown', 'M', 'w_dresses_brown.png'),
('P10126', 'Dresses', 'C00002', 62.00, 'Brown', 'L', 'w_dresses_brown.png'),
('P10127', 'Dresses', 'C00002', 55.00, 'White', 'S', 'w_dresses_white.png'),
('P10128', 'Dresses', 'C00002', 55.00, 'White', 'M', 'w_dresses_white.png'),
('P10129', 'Dresses', 'C00002', 55.00, 'White', 'L', 'w_dresses_white.png'),
('P10130', 'Dresses', 'C00002', 49.90, 'Black/Flower', 'S', 'w_dresses_bf.png'),
('P10131', 'Dresses', 'C00002', 49.90, 'Black/Flower', 'M', 'w_dresses_bf.png'),
('P10132', 'Dresses', 'C00002', 49.90, 'Black/Flower', 'L', 'w_dresses_bf.png'),
('P10133', 'Dresses', 'C00002', 68.00, 'Blue', 'S', 'w_dresses_blue.png'),
('P10134', 'Dresses', 'C00002', 68.00, 'Blue', 'M', 'w_dresses_blue.png'),
('P10135', 'Dresses', 'C00002', 68.00, 'Blue', 'L', 'w_dresses_blue.png'),
('P10136', 'Pants & Jeans', 'C00002', 45.00, 'Black', 'S', 'w_pants_black.png'),
('P10137', 'Pants & Jeans', 'C00002', 45.00, 'Black', 'M', 'w_pants_black.png'),
('P10138', 'Pants & Jeans', 'C00002', 45.00, 'Black', 'L', 'w_pants_black.png'),
('P10139', 'Pants & Jeans', 'C00002', 42.00, 'Blue', 'S', 'w_pants_blue.png'),
('P10140', 'Pants & Jeans', 'C00002', 42.00, 'Blue', 'M', 'w_pants_blue.png'),
('P10141', 'Pants & Jeans', 'C00002', 42.00, 'Blue', 'L', 'w_pants_blue.png'),
('P10142', 'Pants & Jeans', 'C00002', 48.50, 'Yellow', 'S', 'w_pants_yellow.png'),
('P10143', 'Pants & Jeans', 'C00002', 48.50, 'Yellow', 'M', 'w_pants_yellow.png'),
('P10144', 'Pants & Jeans', 'C00002', 48.50, 'Yellow', 'L', 'w_pants_yellow.png'),
('P10145', 'Pants & Jeans', 'C00002', 46.00, 'Pink', 'S', 'w_pants_pink.png'),
('P10146', 'Pants & Jeans', 'C00002', 46.00, 'Pink', 'M', 'w_pants_pink.png'),
('P10147', 'Pants & Jeans', 'C00002', 46.00, 'Pink', 'L', 'w_pants_pink.png'),
('P10148', 'Pants & Jeans', 'C00002', 55.00, 'Blue Jeans', 'S', 'w_pants_jeans.png'),
('P10149', 'Pants & Jeans', 'C00002', 55.00, 'Blue Jeans', 'M', 'w_pants_jeans.png'),
('P10150', 'Pants & Jeans', 'C00002', 55.00, 'Blue Jeans', 'L', 'w_pants_jeans.png'),
('P10151', 'Pants & Jeans', 'C00002', 55.00, 'Dark Blue Jeans', 'S', 'w_pants_bjeans.png'),
('P10152', 'Pants & Jeans', 'C00002', 55.00, 'Dark Blue Jeans', 'M', 'w_pants_bjeans.png'),
('P10153', 'Pants & Jeans', 'C00002', 55.00, 'Dark Blue Jeans', 'L', 'w_pants_bjeans.png'),
('P10154', 'Pants & Jeans', 'C00002', 44.90, 'White', 'S', 'w_pants_white.png'),
('P10155', 'Pants & Jeans', 'C00002', 44.90, 'White', 'M', 'w_pants_white.png'),
('P10156', 'Pants & Jeans', 'C00002', 44.90, 'White', 'L', 'w_pants_white.png'),
('P10157', 'Pants & Jeans', 'C00002', 49.00, 'White/Black Pattern', 'S', 'w_pants_wbbox.png'),
('P10158', 'Pants & Jeans', 'C00002', 49.00, 'White/Black Pattern', 'M', 'w_pants_wbbox.png'),
('P10159', 'Pants & Jeans', 'C00002', 49.00, 'White/Black Pattern', 'L', 'w_pants_wbbox.png'),
('P10160', 'Tops', 'C00003', 18.00, 'Yellow', 'S', 'c_tops_yellow.png'),
('P10161', 'Tops', 'C00003', 18.00, 'Yellow', 'M', 'c_tops_yellow.png'),
('P10162', 'Tops', 'C00003', 18.00, 'Yellow', 'L', 'c_tops_yellow.png'),
('P10163', 'Tops', 'C00003', 19.50, 'White/Blue Striped', 'S', 'c_tops_wb.png'),
('P10164', 'Tops', 'C00003', 19.50, 'White/Blue Striped', 'M', 'c_tops_wb.png'),
('P10165', 'Tops', 'C00003', 19.50, 'White/Blue Striped', 'L', 'c_tops_wb.png'),
('P10166', 'Tops', 'C00003', 20.00, 'Red/Silver', 'S', 'c_tops_rs.png'),
('P10167', 'Tops', 'C00003', 20.00, 'Red/Silver', 'M', 'c_tops_rs.png'),
('P10168', 'Tops', 'C00003', 20.00, 'Red/Silver', 'L', 'c_tops_rs.png'),
('P10169', 'Sleepwear', 'C00003', 25.00, 'Blue', 'S', 'c_sleepwear_blue.png'),
('P10170', 'Sleepwear', 'C00003', 25.00, 'Blue', 'M', 'c_sleepwear_blue.png'),
('P10171', 'Sleepwear', 'C00003', 25.00, 'Blue', 'L', 'c_sleepwear_blue.png'),
('P10172', 'Dresses', 'C00003', 35.00, 'Peach', 'S', 'c_dress_peach.png'),
('P10173', 'Dresses', 'C00003', 35.00, 'Peach', 'M', 'c_dress_peach.png'),
('P10174', 'Dresses', 'C00003', 35.00, 'Peach', 'L', 'c_dress_peach.png'),
('P10175', 'Dresses', 'C00003', 32.00, 'Blue Floral', 'S', 'c_dress_blue.png'),
('P10176', 'Dresses', 'C00003', 32.00, 'Blue Floral', 'M', 'c_dress_blue.png'),
('P10177', 'Dresses', 'C00003', 32.00, 'Blue Floral', 'L', 'c_dress_blue.png'),
('P10178', 'Shorts & Skirts', 'C00003', 22.00, 'Pink/Blue Plaid', 'S', 'c_skirt_pb.png'),
('P10179', 'Shorts & Skirts', 'C00003', 22.00, 'Pink/Blue Plaid', 'M', 'c_skirt_pb.png'),
('P10180', 'Shorts & Skirts', 'C00003', 22.00, 'Pink/Blue Plaid', 'L', 'c_skirt_pb.png'),
('P10181', 'Shorts & Skirts', 'C00003', 24.00, 'Red', 'S', 'c_skirt_red.png'),
('P10182', 'Shorts & Skirts', 'C00003', 24.00, 'Red', 'M', 'c_skirt_red.png'),
('P10183', 'Shorts & Skirts', 'C00003', 24.00, 'Red', 'L', 'c_skirt_red.png'),
('P10184', 'Shorts & Skirts', 'C00003', 15.00, 'Brown', 'S', 'c_shorts_brown.png'),
('P10185', 'Shorts & Skirts', 'C00003', 15.00, 'Brown', 'M', 'c_shorts_brown.png'),
('P10186', 'Shorts & Skirts', 'C00003', 15.00, 'Brown', 'L', 'c_shorts_brown.png'),
('P10187', 'Shorts & Skirts', 'C00003', 15.00, 'Blue', 'S', 'c_shorts_blue.png'),
('P10188', 'Shorts & Skirts', 'C00003', 15.00, 'Blue', 'M', 'c_shorts_blue.png'),
('P10189', 'Shorts & Skirts', 'C00003', 15.00, 'Blue', 'L', 'c_shorts_blue.png'),
('P10190', 'Wallet', 'C00004', 35.00, 'Black', 'NULL', 'a_wallet_black.png'),
('P10191', 'Wallet', 'C00004', 35.00, 'Blue', 'NULL', 'a_wallet_blue.png'),
('P10192', 'Sunglasses', 'C00004', 85.00, 'Black', 'NULL', 'a_sunglasses_black.png'),
('P10193', 'Sunglasses', 'C00004', 85.00, 'Beige', 'NULL', 'a_sunglasses_beige.png'),
('P10194', 'Backpacks', 'C00004', 65.00, 'Black', 'NULL', 'a_backpack_black.png'),
('P10195', 'Backpacks', 'C00004', 65.00, 'Blue', 'NULL', 'a_backpack_blue.png'),
('P10196', 'Cap', 'C00004', 25.00, 'Navy NY', 'M', 'a_caps_ny.png'),
('P10197', 'Cap', 'C00004', 25.00, 'Navy NY', 'L', 'a_caps_ny.png'),
('P10198', 'Cap', 'C00004', 22.00, 'Light Blue', 'M', 'a_caps_blue.png'),
('P10199', 'Cap', 'C00004', 22.00, 'Light Blue', 'L', 'a_caps_blue.png'),
('P10200', 'Shoes', 'C00004', 95.00, 'Black', 'S', 'a_shoes_black.png'),
('P10201', 'Shoes', 'C00004', 95.00, 'Black', 'M', 'a_shoes_black.png'),
('P10202', 'Shoes', 'C00004', 95.00, 'Black', 'L', 'a_shoes_black.png'),
('P10203', 'Shoes', 'C00004', 88.00, 'Brown', 'S', 'a_shoes_brown.png'),
('P10204', 'Shoes', 'C00004', 88.00, 'Brown', 'M', 'a_shoes_brown.png'),
('P10205', 'Shoes', 'C00004', 88.00, 'Brown', 'L', 'a_shoes_brown.png');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` varchar(10) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
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

INSERT INTO `user` (`user_id`, `user_name`, `password`, `email`, `password_hash`, `failed_attempts`, `locked_until`, `photo`, `role`) VALUES
('U00001', 'liam_koh', '123456', 'liam.koh@gmail.com', NULL, 3, '2026-04-09 16:09:24', NULL, 'member'),
('U00002', 'noah_lim', '123456', 'noah.lim@gmail.com', NULL, 1, NULL, NULL, 'member'),
('U00003', 'ava_teo', '123456', 'ava.teo@gmail.com', NULL, 2, NULL, NULL, 'member'),
('U00004', 'lucas_yap', '123456', 'lucas.yap@gmail.com', NULL, 0, NULL, NULL, 'member'),
('U00005', 'mia_goh', '123456', 'mia.goh@gmail.com', NULL, 0, NULL, NULL, 'member'),
('U00006', 'benjamin_ong', '123456', 'benjamin.ong@gmail.com', NULL, 0, NULL, NULL, 'member'),
('U00007', 'ella_tay', '123456', 'ella.tay@gmail.com', NULL, 0, NULL, NULL, 'member'),
('U00008', 'jacob_foo', '123456', 'jacob.foo@gmail.com', NULL, 0, NULL, NULL, 'member'),
('U00009', 'chloe_yeo', '123456', 'chloe.yeo@gmail.com', NULL, 0, NULL, NULL, 'member'),
('U00010', 'aaron_chin', '123456', 'aaron.chin@gmail.com', NULL, 0, NULL, NULL, 'member'),
('U90001', 'ethan_lee', '654321', 'ethan.lee@gmail.com', NULL, 0, NULL, NULL, 'admin'),
('U90002', 'sophia_tan', '654321', 'sophia.tan@gmail.com', NULL, 0, NULL, NULL, 'admin'),
('U90003', 'daniel_wong', '654321', 'daniel.wong@gmail.com', NULL, 0, NULL, NULL, 'admin'),
('U90004', 'amelia_ng', '654321', 'amelia.ng@gmail.com', NULL, 0, NULL, NULL, 'admin'),
('U90005', 'ryan_choo', '654321', 'ryan.choo@gmail.com', NULL, 0, NULL, NULL, 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `cat_id` (`cat_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`),
  ADD CONSTRAINT `item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `categories` (`cat_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
