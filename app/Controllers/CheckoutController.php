<?php
namespace App\Controllers;

require_once '../app/Models/Database.php';
require_once '../app/Models/Order.php';
require_once '../app/Models/Product.php';

use App\Models\Database;
use App\Models\Order;
use App\Models\Product;
use PDO;

class CheckoutController {
    
    private ?PDO $db = null;
    private Order $orderModel;
    private Product $productModel;

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

    // Xử lý khi form checkout được submit (Action: place)
    public function place() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . \BASE_URL . 'index.php?controller=checkout');
            exit;
        }

        $payment_method = $_POST['payment_method'] ?? 'cod';
        // Lấy ID user (nếu đã đăng nhập, mặc định là 0 nếu mua dạng khách)
        $userId = $_SESSION['user']['id'] ?? 0; 
        
        if (empty($_SESSION['cart'])) {
            header('Location: ' . \BASE_URL . 'index.php?controller=cart');
            exit;
        }

        // 2. Khắc phục lỗi Undefined variable '$result' bằng cách mở comment và gọi hàm thực tế
        $result = $this->orderModel->placeOrder($_POST, $_SESSION['cart'], $userId);
        
        // Kiểm tra an toàn xem có tạo đơn thành công không
        if (!$result || !isset($result['order_id'])) {
            $_SESSION['error'] = 'Có lỗi xảy ra khi tạo đơn hàng!';
            header('Location: ' . \BASE_URL . 'index.php?controller=checkout');
            exit;
        }

        $orderId = $result['order_id']; 
        $totalAmount = $result['total']; 

        if ($payment_method === 'vnpay') {
            
            $vnp_Url = \VNP_URL;
            $vnp_Returnurl = \VNP_RETURN_URL;
            $vnp_TmnCode = \VNP_TMN_CODE;
            $vnp_HashSecret = \VNP_HASH_SECRET;

            $vnp_TxnRef = (string) $orderId;
            $vnp_OrderInfo = 'Thanh toan don hang ' . $orderId . ' tai G&T Shop';
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = $totalAmount * 100;
            $vnp_Locale = 'vn';
            
            $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
            if($vnp_IpAddr === '::1' || $vnp_IpAddr === '127.0.0.1') {
                $vnp_IpAddr = '127.0.0.1';
            }

            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $startTime = date('YmdHis');
            $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));

            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => $startTime,
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef,
                "vnp_ExpireDate" => $expire
            );

            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode((string)$value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode((string)$value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode((string)$value) . '&';
            }

            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }

            header('Location: ' . $vnp_Url);
            exit;
        }

        // 4. Nếu là COD, xóa giỏ hàng và báo thành công
        $_SESSION['cart'] = [];
        header('Location: ' . \BASE_URL . 'index.php?controller=checkout&action=success');
        exit;
    }

    public function vnpayReturn() {
        $vnp_SecureHash = $_GET['vnp_SecureHash'] ?? '';
        $inputData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode((string)$value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode((string)$value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, \VNP_HASH_SECRET);
        $orderId = (int)($_GET['vnp_TxnRef'] ?? 0);

        if ($secureHash === $vnp_SecureHash) {
            if ($_GET['vnp_ResponseCode'] == '00') {
                $this->orderModel->updatePaymentStatus($orderId, 'paid', $_GET['vnp_TransactionNo']);
                $_SESSION['cart'] = []; // Xóa session giỏ hàng
                header('Location: ' . \BASE_URL . 'index.php?controller=checkout&action=success');
            } else {
                $this->orderModel->updatePaymentStatus($orderId, 'failed', '');
                $_SESSION['error'] = 'Thanh toán không thành công (Mã lỗi: ' . $_GET['vnp_ResponseCode'] . ')!';
                header('Location: ' . \BASE_URL . 'index.php?controller=checkout');
            }
        } else {
            $_SESSION['error'] = 'Chữ ký bảo mật không hợp lệ!';
            header('Location: ' . \BASE_URL . 'index.php?controller=checkout');
        }
        exit;
    }
}