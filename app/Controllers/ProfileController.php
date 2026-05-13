<?php
namespace App\Controllers;

require_once '../app/Models/Database.php';
require_once '../app/Models/User.php';
require_once '../app/Models/Order.php';
require_once '../app/Models/Product.php';

use App\Models\Database;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;

class ProfileController {
    private $db;
    private $userModel;
    private $orderModel;
    private $productModel;

    public function __construct() {
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "index.php?controller=auth&action=login");
            exit;
        }

        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new User($this->db);
        $this->orderModel = new Order($this->db);
        $this->productModel = new Product($this->db);
    }

    public function index() {
        $nav_categories = $this->productModel->getCategories();
        $user_id = $_SESSION['user']['id'];
        
        $user_info = $this->userModel->findById($user_id);
        $orders = $this->orderModel->getOrdersByUserId($user_id);
        
        // Lấy chi tiết từng đơn hàng
        foreach ($orders as &$order) {
            $order['items'] = $this->orderModel->getOrderItems($order['id']);
        }

        require_once '../app/Views/client/profile.php';
    }

    public function updateInfo() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user']['id'];
            $name = trim($_POST['name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');

            if (empty($name)) {
                $_SESSION['profile_msg'] = ['type' => 'error', 'text' => 'Tên không được để trống!'];
            } else {
                if ($this->userModel->updateInfo($user_id, $name, $phone, $address)) {
                    $_SESSION['user']['name'] = $name; // Cập nhật lại session
                    $_SESSION['profile_msg'] = ['type' => 'success', 'text' => 'Cập nhật thông tin thành công!'];
                } else {
                    $_SESSION['profile_msg'] = ['type' => 'error', 'text' => 'Có lỗi xảy ra, vui lòng thử lại!'];
                }
            }
            header("Location: " . BASE_URL . "index.php?controller=profile");
            exit;
        }
    }

    public function updatePassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user']['id'];
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            $user_info = $this->userModel->findById($user_id);

            if (!password_verify($current_password, $user_info['password'])) {
                $_SESSION['profile_msg'] = ['type' => 'error', 'text' => 'Mật khẩu hiện tại không đúng!'];
            } elseif ($new_password !== $confirm_password) {
                $_SESSION['profile_msg'] = ['type' => 'error', 'text' => 'Mật khẩu xác nhận không khớp!'];
            } elseif (strlen($new_password) < 6) {
                $_SESSION['profile_msg'] = ['type' => 'error', 'text' => 'Mật khẩu mới phải có ít nhất 6 ký tự!'];
            } else {
                if ($this->userModel->updatePassword($user_id, $new_password)) {
                    $_SESSION['profile_msg'] = ['type' => 'success', 'text' => 'Đổi mật khẩu thành công!'];
                } else {
                    $_SESSION['profile_msg'] = ['type' => 'error', 'text' => 'Có lỗi xảy ra, vui lòng thử lại!'];
                }
            }
            header("Location: " . BASE_URL . "index.php?controller=profile");
            exit;
        }
    }
}
?>