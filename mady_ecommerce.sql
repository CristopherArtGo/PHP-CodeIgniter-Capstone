-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jun 06, 2024 at 01:45 PM
-- Server version: 5.7.39
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mady_ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category`, `created_at`, `updated_at`) VALUES
(1, 'Cookies', '2024-02-20 16:37:22', '2024-02-20 16:37:20'),
(2, 'Brownies', '2024-02-20 18:26:26', '2024-02-20 18:26:26'),
(3, 'Bread', '2024-02-20 18:40:58', '2024-02-20 18:40:59'),
(4, 'Cakes', '2024-02-20 18:41:05', '2024-02-20 18:41:04'),
(5, 'Pastries', '2024-02-20 18:41:07', '2024-02-20 18:41:06');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT '1',
  `receiver` varchar(225) DEFAULT NULL,
  `shipping_address` text,
  `same_billing_address` tinyint(1) DEFAULT '1',
  `billing_address` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status_id`, `receiver`, `shipping_address`, `same_billing_address`, `billing_address`, `created_at`, `updated_at`) VALUES
(4, 2, 7, 3, 'John Doe', '519 S. Durham Ave.\nLos Angeles, CA 90042', 1, NULL, '2024-02-25 19:11:56', '2024-02-25 23:45:18'),
(6, 2, 19, 1, 'John Doe', '13 Richardson Court\nAntioch, CA 94509', 1, NULL, '2024-02-25 23:26:31', '2024-02-26 01:02:25'),
(9, 2, 105, 2, 'John Doe', '886 Glendale Dr.\nLake Forest, CA 92630', 1, NULL, '2024-02-25 23:38:46', '2024-02-26 01:02:20'),
(10, 5, 230, 1, 'John Doe', '26160 Kozey Lakes, N/A, Clairshire, Iowa, 59073', 1, NULL, '2024-02-26 02:04:34', '2024-02-26 02:05:31'),
(11, 6, 63, 2, 'John Doe', '26160 Kozey Lakes, N/A, Clairshire, Iowa, 59073', 1, NULL, '2024-02-26 02:17:58', '2024-02-26 02:19:02'),
(12, 7, 53, 2, 'John Doe', '26160 Kozey Lakes, N/A, Clairshire, Iowa, 59073', 1, NULL, '2024-02-26 02:27:58', '2024-02-26 02:29:14'),
(13, 8, 44, 2, 'John Doe', '26160 Kozey Lakes, N/A, Clairshire, Iowa, 59073', 1, NULL, '2024-02-26 02:37:25', '2024-02-26 02:38:30');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 2, '2024-02-25 19:11:56', '2024-02-25 19:11:56'),
(6, 6, 6, 1, '2024-02-25 23:26:31', '2024-02-25 23:26:31'),
(7, 6, 12, 1, '2024-02-25 23:26:31', '2024-02-25 23:26:31'),
(8, 6, 13, 1, '2024-02-25 23:26:31', '2024-02-25 23:26:31'),
(11, 9, 1, 100, '2024-02-25 23:38:46', '2024-02-25 23:38:46'),
(12, 10, 3, 50, '2024-02-26 02:04:34', '2024-02-26 02:04:34'),
(13, 10, 10, 20, '2024-02-26 02:04:34', '2024-02-26 02:04:34'),
(14, 10, 11, 10, '2024-02-26 02:04:34', '2024-02-26 02:04:34'),
(15, 11, 1, 8, '2024-02-26 02:17:58', '2024-02-26 02:17:58'),
(16, 11, 4, 10, '2024-02-26 02:17:58', '2024-02-26 02:17:58'),
(17, 11, 12, 10, '2024-02-26 02:17:58', '2024-02-26 02:17:58'),
(18, 12, 4, 14, '2024-02-26 02:27:58', '2024-02-26 02:27:58'),
(19, 12, 10, 10, '2024-02-26 02:27:58', '2024-02-26 02:27:58'),
(20, 12, 9, 1, '2024-02-26 02:27:58', '2024-02-26 02:27:58'),
(21, 13, 4, 9, '2024-02-26 02:37:25', '2024-02-26 02:37:25'),
(22, 13, 10, 10, '2024-02-26 02:37:25', '2024-02-26 02:37:25');

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

