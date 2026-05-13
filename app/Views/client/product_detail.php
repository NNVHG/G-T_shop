<?php 
/** @var array $product */ 
// Chú thích trên giúp trình kiểm tra mã hiểu biến $product là một mảng được truyền từ Controller
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['title']) ?> - G&T Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= BASE_URL ?>css/base.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/product.css">
</head>
<body>
    <?php include 'layouts/header.php'; ?>

    <main class="container">
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>index.php">Trang Chủ</a> > 
            <a href="<?= BASE_URL ?>index.php?controller=product">Sản Phẩm</a> > 
            <span><?= htmlspecialchars($product['title']) ?></span>
        </div>

        <div class="product-detail-wrapper">
            <div class="product-image-large">
                <img src="/G&T_shop/public/images/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['title']) ?>">
            </div>

            <div class="product-info-detail">
                <h1><?= htmlspecialchars($product['title']) ?></h1>
                <p class="price-large"><?= number_format($product['price'], 0, ',', '.') ?> VNĐ</p>
                
                <div class="description">
                    <h3>Mô tả sản phẩm:</h3>
                    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                </div>
                
                <p class="stock">Kho: <strong><?= $product['stock_quantity'] ?></strong> sản phẩm</p>

                <form action="<?= BASE_URL ?>index.php?controller=cart&action=add" method="POST" class="add-to-cart-form">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    
                    <div class="quantity-control">
                        <label for="qty">Số lượng:</label>
                        <input type="number" id="qty" name="quantity" value="1" min="1" max="<?= $product['stock_quantity'] ?>">
                    </div>
                    
                    <button type="submit" class="btn-add-cart large">Thêm vào giỏ hàng</button>
                </form>
            </div>
        </div>
    </main>

    <?php include 'layouts/footer.php'; ?>
</body>
</html>