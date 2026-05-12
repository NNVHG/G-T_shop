<?php
namespace App\Controllers;

require_once '../app/Models/Database.php';
require_once '../app/Models/Product.php';

use App\Models\Database;
use App\Models\Product;

class ProductController {
    
    public function index() {
        echo "<h1>Đây là trang Danh sách Sản phẩm (Sách, Văn phòng phẩm...)</h1>";
    }

    public function detail() {
        // Lấy ID sản phẩm từ URL, mặc định là 0 nếu không có
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id > 0) {
            // Khởi tạo kết nối DB và Model
            $database = new Database();
            $db = $database->getConnection();
            $productModel = new Product($db);

            // Lấy thông tin sản phẩm
            $product = $productModel->getProductById($id);

            if ($product) {
                // Gọi View hiển thị chi tiết
                require_once '../app/Views/client/product_detail.php';
            } else {
                echo "<h1 style='text-align:center; margin-top:50px;'>Sản phẩm không tồn tại!</h1>";
            }
        } else {
            echo "<h1 style='text-align:center; margin-top:50px;'>ID sản phẩm không hợp lệ!</h1>";
        }
    }
}
?>