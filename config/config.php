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
?>