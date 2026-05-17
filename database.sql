-- ==========================================================
-- G&T Shop Database Schema & Seed Data
-- Database Name: gtbookstore_db
-- ==========================================================

-- Thiết lập múi giờ và bảng mã tiếng Việt
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------------------------------------
-- 1. XÓA BẢNG NẾU ĐÃ TỒN TẠI (DỌN DẸP TRƯỚC KHI CÀI ĐẶT)
-- ----------------------------------------------------------
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS banners;

SET FOREIGN_KEY_CHECKS = 1;

-- ----------------------------------------------------------
-- 2. TẠO BẢNG DANH MỤC (CATEGORIES)
-- ----------------------------------------------------------
CREATE TABLE categories (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100)  NOT NULL,
    slug       VARCHAR(100)  NOT NULL UNIQUE,
    icon       VARCHAR(50)   DEFAULT 'ti-tag',
    sort_order INT           DEFAULT 0,
    created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 3. TẠO BẢNG SẢN PHẨM (PRODUCTS)
-- ----------------------------------------------------------
CREATE TABLE products (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 4. TẠO BẢNG TÀI KHOẢN (USERS)
-- ----------------------------------------------------------
CREATE TABLE users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100)  NOT NULL,
    email      VARCHAR(150)  NOT NULL UNIQUE,
    password   VARCHAR(255)  NOT NULL,
    phone      VARCHAR(20),
    address    TEXT,
    role       ENUM('user','admin') DEFAULT 'user',
    created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 5. TẠO BẢNG ĐƠN HÀNG (ORDERS)
