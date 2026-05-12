<?php
// Khởi tạo session cho giỏ hàng và đăng nhập
session_start(); 

require_once '../config/config.php';

// Tự động nạp các tệp Controller cần thiết
require_once '../app/Controllers/HomeController.php';
require_once '../app/Controllers/ProductController.php';

use App\Controllers\HomeController;
use App\Controllers\ProductController;

// Lấy tham số điều hướng từ URL một cách an toàn
$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$actionName = isset($_GET['action']) ? $_GET['action'] : 'index';

// Khởi tạo routing
switch ($controllerName) {
    case 'home':
        $controller = new HomeController();
        if (method_exists($controller, $actionName)) {
            $controller->$actionName();
        } else {
            echo "404 - Hành động không tồn tại!";
        }
        break;

    case 'product':
        $controller = new ProductController();
        if (method_exists($controller, $actionName)) {
            $controller->$actionName();
        } else {
            echo "404 - Hành động không tồn tại!";
        }
        break;

    default:
        echo "404 - Trang không tồn tại (Not Found)";
        break;
}
?>