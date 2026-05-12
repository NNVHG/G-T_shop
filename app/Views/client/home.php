<?php 
/** @var array $products */ 
// Chú thích trên giúp trình kiểm tra mã (IDE) hiểu biến $products là một mảng được truyền từ Controller
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hiệu Sách & Văn Phòng Phẩm</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
</head>
<body>
    <?php include 'layouts/header.php'; ?>

    <main class="container">
        <h1>Sản Phẩm Mới Nhất</h1>
        <div class="product-grid">
            <?php foreach ($products as $p): ?>
                <div class="product-card">
                    <img src="<?= BASE_URL ?>images/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['title']) ?>">
                    <a href="<?= BASE_URL ?>index.php?controller=product&action=detail&id=<?= $p['id'] ?>" style="text-decoration: none;">
                        <h3><?= htmlspecialchars($p['title']) ?></h3>
                    </a>
                    <p class="price"><?= number_format($p['price'], 0, ',', '.') ?> VNĐ</p>
                    <button class="btn-add-cart">Thêm vào giỏ</button>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <?php include 'layouts/footer.php'; ?>
</body>
</html>