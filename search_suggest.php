<?php
// search_suggest.php — AJAX gợi ý tìm kiếm real-time
require_once 'includes/config.php';
header('Content-Type: application/json');

$q = trim($_GET['q'] ?? '');

if (strlen($q) < 2) { echo '[]'; exit; }

// Dùng FULLTEXT SEARCH hoặc LIKE tùy dữ liệu
$stmt = $pdo->prepare("
    SELECT p.id, p.name, p.slug,
           COALESCE(p.sale_price, p.price) AS display_price
    FROM products p
    WHERE p.is_active = 1
      AND (p.name LIKE ? OR p.author LIKE ?)
    ORDER BY p.sold_count DESC
    LIMIT 8
");
$like = '%' . $q . '%';
$stmt->execute([$like, $like]);
$results = $stmt->fetchAll();

$output = array_map(fn($p) => [
    'id'        => $p['id'],
    'name'      => $p['name'],
    'slug'      => $p['slug'],
    'price_fmt' => formatPrice((int)$p['display_price']),
], $results);

echo json_encode($output, JSON_UNESCAPED_UNICODE);
