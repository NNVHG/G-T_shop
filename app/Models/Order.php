<?php
namespace App\Models;
use PDO;

class Order {
    private ?PDO $conn = null;

    public function __construct(PDO $dbConnection) {
        $this->conn = $dbConnection;
    }

    public function createOrder($user_id, $customer_name, $phone, $address, $total, $note, $cart_items) {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("INSERT INTO orders (user_id, customer_name, phone, address, total, note) VALUES (:user_id, :customer_name, :phone, :address, :total, :note)");
            $stmt->execute([
                'user_id' => $user_id,
                'customer_name' => $customer_name,
                'phone' => $phone,
                'address' => $address,
                'total' => $total,
                'note' => $note
            ]);

            $order_id = $this->conn->lastInsertId();

            $stmtItem = $this->conn->prepare("INSERT INTO order_items (order_id, product_id, qty, price) VALUES (:order_id, :product_id, :qty, :price)");
            
            // Cập nhật số lượng tồn kho
            $stmtStock = $this->conn->prepare("UPDATE products SET stock = stock - :qty, sold_count = sold_count + :qty WHERE id = :product_id");

            foreach ($cart_items as $item) {
                $stmtItem->execute([
                    'order_id' => $order_id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price']
                ]);

                $stmtStock->execute([
                    'qty' => $item['qty'],
                    'product_id' => $item['product_id']
                ]);
            }

            $this->conn->commit();
            return $order_id;
        } catch (\Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function getOrdersByUserId($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderItems($order_id) {
        $stmt = $this->conn->prepare("SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = :order_id");
        $stmt->execute(['order_id' => $order_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>