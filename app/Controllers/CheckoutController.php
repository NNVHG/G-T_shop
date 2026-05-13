<?php
namespace App\Controllers;

require_once '../app/Models/Database.php';
require_once '../app/Models/Order.php';
require_once '../app/Models/Product.php';

use App\Models\Database;
use App\Models\Order;
use App\Models\Product;

class CheckoutController {
    private $db;
    private $orderModel;
    private $productModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->orderModel = new Order($this->db);
        $this->productModel = new Product($this->db);
    }

    public function index() {
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header("Location: " . BASE_URL . "index.php?controller=cart");
            exit;
        }

        $nav_categories = $this->productModel->getCategories();
        $cart_count = array_sum(array_column($cart, 'qty'));
        $total_amount = array_sum(array_map(function($item) {
            return $item['price'] * $item['qty'];
        }, $cart));

        require_once '../app/Views/client/checkout.php';
    }

    public function process() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cart = $_SESSION['cart'] ?? [];
            if (empty($cart)) {
                header("Location: " . BASE_URL . "index.php?controller=cart");
                exit;
            }

            $customer_name = $_POST['customer_name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';
            $note = $_POST['note'] ?? '';
            $user_id = $_SESSION['user']['id'] ?? null;

            if (empty($customer_name) || empty($phone) || empty($address)) {
                die("Vui lòng điền đầy đủ thông tin giao hàng!");
            }

            $total = array_sum(array_map(function($item) {
                return $item['price'] * $item['qty'];
            }, $cart));

            $order_id = $this->orderModel->createOrder($user_id, $customer_name, $phone, $address, $total, $note, $cart);

            if ($order_id) {
                // Clear cart
                unset($_SESSION['cart']);
                // Redirect to success page or home
                echo "<script>alert('Đặt hàng thành công! Mã đơn hàng: #$order_id'); window.location.href='" . BASE_URL . "index.php';</script>";
            } else {
                echo "<script>alert('Có lỗi xảy ra khi đặt hàng!'); window.history.back();</script>";
            }
        }
    }
}
?>