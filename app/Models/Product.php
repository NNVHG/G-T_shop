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

    // Hàm lấy chi tiết một sản phẩm theo ID
    public function getProductById(int $id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = :id AND is_active = 1 LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProductsList($category_slug = '', $keyword = '', $sort = '', $min_price = null, $max_price = null) {
        $sql = "SELECT p.*, c.name AS cat_name FROM products p LEFT JOIN categories c ON c.id = p.category_id WHERE p.is_active = 1";
        $params = [];

        if (!empty($category_slug)) {
            $sql .= " AND c.slug = :category_slug";
            $params['category_slug'] = $category_slug;
        }

        if (!empty($keyword)) {
            $sql .= " AND (p.name LIKE :keyword OR p.author LIKE :keyword OR p.description LIKE :keyword)";
            $params['keyword'] = '%' . $keyword . '%';
        }

        if ($min_price !== null && $min_price !== '') {
            $sql .= " AND COALESCE(p.sale_price, p.price) >= :min_price";
            $params['min_price'] = $min_price;
        }

        if ($max_price !== null && $max_price !== '') {
            $sql .= " AND COALESCE(p.sale_price, p.price) <= :max_price";
            $params['max_price'] = $max_price;
        }

        switch ($sort) {
            case 'price_asc':
                $sql .= " ORDER BY COALESCE(p.sale_price, p.price) ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY COALESCE(p.sale_price, p.price) DESC";
                break;
            case 'newest':
                $sql .= " ORDER BY p.created_at DESC";
                break;
            case 'rating':
                $sql .= " ORDER BY p.rating DESC";
                break;
            default:
                $sql .= " ORDER BY p.sold_count DESC";
                break;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>