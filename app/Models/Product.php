<?php
namespace App\Models;

use PDO;

class Product {
    private ?PDO $conn = null;

    // Khai báo rõ kiểu dữ liệu PDO cho tham số $dbConnection
    public function __construct(PDO $dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getAllProducts() {
        $stmt = $this->conn->prepare("SELECT * FROM products ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Khai báo rõ kiểu dữ liệu int cho tham số $id
    public function getProductById(int $id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>