-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 27, 2025 lúc 08:58 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `store`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `blogs`
--

CREATE TABLE `blogs` (
  `blog_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `blogs`
--

INSERT INTO `blogs` (`blog_id`, `title`, `content`, `author`, `image`) VALUES
(1, 'Fresh Eats Blog', 'Welcome to Fresh Eats...', 'Admin', 'images/blog1.png'),
(2, 'Wellness Tips', 'Dive into wellness tips...', 'Admin', 'images/blog2.png');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `product_id`, `quantity`, `added_at`) VALUES
(1, 2, 1, 2, '2025-05-28 13:29:47'),
(2, 2, 3, 1, '2025-05-28 13:29:47'),
(3, 3, 2, 5, '2025-05-28 13:29:47');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`category_id`, `name`) VALUES
(1, 'Vegetables'),
(2, 'Fish'),
(3, 'Meat'),
(4, 'Beauty'),
(5, 'Gardening');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `address` text DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `total_amount`, `status`, `address`, `phone`, `payment_method`) VALUES
(1, 2, '2025-06-08 14:41:07', 20.00, 'Đang xử lý', 'Long xuyên', '0354644361', 'cash'),
(2, 4, '2025-06-08 15:00:42', 10.00, 'Đang xử lý', 'AG', '01245689', 'cash'),
(3, 2, '2025-06-15 13:17:36', 60.00, 'Đang xử lý', 'AG', '0354644361', 'cash'),
(4, 2, '2025-09-05 15:57:24', 20.00, 'Đang xử lý', 'AG', '0354644361', 'cash'),
(5, 2, '2025-09-06 03:27:40', 20.00, 'Hoàn thành', 'AG', '0354644361', 'cash'),
(6, 2, '2025-09-06 07:09:53', 14.75, 'Hoàn thành', 'AG', '0354644361', 'cash'),
(7, 1, '2025-09-06 07:12:00', 10.00, 'Đang xử lý', 'AG', '0354644361', 'cash'),
(8, 1, '2025-11-26 10:17:21', 10.00, 'Hoàn thành', 'Bình Khánh 4 Bình Khánh Long Xuyên An Giang', '0354644361', 'cash');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`order_detail_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 12, 1, 10.00),
(2, 1, 2, 1, 10.00),
(3, 2, 2, 1, 10.00),
(4, 3, 23, 2, 15.00),
(5, 3, 2, 3, 10.00),
(6, 4, 19, 2, 10.00),
(7, 5, 12, 2, 10.00),
(8, 6, 20, 1, 5.00),
(9, 6, 15, 1, 9.75),
(10, 7, 16, 1, 10.00),
(11, 8, 17, 1, 10.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`product_id`, `name`, `price`, `image`, `category_id`, `description`) VALUES
(1, 'Tomato', 10.00, 'images/p2.png', 1, 'Fresh red tomatoes.'),
(2, 'Broccoli', 10.00, 'images/p3.png', 1, 'Green and fresh.'),
(3, 'Mixed Fish Selection', 10.00, 'images/f1.png', 2, 'Fresh fish combo.'),
(4, 'Beef Cubes', 10.00, 'images/m1.png', 3, 'Tender beef cubes.'),
(5, 'Green Cabbage', 10.00, 'images/p5.png', 1, 'Organic cabbage.'),
(6, 'Vegetables', 10.00, 'images/p1.png', 1, 'Fresh mixed vegetables.'),
(9, 'Carrot', 10.00, 'images/p4.png', 1, 'Organic carrots.'),
(11, 'Green Leaf Lettuce', 10.00, 'images/p6.png', 1, 'Crispy green leaf lettuce.'),
(12, 'Salat', 10.00, 'images/p7.png', 1, 'Organic salad greens.'),
(13, 'WaterMelon', 10.00, 'images/p8.png', 1, 'Juicy watermelon.'),
(15, 'Mixed Seafood Selection', 9.75, 'images/f2.png', 2, 'Assorted seafood.'),
(16, 'Sea Bream', 10.00, 'images/f3.png', 2, 'Fresh sea bream.'),
(17, 'Yellowtail and Snapper', 10.00, 'images/f4.png', 2, 'Yellowtail and snapper combo.'),
(19, 'Lamb Chops', 10.00, 'images/m2.png', 3, 'Juicy lamb chops.'),
(20, 'Rack of Lamb', 5.00, 'images/m3.png', 3, 'Full rack of lamb.'),
(21, 'Beef Tenderloin', 9.80, 'images/m4.png', 3, 'Premium tenderloin beef.'),
(22, 'Seafood Big Pack', 10.00, 'images/pack1.jpg', 4, 'Seafood combo pack.'),
(23, 'Vegetable Big Pack', 15.00, 'images/pack2.jpg', 4, 'Vegetables combo pack.'),
(24, 'Fruit & Veggie Big Pack', 10.00, 'images/pack3.png', 4, 'Fruits and vegetables pack.'),
(37, 'Ambi Cleansing Bar Soap Cocoa Butter 3.5oz', 2.51, 'images/products/prod_6928022e2fcc4.jpg', 4, NULL),
(39, 'Dr Teals Body Wash Melatonin Lavender & Chamomile 24oz', 10.00, 'images/products/prod_69280218f0819.jpg', 4, NULL),
(40, 'Dearcloud Travel Size Sunny Defense Sunscreen Stick SPF 50+, 1 Ea', 13.28, 'images/products/prod_6928027535d41.jpg', 4, NULL),
(41, 'Kids Garden Hoe', 6.39, 'images/products/prod_6928037b84eb9.jpg', 5, 'Kids Garden Hoe - Description\nKids Garden Hoe\nA Real Garden Tool For Children\nPart of Our Junior Garden Tools Range\nSturdy & Durable\nHoe & Soil Cultivator\nColour: Green\nStrength: Medium Strength Tool\nMaterial\nGreen Head: Metal\nAll Handles: Wooden\nLightweight Garden Tool For Kids\nHead Size (WxL): 9 x 12cm\nOverall Length: 92cm'),
(42, 'Oleo Mac® Rotavator', 735.00, 'images/products/prod_692803bb36008.jpg', 5, 'Rotavator - Description\nCompact Rotary Tiller\nManufacturer: Oleo Mac\nSeries: MH 175 RKS\nEngine: Emak K 800 HT OHV\n4-stroke engine\nHorsepower: 6HP\nColour: Orange / Silver / Black\nDisplacement: 182cm3\nGearbox: 1 Forward Speed & 1 Reverse\nRotor: 3+3 blades - 82 cm reducible to 55 cm\nHandlebars: Fully Adjustable\nReinforced Transmission\nDimension: 145 x 100 x 50-80cm\nWeight: 57Kg'),
(43, 'Ultimate Kids Gardening Tool Set', 89.00, 'images/products/prod_692803e86df82.jpg', 5, 'Kids Gardening Tool Set - Description\nComplete Kids Gardening Tool Set\nFor Kids Who Love Gardening\nFor Parents Who Would Like to Introduce Kids to Gardening\nColours\nTools: Mix of Colours\nWheelbarrow: Green\nKit Includes\nHand Trowel\nHand Shovel\nHand Fork\nKids Garden Shovel\nKids Soil Rake\nKids Leaf Rake\nKids Hoe\nKids Apron\nKids Watering Can\nKids Wheelbarrow\nKids Gardening Gloves\nSuits Ages: 3 to 10 Years');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `report_date` date DEFAULT NULL,
  `total_orders` int(11) DEFAULT NULL,
  `total_revenue` decimal(10,2) DEFAULT NULL,
  `top_selling_product` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `reports`
