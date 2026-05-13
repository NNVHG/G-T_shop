<?php
// config/config.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
// Đã sửa tên database thành gtbookstore_db theo đúng yêu cầu của Gia
define('DB_NAME', 'gtbookstore_db');

define('SITE_NAME', 'G&T Shop');
// Khai báo đường dẫn trỏ thẳng vào public (Front Controller)
define('BASE_URL', 'http://localhost/G&T_shop/public/');

// Helper: format tiền tệ Việt Nam
function formatPrice(int $price): string {
    return number_format($price, 0, ',', '.') . ' ₫';
}

// Helper: xuất chuỗi an toàn
function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Cấu hình VNPAY
define('VNP_TMN_CODE', 'XLHKEVA9'); // Thay bằng Mã Website (TmnCode) của bạn
define('VNP_HASH_SECRET', 'POOO24CO2FUU8PF8YSJJQKML2G174Y8M'); // Thay bằng Chuỗi bí mật
define('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'); // URL API Sandbox
define('VNP_RETURN_URL', BASE_URL . 'index.php?controller=checkout&action=vnpay_return');
?>