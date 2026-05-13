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

INSERT INTO products
    (category_id, name, slug, author, price, sale_price, stock, badge, rating, sold_count, is_featured) VALUES
(1, 'Đắc Nhân Tâm',          'dac-nhan-tam',         'Dale Carnegie',  89000,  NULL,   50, 'hot',  4.9, 1230, 1),
(1, 'Nhà Giả Kim',            'nha-gia-kim',          'Paulo Coelho',   75000,  59000,  40, 'sale', 4.8,  980, 1),
(1, 'Sapiens: Lược Sử Loài Người', 'sapiens',         'Yuval N. Harari',115000, 95000, 30, 'sale', 4.7,  750, 1),
(1, 'Tôi Tài Giỏi, Bạn Cũng Thế','toi-tai-gioi',     'Adam Khoo',      79000,  NULL,   60, '',     4.6,  620, 0),
(2, 'Thám Tử Conan tập 100',  'conan-tap-100',        'Gosho Aoyama',   25000,  NULL,  120, 'new',  4.8,  430, 1),
(2, 'Doraemon tập 45',        'doraemon-tap-45',      'Fujiko F. Fujio',22000,  NULL,  100, '',     4.7,  380, 0),
(2, 'Naruto tập 72',          'naruto-tap-72',        'Masashi Kishimoto',28000, NULL, 80, 'new',  4.9,  510, 1),
(2, 'Dragon Ball tập 42',     'dragon-ball-tap-42',   'Akira Toriyama', 26000,  NULL,   70, '',     4.8,  290, 0),
(3, 'Bút bi Thiên Long FO-03','but-thien-long',       'Thiên Long',     32000,  NULL,  200, '',     4.5,  840, 1),
(3, 'Vở kẻ ngang 200 trang',  'vo-ke-ngang-200',      'Hồng Hà',        15000,  NULL,  300, '',     4.4,  920, 0),
(3, 'Tẩy Staedtler Mars',     'tay-staedtler',        'Staedtler',      12000,  NULL,  150, 'new',  4.6,  410, 0),
(4, 'Snack Oishi Tôm cay',    'snack-oishi-tom',      'Oishi',          72000,  58000, 80,  'sale', 4.5,  670, 1),
(4, 'Kẹo dẻo gấu Haribo',    'keo-deo-haribo',       'Haribo',         45000,  NULL,  60,  'hot',  4.7,  320, 0),
(5, 'Pepsi lon 330ml (thùng)','pepsi-lon-330',        'PepsiCo',        185000, 155000,40, 'sale', 4.6,  210, 1),
(5, 'Trà xanh Không Độ chai', 'tra-xanh-khong-do',   'Tân Hiệp Phát',  15000,  NULL,  150, '',     4.4,  480, 0),
(6, 'Rubik 3x3 Moyu',         'rubik-moyu-3x3',       'Moyu',           85000,  NULL,   40, 'new',  4.8,  190, 1),
(6, 'Xếp hình 500 mảnh',      'xep-hinh-500',         'Ravensburger',   120000, 95000, 30, 'sale', 4.6,  140, 0);

INSERT INTO banners (title, subtitle, btn_text, btn_link, bg_color, sort_order) VALUES
('🎓 Ưu đãi học sinh, sinh viên', 'Giảm thêm 10% cho văn phòng phẩm khi xuất trình thẻ SV', 'Mua ngay', '/gt_shop/?cat=van-phong-pham', '#2C1A0E', 1),
('📚 Sách mới về hàng mỗi tuần',  'Cập nhật đầu sách hot nhất từ các NXB lớn',              'Khám phá', '/gt_shop/?cat=sach-van-hoc',   '#1D3A2A', 2);

-- Tài khoản admin mặc định (password: admin123)
INSERT INTO users (name, email, password, role) VALUES
('Admin GT Shop', 'admin@gtshop.vn', '$2y$12$LcJYCqYlxvXHg6xJFbNineQGQHEqB3s.vmqsNz/IXtXQAVXMJI4Ny', 'admin');

