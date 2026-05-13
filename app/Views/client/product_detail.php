<?php 
/** 
 * @var array $product 
 * @var array $related_products
 */ 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($product['name']) ?> - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/base.css">
    <style>
        .product-detail-page { padding: 40px 0; background: var(--cream); min-height: 70vh; }
        .breadcrumb { margin-bottom: 30px; font-size: 14px; color: var(--text-muted); }
        .breadcrumb a { color: var(--brown-dark); text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }

        .product-main { background: var(--white); border-radius: var(--radius-lg); padding: 40px; border: 1px solid var(--border); display: flex; gap: 40px; align-items: flex-start; margin-bottom: 40px;}
        .product-image-container { flex: 0 0 400px; }
        .product-image-large { width: 100%; height: 500px; object-fit: cover; border-radius: var(--radius-md); border: 1px solid var(--cream-2); }
        
        .product-info-detail { flex: 1; }
        .product-title { font-family: 'Playfair Display', serif; font-size: 32px; color: var(--brown-dark); margin-bottom: 15px; }
        
        .product-meta { display: flex; align-items: center; gap: 20px; margin-bottom: 20px; font-size: 15px; color: var(--text-muted); }
        .product-rating i { color: #FFC107; font-size: 16px;}
        
        .product-price-box { margin-bottom: 30px; border-bottom: 1px solid var(--cream-2); padding-bottom: 20px;}
        .price-current { font-size: 28px; font-weight: bold; color: var(--amber-dark); }
        .price-old { font-size: 18px; text-decoration: line-through; color: #999; margin-left: 10px; }

        .product-desc { margin-bottom: 30px; line-height: 1.6; color: var(--text-main); }
        .product-desc h3 { font-size: 18px; color: var(--brown-dark); margin-bottom: 10px; }

        .add-to-cart-box { display: flex; align-items: center; gap: 20px; }
        .qty-input { width: 60px; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); text-align: center; font-size: 16px; outline: none; }
        .btn-add-cart-large { flex: 1; padding: 15px 30px; background: var(--brown-dark); color: var(--amber); border: none; border-radius: var(--radius-sm); font-size: 16px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn-add-cart-large:hover { background: var(--amber); color: var(--brown-dark); }
        .out-of-stock { color: #D32F2F; font-weight: bold; font-size: 16px; }

        /* Related Products */
        .section-title { font-family: 'Playfair Display', serif; font-size: 28px; color: var(--brown-dark); margin-bottom: 20px; text-align: center;}
        .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; }
        .product-card { background: var(--white); border-radius: var(--radius-lg); padding: 15px; text-align: center; border: 1px solid var(--border); transition: 0.3s; display: flex; flex-direction: column; justify-content: space-between;}
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); border-color: var(--amber-dark); }
        .product-img { width: 100%; height: 200px; object-fit: cover; border-radius: var(--radius-md); margin-bottom: 15px; border: 1px solid var(--cream-2);}
        .product-name { font-size: 16px; font-weight: 600; color: var(--brown-dark); text-decoration: none; margin-bottom: 10px; display: block; line-height: 1.4; height: 44px; overflow: hidden; }
        .product-price { color: var(--amber-dark); font-weight: bold; font-size: 18px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <?php include 'layouts/header.php'; ?>

    <main class="product-detail-page">
        <div class="container">
            <div class="breadcrumb">
                <a href="<?= BASE_URL ?>index.php">Trang Chủ</a> > 
                <a href="<?= BASE_URL ?>index.php?controller=product&action=category&slug=<?= isset($product['cat_slug']) ? e($product['cat_slug']) : '' ?>">Sản Phẩm</a> > 
                <span><?= e($product['name']) ?></span>
            </div>

            <div class="product-main">
                <div class="product-image-container">
                    <img class="product-image-large" src="<?= BASE_URL ?>images/<?= e($product['image'] ?? 'placeholder.png') ?>" alt="<?= e($product['name']) ?>">
                </div>

                <div class="product-info-detail">
                    <h1 class="product-title"><?= e($product['name']) ?></h1>
                    
                    <div class="product-meta">
                        <?php if(!empty($product['author'])): ?>
                            <span>Tác giả: <strong><?= e($product['author']) ?></strong></span>
                        <?php endif; ?>
                        <div class="product-rating">
                            <?= number_format($product['rating'], 1) ?> <i class="ti ti-star-filled"></i>
                        </div>
                        <span>Đã bán: <?= $product['sold_count'] ?></span>
                        <span>Kho: <?= $product['stock'] ?></span>
                    </div>

                    <div class="product-price-box">
                        <?php if(isset($product['sale_price'])): ?>
                            <span class="price-current"><?= formatPrice($product['sale_price']) ?></span>
                            <span class="price-old"><?= formatPrice($product['price']) ?></span>
                        <?php else: ?>
                            <span class="price-current"><?= formatPrice($product['price']) ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-desc">
                        <h3>Mô tả sản phẩm</h3>
                        <p><?= nl2br(e($product['description'] ?? 'Đang cập nhật...')) ?></p>
                    </div>

                    <?php if($product['stock'] > 0): ?>
                        <div class="add-to-cart-box">
                            <input type="number" id="qty" class="qty-input" value="1" min="1" max="<?= $product['stock'] ?>">
                            <button class="btn-add-cart-large" onclick="addToCartDetail(<?= $product['id'] ?>)">Thêm vào giỏ hàng</button>
                        </div>
                    <?php else: ?>
                        <div class="out-of-stock">Sản phẩm hiện đang hết hàng</div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if(!empty($related_products)): ?>
                <h2 class="section-title">Sản phẩm liên quan</h2>
                <div class="products-grid">
                    <?php foreach ($related_products as $p): ?>
                        <div class="product-card">
                            <a href="<?= BASE_URL ?>index.php?controller=product&action=detail&id=<?= $p['id'] ?>">
                                <img src="<?= BASE_URL ?>images/<?= e($p['image'] ?? 'placeholder.png') ?>" alt="<?= e($p['name']) ?>" class="product-img">
                            </a>
                            <div>
                                <a href="<?= BASE_URL ?>index.php?controller=product&action=detail&id=<?= $p['id'] ?>" class="product-name"><?= e($p['name']) ?></a>
                                <div class="product-price">
                                    <?php if(isset($p['sale_price'])): ?>
                                        <span style="text-decoration: line-through; color: #999; font-size: 14px;"><?= formatPrice($p['price']) ?></span>
                                        <?= formatPrice($p['sale_price']) ?>
                                    <?php else: ?>
                                        <?= formatPrice($p['price']) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </main>

    <?php include 'layouts/footer.php'; ?>
    <script>
        function addToCartDetail(productId) {
            let qty = document.getElementById('qty').value;
            fetch('<?= BASE_URL ?>index.php?controller=cart&action=add', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'product_id=' + productId + '&qty=' + qty
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    showToast('Đã thêm ' + qty + ' sản phẩm vào giỏ hàng!', 'success');
                    let badge = document.querySelector('.cart-badge');
                    if (badge) {
                        badge.textContent = data.cart_count;
                    } else {
                        location.reload();
                    }
                } else {
                    if (data.redirect) {
                        Swal.fire({
                            title: 'Yêu cầu đăng nhập',
                            text: data.message + "\nBạn có muốn chuyển đến trang đăng nhập không?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#5c4033',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Đăng nhập',
                            cancelButtonText: 'Hủy'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = data.redirect;
                            }
                        });
                    } else {
                        showToast(data.message || 'Có lỗi xảy ra', 'error');
                    }
                }
            });
        }
    </script>
</body>
</html>