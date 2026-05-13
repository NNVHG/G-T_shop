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
    <title>Thanh toán — <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/base.css">
    <style>
        .checkout-page { padding: 40px 0 80px; background: var(--cream); min-height: 60vh; }
        .checkout-title { font-family: 'Playfair Display', serif; font-size: 32px; color: var(--brown-dark); margin-bottom: 30px; border-bottom: 2px solid var(--border); padding-bottom: 15px; }
        .checkout-container { display: flex; gap: 30px; align-items: flex-start; }
        .checkout-form { flex: 1; background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 30px; }
        
        .form-title { font-family: 'Playfair Display', serif; font-size: 22px; color: var(--brown-dark); margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: var(--text-main); }
        .form-group input, .form-group textarea { width: 100%; padding: 12px 15px; border: 1px solid var(--border); border-radius: var(--radius-md); outline: none; transition: 0.3s; font-family: 'DM Sans', sans-serif;}
        .form-group input:focus, .form-group textarea:focus { border-color: var(--amber-dark); }
        .form-group textarea { resize: vertical; min-height: 100px; }

        .checkout-summary { width: 400px; background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 25px; position: sticky; top: 100px; }
        .summary-item { display: flex; align-items: center; padding: 15px 0; border-bottom: 1px solid var(--cream-2); gap: 15px; }
        .summary-item:last-of-type { border-bottom: none; margin-bottom: 15px;}
        .summary-item-img { width: 60px; height: 60px; border-radius: var(--radius-sm); object-fit: cover; border: 1px solid var(--border); }
        .summary-item-info { flex: 1; }
        .summary-item-name { font-size: 14px; font-weight: 600; color: var(--brown-dark); display: block; margin-bottom: 4px; }
        .summary-item-meta { font-size: 13px; color: var(--text-muted); }
        .summary-item-price { font-weight: bold; color: var(--brown-dark); }
        
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 15px; color: var(--text-muted); }
        .summary-total { display: flex; justify-content: space-between; margin-top: 20px; padding-top: 20px; border-top: 1px dashed var(--border); font-size: 20px; font-weight: bold; color: var(--amber-dark); }
        .btn-checkout { display: block; width: 100%; text-align: center; background: var(--brown-dark); color: var(--amber); padding: 15px; border-radius: var(--radius-md); font-weight: 600; font-size: 16px; margin-top: 25px; transition: 0.3s; border: none; cursor: pointer;}
        .btn-checkout:hover { background: var(--amber); color: var(--brown-dark); }
    </style>
</head>
<body>
    <?php include 'layouts/header.php'; ?>

    <main class="checkout-page">
        <div class="container">
            <h1 class="checkout-title">Thanh toán</h1>
            
            <div class="checkout-container">
                <div class="checkout-form">
                    <h3 class="form-title">Thông tin giao hàng</h3>
                    <form action="<?= BASE_URL ?>index.php?controller=checkout&action=process" method="POST" id="checkoutForm">
                        <div class="form-group">
                            <label for="customer_name">Họ và tên người nhận *</label>
                            <input type="text" id="customer_name" name="customer_name" required value="<?= isset($_SESSION['user']) ? e($_SESSION['user']['name']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="phone">Số điện thoại *</label>
                            <input type="tel" id="phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Địa chỉ giao hàng chi tiết *</label>
                            <input type="text" id="address" name="address" required>
                        </div>
                        <div class="form-group">
                            <label for="note">Ghi chú đơn hàng (tùy chọn)</label>
                            <textarea id="note" name="note" placeholder="Ví dụ: Giao hàng giờ hành chính..."></textarea>
                        </div>
                    </form>
                </div>

                <div class="checkout-summary">
                    <h3 class="form-title">Đơn hàng của bạn (<?= $cart_count ?> sản phẩm)</h3>
                    
                    <div style="max-height: 350px; overflow-y: auto; padding-right: 10px; margin-bottom: 20px;">
                        <?php foreach ($cart as $key => $item): ?>
                            <div class="summary-item">
                                <img src="<?= BASE_URL ?>images/<?= e($item['image']) ?>" alt="<?= e($item['name']) ?>" class="summary-item-img">
                                <div class="summary-item-info">
                                    <span class="summary-item-name"><?= e($item['name']) ?></span>
                                    <span class="summary-item-meta">SL: <?= $item['qty'] ?></span>
                                </div>
                                <div class="summary-item-price"><?= formatPrice($item['price'] * $item['qty']) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="summary-row">
                        <span>Tạm tính:</span>
                        <span><?= formatPrice($total_amount) ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Phí giao hàng:</span>
                        <span>Miễn phí</span>
                    </div>
                    <div class="summary-total">
                        <span>Tổng cộng:</span>
                        <span><?= formatPrice($total_amount) ?></span>
                    </div>
                    
                    <button type="submit" form="checkoutForm" class="btn-checkout">Hoàn tất đặt hàng →</button>
                </div>
            </div>
        </div>
    </main>
    
    <?php include 'layouts/footer.php'; ?>
</body>
</html>