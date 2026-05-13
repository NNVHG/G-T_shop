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
            $nav_categories = $productModel->getCategories();
            
            // Get related products (same category)
            $related_products = [];
            if ($product) {
                $related_products = $productModel->getProductsList($product['category_id'] ?? '', '', '', null, null);
                // Limit to 4 related products, exclude current
                $related_products = array_filter($related_products, function($p) use ($id) { return $p['id'] != $id; });
                $related_products = array_slice($related_products, 0, 4);
                
                require_once '../app/Views/client/product_detail.php';
            } else {
                $errorMessage = "Không tìm thấy sản phẩm có ID: $id trong Cơ sở dữ liệu!";
                require_once '../app/Views/errors/404.php';
            }
        } else {
            $errorMessage = "ID sản phẩm không hợp lệ (ID=$id)! Vui lòng kiểm tra lại URL.";
            require_once '../app/Views/errors/404.php';
        }
    }

    public function category() {
        $slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
        $sort = isset($_GET['sort']) ? trim($_GET['sort']) : '';
        $min_price = isset($_GET['min_price']) ? trim($_GET['min_price']) : null;
        $max_price = isset($_GET['max_price']) ? trim($_GET['max_price']) : null;

        $database = new Database();
        $db = $database->getConnection();
        $productModel = new Product($db);

        $nav_categories = $productModel->getCategories();
        $products = $productModel->getProductsList($slug, '', $sort, $min_price, $max_price);
        
        $page_title = "Danh mục sản phẩm";
        if (!empty($slug)) {
            foreach ($nav_categories as $cat) {
                if ($cat['slug'] === $slug) {
                    $page_title = $cat['name'];
                    break;
                }
            }
        }

        require_once '../app/Views/client/products.php';
    }

    public function search() {
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        $sort = isset($_GET['sort']) ? trim($_GET['sort']) : '';
        $min_price = isset($_GET['min_price']) ? trim($_GET['min_price']) : null;
        $max_price = isset($_GET['max_price']) ? trim($_GET['max_price']) : null;

        $database = new Database();
        $db = $database->getConnection();
        $productModel = new Product($db);

        $nav_categories = $productModel->getCategories();
        $products = $productModel->getProductsList('', $q, $sort, $min_price, $max_price);
        
        $page_title = "Kết quả tìm kiếm cho: " . e($q);

        require_once '../app/Views/client/products.php';
    }

    // Trả về JSON cho tính năng AJAX Suggestion
    public function suggest() {
        header('Content-Type: application/json');
        
        // Lấy từ khóa, mặc định là rỗng
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        // Yêu cầu gõ ít nhất 2 ký tự mới tìm kiếm để giảm tải Server
        if (mb_strlen($q) < 2) {
            echo json_encode([]);
            return;
        }

        $database = new Database();
        $db = $database->getConnection();
        $productModel = new Product($db);

        $results = $productModel->searchProducts($q);

        // Format lại mảng dữ liệu để nhúng vào HTML bằng JS
        $output = array_map(function($p) {
            return [
                'id'        => $p['id'],
                'name'      => $p['name'],
                'slug'      => $p['slug'],
                'price_fmt' => formatPrice((int)$p['display_price']), // Đã có trong config.php
            ];
        }, $results);

        // Giữ nguyên định dạng Unicode tiếng Việt
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
    }
}
?>