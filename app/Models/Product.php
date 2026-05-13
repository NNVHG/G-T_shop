<?php
namespace App\Models;
use PDO;

class Product {
    private ?PDO $conn = null;

    public function __construct(PDO $dbConnection) {
        $this->conn = $dbConnection;
    }

    // Lấy danh mục cho thanh điều hướng (Header)
    public function getCategories() {
        $stmt = $this->conn->prepare("SELECT * FROM categories ORDER BY sort_order");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh mục kèm số lượng sản phẩm
    public function getCategoriesWithCount() {
        $stmt = $this->conn->prepare("SELECT c.*, COUNT(p.id) AS product_count FROM categories c LEFT JOIN products p ON p.category_id = c.id AND p.is_active = 1 GROUP BY c.id ORDER BY c.sort_order");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Sản phẩm nổi bật
    public function getFeaturedProducts() {
        $stmt = $this->conn->prepare("SELECT p.*, c.name AS cat_name FROM products p JOIN categories c ON c.id = p.category_id WHERE p.is_featured = 1 AND p.is_active = 1 ORDER BY p.sold_count DESC LIMIT 10");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Sản phẩm mới nhất
    public function getNewestProducts() {
        $stmt = $this->conn->prepare("SELECT p.*, c.name AS cat_name FROM products p JOIN categories c ON c.id = p.category_id WHERE p.is_active = 1 ORDER BY p.created_at DESC LIMIT 10");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Banners
    public function getBanners() {
        $stmt = $this->conn->prepare("SELECT * FROM banners WHERE is_active = 1 ORDER BY sort_order");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchProducts(string $keyword) {
        // Kết hợp FULLTEXT SEARCH và LIKE để tối ưu kết quả tìm kiếm
        $stmt = $this->conn->prepare("
            SELECT id, name, slug, COALESCE(sale_price, price) AS display_price
            FROM products 
            WHERE is_active = 1 
              AND (
                  MATCH(name, description, author) AGAINST(:keyword IN BOOLEAN MODE)
                  OR name LIKE :like_keyword
              )
            ORDER BY sold_count DESC 
            LIMIT 8
        ");
        
        // Thêm '*' cho Fulltext để tìm cả những từ đang gõ dở
        $stmt->execute([
            'keyword' => $keyword . '*',
            'like_keyword' => "%$keyword%"
        ]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>