-- ----------------------------------------------------------
CREATE TABLE orders (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    user_id        INT           DEFAULT NULL,
    customer_name  VARCHAR(100)  NOT NULL,
    phone          VARCHAR(20)   NOT NULL,
    address        TEXT          NOT NULL,
    total          INT           NOT NULL,
    status         ENUM('pending','processing','shipped','done','cancelled') DEFAULT 'pending',
    payment_method VARCHAR(50)   DEFAULT 'cod',
    payment_status ENUM('unpaid', 'paid', 'failed') DEFAULT 'unpaid',
    transaction_id VARCHAR(100)  DEFAULT NULL,
    note           TEXT,
    created_at     TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 6. TẠO BẢNG CHI TIẾT ĐƠN HÀNG (ORDER_ITEMS)
-- ----------------------------------------------------------
CREATE TABLE order_items (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    order_id   INT NOT NULL,
    product_id INT NOT NULL,
    qty        INT NOT NULL,
    price      INT NOT NULL,
    FOREIGN KEY (order_id)   REFERENCES orders(id)   ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 7. TẠO BẢNG QUẢNG CÁO (BANNERS)
-- ----------------------------------------------------------
CREATE TABLE banners (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    title      VARCHAR(200),
    subtitle   VARCHAR(200),
    btn_text   VARCHAR(50)  DEFAULT 'Xem ngay',
    btn_link   VARCHAR(255) DEFAULT '#',
    bg_color   VARCHAR(20)  DEFAULT '#2C1A0E',
    is_active  TINYINT(1)   DEFAULT 1,
    sort_order INT          DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- 8. THÊM DỮ LIỆU MẪU CHO DANH MỤC (CATEGORIES)
-- ----------------------------------------------------------
INSERT INTO categories (id, name, slug, icon, sort_order) VALUES
(1, 'Sách văn học',    'sach-van-hoc',    'ti-book',              1),
(2, 'Truyện tranh',    'truyen-tranh',    'ti-device-gamepad-2',  2),
(3, 'Văn phòng phẩm',  'van-phong-pham',  'ti-pencil',            3),
(4, 'Đồ ăn vặt',       'do-an-vat',       'ti-candy',             4),
(5, 'Nước ngọt',       'nuoc-ngot',       'ti-bottle',            5),
(6, 'Đồ chơi',         'do-choi',         'ti-puzzle',            6);

-- ----------------------------------------------------------
-- 9. THÊM DỮ LIỆU MẪU CHO SẢN PHẨM (PRODUCTS)
-- ----------------------------------------------------------
INSERT INTO products (id, category_id, name, slug, description, author, price, sale_price, stock, image, badge, rating, sold_count, is_featured, is_active, created_at) VALUES
(1, 1, 'Đắc Nhân Tâm', 'dac-nhan-tam', 'Đắc nhân tâm của Dale Carnegie là quyển sách duy nhất về thể loại self-help liên tục đứng đầu danh mục sách bán chạy nhất của báo The New York Times trong suốt 10 năm liền.', 'Dale Carnegie', 89000, NULL, 50, 'default.jpg', 'hot', 4.9, 1230, 1, 1, '2026-05-13 05:52:02'),
(2, 1, 'Nhà Giả Kim', 'nha-gia-kim', 'Nhà giả kim là một cuốn tiểu thuyết của nhà văn Paulo Coelho được xuất bản lần đầu ở Brasil năm 1988. Đây là cuốn sách bán chạy nhất của ông và là một trong những cuốn sách bán chạy nhất lịch sử.', 'Paulo Coelho', 75000, 59000, 40, 'default.jpg', 'sale', 4.8, 980, 1, 1, '2026-05-13 05:52:02'),
(3, 1, 'Sapiens: Lược Sử Loài Người', 'sapiens', 'Sapiens: Lược sử loài người là một cuốn sách của Yuval Noah Harari, xuất bản lần đầu bằng tiếng Do Thái ở Israel năm 2011, và bằng tiếng Anh vào năm 2014.', 'Yuval N. Harari', 115000, 95000, 30, 'default.jpg', 'sale', 4.7, 750, 1, 1, '2026-05-13 05:52:02'),
(4, 1, 'Tôi Tài Giỏi, Bạn Cũng Thế', 'toi-tai-gioi', 'Tôi tài giỏi, bạn cũng thế! là cuốn sách bán chạy nhất của doanh nhân người Singapore Adam Khoo, tổng hợp những kỹ năng và phương pháp học tập hiệu quả.', 'Adam Khoo', 79000, NULL, 60, 'default.jpg', '', 4.6, 620, 0, 1, '2026-05-13 05:52:02'),
(5, 2, 'Thám Tử Conan tập 100', 'conan-tap-100', 'Tập thứ 100 của bộ truyện tranh đình đám Thám tử lừng danh Conan. Đánh dấu cột mốc lịch sử với những vụ án hấp dẫn và kịch tính nhất.', 'Gosho Aoyama', 25000, NULL, 120, 'default.jpg', 'new', 4.8, 430, 1, 1, '2026-05-13 05:52:02'),
(6, 2, 'Doraemon tập 45', 'doraemon-tap-45', 'Tập cuối cùng trong bộ truyện Doraemon ngắn chính thức của tác giả Fujiko F. Fujio, chứa đựng nhiều câu chuyện cảm động.', 'Fujiko F. Fujio', 22000, NULL, 100, 'default.jpg', '', 4.7, 380, 0, 1, '2026-05-13 05:52:02'),
(7, 2, 'Naruto tập 72', 'naruto-tap-72', 'Tập cuối cùng của loạt truyện tranh huyền thoại Naruto. Trận chiến quyết định giữa Naruto và Sasuke khép lại hành trình đầy cảm xúc.', 'Masashi Kishimoto', 28000, NULL, 80, 'default.jpg', 'new', 4.9, 510, 1, 1, '2026-05-13 05:52:02'),
(8, 2, 'Dragon Ball tập 42', 'dragon-ball-tap-42', 'Tập cuối cùng của bộ truyện 7 Viên Ngọc Rồng gốc, kết thúc cuộc hành trình phiêu lưu và chiến đấu tuyệt vời của Son Goku.', 'Akira Toriyama', 26000, NULL, 70, 'default.jpg', '', 4.8, 290, 0, 1, '2026-05-13 05:52:02'),
(9, 3, 'Bút bi Thiên Long FO-03', 'but-thien-long', 'Bút bi cao cấp của tập đoàn Thiên Long, nét chữ thanh mảnh, mực ra đều và nhanh khô, phù hợp cho học sinh, văn phòng.', 'Thiên Long', 32000, NULL, 200, 'default.jpg', '', 4.5, 840, 1, 1, '2026-05-13 05:52:02'),
(10, 3, 'Vở kẻ ngang 200 trang', 'vo-ke-ngang-200', 'Vở kẻ ngang Hồng Hà 200 trang định lượng giấy cao, viết không nhòe, không thấm mực sang trang sau.', 'Hồng Hà', 15000, NULL, 300, 'default.jpg', '', 4.4, 920, 0, 1, '2026-05-13 05:52:02'),
(11, 3, 'Tẩy Staedtler Mars', 'tay-staedtler', 'Gôm tẩy cao cấp xuất xứ Đức, tẩy sạch bụi chì mà không làm rách hay mòn mặt giấy.', 'Staedtler', 12000, NULL, 150, 'default.jpg', 'new', 4.6, 410, 0, 1, '2026-05-13 05:52:02'),
(12, 4, 'Snack Oishi Tôm cay', 'snack-oishi-tom', 'Bim bim snack vị tôm cay huyền thoại của hãng Oishi, hương vị giòn rụm thơm ngon khó cưỡng.', 'Oishi', 72000, 58000, 80, 'default.jpg', 'sale', 4.5, 670, 1, 1, '2026-05-13 05:52:02'),
(13, 4, 'Kẹo dẻo gấu Haribo', 'keo-deo-haribo', 'Kẹo dẻo gấu vàng Haribo Goldbären nhập khẩu Đức, vị trái cây tự nhiên dai ngon hấp dẫn.', 'Haribo', 45000, NULL, 60, 'default.jpg', 'hot', 4.7, 320, 0, 1, '2026-05-13 05:52:02'),
(14, 5, 'Pepsi lon 330ml (thùng)', 'pepsi-lon-330', 'Thùng nước ngọt có ga Pepsi 24 lon 330ml giúo giải tỏa cơn khát tức thì, sảng khoái cực đỉnh.', 'PepsiCo', 185000, 155000, 40, 'default.jpg', 'sale', 4.6, 210, 1, 1, '2026-05-13 05:52:02'),
(15, 5, 'Trà xanh Không Độ chai', 'tra-xanh-khong-do', 'Nước trà xanh Không Độ được sản xuất từ những lá chè tươi xanh tự nhiên giúp giải nhiệt cuộc sống.', 'Tân Hiệp Phát', 15000, NULL, 150, 'default.jpg', '', 4.4, 480, 0, 1, '2026-05-13 05:52:02'),
(16, 6, 'Rubik 3x3 Moyu', 'rubik-moyu-3x3', 'Khối Rubik 3x3 đến từ hãng Moyu xoay mượt mà, cắt góc tốt, phù hợp cho người tập chơi và thi đấu.', 'Moyu', 85000, NULL, 40, 'default.jpg', 'new', 4.8, 190, 1, 1, '2026-05-13 05:52:02'),
(17, 6, 'Xếp hình 500 mảnh', 'xep-hinh-500', 'Bộ xếp hình puzzle phong cảnh 500 mảnh giúp rèn luyện trí tuệ và tính kiên nhẫn.', 'Ravensburger', 120000, 95000, 30, 'default.jpg', 'sale', 4.6, 140, 0, 1, '2026-05-13 05:52:02'),
(18, 1, 'Cây Cam Ngọt Của Tôi', 'cay-cam-ngot-cua-toi', 'Đây là thông tin mô tả chi tiết cho sản phẩm Cây Cam Ngọt Của Tôi. Cuốn tiểu thuyết tự truyện cảm động của nhà văn José Mauro de Vasconcelos.', 'José Mauro de Vasconcelos', 108000, NULL, 50, 'seed_cay-cam-ngot-cua-toi.png', 'hot', 4.5, 439, 0, 1, '2026-05-13 09:56:42'),
(19, 1, 'Không Gia Đình', 'khong-gia-dinh', 'Đây là thông tin mô tả chi tiết cho sản phẩm Không Gia Đình. Tác phẩm văn học kinh điển dành cho thiếu nhi của Hector Malot.', 'Hector Malot', 125000, 100000, 40, 'seed_khong-gia-dinh.png', 'sale', 4.6, 228, 0, 1, '2026-05-13 09:56:43'),
(20, 1, 'Hai Số Phận', 'hai-so-phan', 'Tiểu thuyết kinh điển của Jeffrey Archer kể về cuộc đời đầy thăng trầm của hai con người xuất thân hoàn toàn khác biệt.', 'Jeffrey Archer', 160000, NULL, 30, 'seed_hai-so-phan.png', 'hot', 4.7, 36, 0, 1, '2026-05-13 09:56:44'),
(21, 1, 'Suối Nguồn', 'suoi-nguon', 'Tác phẩm văn học triết lý đồ sộ của nhà văn Ayn Rand, ca ngợi chủ nghĩa cá nhân và sự sáng tạo độc lập.', 'Ayn Rand', 250000, NULL, 20, 'seed_suoi-nguon.png', 'new', 4.7, 51, 0, 1, '2026-05-13 09:56:45'),
(22, 1, 'Thiên Thần Và Ác Quỷ', 'thien-than-va-ac-quy', 'Tiểu thuyết trinh thám tôn giáo nghẹt thở của Dan Brown có sự xuất hiện của giáo sư biểu tượng học Robert Langdon.', 'Dan Brown', 180000, NULL, 60, 'seed_thien-than-va-ac-quy.png', '', 5.0, 82, 0, 1, '2026-05-13 09:56:46'),
(23, 2, 'One Piece Tập 100', 'one-piece-tap-100', 'Tập truyện tranh đặc biệt đánh dấu cột mốc tập 100 của hành trình tìm kiếm kho báu huyền thoại của băng Mũ Rơm.', 'Eiichiro Oda', 25000, NULL, 100, 'seed_one-piece-tap-100.png', 'new', 4.2, 166, 0, 1, '2026-05-13 09:56:46'),
(24, 2, 'Chú Thuật Hồi Chiến Tập 1', 'chu-thuat-hoi-chien-tap-1', 'Bộ truyện tranh giả tưởng, hành động chống lại các nguyền hồn cực kỳ ăn khách của tác giả Gege Akutami.', 'Gege Akutami', 30000, NULL, 80, 'seed_chu-thuat-hoi-chien-tap-1.png', 'hot', 4.4, 299, 0, 1, '2026-05-13 09:56:47'),
(25, 2, 'Thám Tử Conan Tập 101', 'tham-tu-conan-tap-101', 'Tập tiếp theo trong chuỗi vụ án mạng ly kỳ và hành trình đối đầu Tổ chức Áo đen đầy kịch tính.', 'Gosho Aoyama', 25000, NULL, 120, 'seed_tham-tu-conan-tap-101.png', '', 4.7, 490, 0, 1, '2026-05-13 09:56:48'),
(26, 3, 'Sổ Tay Bìa Da Cao Cấp', 'so-tay-bia-da-cao-cap', 'Sổ ghi chép bìa da sang trọng Hải Tiến, chất lượng giấy ngà chống lóa mắt, bền đẹp.', 'Hải Tiến', 85000, NULL, 200, 'seed_so-tay-bia-da-cao-cap.png', 'hot', 4.4, 162, 0, 1, '2026-05-13 09:56:49'),
(27, 3, 'Bút Mực Gel Thiên Long', 'but-muc-gel-thien-long', 'Bút gel Thiên Long viết cực kỳ êm tay, mực gel nước đậm và đều, không bị lem giấy.', 'Thiên Long', 15000, NULL, 500, 'seed_but-muc-gel-thien-long.png', '', 4.5, 497, 0, 1, '2026-05-13 09:56:50'),
(28, 3, 'Hộp Đựng Bút Đa Năng', 'hop-dung-but-da-nang', 'Hộp cắm bút nhựa nhiều ngăn tiện dụng từ hãng Deli, giúp bàn học và bàn làm việc gọn gàng.', 'Deli', 45000, 36000, 150, 'seed_hop-dung-but-da-nang.png', 'sale', 4.6, 449, 0, 1, '2026-05-13 09:56:51'),
(29, 4, 'Khô Gà Lá Chanh 500g', 'kho-ga-la-chanh-500g', 'Khô gà lá chanh loại thượng hạng thơm cay, giòn ngọt, đảm bảo vệ sinh an toàn thực phẩm.', 'G&T Food', 95000, NULL, 150, 'seed_kho-ga-la-chanh-500g.png', 'hot', 4.8, 197, 0, 1, '2026-05-13 09:56:52'),
(30, 4, 'Bánh Tráng Trộn Sa Tế', 'banh-trang-tron-sa-te', 'Bánh tráng trộn sa tế cay nồng chuẩn vị đặc sản ăn vặt đường phố, giòn ngon khó cưỡng.', 'G&T Food', 25000, NULL, 300, 'seed_banh-trang-tron-sa-te.png', 'new', 4.9, 130, 0, 1, '2026-05-13 09:56:53'),
(31, 4, 'Cơm Cháy Chà Bông', 'com-chay-cha-bong', 'Cơm cháy đáy nồi giòn rụm phủ đầy chà bông heo nguyên chất đậm đà gia vị tỏi ớt.', 'G&T Food', 65000, 52000, 100, 'seed_com-chay-cha-bong.png', 'sale', 4.5, 320, 0, 1, '2026-05-13 09:56:54'),
(53, 1, 'Sapiens Lược Sử Loài Người', 'sapiens-luoc-su-loai-nguoi', 'Bản dịch đầy đủ tiếng Việt của cuốn sách khoa học/lược sử bán chạy toàn cầu của Harari.', 'Yuval Noah Harari', 210000, NULL, 45, 'seed_sapiens-luoc-su-loai-nguoi.png', 'new', 4.8, 461, 0, 1, '2026-05-13 10:01:56'),
(54, 1, 'Bắt Trẻ Đồng Xanh', 'bat-tre-dong-xanh', 'Cuốn tiểu thuyết xuất sắc và đầy tranh cãi của J.D. Salinger về cuộc sống nổi loạn của tuổi trẻ.', 'J.D. Salinger', 95000, NULL, 30, 'seed_bat-tre-dong-xanh.png', '', 4.3, 14, 0, 1, '2026-05-13 10:01:56'),
(55, 1, 'Đại Gia Gatsby', 'dai-gia-gatsby', 'Bức tranh thu nhỏ của nước Mỹ thời đại nhạc Jazz những năm 1920 qua mối tình lãng mạn và bi kịch.', 'F. Scott Fitzgerald', 85000, NULL, 40, 'seed_dai-gia-gatsby.png', '', 4.1, 263, 0, 1, '2026-05-13 10:01:58'),
(56, 1, 'Cuốn Theo Chiều Gió', 'cuon-theo-chieu-gio', 'Thiên tiểu thuyết tình yêu vĩ đại thời kỳ nội chiến Mỹ của nữ nhà văn Margaret Mitchell.', 'Margaret Mitchell', 190000, 152000, 25, 'seed_cuon-theo-chieu-gio.png', 'sale', 4.9, 400, 0, 1, '2026-05-13 10:01:59'),
(63, 2, 'Attack on Titan Tập 34', 'attack-on-titan-tap-34', 'Tập cuối cùng khép lại cuộc chiến đầy bi kịch và ý nghĩa giữa loài người và các Titan khổng lồ.', 'Hajime Isayama', 35000, NULL, 60, 'seed_attack-on-titan-tap-34.png', 'new', 4.9, 149, 0, 1, '2026-05-13 10:02:02'),
(64, 2, 'Spy x Family Tập 1', 'spy-x-family-tap-1', 'Bộ truyện tranh hài hước, ấm áp về gia đình đặc vụ giả danh cực kỳ ăn khách tại Nhật Bản.', 'Tatsuya Endo', 30000, NULL, 110, 'seed_spy-x-family-tap-1.png', 'hot', 4.7, 157, 0, 1, '2026-05-13 10:02:02'),
(68, 3, 'Giấy Note 5 Màu', 'giay-note-5-mau', 'Xấp giấy phân trang note 5 màu tiện dụng từ Deli, chất keo bám dính tốt, dễ ghi chú.', 'Deli', 12000, NULL, 300, 'seed_giay-note-5-mau.png', '', 4.6, 458, 0, 1, '2026-05-13 10:02:03'),
(69, 3, 'Tẩy Chì Staedtler', 'tay-chi-staedtler', 'Gôm tẩy cao su tự nhiên mềm mại, làm sạch nét chì đen nhanh chóng mà không làm hư hại mặt giấy.', 'Staedtler', 10000, NULL, 400, 'seed_tay-chi-staedtler.png', '', 4.3, 386, 0, 1, '2026-05-13 10:02:04'),
(70, 3, 'Bút Dạ Quang Màu Vàng', 'but-da-quang-mau-vang', 'Bút dạ quang highlight màu vàng chanh nổi bật, mực sáng, dễ bám màu trên mọi loại giấy.', 'Thiên Long', 8000, NULL, 250, 'seed_but-da-quang-mau-vang.png', '', 4.8, 333, 0, 1, '2026-05-13 10:02:05'),
(71, 3, 'Máy Tính Casio FX-580', 'may-tinh-casio-fx-580', 'Máy tính khoa học chuyên dụng cho học sinh cấp 2, cấp 3 và thi đại học với 521 tính năng cao cấp.', 'Casio', 650000, NULL, 50, 'seed_may-tinh-casio-fx-580.png', 'hot', 4.6, 330, 0, 1, '2026-05-13 10:02:06'),
(72, 3, 'Bìa Còng Đựng Tài Liệu', 'bia-cong-dung-tai-lieu', 'File còng lưu trữ hồ sơ, tài liệu khổ A4 chắc chắn, giữ tài liệu phẳng phiu và khoa học.', 'Thiên Long', 35000, NULL, 120, 'seed_bia-cong-dung-tai-lieu.png', '', 4.5, 285, 0, 1, '2026-05-13 10:02:07'),
(73, 3, 'Băng Keo Trong Nhỏ', 'bang-keo-trong-nho', 'Cuộn băng dính trong khổ nhỏ Deli, độ dính cao, kéo êm mượt, thích hợp dán bọc tài liệu.', 'Deli', 5000, NULL, 500, 'seed_bang-keo-trong-nho.png', '', 4.3, 31, 0, 1, '2026-05-13 10:02:08'),
(77, 4, 'Snack Oishi Tôm Cay', 'snack-oishi-tom-cay', 'Snack vị tôm cay giòn rụm từ Oishi, món ăn vặt được mọi lứa tuổi học sinh yêu thích.', 'Oishi', 10000, NULL, 200, 'seed_snack-oishi-tom-cay.png', '', 4.9, 453, 0, 1, '2026-05-13 10:02:08'),
(78, 4, 'Kẹo Dẻo Chupa Chups', 'keo-deo-chupa-chups', 'Kẹo dẻo vị trái cây thơm ngon, dai mềm Chupa Chups hình gấu ngộ nghĩnh.', 'Chupa Chups', 15000, NULL, 180, 'seed_keo-deo-chupa-chups.png', '', 4.3, 220, 0, 1, '2026-05-13 10:02:09'),
(79, 4, 'Hạt Hướng Dương Vị Dừa', 'hat-huong-duong-vi-dua', 'Hạt hướng dương rang chín thơm bùi thấm đẫm vị nước cốt dừa ngậy thơm hảo hạng.', 'G&T Food', 35000, NULL, 90, 'seed_hat-huong-duong-vi-dua.png', 'new', 4.6, 364, 0, 1, '2026-05-13 10:02:10'),
(80, 4, 'Mực Xé Sợi Tẩm Gia Vị', 'muc-xe-soi-tam-gia-vi', 'Mực xé sợi tẩm ướp cay ngọt đậm đà, thích hợp làm đồ nhắm hay đồ ăn vặt hàng ngày.', 'G&T Food', 110000, NULL, 60, 'seed_muc-xe-soi-tam-gia-vi.png', 'hot', 4.9, 47, 0, 1, '2026-05-13 10:02:11'),
(81, 5, 'Pepsi Lon 330ml', 'pepsi-lon-330ml', 'Lon nước ngọt Pepsi có ga thơm ngon sảng khoái, uống ngon hơn khi ướp lạnh.', 'PepsiCo', 12000, NULL, 200, 'seed_pepsi-lon-330ml.png', '', 4.5, 228, 0, 1, '2026-05-13 10:02:12'),
(82, 5, 'Coca Cola Lon 330ml', 'coca-cola-lon-330ml', 'Lon nước giải khát có ga Coca Cola truyền thống thơm ngon, giải nhiệt nhanh chóng.', 'Coca Cola', 12000, NULL, 200, 'seed_coca-cola-lon-330ml.png', '', 4.3, 254, 0, 1, '2026-05-13 10:02:12'),
(83, 5, 'Trà Đào Cam Sả', 'tra-dao-cam-sa', 'Nước trà đào cam sả đóng chai thơm ngọt dịu nhẹ, thanh mát hương sả và cam tươi.', 'G&T Drink', 25000, NULL, 50, 'seed_tra-dao-cam-sa.png', 'hot', 4.4, 28, 0, 1, '2026-05-13 10:02:13'),
(84, 5, 'Trà Sữa Trân Châu Đen', 'tra-sua-tran-chau-den', 'Trà sữa đóng chai G&T đậm đà vị trà, béo ngậy vị sữa đi kèm thạch trân châu đen dai ngon.', 'G&T Drink', 30000, 24000, 60, 'seed_tra-sua-tran-chau-den.png', 'sale', 4.6, 205, 0, 1, '2026-05-13 10:02:14'),
(85, 5, 'Nước Suối Aquafina 500ml', 'nuoc-suoi-aquafina-500ml', 'Nước uống tinh khiết Aquafina đóng chai tiện dụng, thanh lọc cơ thể mỗi ngày.', 'Suntory', 5000, NULL, 300, 'seed_nuoc-suoi-aquafina-500ml.png', '', 4.2, 478, 0, 1, '2026-05-13 10:02:15'),
(86, 6, 'Rubik 3x3 Moyu', 'rubik-3x3-moyu', 'Rubik 3x3 Moyu thế hệ mới, xoay siêu tốc, mượt mà và không bị kẹt khi giải nhanh.', 'Moyu', 85000, NULL, 40, 'seed_rubik-3x3-moyu.png', 'new', 4.5, 213, 0, 1, '2026-05-13 10:02:16'),
(87, 6, 'Xếp Hình Puzzle 1000 Mảnh', 'xep-hinh-puzzle-1000-manh', 'Bộ tranh ghép hình puzzle nghệ thuật 1000 mảnh bằng giấy bìa ép cứng siêu bền đẹp.', 'Ravensburger', 250000, 200000, 20, 'seed_xep-hinh-puzzle-1000-manh.png', 'sale', 4.2, 479, 0, 1, '2026-05-13 10:02:17'),
(88, 6, 'Bộ Bài Uno Mở Rộng', 'bo-bai-uno-mo-rong', 'Bộ bài Uno kèm các lá bài mở rộng hành động mới lạ, tăng phần kịch tính khi chơi nhóm.', 'Mattel', 75000, NULL, 80, 'seed_bo-bai-uno-mo-rong.png', 'hot', 4.3, 302, 0, 1, '2026-05-13 10:02:17'),
(89, 6, 'Cờ Tỷ Phú Bằng Nhựa', 'co-ty-phu-bang-nhua', 'Bộ bàn cờ tỷ phú Việt hóa bằng nhựa cao cấp, thiết kế đẹp mắt, giúp phát triển tư duy tài chính.', 'Việt Nam', 120000, NULL, 30, 'seed_co-ty-phu-bang-nhua.png', '', 4.0, 373, 0, 1, '2026-05-13 10:02:18');

-- ----------------------------------------------------------
-- 10. THÊM DỮ LIỆU MẪU CHO QUẢNG CÁO (BANNERS)
-- ----------------------------------------------------------
INSERT INTO banners (id, title, subtitle, btn_text, btn_link, bg_color, is_active, sort_order) VALUES
(1, '🎓 Ưu đãi học sinh, sinh viên', 'Giảm thêm 10% cho văn phòng phẩm khi xuất trình thẻ SV', 'Mua ngay', '/gt_shop/?cat=van-phong-pham', '#2C1A0E', 1, 1),
(2, '📚 Sách mới về hàng mỗi tuần',  'Cập nhật đầu sách hot nhất từ các NXB lớn',              'Khám phá', '/gt_shop/?cat=sach-van-hoc',   '#1D3A2A', 1, 2);

-- ----------------------------------------------------------
-- 11. THÊM TÀI KHOẢN ADMIN MẶC ĐỊNH
-- ----------------------------------------------------------
-- Mật khẩu mặc định là: admin123 (mã hóa bcrypt)
INSERT INTO users (id, name, email, password, role) VALUES
(1, 'Admin GT Shop', 'admin@gtshop.vn', '$2y$12$LcJYCqYlxvXHg6xJFbNineQGQHEqB3s.vmqsNz/IXtXQAVXMJI4Ny', 'admin');

SET FOREIGN_KEY_CHECKS = 1;
