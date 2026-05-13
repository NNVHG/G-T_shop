<?php
namespace App\Controllers;

require_once '../app/Models/Database.php';
require_once '../app/Models/User.php';
require_once '../app/Models/Product.php';

use App\Models\Database;
use App\Models\User;
use App\Models\Product;

class AuthController {
    private $db;
    private $userModel;
    private $productModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new User($this->db);
        $this->productModel = new Product($this->db);
    }

    public function login() {
        if (isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "index.php");
            exit;
        }
        $nav_categories = $this->productModel->getCategories();
        require_once '../app/Views/client/auth/login.php';
    }

    public function postLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->findByEmail($email);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];
                header("Location: " . BASE_URL . "index.php");
                exit;
            } else {
                $error = "Email hoặc mật khẩu không đúng!";
                $nav_categories = $this->productModel->getCategories();
                require_once '../app/Views/client/auth/login.php';
            }
        }
    }

    public function register() {
        if (isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "index.php");
            exit;
        }
        $nav_categories = $this->productModel->getCategories();
        require_once '../app/Views/client/auth/register.php';
    }

    public function postRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (empty($name) || empty($email) || empty($password)) {
                $error = "Vui lòng điền đầy đủ thông tin!";
                $nav_categories = $this->productModel->getCategories();
                require_once '../app/Views/client/auth/register.php';
                return;
            }

            if ($password !== $confirm_password) {
                $error = "Mật khẩu xác nhận không khớp!";
                $nav_categories = $this->productModel->getCategories();
                require_once '../app/Views/client/auth/register.php';
                return;
            }

            if ($this->userModel->findByEmail($email)) {
                $error = "Email đã được sử dụng!";
                $nav_categories = $this->productModel->getCategories();
                require_once '../app/Views/client/auth/register.php';
                return;
            }

            if ($this->userModel->register($name, $email, $password)) {
                // Auto login after register
                $user = $this->userModel->findByEmail($email);
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];
                header("Location: " . BASE_URL . "index.php");
                exit;
            } else {
                $error = "Có lỗi xảy ra, vui lòng thử lại!";
                $nav_categories = $this->productModel->getCategories();
                require_once '../app/Views/client/auth/register.php';
            }
        }
    }

    public function logout() {
        unset($_SESSION['user']);
        header("Location: " . BASE_URL . "index.php");
        exit;
    }
}
?>