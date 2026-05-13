<?php
// cart_action.php — Xử lý giỏ hàng qua AJAX
require_once 'includes/config.php';
header('Content-Type: application/json');

$action     = $_POST['action']     ?? '';
$product_id = (int)($_POST['product_id'] ?? 0);
$qty        = max(1, (int)($_POST['qty'] ?? 1));

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if ($action === 'add') {
    // Kiểm tra sản phẩm tồn tại
    $stmt = $pdo->prepare("SELECT id, name, price, sale_price, stock FROM products WHERE id = ? AND is_active = 1");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
        exit;
    }
    if ($product['stock'] < 1) {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm đã hết hàng']);
        exit;
    }

    $key = 'p_' . $product_id;
    if (isset($_SESSION['cart'][$key])) {
        $_SESSION['cart'][$key]['qty'] += $qty;
    } else {
        $_SESSION['cart'][$key] = [
            'product_id' => $product_id,
            'name'       => $product['name'],
            'price'      => $product['sale_price'] ?? $product['price'],
            'qty'        => $qty,
        ];
    }

    $cart_count = array_sum(array_column($_SESSION['cart'], 'qty'));
    echo json_encode(['success' => true, 'cart_count' => $cart_count]);

} elseif ($action === 'remove') {
    $key = 'p_' . $product_id;
    unset($_SESSION['cart'][$key]);
    $cart_count = array_sum(array_column($_SESSION['cart'], 'qty'));
    echo json_encode(['success' => true, 'cart_count' => $cart_count]);

} elseif ($action === 'update') {
    $key = 'p_' . $product_id;
    if ($qty < 1) {
        unset($_SESSION['cart'][$key]);
    } elseif (isset($_SESSION['cart'][$key])) {
        $_SESSION['cart'][$key]['qty'] = $qty;
    }
    $cart_count = array_sum(array_column($_SESSION['cart'], 'qty'));
    $total = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $_SESSION['cart']));
    echo json_encode(['success' => true, 'cart_count' => $cart_count, 'total' => formatPrice($total)]);

} else {
    echo json_encode(['success' => false, 'message' => 'Action không hợp lệ']);
}
