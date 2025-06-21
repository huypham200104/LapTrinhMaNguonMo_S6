-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for my_store_huy
CREATE DATABASE IF NOT EXISTS `my_store_huy` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `my_store_huy`;

-- Dumping structure for table my_store_huy.account
CREATE TABLE IF NOT EXISTS `account` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table my_store_huy.account: ~1 rows (approximately)
INSERT INTO `account` (`id`, `username`, `fullname`, `email`, `phone`, `avatar`, `password`, `role`) VALUES
	(2, 'admin', 'admin3', 'admin@gmail.com', '0123456789', 'admin_1749175831.jpg', '$2y$10$XlcVuPqdmwsosanvAilhIuQ9cKp5xg.NZaFRj.3UfxGuLAhewNLp6', 'admin');

-- Dumping structure for table my_store_huy.category
CREATE TABLE IF NOT EXISTS `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table my_store_huy.category: ~4 rows (approximately)
INSERT INTO `category` (`id`, `name`, `description`) VALUES
	(1, 'Khoa học lập trình', 'Tập trung vàcategoryo các nguyên lý, phương pháp và công cụ để phát triển phần mềm và hệ thống máy tính.'),
	(2, 'Trí tuệ nhân tạo', 'Nghiên cứu về cách làm cho máy tính có thể suy nghĩ và học hỏi như con người.'),
	(4, 'An toàn thông tin', 'Bảo vệ dữ liệu và hệ thống khỏi các mối đe dọa và tấn công mạng.'),
	(6, 'Lập trình IOT', 'IOT');

-- Dumping structure for table my_store_huy.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `payment_method` enum('VNpay','Momo','Ngân hàng','Tiền mặt') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table my_store_huy.orders: ~8 rows (approximately)
INSERT INTO `orders` (`id`, `name`, `email`, `phone`, `address`, `payment_method`, `created_at`) VALUES
	(1, 'Huy', 'us1@gmail.com', '0123456789', 'abc xyz', 'Tiền mặt', '2025-05-30 01:58:28'),
	(2, 'Huy', 'us1@gmail.com', '0123456789', 'abc', 'VNpay', '2025-05-30 02:34:38'),
	(3, 'Huy', 'cus1@gmail.com', '0123456789', 'abc', 'Momo', '2025-05-30 02:49:45'),
	(4, 'Huy', 'cus1@gmail.com', '0123456789', 'abc', 'VNpay', '2025-05-30 03:01:47'),
	(5, 'Huy', 'cus1@gmail.com', '0123456789', 'abc', 'Ngân hàng', '2025-05-30 03:05:02'),
	(6, 'Huy', 'cus1@gmail.com', '0123456789', '213213', 'Momo', '2025-05-30 03:54:21'),
	(7, 'Huy', 'cus1@gmail.com', '0123456789', 'áddsdfsdsfdsf', 'Ngân hàng', '2025-05-30 03:54:46'),
	(8, 'Huy', 'cus1@gmail.com', '0123456789', 'abc', 'VNpay', '2025-05-30 04:11:10');

-- Dumping structure for table my_store_huy.order_details
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table my_store_huy.order_details: ~13 rows (approximately)
INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
	(1, 1, 2, 8, 150000.00),
	(2, 2, 2, 1, 150000.00),
	(3, 2, 3, 2, 100000.00),
	(4, 3, 2, 4, 150000.00),
	(5, 4, 3, 7, 100000.00),
	(6, 4, 4, 2, 75000.00),
	(7, 5, 7, 1, 135000.00),
	(8, 5, 5, 4, 1200000.00),
	(9, 6, 2, 1, 150000.00),
	(10, 7, 3, 5, 500000.00),
	(11, 7, 4, 2, 150000.00),
	(12, 7, 7, 2, 270000.00),
	(13, 8, 2, 2, 150000.00);

-- Dumping structure for table my_store_huy.product
CREATE TABLE IF NOT EXISTS `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table my_store_huy.product: ~6 rows (approximately)
INSERT INTO `product` (`id`, `name`, `description`, `price`, `image`, `category_id`) VALUES
	(2, 'AI', 'Machine learning', 150000.00, 'public/images/ai.jpg', 2),
	(3, 'C++', 'Lập trình c++', 100000.00, 'public/images/c.jpg', 1),
	(4, 'Androi', 'Lập trình androi', 75000.00, 'public/images/androi.jpg', 1),
	(5, 'Deep learning ', 'Deep learning basic', 300000.00, 'public/images/deeplearning.jpg', 1),
	(6, 'Java', 'Java basic', 180000.00, 'public/images/java.jpg', 1),
	(7, 'Hacking', 'Hacking for newbie', 135000.00, 'public/images/hacking.jpg', 4);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
