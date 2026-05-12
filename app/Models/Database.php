<?php
namespace App\Models;

use PDO;
use PDOException;

class Database {
    // Khai báo rõ kiểu dữ liệu 'string' cho các biến chuỗi
    private string $host = DB_HOST;
    private string $user = DB_USER;
    private string $pass = DB_PASS;
    private string $dbname = DB_NAME;
    
    // Khai báo kiểu '?PDO' (có thể là đối tượng PDO hoặc null)
    protected ?PDO $conn = null;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->conn = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            die("Lỗi kết nối Cơ sở dữ liệu: " . $e->getMessage());
        }
    }
    
    public function getConnection() {
        return $this->conn;
    }
}
?>