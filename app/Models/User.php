<?php
namespace App\Models;
use PDO;

class User {
    private ?PDO $conn = null;

    public function __construct(PDO $dbConnection) {
        $this->conn = $dbConnection;
    }

    public function findByEmail(string $email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById(int $id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateInfo(int $id, string $name, string $phone, string $address) {
        $stmt = $this->conn->prepare("UPDATE users SET name = :name, phone = :phone, address = :address WHERE id = :id");
        return $stmt->execute([
            'name' => $name,
            'phone' => $phone,
            'address' => $address,
            'id' => $id
        ]);
    }

    public function updatePassword(int $id, string $new_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE users SET password = :password WHERE id = :id");
        return $stmt->execute([
            'password' => $hashed_password,
            'id' => $id
        ]);
    }

    public function register(string $name, string $email, string $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $hashed_password
        ]);
    }
}
?>