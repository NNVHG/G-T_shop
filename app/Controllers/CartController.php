<?php
namespace App\Controllers;

require_once '../app/Models/Database.php';
require_once '../app/Models/Product.php';

use App\Models\Database;
use App\Models\Product;

class CartController {
    
    // 1. Hiển thị trang Giỏ hàng
    public function index() {
        $database = new Database();
        $db = $database->getConnection();
        $productModel = new Product($db);

        // Lấy danh mục cho header
        $nav_categories = $productModel->getCategories();
        
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $cart_count = array_sum(array_column($cart, 'qty'));
        $total_amount = array_sum(array_map(function($item) {
            return $item['price'] * $item['qty'];
        }, $cart));

        require_once '../app/Views/client/cart.php';
    }

    // 2. API Thêm vào giỏ hàng (AJAX)
    public function add() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'redirect' => BASE_URL . 'index.php?controller=auth&action=login', 'message' => 'Vui lòng đăng nhập để thêm vào giỏ hàng!']);
            return;
        }

        $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $qty = isset($_POST['qty']) ? max(1, (int)$_POST['qty']) : 1;

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $database = new Database();
        $db = $database->getConnection();
        
        // Kiểm tra sản phẩm có tồn tại và còn hàng không
        $stmt = $db->prepare("SELECT id, name, price, sale_price, stock, image, slug FROM products WHERE id = ? AND is_active = 1");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$product) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại!']);
            return;
        }

        if ($product['stock'] < 1) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm đã hết hàng!']);
            return;
        }

        $key = 'p_' . $product_id;
        $sell_price = $product['sale_price'] ?? $product['price']; // Ưu tiên giá Sale

        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['qty'] += $qty;
        } else {
            $_SESSION['cart'][$key] = [
                'product_id' => $product['id'],
                'name'       => $product['name'],
                'image'      => $product['image'],
                'price'      => $sell_price,
                'qty'        => $qty,
                'stock'      => $product['stock']
            ];
        }

        $cart_count = array_sum(array_column($_SESSION['cart'], 'qty'));
        echo json_encode(['success' => true, 'cart_count' => $cart_count, 'message' => 'Đã thêm vào giỏ hàng!']);
    }

    // 3. API Cập nhật số lượng (AJAX)
    public function update() {
        header('Content-Type: application/json');
        $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 0;
        $key = 'p_' . $product_id;

        if (isset($_SESSION['cart'][$key])) {
            if ($qty > 0 && $qty <= $_SESSION['cart'][$key]['stock']) {
                $_SESSION['cart'][$key]['qty'] = $qty;
            } elseif ($qty <= 0) {
                unset($_SESSION['cart'][$key]);
            }
        }

        $cart_count = array_sum(array_column($_SESSION['cart'], 'qty'));
        $total = array_sum(array_map(function($i) { return $i['price'] * $i['qty']; }, $_SESSION['cart'] ?? []));
        
        echo json_encode([
            'success' => true, 
            'cart_count' => $cart_count, 
            'total_fmt' => formatPrice($total),
            'item_total_fmt' => isset($_SESSION['cart'][$key]) ? formatPrice($_SESSION['cart'][$key]['price'] * $_SESSION['cart'][$key]['qty']) : '0 ₫'
        ]);
    }

    // 4. API Xóa sản phẩm (AJAX)
    public function remove() {
        header('Content-Type: application/json');
        $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $key = 'p_' . $product_id;

        if (isset($_SESSION['cart'][$key])) {
            unset($_SESSION['cart'][$key]);
        }

        $cart_count = array_sum(array_column($_SESSION['cart'], 'qty'));
        $total = array_sum(array_map(function($i) { return $i['price'] * $i['qty']; }, $_SESSION['cart'] ?? []));
        
        echo json_encode(['success' => true, 'cart_count' => $cart_count, 'total_fmt' => formatPrice($total)]);
    }
}
?>