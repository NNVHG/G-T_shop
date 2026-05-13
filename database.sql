CREATE TABLE IF NOT EXISTS categories (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100)  NOT NULL,
    slug       VARCHAR(100)  NOT NULL UNIQUE,
    icon       VARCHAR(50)   DEFAULT 'ti-tag',
    sort_order INT           DEFAULT 0,
    created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    category_id  INT           NOT NULL,
    name         VARCHAR(200)  NOT NULL,
    slug         VARCHAR(200)  NOT NULL UNIQUE,
    description  TEXT,
    author       VARCHAR(100),
    price        INT           NOT NULL,
    sale_price   INT           DEFAULT NULL,
    stock        INT           DEFAULT 0,
    image        VARCHAR(255)  DEFAULT 'default.jpg',
    badge        ENUM('','new','hot','sale') DEFAULT '',
    rating       DECIMAL(2,1)  DEFAULT 0.0,
    sold_count   INT           DEFAULT 0,
    is_featured  TINYINT(1)    DEFAULT 0,
    is_active    TINYINT(1)    DEFAULT 1,
    created_at   TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FULLTEXT KEY ft_search (name, description, author)
);

CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100)  NOT NULL,
    email      VARCHAR(150)  NOT NULL UNIQUE,
    password   VARCHAR(255)  NOT NULL,
    phone      VARCHAR(20),
    address    TEXT,
    role       ENUM('user','admin') DEFAULT 'user',
    created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT           DEFAULT NULL,
    customer_name VARCHAR(100) NOT NULL,
    phone        VARCHAR(20)   NOT NULL,
    address      TEXT          NOT NULL,
    total        INT           NOT NULL,
    status       ENUM('pending','processing','shipped','done','cancelled') DEFAULT 'pending',
    note         TEXT,
    created_at   TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS order_items (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    order_id   INT NOT NULL,
    product_id INT NOT NULL,
    qty        INT NOT NULL,
    price      INT NOT NULL,
    FOREIGN KEY (order_id)   REFERENCES orders(id)   ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS banners (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    title      VARCHAR(200),
    subtitle   VARCHAR(200),
    btn_text   VARCHAR(50)  DEFAULT 'Xem ngay',
    btn_link   VARCHAR(255) DEFAULT '#',
    bg_color   VARCHAR(20)  DEFAULT '#2C1A0E',
    is_active  TINYINT(1)   DEFAULT 1,
    sort_order INT          DEFAULT 0
);

ALTER TABLE orders 
ADD COLUMN payment_method VARCHAR(50) DEFAULT 'cod' AFTER status,
ADD COLUMN payment_status ENUM('unpaid', 'paid', 'failed') DEFAULT 'unpaid' AFTER payment_method,
ADD COLUMN transaction_id VARCHAR(100) DEFAULT NULL AFTER payment_status;

INSERT INTO categories (name, slug, icon, sort_order) VALUES
('Sách văn học',    'sach-van-hoc',    'ti-book',              1),
('Truyện tranh',    'truyen-tranh',    'ti-device-gamepad-2',  2),
('Văn phòng phẩm',  'van-phong-pham',  'ti-pencil',            3),
('Đồ ăn vặt',       'do-an-vat',       'ti-candy',             4),
('Nước ngọt',       'nuoc-ngot',       'ti-bottle',            5),
('Đồ chơi',         'do-choi',         'ti-puzzle',            6);

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `author`, `price`, `sale_price`, `stock`, `image`, `badge`, `rating`, `sold_count`, `is_featured`, `is_active`, `created_at`) VALUES
(1, 1, 'Đắc Nhân Tâm', 'dac-nhan-tam', NULL, 'Dale Carnegie', 89000, NULL, 50, 'default.jpg', 'hot', 4.9, 1230, 1, 1, '2026-05-13 05:52:02'),
(2, 1, 'Nhà Giả Kim', 'nha-gia-kim', NULL, 'Paulo Coelho', 75000, 59000, 40, 'default.jpg', 'sale', 4.8, 980, 1, 1, '2026-05-13 05:52:02'),
(3, 1, 'Sapiens: Lược Sử Loài Người', 'sapiens', NULL, 'Yuval N. Harari', 115000, 95000, 30, 'default.jpg', 'sale', 4.7, 750, 1, 1, '2026-05-13 05:52:02'),
(4, 1, 'Tôi Tài Giỏi, Bạn Cũng Thế', 'toi-tai-gioi', NULL, 'Adam Khoo', 79000, NULL, 60, 'default.jpg', '', 4.6, 620, 0, 1, '2026-05-13 05:52:02'),
(5, 2, 'Thám Tử Conan tập 100', 'conan-tap-100', NULL, 'Gosho Aoyama', 25000, NULL, 120, 'default.jpg', 'new', 4.8, 430, 1, 1, '2026-05-13 05:52:02'),
(6, 2, 'Doraemon tập 45', 'doraemon-tap-45', NULL, 'Fujiko F. Fujio', 22000, NULL, 100, 'default.jpg', '', 4.7, 380, 0, 1, '2026-05-13 05:52:02'),
(7, 2, 'Naruto tập 72', 'naruto-tap-72', NULL, 'Masashi Kishimoto', 28000, NULL, 80, 'default.jpg', 'new', 4.9, 510, 1, 1, '2026-05-13 05:52:02'),
(8, 2, 'Dragon Ball tập 42', 'dragon-ball-tap-42', NULL, 'Akira Toriyama', 26000, NULL, 70, 'default.jpg', '', 4.8, 290, 0, 1, '2026-05-13 05:52:02'),
(9, 3, 'Bút bi Thiên Long FO-03', 'but-thien-long', NULL, 'Thiên Long', 32000, NULL, 200, 'default.jpg', '', 4.5, 840, 1, 1, '2026-05-13 05:52:02'),
(10, 3, 'Vở kẻ ngang 200 trang', 'vo-ke-ngang-200', NULL, 'Hồng Hà', 15000, NULL, 300, 'default.jpg', '', 4.4, 920, 0, 1, '2026-05-13 05:52:02'),
(11, 3, 'Tẩy Staedtler Mars', 'tay-staedtler', NULL, 'Staedtler', 12000, NULL, 150, 'default.jpg', 'new', 4.6, 410, 0, 1, '2026-05-13 05:52:02'),
(12, 4, 'Snack Oishi Tôm cay', 'snack-oishi-tom', NULL, 'Oishi', 72000, 58000, 80, 'default.jpg', 'sale', 4.5, 670, 1, 1, '2026-05-13 05:52:02'),
(13, 4, 'Kẹo dẻo gấu Haribo', 'keo-deo-haribo', NULL, 'Haribo', 45000, NULL, 60, 'default.jpg', 'hot', 4.7, 320, 0, 1, '2026-05-13 05:52:02'),
(14, 5, 'Pepsi lon 330ml (thùng)', 'pepsi-lon-330', NULL, 'PepsiCo', 185000, 155000, 40, 'default.jpg', 'sale', 4.6, 210, 1, 1, '2026-05-13 05:52:02'),
(15, 5, 'Trà xanh Không Độ chai', 'tra-xanh-khong-do', NULL, 'Tân Hiệp Phát', 15000, NULL, 150, 'default.jpg', '', 4.4, 480, 0, 1, '2026-05-13 05:52:02'),
(16, 6, 'Rubik 3x3 Moyu', 'rubik-moyu-3x3', NULL, 'Moyu', 85000, NULL, 40, 'default.jpg', 'new', 4.8, 190, 1, 1, '2026-05-13 05:52:02'),
(17, 6, 'Xếp hình 500 mảnh', 'xep-hinh-500', NULL, 'Ravensburger', 120000, 95000, 30, 'default.jpg', 'sale', 4.6, 140, 0, 1, '2026-05-13 05:52:02'),
(18, 1, 'Cây Cam Ngọt Của Tôi', 'cay-cam-ngot-cua-toi', 'Đây là thông tin mô tả chi tiết cho sản phẩm Cây Cam Ngọt Của Tôi. Hàng chính hãng, chất lượng cao, phù hợp cho học sinh và sinh viên.', 'José Mauro de Vasconcelos', 108000, NULL, 50, 'seed_cay-cam-ngot-cua-toi.png', 'hot', 4.5, 439, 0, 1, '2026-05-13 09:56:42'),
(19, 1, 'Không Gia Đình', 'khong-gia-dinh', 'Đây là thông tin mô tả chi tiết cho sản phẩm Không Gia Đình. Hàng chính hãng, chất lượng cao, phù hợp cho học sinh và sinh viên.', 'Hector Malot', 125000, 100000, 40, 'seed_khong-gia-dinh.png', 'sale', 4.6, 228, 0, 1, '2026-05-13 09:56:43'),
(20, 1, 'Hai Số Phận', 'hai-so-phan', 'Đây là thông tin mô tả chi tiết cho sản phẩm Hai Số Phận. Hàng chính hãng, chất lượng cao, phù hợp cho học sinh và sinh viên.', 'Jeffrey Archer', 160000, NULL, 30, 'seed_hai-so-phan.png', 'hot', 4.7, 36, 0, 1, '2026-05-13 09:56:44'),
(21, 1, 'Suối Nguồn', 'suoi-nguon', 'Đây là thông tin mô tả chi tiết cho sản phẩm Suối Nguồn. Hàng chính hãng, chất lượng cao, phù hợp cho học sinh và sinh viên.', 'Ayn Rand', 250000, NULL, 20, 'seed_suoi-nguon.png', 'new', 4.7, 51, 0, 1, '2026-05-13 09:56:45'),
(22, 1, 'Thiên Thần Và Ác Quỷ', 'thien-than-va-ac-quy', 'Đây là thông tin mô tả chi tiết cho sản phẩm Thiên Thần Và Ác Quỷ. Hàng chính hãng, chất lượng cao, phù hợp cho học sinh và sinh viên.', 'Dan Brown', 180000, NULL, 60, 'seed_thien-than-va-ac-quy.png', '', 5.0, 82, 0, 1, '2026-05-13 09:56:46'),
(23, 2, 'One Piece Tập 100', 'one-piece-tap-100', 'Đây là thông tin mô tả chi tiết cho sản phẩm One Piece Tập 100. Hàng chính hãng, chất lượng cao, phù hợp cho học sinh và sinh viên.', 'Eiichiro Oda', 25000, NULL, 100, 'seed_one-piece-tap-100.png', 'new', 4.2, 166, 0, 1, '2026-05-13 09:56:46'),
(24, 2, 'Chú Thuật Hồi Chiến Tập 1', 'chu-thuat-hoi-chien-tap-1', 'Đây là thông tin mô tả chi tiết cho sản phẩm Chú Thuật Hồi Chiến Tập 1. Hàng chính hãng, chất lượng cao, phù hợp cho học sinh và sinh viên.', 'Gege Akutami', 30000, NULL, 80, 'seed_chu-thuat-hoi-chien-tap-1.png', 'hot', 4.4, 299, 0, 1, '2026-05-13 09:56:47'),
(25, 2, 'Thám Tử Conan Tập 101', 'tham-tu-conan-tap-101', 'Đây là thông tin mô tả chi tiết cho sản phẩm Thám Tử Conan Tập 101. Hàng chính hãng, chất lượng cao, phù hợp cho học sinh và sinh viên.', 'Gosho Aoyama', 25000, NULL, 120, 'seed_tham-tu-conan-tap-101.png', '', 4.7, 490, 0, 1, '2026-05-13 09:56:48'),
(26, 3, 'Sổ Tay Bìa Da Cao Cấp', 'so-tay-bia-da-cao-cap', 'Đây là thông tin mô tả chi tiết cho sản phẩm Sổ Tay Bìa Da Cao Cấp. Hàng chính hãng, chất lượng cao, phù hợp cho học sinh và sinh viên.', 'Hải Tiến', 85000, NULL, 200, 'seed_so-tay-bia-da-cao-cap.png', 'hot', 4.4, 162, 0, 1, '2026-05-13 09:56:49'),
(27, 3, 'Bút Mực Gel Thiên Long', 'but-muc-gel-thien-long', 'Đây là thông tin mô tả chi tiết cho sản phẩm Bút Mực Gel Thiên Long. Hàng chính hãng, chất lượng cao, phù hợp cho học sinh và sinh viên.', 'Thiên Long', 15000, NULL, 500, 'seed_but-muc-gel-thien-long.png', '', 4.5, 497, 0, 1, '2026-05-13 09:56:50'),
(28, 3, 'Hộp Đựng Bút Đa Năng', 'hop-dung-but-da-nang', 'Đây là thông tin mô tả chi tiết cho sản phẩm Hộp Đựng Bút Đa Năng. Hàng chính hãng, chất lượng cao, phù hợp cho học sinh và sinh viên.', 'Deli', 45000, 36000, 150, 'seed_hop-dung-but-da-nang.png', 'sale', 4.6, 449, 0, 1, '2026-05-13 09:56:51'),
(29, 4, 'Khô Gà Lá Chanh 500g', 'kho-ga-la-chanh-500g', 'Đây là thông tin mô tả chi tiết cho sản phẩm Khô Gà Lá Chanh 500g. Hàng chính hãng, chất lượng cao, phù hợp cho học sinh và sinh viên.', 'G&T Food', 95000, NULL, 150, 'seed_kho-ga-la-chanh-500g.png', 'hot', 4.8, 197, 0, 1, '2026-05-13 09:56:52'),
(30, 4, 'Bánh Tráng Trộn Sa Tế', 'banh-trang-tron-sa-te', 'Đây là thông tin mô tả chi tiết cho sản phẩm Bánh Tráng Trộn Sa Tế. Hàng chính hãng, chất lượng cao, phù hợp cho học sinh và sinh viên.', 'G&T Food', 25000, NULL, 300, 'seed_banh-trang-tron-sa-te.png', 'new', 4.9, 130, 0, 1, '2026-05-13 09:56:53'),
(31, 4, 'Cơm Cháy Chà Bông', 'com-chay-cha-bong', 'Đây là thông tin mô tả chi tiết cho sản phẩm Cơm Cháy Chà Bông. Hàng chính hãng, chất lượng cao, phù hợp cho học sinh và sinh viên.', 'G&T Food', 65000, 52000, 100, 'seed_com-chay-cha-bong.png', 'sale', 4.5, 320, 0, 1, '2026-05-13 09:56:54'),
(53, 1, 'Sapiens Lược Sử Loài Người', 'sapiens-luoc-su-loai-nguoi', 'Đây là thông tin mô tả chi tiết cho sản phẩm Sapiens Lược Sử Loài Người. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'Yuval Noah Harari', 210000, NULL, 45, 'seed_sapiens-luoc-su-loai-nguoi.png', 'new', 4.8, 461, 0, 1, '2026-05-13 10:01:56'),
(54, 1, 'Bắt Trẻ Đồng Xanh', 'bat-tre-dong-xanh', 'Đây là thông tin mô tả chi tiết cho sản phẩm Bắt Trẻ Đồng Xanh. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'J.D. Salinger', 95000, NULL, 30, 'seed_bat-tre-dong-xanh.png', '', 4.3, 14, 0, 1, '2026-05-13 10:01:56'),
(55, 1, 'Đại Gia Gatsby', 'dai-gia-gatsby', 'Đây là thông tin mô tả chi tiết cho sản phẩm Đại Gia Gatsby. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'F. Scott Fitzgerald', 85000, NULL, 40, 'seed_dai-gia-gatsby.png', '', 4.1, 263, 0, 1, '2026-05-13 10:01:58'),
(56, 1, 'Cuốn Theo Chiều Gió', 'cuon-theo-chieu-gio', 'Đây là thông tin mô tả chi tiết cho sản phẩm Cuốn Theo Chiều Gió. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'Margaret Mitchell', 190000, 152000, 25, 'seed_cuon-theo-chieu-gio.png', 'sale', 4.9, 400, 0, 1, '2026-05-13 10:01:59'),
(63, 2, 'Attack on Titan Tập 34', 'attack-on-titan-tap-34', 'Đây là thông tin mô tả chi tiết cho sản phẩm Attack on Titan Tập 34. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'Hajime Isayama', 35000, NULL, 60, 'seed_attack-on-titan-tap-34.png', 'new', 4.9, 149, 0, 1, '2026-05-13 10:02:02'),
(64, 2, 'Spy x Family Tập 1', 'spy-x-family-tap-1', 'Đây là thông tin mô tả chi tiết cho sản phẩm Spy x Family Tập 1. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'Tatsuya Endo', 30000, NULL, 110, 'seed_spy-x-family-tap-1.png', 'hot', 4.7, 157, 0, 1, '2026-05-13 10:02:02'),
(68, 3, 'Giấy Note 5 Màu', 'giay-note-5-mau', 'Đây là thông tin mô tả chi tiết cho sản phẩm Giấy Note 5 Màu. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'Deli', 12000, NULL, 300, 'seed_giay-note-5-mau.png', '', 4.6, 458, 0, 1, '2026-05-13 10:02:03'),
(69, 3, 'Tẩy Chì Staedtler', 'tay-chi-staedtler', 'Đây là thông tin mô tả chi tiết cho sản phẩm Tẩy Chì Staedtler. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'Staedtler', 10000, NULL, 400, 'seed_tay-chi-staedtler.png', '', 4.3, 386, 0, 1, '2026-05-13 10:02:04'),
(70, 3, 'Bút Dạ Quang Màu Vàng', 'but-da-quang-mau-vang', 'Đây là thông tin mô tả chi tiết cho sản phẩm Bút Dạ Quang Màu Vàng. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'Thiên Long', 8000, NULL, 250, 'seed_but-da-quang-mau-vang.png', '', 4.8, 333, 0, 1, '2026-05-13 10:02:05'),
(71, 3, 'Máy Tính Casio FX-580', 'may-tinh-casio-fx-580', 'Đây là thông tin mô tả chi tiết cho sản phẩm Máy Tính Casio FX-580. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'Casio', 650000, NULL, 50, 'seed_may-tinh-casio-fx-580.png', 'hot', 4.6, 330, 0, 1, '2026-05-13 10:02:06'),
(72, 3, 'Bìa Còng Đựng Tài Liệu', 'bia-cong-dung-tai-lieu', 'Đây là thông tin mô tả chi tiết cho sản phẩm Bìa Còng Đựng Tài Liệu. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'Thiên Long', 35000, NULL, 120, 'seed_bia-cong-dung-tai-lieu.png', '', 4.5, 285, 0, 1, '2026-05-13 10:02:07'),
(73, 3, 'Băng Keo Trong Nhỏ', 'bang-keo-trong-nho', 'Đây là thông tin mô tả chi tiết cho sản phẩm Băng Keo Trong Nhỏ. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'Deli', 5000, NULL, 500, 'seed_bang-keo-trong-nho.png', '', 4.3, 31, 0, 1, '2026-05-13 10:02:08'),
(77, 4, 'Snack Oishi Tôm Cay', 'snack-oishi-tom-cay', 'Đây là thông tin mô tả chi tiết cho sản phẩm Snack Oishi Tôm Cay. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'Oishi', 10000, NULL, 200, 'seed_snack-oishi-tom-cay.png', '', 4.9, 453, 0, 1, '2026-05-13 10:02:08'),
(78, 4, 'Kẹo Dẻo Chupa Chups', 'keo-deo-chupa-chups', 'Đây là thông tin mô tả chi tiết cho sản phẩm Kẹo Dẻo Chupa Chups. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'Chupa Chups', 15000, NULL, 180, 'seed_keo-deo-chupa-chups.png', '', 4.3, 220, 0, 1, '2026-05-13 10:02:09'),
(79, 4, 'Hạt Hướng Dương Vị Dừa', 'hat-huong-duong-vi-dua', 'Đây là thông tin mô tả chi tiết cho sản phẩm Hạt Hướng Dương Vị Dừa. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'G&T Food', 35000, NULL, 90, 'seed_hat-huong-duong-vi-dua.png', 'new', 4.6, 364, 0, 1, '2026-05-13 10:02:10'),
(80, 4, 'Mực Xé Sợi Tẩm Gia Vị', 'muc-xe-soi-tam-gia-vi', 'Đây là thông tin mô tả chi tiết cho sản phẩm Mực Xé Sợi Tẩm Gia Vị. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'G&T Food', 110000, NULL, 60, 'seed_muc-xe-soi-tam-gia-vi.png', 'hot', 4.9, 47, 0, 1, '2026-05-13 10:02:11'),
(81, 5, 'Pepsi Lon 330ml', 'pepsi-lon-330ml', 'Đây là thông tin mô tả chi tiết cho sản phẩm Pepsi Lon 330ml. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'PepsiCo', 12000, NULL, 200, 'seed_pepsi-lon-330ml.png', '', 4.5, 228, 0, 1, '2026-05-13 10:02:12'),
(82, 5, 'Coca Cola Lon 330ml', 'coca-cola-lon-330ml', 'Đây là thông tin mô tả chi tiết cho sản phẩm Coca Cola Lon 330ml. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'Coca Cola', 12000, NULL, 200, 'seed_coca-cola-lon-330ml.png', '', 4.3, 254, 0, 1, '2026-05-13 10:02:12'),
(83, 5, 'Trà Đào Cam Sả', 'tra-dao-cam-sa', 'Đây là thông tin mô tả chi tiết cho sản phẩm Trà Đào Cam Sả. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'G&T Drink', 25000, NULL, 50, 'seed_tra-dao-cam-sa.png', 'hot', 4.4, 28, 0, 1, '2026-05-13 10:02:13'),
(84, 5, 'Trà Sữa Trân Châu Đen', 'tra-sua-tran-chau-den', 'Đây là thông tin mô tả chi tiết cho sản phẩm Trà Sữa Trân Châu Đen. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'G&T Drink', 30000, 24000, 60, 'seed_tra-sua-tran-chau-den.png', 'sale', 4.6, 205, 0, 1, '2026-05-13 10:02:14'),
(85, 5, 'Nước Suối Aquafina 500ml', 'nuoc-suoi-aquafina-500ml', 'Đây là thông tin mô tả chi tiết cho sản phẩm Nước Suối Aquafina 500ml. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'Suntory', 5000, NULL, 300, 'seed_nuoc-suoi-aquafina-500ml.png', '', 4.2, 478, 0, 1, '2026-05-13 10:02:15'),
(86, 6, 'Rubik 3x3 Moyu', 'rubik-3x3-moyu', 'Đây là thông tin mô tả chi tiết cho sản phẩm Rubik 3x3 Moyu. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'Moyu', 85000, NULL, 40, 'seed_rubik-3x3-moyu.png', 'new', 4.5, 213, 0, 1, '2026-05-13 10:02:16'),
(87, 6, 'Xếp Hình Puzzle 1000 Mảnh', 'xep-hinh-puzzle-1000-manh', 'Đây là thông tin mô tả chi tiết cho sản phẩm Xếp Hình Puzzle 1000 Mảnh. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'Ravensburger', 250000, 200000, 20, 'seed_xep-hinh-puzzle-1000-manh.png', 'sale', 4.2, 479, 0, 1, '2026-05-13 10:02:17'),
(88, 6, 'Bộ Bài Uno Mở Rộng', 'bo-bai-uno-mo-rong', 'Đây là thông tin mô tả chi tiết cho sản phẩm Bộ Bài Uno Mở Rộng. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'Mattel', 75000, NULL, 80, 'seed_bo-bai-uno-mo-rong.png', 'hot', 4.3, 302, 0, 1, '2026-05-13 10:02:17'),
(89, 6, 'Cờ Tỷ Phú Bằng Nhựa', 'co-ty-phu-bang-nhua', 'Đây là thông tin mô tả chi tiết cho sản phẩm Cờ Tỷ Phú Bằng Nhựa. Hàng chất lượng cao, thiết kế đẹp mắt và đáp ứng tốt nhu cầu sử dụng thực tế.', 'Việt Nam', 120000, NULL, 30, 'seed_co-ty-phu-bang-nhua.png', '', 4.0, 373, 0, 1, '2026-05-13 10:02:18');

INSERT INTO banners (title, subtitle, btn_text, btn_link, bg_color, sort_order) VALUES
('🎓 Ưu đãi học sinh, sinh viên', 'Giảm thêm 10% cho văn phòng phẩm khi xuất trình thẻ SV', 'Mua ngay', '/gt_shop/?cat=van-phong-pham', '#2C1A0E', 1),
('📚 Sách mới về hàng mỗi tuần',  'Cập nhật đầu sách hot nhất từ các NXB lớn',              'Khám phá', '/gt_shop/?cat=sach-van-hoc',   '#1D3A2A', 2);

-- Tài khoản admin mặc định (password: admin123)
INSERT INTO users (name, email, password, role) VALUES
('Admin GT Shop', 'admin@gtshop.vn', '$2y$12$LcJYCqYlxvXHg6xJFbNineQGQHEqB3s.vmqsNz/IXtXQAVXMJI4Ny', 'admin');

