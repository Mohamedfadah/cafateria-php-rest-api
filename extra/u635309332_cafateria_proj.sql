-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 18, 2022 at 04:54 PM
-- Server version: 10.5.12-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u635309332_cafateria_proj`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(2, 'hot drinks'),
(3, 'juice');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar` varchar(500) NOT NULL,
  `role` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`id`, `name`, `pass`, `email`, `avatar`, `role`) VALUES
(1, 'Admin', 'admin', 'admin@gmail.com', '', 1),
(2, 'User 1', 'user1', 'user1@gmail.com', 'http://localhost:8080/cafateria-php/storage/client_avatar/1649845685-5.jpg', 0),
(3, 'arwa', '1234', 'arwa@mail.com', 'http://localhost:8080/Cafetria/storage/client_avatar/1649967356-come_galletas-wallpaper-1366x768.jpg', 0),
(7, 'mohamed', '1234', 'mohamed@gmail.com', 'http://localhost:8080/Cafetria/storage/client_avatar/1649964217-avatar.jpg', 0),
(30, 'wael', '1234', 'wael@gmail.com', 'http://localhost:8080/Cafetria/storage/client_avatar/avatar.jpg', 0),
(31, 'ahmed', '1234', 'ahmed@gmail.com', 'http://localhost:8080/Cafetria/storage/client_avatar/avatar.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 0,
  `price` int(11) NOT NULL,
  `note` text DEFAULT NULL,
  `customer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `date`, `status`, `price`, `note`, `customer_id`) VALUES
(26, '2022-04-15 18:50:27', 0, 120, '', 1),
(27, '2022-04-15 18:52:01', 0, 120, '', 1),
(28, '2022-04-15 18:55:07', 0, 120, '', 1),
(29, '2022-04-15 18:57:13', 0, 120, '', 1),
(30, '2022-04-15 18:58:09', 0, 120, '', 1),
(31, '2022-04-15 18:59:21', 0, 120, '', 1),
(32, '2022-04-15 18:59:34', 0, 120, '', 1),
(33, '2022-04-15 19:00:22', 0, 120, '', 1),
(34, '2022-04-15 19:04:58', 0, 120, '', 1),
(35, '2022-04-15 19:07:52', 0, 120, '', 1),
(36, '2022-04-15 19:08:46', 0, 120, '', 1),
(37, '2022-04-15 19:09:18', 0, 120, '', 1),
(38, '2022-04-15 19:09:40', 0, 120, '', 1),
(39, '2022-04-15 19:10:27', 0, 120, '', 1),
(40, '2022-04-18 09:23:49', 0, 120, '', 1),
(41, '2022-04-15 20:55:44', 0, 120, '', 1),
(43, '2022-04-17 17:29:49', 0, 120, 'test', 3),
(44, '2022-04-17 22:14:27', 0, 30, 'Hello', 3),
(45, '2022-04-18 09:26:20', 1, 5, '', 3),
(46, '2022-04-18 11:08:22', 0, 5, '', 3),
(47, '2022-04-18 11:16:59', 0, 5, '', 30);

-- --------------------------------------------------------

--
-- Table structure for table `orders_product`
--

CREATE TABLE `orders_product` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders_product`
--

INSERT INTO `orders_product` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(78, 40, 8, 5, 20),
(79, 40, 2, 5, 20),
(80, 40, 9, 5, 20),
(81, 41, 8, 5, 20),
(82, 41, 2, 5, 20),
(83, 41, 9, 5, 20),
(87, 43, 8, 5, 20),
(88, 43, 2, 5, 20),
(89, 43, 9, 5, 20),
(90, 44, 8, 1, 20),
(91, 44, 2, 2, 5),
(92, 45, 2, 1, 5),
(93, 46, 2, 1, 5),
(94, 47, 2, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `cat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `price`, `status`, `avatar`, `cat_id`) VALUES
(2, 'tea', 5, 0, 'https://images.pexels.com/photos/90946/pexels-photo-90946.jpeg?cs=srgb&dl=pexels-math-90946.jpg&fm=jpg', 2),
(8, 'nescafee', 20, 1, 'https://images.pexels.com/photos/90946/pexels-photo-90946.jpeg?cs=srgb&dl=pexels-math-90946.jpg&fm=jpg', 2),
(9, 'banana', 19, 1, 'http://localhost:80/c/v3/storage/product_avatar/1650299066-come_galletas-wallpaper-1366x768.jpg', 2),
(10, 'water', 11, 1, 'http://localhost:8080/Cafetria/storage/product_avatar/avatar.jpg', 2),
(11, 'ko7ol', 27, 1, 'http://localhost:80/c/v3/storage/product_avatar/1650295561-come_galletas-wallpaper-1366x768.jpg', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `orders_product`
--
ALTER TABLE `orders_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cat_id` (`cat_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `orders_product`
--
ALTER TABLE `orders_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `client` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders_product`
--
ALTER TABLE `orders_product`
  ADD CONSTRAINT `orders_product_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_product_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
