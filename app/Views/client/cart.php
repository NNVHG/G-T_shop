<?php 
/** * @var array $cart 
 * @var int $cart_count 
 * @var int $total_amount 
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng của bạn — <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/base.css">
    <style>
        .cart-page { padding: 40px 0 80px; background: var(--cream); min-height: 60vh; }
        .cart-title { font-family: 'Playfair Display', serif; font-size: 32px; color: var(--brown-dark); margin-bottom: 30px; border-bottom: 2px solid var(--border); padding-bottom: 15px; }
        .cart-container { display: flex; gap: 30px; align-items: flex-start; }
        .cart-items { flex: 1; background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--border); overflow: hidden; }
        .cart-item { display: flex; align-items: center; padding: 20px; border-bottom: 1px solid var(--border); gap: 20px; }
        .cart-item:last-child { border-bottom: none; }
        .cart-item-img { width: 80px; height: 80px; border-radius: var(--radius-md); background: var(--cream-2); object-fit: cover; border: 1px solid var(--border);}
        .cart-item-info { flex: 1; }
        .cart-item-name { font-size: 16px; font-weight: 600; color: var(--brown-dark); margin-bottom: 5px; display: block; }
        .cart-item-price { color: var(--amber-dark); font-weight: bold; }
        .cart-qty { display: flex; align-items: center; border: 1px solid var(--border); border-radius: var(--radius-sm); overflow: hidden; width: max-content; }
        .qty-btn { background: var(--cream-2); border: none; width: 32px; height: 32px; cursor: pointer; transition: 0.2s; }
        .qty-btn:hover { background: var(--border); }
        .qty-input { width: 40px; height: 32px; border: none; text-align: center; font-weight: 500; outline: none; border-left: 1px solid var(--border); border-right: 1px solid var(--border); background: var(--white); color: var(--text-main);}
        .cart-item-total { font-size: 16px; font-weight: bold; color: var(--brown-dark); min-width: 100px; text-align: right; }
        .btn-remove { background: none; border: none; color: #D32F2F; font-size: 20px; cursor: pointer; padding: 5px; transition: 0.2s; }
        .btn-remove:hover { transform: scale(1.1); }
        
        .cart-summary { width: 350px; background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 25px; position: sticky; top: 100px; }
        .summary-title { font-family: 'Playfair Display', serif; font-size: 22px; color: var(--brown-dark); margin-bottom: 20px; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 15px; color: var(--text-muted); }
        .summary-total { display: flex; justify-content: space-between; margin-top: 20px; padding-top: 20px; border-top: 1px dashed var(--border); font-size: 20px; font-weight: bold; color: var(--amber-dark); }
        .btn-checkout { display: block; width: 100%; text-align: center; background: var(--brown-dark); color: var(--amber); padding: 15px; border-radius: var(--radius-md); font-weight: 600; font-size: 16px; margin-top: 25px; transition: 0.3s; border: none; cursor: pointer;}
        .btn-checkout:hover { background: var(--amber); color: var(--brown-dark); }
        .empty-cart { text-align: center; padding: 60px 20px; color: var(--text-muted); }
        .empty-cart i { font-size: 60px; color: var(--border); margin-bottom: 20px; }
    </style>
</head>
<body>
    <?php include 'layouts/header.php'; ?>

    <main class="cart-page">
        <div class="container">
            <h1 class="cart-title">Giỏ hàng của bạn (<span id="cartCountTitle"><?= $cart_count ?></span>)</h1>
            
            <?php if (empty($cart)): ?>
                <div class="empty-cart">
                    <i class="ti ti-shopping-cart-x"></i>
                    <h2>Giỏ hàng đang trống!</h2>
                    <p>Hãy tìm thêm những sản phẩm yêu thích và thêm vào giỏ hàng nhé.</p>
                    <a href="<?= BASE_URL ?>index.php" class="btn-primary" style="margin-top:20px;">Tiếp tục mua sắm</a>
                </div>
            <?php else: ?>
                <div class="cart-container">
                    <div class="cart-items">
                        <?php foreach ($cart as $key => $item): ?>
                            <div class="cart-item" data-id="<?= $item['product_id'] ?>">
                                <img src="<?= BASE_URL ?>images/<?= e($item['image']) ?>" alt="<?= e($item['name']) ?>" class="cart-item-img">
                                <div class="cart-item-info">
                                    <a href="<?= BASE_URL ?>index.php?controller=product&action=detail&id=<?= $item['product_id'] ?>" class="cart-item-name"><?= e($item['name']) ?></a>
                                    <div class="cart-item-price"><?= formatPrice($item['price']) ?></div>
                                </div>
                                <div class="cart-qty">
                                    <button class="qty-btn btn-minus"><i class="ti ti-minus"></i></button>
                                    <input type="number" class="qty-input" value="<?= $item['qty'] ?>" min="1" max="<?= $item['stock'] ?>" readonly>
                                    <button class="qty-btn btn-plus"><i class="ti ti-plus"></i></button>
                                </div>
                                <div class="cart-item-total item-total-price"><?= formatPrice($item['price'] * $item['qty']) ?></div>
                                <button class="btn-remove" title="Xóa"><i class="ti ti-trash"></i></button>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="cart-summary">
                        <h3 class="summary-title">Tóm tắt đơn hàng</h3>
                        <div class="summary-row">
                            <span>Tạm tính:</span>
                            <span id="summarySubtotal"><?= formatPrice($total_amount) ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Phí giao hàng:</span>
                            <span>Miễn phí</span>
                        </div>
                        <div class="summary-total">
                            <span>Tổng cộng:</span>
                            <span id="summaryTotal"><?= formatPrice($total_amount) ?></span>
                        </div>
                        <button class="btn-checkout" onclick="location.href='<?= BASE_URL ?>index.php?controller=checkout'">Tiến hành thanh toán →</button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'layouts/footer.php'; ?>
</body>
</html>