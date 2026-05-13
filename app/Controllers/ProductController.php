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
            $database = new Database();
            $db = $database->getConnection();
            $productModel = new Product($db);

            $product = $productModel->getProductById($id);

            if ($product) {
                require_once '../app/Views/client/product_detail.php';
            } else {
                // Gọi 404 khi truy vấn ID không có trong MySQL
                $errorMessage = "Không tìm thấy sản phẩm có ID: $id trong Cơ sở dữ liệu!";
                require_once '../app/Views/errors/404.php';
            }
        } else {
            // Gọi 404 khi người dùng nhập ID là chữ hoặc ID âm
            $errorMessage = "ID sản phẩm không hợp lệ (ID=$id)! Vui lòng kiểm tra lại URL.";
            require_once '../app/Views/errors/404.php';
        }
    }
}
?>