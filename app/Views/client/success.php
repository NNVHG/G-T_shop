<?php 
/** 
 * @var array $nav_categories
 * @var int $cart_count
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công — <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/base.css">
    <style>
        .success-page { 
            padding: 80px 0 100px; 
            background: var(--cream); 
            min-height: 70vh; 
            display: flex;
            align-items: center;
        }
        .success-card {
            max-width: 600px;
            margin: 0 auto;
            background: var(--white);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            padding: 50px 40px;
            text-align: center;
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }
        .success-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--amber), var(--amber-dark));
        }
        .success-icon-wrap {
            width: 80px;
            height: 80px;
            background: var(--cream);
            color: var(--amber-dark);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            margin-bottom: 25px;
            border: 2px solid var(--border);
            animation: scaleIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .success-title {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: var(--brown-dark);
            margin-bottom: 15px;
        }
        .success-message {
            font-size: 16px;
            color: var(--text-main);
            line-height: 1.6;
            margin-bottom: 35px;
            padding: 0 10px;
        }
        .success-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        .btn-success-primary {
            background: var(--brown-dark);
            color: var(--amber);
            padding: 14px 28px;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid var(--brown-dark);
        }
        .btn-success-primary:hover {
            background: var(--amber);
            color: var(--brown-dark);
            border-color: var(--amber);
            transform: translateY(-2px);
        }
        .btn-success-secondary {
            background: transparent;
            color: var(--brown-dark);
            padding: 14px 28px;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid var(--border);
        }
        .btn-success-secondary:hover {
            background: var(--cream);
            border-color: var(--brown-dark);
            transform: translateY(-2px);
        }
        @keyframes scaleIn {
            from { transform: scale(0); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        @media (max-width: 576px) {
            .success-card { padding: 40px 20px; }
            .success-title { font-size: 26px; }
            .success-actions { flex-direction: column; gap: 10px; }
            .btn-success-primary, .btn-success-secondary { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    <?php include 'layouts/header.php'; ?>

    <main class="success-page">
        <div class="container">
            <div class="success-card">
                <div class="success-icon-wrap">
                    <i class="ti ti-circle-check-filled"></i>
                </div>
                <h1 class="success-title">Đặt hàng thành công!</h1>
                <p class="success-message">
                    Cảm ơn bạn đã tin tưởng mua sắm tại <strong>G&amp;T Shop</strong>.<br>
                    Đơn hàng của bạn đã được tiếp nhận và đang trong quá trình xử lý.<br>
                    Chúng tôi sẽ sớm liên hệ với bạn để xác nhận thông tin giao hàng.
                </p>
                <div class="success-actions">
                    <a href="<?= BASE_URL ?>index.php" class="btn-success-primary">
                        <i class="ti ti-building-store"></i> Tiếp tục mua sắm
                    </a>
                    <a href="<?= BASE_URL ?>index.php?controller=profile" class="btn-success-secondary">
                        <i class="ti ti-file-text"></i> Xem đơn hàng
                    </a>
                </div>
            </div>
        </div>
    </main>

    <?php include 'layouts/footer.php'; ?>
</body>
</html>
