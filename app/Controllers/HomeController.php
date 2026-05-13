<?php
namespace App\Controllers;

require_once '../app/Models/Database.php';
require_once '../app/Models/Product.php';

use App\Models\Database;
use App\Models\Product;

class HomeController {
    public function index() {
    $database = new Database();
    $db = $database->getConnection();
    $productModel = new Product($db);

    // Lấy dữ liệu theo đúng logic của file index.php gốc
    $nav_categories = $productModel->getCategories();
    $categories = $productModel->getCategoriesWithCount();
    $featured = $productModel->getFeaturedProducts();
    $newest = $productModel->getNewestProducts();
    $banners = $productModel->getBanners();
    $cart_count = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0;

    // Truyền dữ liệu sang View
    require_once '../app/Views/client/home.php';
    }
}
?>