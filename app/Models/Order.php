<?php
namespace App\Models;

use PDO;
use Exception;

class Order {
    private ?PDO $db = null;

    public function __construct(PDO $dbConnection) {
        $this->db = $dbConnection;
    }

    // Hàm tạo đơn hàng mới
    public function placeOrder(array $postData, array $cart, int $userId = 0) {
        try {
            $this->db->beginTransaction();

            $totalAmount = 0;
            foreach ($cart as $item) {
                $totalAmount += $item['price'] * $item['qty'];
            }

            // Đã sửa lại tên cột cho khớp với database.sql (customer_name, phone, address, total)
            $stmt = $this->db->prepare("INSERT INTO orders (user_id, customer_name, phone, address, total, payment_method, payment_status, status) VALUES (?, ?, ?, ?, ?, ?, 'unpaid', 'pending')");
            
            $customerName = $postData['customer_name'] ?? '';
            $phone = $postData['phone'] ?? '';
            $address = $postData['address'] ?? '';
            $paymentMethod = $postData['payment_method'] ?? 'cod';
            
            $stmt->execute([$userId, $customerName, $phone, $address, $totalAmount, $paymentMethod]);
            $orderId = $this->db->lastInsertId();

            // Đã sửa lại tên bảng thành order_items và cột thành qty
            $stmtDetail = $this->db->prepare("INSERT INTO order_items (order_id, product_id, qty, price) VALUES (?, ?, ?, ?)");
            foreach ($cart as $item) {
                $stmtDetail->execute([$orderId, $item['product_id'], $item['qty'], $item['price']]);
            }

            $this->db->commit();
            return ['order_id' => $orderId, 'total' => $totalAmount];

        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // Hàm cập nhật trạng thái thanh toán (Dùng cho VNPAY)
    public function updatePaymentStatus(int $orderId, string $status, ?string $transactionId = null): bool {
        $allowed = ['unpaid', 'paid', 'failed'];
        if (!in_array($status, $allowed)) return false;

        $stmt = $this->db->prepare("UPDATE orders SET payment_status = :status, transaction_id = :txn_id WHERE id = :id");
        return $stmt->execute([
            ':status' => $status,
            ':txn_id' => $transactionId,
            ':id'     => $orderId
        ]);
    }

    // Lấy danh sách đơn hàng theo ID người dùng
    public function getOrdersByUserId(int $userId) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết các sản phẩm trong một đơn hàng
    public function getOrderItems(int $orderId) {
        $stmt = $this->db->prepare("
            SELECT oi.*, p.name, p.image
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = :order_id
        ");
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>