CREATE TABLE `order_status` (
  `status_id` int(11) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_status`
--

INSERT INTO `order_status` (`status_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Pending', '2024-02-25 23:57:04', '2024-02-25 23:57:05'),
(2, 'On-Process', '2024-02-25 23:57:04', '2024-02-25 23:57:05'),
(3, 'Shipped', '2024-02-25 23:57:04', '2024-02-25 23:57:05'),
(4, 'Delivered', '2024-02-25 23:57:04', '2024-02-25 23:57:05');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` decimal(5,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `sold` int(11) DEFAULT '0',
  `category_id` int(11) DEFAULT NULL,
  `description` text,
  `images` json DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `stock`, `sold`, `category_id`, `description`, `images`, `created_at`, `updated_at`) VALUES
(1, 'Chocolate Chip', '2.00', 100, 8, 1, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum libero nulla recusandae! Dolores mollitia quod illum inventore', '{\"1\": \"IMG_4251.jpg\", \"2\": \"IMG_5903.jpg\", \"3\": \"IMG_0243.JPG\", \"4\": \"IMG_4287.jpeg\"}', '2024-02-19 08:43:38', '2024-02-26 02:40:07'),
(2, 'Chocolate Walnut', '2.00', 100, 0, 1, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum libero nulla recusandae! Dolores mollitia quod illum inventore', '{\"1\": \"IMG_4247.jpg\", \"2\": \"IMG_1790.jpg\", \"3\": \"IMG_4287.jpg\", \"4\": \"IMG_5903.jpg\"}', '2024-02-20 16:49:49', '2024-02-20 16:49:47'),
(3, 'Choco Hazelnut', '2.50', 0, 50, 1, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum libero nulla recusandae! Dolores mollitia quod illum inventore', '{\"1\": \"IMG_4249.jpg\", \"2\": \"IMG_3638.jpg\", \"3\": \"IMG_4056.jpg\", \"4\": \"IMG_4287.jpg\"}', '2024-02-20 17:00:21', '2024-02-26 02:04:34'),
(4, 'Triple Chocolate', '1.50', 0, 9, 1, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum libero nulla recusandae! Dolores mollitia quod illum inventore', '{\"1\": \"IMG_4248.jpg\", \"2\": \"IMG_4287.jpg\", \"3\": \"IMG_5903.jpg\", \"4\": \"IMG_4056.jpg\"}', '2024-02-20 17:01:15', '2024-02-26 02:37:25'),
(5, 'Cranberry Oatmeal', '2.00', 100, 0, 1, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum libero nulla recusandae! Dolores mollitia quod illum inventore', '{\"1\": \"IMG_7002.jpg\", \"2\": \"IMG_1800.jpg\"}', '2024-02-20 17:02:23', '2024-02-20 17:02:24'),
(6, 'Ube Cheesecake', '3.00', 100, 0, 1, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum libero nulla recusandae! Dolores mollitia quod illum inventore', '{\"1\": \"IMG_7014.jpg\", \"2\": \"IMG_1826.jpg\"}', '2024-02-20 17:02:51', '2024-02-20 17:02:52'),
(7, 'White Choco Biscoff', '3.00', 100, 0, 1, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum libero nulla recusandae! Dolores mollitia quod illum inventore', '{\"1\": \"IMG_5379.jpg\", \"2\": \"IMG_5903.jpg\", \"3\": \"IMG_0243.jpg\"}', '2024-02-20 17:03:40', '2024-02-20 17:03:41'),
(8, 'Red Velvet', '2.00', 100, 0, 1, 'Red Velvet Cookie', '{\"1\": \"IMG_8600.jpg\"}', '2024-02-24 21:20:33', '2024-02-24 21:20:33'),
(9, 'Blondie', '2.00', 0, 1, 2, 'Vanilla Brownie', '{\"1\": \"blondie1.jpeg\"}', '2024-02-24 21:22:03', '2024-02-26 02:27:58'),
(10, 'Chocolate Brownie', '2.50', 0, 10, 2, 'Classic Chocolate Fudge Brownie', '{\"1\": \"Brownie1.jpeg\"}', '2024-02-24 21:24:49', '2024-02-26 02:37:25'),
(11, 'Baguette', '5.00', 0, 10, 3, 'French Baguette', '{\"1\": \"baguette.jpeg\"}', '2024-02-24 21:26:09', '2024-02-26 02:04:34'),
(12, 'Blueberry Cheesecake', '3.50', 0, 10, 4, 'Blueberry Cheesecake', '{\"1\": \"blueberry_cheesecake.jpeg\"}', '2024-02-24 21:28:29', '2024-02-26 02:17:58'),
(13, 'Burnt Basque', '7.50', 101, 0, 4, 'Burnt Basque Cheesecake', '{\"1\": \"burnt_basque_cheesecake.jpeg\"}', '2024-02-24 21:29:15', '2024-02-25 23:48:59'),
(15, 'Croissant', '5.00', 100, 0, 5, 'Croissant', '{\"1\": \"croissant2.jpeg\", \"2\": \"croissant1.jpeg\"}', '2024-02-25 23:49:50', '2024-02-25 23:54:21'),
(16, 'Garlic Bread', '3.00', 100, 0, 3, 'Garlic Bread', '{\"1\": \"garlic_bread.jpeg\"}', '2024-02-26 01:04:20', '2024-02-26 01:04:20'),
(17, 'Focaccia', '4.00', 100, 0, 3, 'Focaccia', '{\"1\": \"focaccia.jpeg\"}', '2024-02-26 01:04:44', '2024-02-26 01:05:55');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `order_item_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `content` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `salt` varchar(225) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `salt`, `created_at`, `updated_at`, `is_admin`) VALUES
(2, 'Cristopher Art', 'Go', 'go.cristopher@gmail.com', '25d55ad283aa400af464c76d713c07ad', '62db8a7934a1d4427d0d4808afb08cac8b1957c61eb3', '2024-02-20 14:37:36', '2024-02-20 14:37:36', 1),
(3, 'John', 'Doe', 'john.doe@example.com', '25d55ad283aa400af464c76d713c07ad', '1c69626003573e9e44f92d91ab03c299a4cb1d32d80f', '2024-02-21 14:06:17', '2024-02-21 14:06:17', 0),
(4, 'test', 'test', 'test2@email.com', '25d55ad283aa400af464c76d713c07ad', '41724bd841b8b66dc56b039cae1a9e33e2fc9b9679a2', '2024-02-24 23:48:30', '2024-02-24 23:48:30', 0),
(5, 'test', 'test', 'test1@email.com', '25d55ad283aa400af464c76d713c07ad', 'bb3edf9a09e33838581a90709b86147508446f67a133', '2024-02-26 02:01:08', '2024-02-26 02:01:08', 0),
(6, 'test', 'test', 'test3@email.com', '25d55ad283aa400af464c76d713c07ad', '8ca914835a516f2e671e68bacb5caf276668f2e580e8', '2024-02-26 02:14:01', '2024-02-26 02:14:01', 0),
(7, 'test', 'test', 'test@email.com', '25d55ad283aa400af464c76d713c07ad', '0cc81f0f3e078b225b7eb59f6112cc9bd4aa8f4911ab', '2024-02-26 02:24:48', '2024-02-26 02:24:48', 0),
(8, 'test', 'test', 'test5@email.com', '25d55ad283aa400af464c76d713c07ad', '423dc240637749a315568f954dd68d82439f48e19dc5', '2024-02-26 02:33:39', '2024-02-26 02:33:39', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_items_products_id_fk` (`product_id`),
  ADD KEY `cart_items_users_id_fk` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_users_id_fk` (`user_id`),
  ADD KEY `orders_order_status_id_fk` (`status_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_products_id_fk` (`product_id`),
  ADD KEY `orders___fk` (`order_id`);

--
-- Indexes for table `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products___fk` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_order_items_id_fk` (`order_item_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `order_status`
--
ALTER TABLE `order_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_products_id_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `cart_items_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_order_status_id_fk` FOREIGN KEY (`status_id`) REFERENCES `order_status` (`status_id`),
  ADD CONSTRAINT `transactions_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `orders___fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `orders_products_id_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products___fk` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_order_items_id_fk` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
