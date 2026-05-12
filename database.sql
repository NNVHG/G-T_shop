-- Tạo cơ sở dữ liệu nếu chưa tồn tại và sử dụng nó
CREATE DATABASE IF NOT EXISTS gtbookstore_db DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gtbookstore_db;

-- 1. Bảng Users (Người dùng)
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` tinyint(1) DEFAULT 0 COMMENT '0: Khách hàng, 1: Quản trị viên',
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Bảng Categories (Danh mục sản phẩm)
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `status` tinyint(1) DEFAULT 1 COMMENT '1: Hiển thị, 0: Ẩn',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Bảng Products (Sản phẩm)
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` int(11) NOT NULL,
  `image` varchar(255) DEFAULT 'default.jpg',
  `stock_quantity` int(11) DEFAULT 0,
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Bảng Orders (Đơn hàng)
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total_amount` int(11) NOT NULL,
  `shipping_address` text NOT NULL,
  `status` tinyint(1) DEFAULT 0 COMMENT '0: Chờ xử lý, 1: Đang giao, 2: Đã giao, 3: Đã hủy',
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Bảng Order_Details (Chi tiết đơn hàng)
CREATE TABLE `order_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL COMMENT 'Giá tại thời điểm mua',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_detail_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_detail_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Thêm danh mục mẫu
INSERT INTO `categories` (`id`, `name`, `slug`, `status`) VALUES
(1, 'Sách Học Thuật', 'sach-hoc-thuat', 1),
(2, 'Văn Phòng Phẩm', 'van-phong-pham', 1),
(3, 'Đồ Chơi Giáo Dục', 'do-choi-giao-duc', 1);

-- Thêm sản phẩm mẫu
INSERT INTO `products` (`id`, `category_id`, `title`, `slug`, `description`, `price`, `image`, `stock_quantity`) VALUES
(1, 1, 'Lập Trình PHP & MySQL Cơ Bản', 'lap-trinh-php-mysql', 'Sách hướng dẫn lập trình web cơ bản.', 150000, 'php-book.jpg', 50),
(2, 2, 'Bút Bi Thiên Long', 'but-bi-thien-long', 'Bút mực xanh, viết êm.', 5000, 'but-bi.jpg', 500),
(3, 2, 'Sổ Tay Sinh Viên', 'so-tay-sinh-vien', 'Sổ tay bìa da cao cấp.', 45000, 'so-tay.jpg', 100),
(4, 3, 'Khối Rubik 3x3', 'khoi-rubik-3x3', 'Đồ chơi phát triển trí tuệ.', 35000, 'rubik.jpg', 30);

-- Thêm tài khoản Admin (Mật khẩu: 123456 - Cần mã hóa MD5/Bcrypt trong thực tế)
INSERT INTO `users` (`fullname`, `email`, `password`, `phone`, `role`) VALUES
('Quản Trị Viên', 'admin@gtshop.com', '123456', '0987654321', 1),
('Khách Hàng', 'khachhang@gmail.com', '123456', '0123456789', 0);