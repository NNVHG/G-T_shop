<?php
namespace App\Controllers;

require_once '../app/Models/Database.php';
require_once '../app/Models/Product.php';

use App\Models\Database;
use App\Models\Product;

class HomeController {
    public function index() {
        // 1. Kết nối CSDL
        $database = new Database();
        $db = $database->getConnection();

        // 2. Khởi tạo Product Model và truyền kết nối vào
        $productModel = new Product($db);
        
        // 3. Lấy danh sách sản phẩm
        // Gán mảng rỗng [] nếu CSDL chưa có bảng/dữ liệu để frontend không bị crash
        $products = [];
        try {
            $products = $productModel->getAllProducts();
        } catch (\Exception $e) {
            // Tạm thời bỏ qua lỗi nếu chưa tạo bảng 'products' trong MySQL
        }

        // 4. Gọi View (Lúc này biến $products đã tồn tại và được truyền ngầm xuống View)
        require_once '../app/Views/client/home.php';
    }
}
?>