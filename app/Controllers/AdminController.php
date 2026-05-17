<?php
namespace App\Controllers;

require_once '../app/Models/Database.php';
require_once '../app/Models/User.php';
require_once '../app/Models/Product.php';
require_once '../app/Models/Order.php';

use App\Models\Database;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use PDO;

class AdminController {
    private ?PDO $db = null;
    private User $userModel;
    private Product $productModel;
    private Order $orderModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new User($this->db);
        $this->productModel = new Product($this->db);
        $this->orderModel = new Order($this->db);
    }

    private function isAdmin(): bool {
        return ($_SESSION['user']['role'] ?? '') === 'admin';
    }

    private function requireAdmin(): void {
        if (!$this->isAdmin()) {
            header('Location: ' . BASE_URL . 'index.php?controller=admin&action=login');
            exit;
        }
    }

    // --- XỬ LÝ ĐĂNG NHẬP / ĐĂNG XUẤT ADMIN ---
    
    public function login() {
        if ($this->isAdmin()) {
            header('Location: ' . BASE_URL . 'index.php?controller=admin&action=dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $user = $this->userModel->findByEmail($email);
            if ($user && $user['role'] === 'admin' && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];
                header('Location: ' . BASE_URL . 'index.php?controller=admin&action=dashboard');
                exit;
            } else {
                $error = 'Email hoặc mật khẩu Admin không chính xác!';
                require_once '../app/Views/admin/login.php';
            }
        } else {
            require_once '../app/Views/admin/login.php';
        }
    }

    public function logout() {
        unset($_SESSION['user']);
        header('Location: ' . BASE_URL . 'index.php?controller=admin&action=login');
        exit;
    }

    // --- DASHBOARD ---
    
    public function dashboard() {
        $this->requireAdmin();

        // 1. Thống kê sản phẩm
        $stmt = $this->db->query("SELECT COUNT(*) FROM products");
        $total_products = $stmt->fetchColumn();

        // 2. Thống kê đơn hàng
        $stmt = $this->db->query("SELECT COUNT(*) FROM orders");
        $total_orders = $stmt->fetchColumn();

        // 3. Doanh thu tổng (các đơn hàng không bị hủy và đã thanh toán)
        $stmt = $this->db->query("SELECT SUM(total) FROM orders WHERE status != 'cancelled' AND payment_status = 'paid'");
        $total_sales = $stmt->fetchColumn() ?? 0;

        // 4. Số sản phẩm sắp hết hàng (stock <= 5)
        $stmt = $this->db->query("SELECT COUNT(*) FROM products WHERE stock <= 5 AND is_active = 1");
        $low_stock_count = $stmt->fetchColumn();

        // 5. Đơn hàng gần đây
        $stmt = $this->db->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");
        $latest_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 6. Sản phẩm sắp hết hàng chi tiết
        $stmt = $this->db->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.stock <= 5 AND p.is_active = 1 ORDER BY p.stock ASC LIMIT 5");
        $low_stock_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $title = 'Bảng điều khiển';
        $content_view = '../app/Views/admin/dashboard.php';
        require_once '../app/Views/layouts/admin.php';
    }

    // --- QUẢN LÝ SẢN PHẨM (CRUD) ---
    
    public function products() {
        $this->requireAdmin();

        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
        $status = isset($_GET['status']) ? trim($_GET['status']) : 'all';

        $query = "SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            if (is_numeric($search)) {
                $query .= " AND (p.id = ? OR p.name LIKE ? OR p.author LIKE ? OR p.slug LIKE ?)";
                $params[] = (int)$search;
                $params[] = "%$search%";
                $params[] = "%$search%";
                $params[] = "%$search%";
            } elseif (strpos($search, '#') === 0 && is_numeric(substr($search, 1))) {
                $query .= " AND p.id = ?";
                $params[] = (int)substr($search, 1);
            } else {
                $query .= " AND (p.name LIKE ? OR p.author LIKE ? OR p.slug LIKE ?)";
                $params[] = "%$search%";
                $params[] = "%$search%";
                $params[] = "%$search%";
            }
        }

        if ($category_id > 0) {
            $query .= " AND p.category_id = ?";
            $params[] = $category_id;
        }

        if ($status === 'active') {
            $query .= " AND p.is_active = 1";
        } elseif ($status === 'inactive') {
            $query .= " AND p.is_active = 0";
        }

        $query .= " ORDER BY p.id DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch categories for filtering
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY sort_order");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $title = 'Quản lý Sản phẩm';
        $content_view = '../app/Views/admin/products/index.php';
        require_once '../app/Views/layouts/admin.php';
    }

    public function productCreate() {
        $this->requireAdmin();
        
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY sort_order");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $title = 'Thêm sản phẩm';
        $content_view = '../app/Views/admin/products/form.php';
        require_once '../app/Views/layouts/admin.php';
    }

    public function productStore() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $author = trim($_POST['author'] ?? '');
            $price = (int)($_POST['price'] ?? 0);
            $sale_price = !empty($_POST['sale_price']) ? (int)$_POST['sale_price'] : null;
            $stock = (int)($_POST['stock'] ?? 0);
            $category_id = (int)$_POST['category_id'] ?? 0;
            $description = trim($_POST['description'] ?? '');
            $badge = trim($_POST['badge'] ?? '');
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            if (empty($slug)) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
            }

            // Xử lý upload ảnh
            $image = 'default.jpg';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $new_name = 'prod_' . time() . '_' . uniqid() . '.' . $ext;
                $target = '../public/images/' . $new_name;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                    $image = $new_name;
                }
            }

            $stmt = $this->db->prepare("
                INSERT INTO products (category_id, name, slug, description, author, price, sale_price, stock, image, badge, is_featured, is_active)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $category_id, $name, $slug, $description, $author, $price, $sale_price, $stock, $image, $badge, $is_featured, $is_active
            ]);

            $_SESSION['toast_msg'] = ['type' => 'success', 'text' => 'Thêm sản phẩm mới thành công!'];
            header('Location: ' . BASE_URL . 'index.php?controller=admin&action=products');
            exit;
        }
    }

    public function productEdit() {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);

        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            header('Location: ' . BASE_URL . 'index.php?controller=admin&action=products');
            exit;
        }

        $stmt = $this->db->query("SELECT * FROM categories ORDER BY sort_order");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $title = 'Sửa sản phẩm: ' . $product['name'];
        $content_view = '../app/Views/admin/products/form.php';
        require_once '../app/Views/layouts/admin.php';
    }

    public function productUpdate() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $author = trim($_POST['author'] ?? '');
            $price = (int)($_POST['price'] ?? 0);
            $sale_price = !empty($_POST['sale_price']) ? (int)$_POST['sale_price'] : null;
            $stock = (int)($_POST['stock'] ?? 0);
            $category_id = (int)$_POST['category_id'] ?? 0;
            $description = trim($_POST['description'] ?? '');
            $badge = trim($_POST['badge'] ?? '');
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            if (empty($slug)) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
            }

            // Lấy ảnh cũ
            $stmt = $this->db->prepare("SELECT image FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $image = $stmt->fetchColumn() ?: 'default.jpg';

            // Xử lý upload ảnh mới nếu có
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $new_name = 'prod_' . time() . '_' . uniqid() . '.' . $ext;
                $target = '../public/images/' . $new_name;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                    // Xóa ảnh cũ nếu không phải default
                    if ($image !== 'default.jpg' && file_exists('../public/images/' . $image)) {
                        @unlink('../public/images/' . $image);
                    }
                    $image = $new_name;
                }
            }

            $stmt = $this->db->prepare("
                UPDATE products 
                SET category_id = ?, name = ?, slug = ?, description = ?, author = ?, price = ?, sale_price = ?, stock = ?, image = ?, badge = ?, is_featured = ?, is_active = ?
                WHERE id = ?
            ");
            
            $stmt->execute([
                $category_id, $name, $slug, $description, $author, $price, $sale_price, $stock, $image, $badge, $is_featured, $is_active, $id
            ]);

            $_SESSION['toast_msg'] = ['type' => 'success', 'text' => 'Cập nhật sản phẩm thành công!'];
            header('Location: ' . BASE_URL . 'index.php?controller=admin&action=products');
            exit;
        }
    }

    public function productDelete() {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);

        // Xóa ảnh cũ
        $stmt = $this->db->prepare("SELECT image FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $image = $stmt->fetchColumn();
        if ($image && $image !== 'default.jpg' && file_exists('../public/images/' . $image)) {
            @unlink('../public/images/' . $image);
        }

        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);

        $_SESSION['toast_msg'] = ['type' => 'success', 'text' => 'Xóa sản phẩm thành công!'];
        header('Location: ' . BASE_URL . 'index.php?controller=admin&action=products');
        exit;
    }

    // --- QUẢN LÝ DANH MỤC (CRUD) ---
    
    public function categories() {
        $this->requireAdmin();

        $stmt = $this->db->query("SELECT c.*, COUNT(p.id) as product_count FROM categories c LEFT JOIN products p ON p.category_id = c.id GROUP BY c.id ORDER BY c.sort_order");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $title = 'Quản lý Danh mục';
        $content_view = '../app/Views/admin/categories/index.php';
        require_once '../app/Views/layouts/admin.php';
    }

    public function categoryCreate() {
        $this->requireAdmin();
        $title = 'Thêm danh mục';
        $content_view = '../app/Views/admin/categories/form.php';
        require_once '../app/Views/layouts/admin.php';
    }

    public function categoryStore() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $icon = trim($_POST['icon'] ?? 'ti-tag');
            $sort_order = (int)($_POST['sort_order'] ?? 0);

            if (empty($slug)) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
            }

            $stmt = $this->db->prepare("INSERT INTO categories (name, slug, icon, sort_order) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $slug, $icon, $sort_order]);

            $_SESSION['toast_msg'] = ['type' => 'success', 'text' => 'Thêm danh mục thành công!'];
            header('Location: ' . BASE_URL . 'index.php?controller=admin&action=categories');
            exit;
        }
    }

    public function categoryEdit() {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);

        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$category) {
            header('Location: ' . BASE_URL . 'index.php?controller=admin&action=categories');
            exit;
        }

        $title = 'Sửa danh mục: ' . $category['name'];
        $content_view = '../app/Views/admin/categories/form.php';
        require_once '../app/Views/layouts/admin.php';
    }

    public function categoryUpdate() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $icon = trim($_POST['icon'] ?? 'ti-tag');
            $sort_order = (int)($_POST['sort_order'] ?? 0);

            if (empty($slug)) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
            }

            $stmt = $this->db->prepare("UPDATE categories SET name = ?, slug = ?, icon = ?, sort_order = ? WHERE id = ?");
            $stmt->execute([$name, $slug, $icon, $sort_order, $id]);

            $_SESSION['toast_msg'] = ['type' => 'success', 'text' => 'Cập nhật danh mục thành công!'];
            header('Location: ' . BASE_URL . 'index.php?controller=admin&action=categories');
            exit;
        }
    }

    public function categoryDelete() {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);

        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);

        $_SESSION['toast_msg'] = ['type' => 'success', 'text' => 'Xóa danh mục thành công!'];
        header('Location: ' . BASE_URL . 'index.php?controller=admin&action=categories');
        exit;
    }

    // --- QUẢN LÝ ĐƠN HÀNG ---
    
    public function orders() {
        $this->requireAdmin();

        $stmt = $this->db->query("SELECT * FROM orders ORDER BY created_at DESC");
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $title = 'Quản lý Đơn hàng';
        $content_view = '../app/Views/admin/orders/index.php';
        require_once '../app/Views/layouts/admin.php';
    }

    public function orderDetail() {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);

        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            header('Location: ' . BASE_URL . 'index.php?controller=admin&action=orders');
            exit;
        }

        // Lấy danh sách sản phẩm trong đơn hàng
        $stmt = $this->db->prepare("
            SELECT oi.*, p.name as product_name, p.image 
            FROM order_items oi 
            JOIN products p ON oi.product_id = p.id 
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $title = 'Đơn hàng #' . $order['id'];
        $content_view = '../app/Views/admin/orders/detail.php';
        require_once '../app/Views/layouts/admin.php';
    }

    public function orderStatus() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $status = trim($_POST['status'] ?? '');

            $stmt = $this->db->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);

            $_SESSION['toast_msg'] = ['type' => 'success', 'text' => 'Cập nhật trạng thái đơn hàng thành công!'];
            header('Location: ' . BASE_URL . 'index.php?controller=admin&action=orderDetail&id=' . $id);
            exit;
        }
    }

    public function markPaid() {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);

        $stmt = $this->db->prepare("UPDATE orders SET payment_status = 'paid' WHERE id = ?");
        $stmt->execute([$id]);

        $_SESSION['toast_msg'] = ['type' => 'success', 'text' => 'Đã đánh dấu đơn hàng đã thanh toán!'];
        header('Location: ' . BASE_URL . 'index.php?controller=admin&action=orderDetail&id=' . $id);
        exit;
    }

    // --- QUẢN LÝ KHÁCH HÀNG / USER ---
    
    public function users() {
        $this->requireAdmin();

        $stmt = $this->db->query("SELECT * FROM users ORDER BY id DESC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $title = 'Quản lý Tài khoản';
        $content_view = '../app/Views/admin/users/index.php';
        require_once '../app/Views/layouts/admin.php';
    }

    public function changeUserRole() {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        $role = trim($_GET['role'] ?? 'user');

        if ($id === (int)($_SESSION['user']['id'] ?? 0)) {
            $_SESSION['toast_msg'] = ['type' => 'error', 'text' => 'Bạn không thể tự đổi quyền của chính mình!'];
            header('Location: ' . BASE_URL . 'index.php?controller=admin&action=users');
            exit;
        }

        $stmt = $this->db->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$role, $id]);

        $_SESSION['toast_msg'] = ['type' => 'success', 'text' => 'Cập nhật quyền tài khoản thành công!'];
        header('Location: ' . BASE_URL . 'index.php?controller=admin&action=users');
        exit;
    }

    // --- QUẢN LÝ KHO HÀNG (INVENTORY) ---
    
    public function inventory() {
        $this->requireAdmin();

        // Lấy bộ lọc từ URL
        $cat_filter = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
        $stock_filter = isset($_GET['stock_status']) ? trim($_GET['stock_status']) : 'all';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // 1. Thống kê tổng quan kho
        $stmt = $this->db->query("SELECT COUNT(*) FROM products");
        $total_skus = $stmt->fetchColumn();

        $stmt = $this->db->query("SELECT SUM(stock) FROM products");
        $total_stock_items = $stmt->fetchColumn() ?? 0;

        $stmt = $this->db->query("SELECT COUNT(*) FROM products WHERE stock = 0 AND is_active = 1");
        $out_of_stock_count = $stmt->fetchColumn();

        $stmt = $this->db->query("SELECT COUNT(*) FROM products WHERE stock > 0 AND stock <= 5 AND is_active = 1");
        $low_stock_count = $stmt->fetchColumn();

        // 2. Xây dựng câu truy vấn có điều kiện lọc
        $query = "
            SELECT p.*, c.name as category_name 
            FROM products p 
            JOIN categories c ON p.category_id = c.id 
            WHERE 1=1
        ";
        $params = [];

        if ($cat_filter > 0) {
            $query .= " AND p.category_id = ?";
            $params[] = $cat_filter;
        }

        if ($stock_filter === 'out') {
            $query .= " AND p.stock = 0";
        } elseif ($stock_filter === 'low') {
            $query .= " AND p.stock > 0 AND p.stock <= 5";
        } elseif ($stock_filter === 'safe') {
            $query .= " AND p.stock > 5";
        }

        if (!empty($search)) {
            $query .= " AND (p.name LIKE ? OR p.author LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        $query .= " ORDER BY p.stock ASC, p.id DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Lấy danh sách danh mục để đổ vào bộ lọc
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY sort_order");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $title = 'Quản lý Kho hàng';
        $content_view = '../app/Views/admin/inventory/index.php';
        require_once '../app/Views/layouts/admin.php';
    }

    public function inventoryUpdate() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $stock = (int)($_POST['stock'] ?? 0);

            if ($stock < 0) {
                $_SESSION['toast_msg'] = ['type' => 'error', 'text' => 'Số lượng tồn kho không được nhỏ hơn 0!'];
            } else {
                $stmt = $this->db->prepare("UPDATE products SET stock = ? WHERE id = ?");
                $stmt->execute([$stock, $id]);
                $_SESSION['toast_msg'] = ['type' => 'success', 'text' => 'Cập nhật tồn kho thành công!'];
            }

            // Quay lại trang kho hàng có giữ nguyên bộ lọc
            $cat = (int)($_POST['cat'] ?? 0);
            $status = trim($_POST['stock_status'] ?? 'all');
            $search = trim($_POST['search'] ?? '');
            
            header('Location: ' . BASE_URL . 'index.php?controller=admin&action=inventory&cat=' . $cat . '&stock_status=' . $status . '&search=' . urlencode($search));
            exit;
        }
    }
}
