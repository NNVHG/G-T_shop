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
            // Lỗi 404: Không tìm thấy phương thức trong HomeController
            $errorMessage = "Hành động (Action) '$actionName' không tồn tại trong HomeController!";
            require_once '../app/Views/errors/404.php';
        }
        break;

    case 'product':
        $controller = new ProductController();
        if (method_exists($controller, $actionName)) {
            $controller->$actionName();
        } else {
            // Lỗi 404: Không tìm thấy phương thức trong ProductController
            $errorMessage = "Hành động (Action) '$actionName' không tồn tại trong ProductController!";
            require_once '../app/Views/errors/404.php';
        }
        break;

    default:
        // Lỗi 404: Không tìm thấy Controller
        $errorMessage = "Bộ điều khiển (Controller) '$controllerName' không tồn tại trên hệ thống!";
        require_once '../app/Views/errors/404.php';
        break;
}
?>