--

INSERT INTO `reports` (`report_id`, `report_date`, `total_orders`, `total_revenue`, `top_selling_product`) VALUES
(1, '2025-05-28', 10, 120.00, 'Tomato'),
(2, '2025-05-27', 8, 95.00, 'Broccoli');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id`, `user_name`, `content`, `created_at`) VALUES
(2, 'Admin', 'Good job', '2025-06-15 14:23:34'),
(4, 'TRIET', 'trang web tốt', '2025-09-06 07:11:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `full_name`, `is_admin`) VALUES
(1, 'admin@gmail.com', '$2y$10$a7dOdKL7vWqZCj1Zb.Qs5ecqgmvq.LikWwT4urKWiX81zbcd8unoq', 'Admin User', 1),
(2, 'khach@gmail.com', '$2y$10$qgiBfhNqWv/LzGIEimHTNOfSl4wiG4TLWINInK3cHzttiiVnKGj32', 'Nguyen Van A', 0),
(3, 'user2@example.com', 'hashed_password', 'Tran Thi B', 0),
(4, 'sieunhan@gmail.com', '$2y$10$xifXIdSZt863OmEDFx8a/.yXMUbZolM7PcyBKVYNOYBkOD2ZsxNvK', 'Siêu nhân', 0),
(5, 'sieunhan12@gmail.com', '$2y$10$R/RVvXm7YVrloygJdRwMzefzc/89E/VLGhriXLaQFtabmDBd84DXS', 'Siêu nhân', 0);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`blog_id`);

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `blogs`
--
ALTER TABLE `blogs`
  MODIFY `blog_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT cho bảng `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
