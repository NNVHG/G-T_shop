<?php
/** * @var array $banners
 * @var array $categories
 * @var array $featured
 * @var array $newest
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ — <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="<?= BASE_URL ?>css/base.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/home.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/product.css">
</head>
<body>
    <?php include 'layouts/header.php'; ?>

    <section class="hero">
        <div class="container hero-inner">
            <div class="hero-content">
                <h1 class="hero-title">Thiên đường<br>sách & đồ dùng<br>học tập</h1>
                <p class="hero-desc">Hàng nghìn đầu sách, văn phòng phẩm và đồ ăn vặt yêu thích — tất cả trong một nơi!</p>
                <a href="#featured" class="btn-primary">Khám phá ngay →</a>
            </div>
            <div class="hero-books">
                <div class="book-card b-green">Đắc Nhân<br>Tâm</div>
                <div class="book-card b-blue">Conan<br>T.100</div>
                <div class="book-card b-orange">Doraemon</div>
                <div class="book-card b-purple">Nhà Giả<br>Kim</div>
            </div>
        </div>
    </section>

    <div class="main-bg"> <section class="section">
            <div class="container">
                <div class="section-header"><h2 class="section-title">Danh mục sản phẩm</h2></div>
                <div class="cat-grid">
                    <?php foreach ($categories as $c): ?>
                        <a href="<?= BASE_URL ?>index.php?controller=product&action=category&slug=<?= e($c['slug']) ?>" class="cat-card">
                            <div class="cat-icon"><i class="ti <?= e($c['icon']) ?>"></i></div>
                            <div class="cat-name"><?= e($c['name']) ?></div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="section" id="featured">
            <div class="container">
                <div class="section-header"><h2 class="section-title">Sản phẩm nổi bật</h2></div>
                <div class="product-grid">
                    <?php foreach ($featured as $p): ?>
                        <div class="prod-card" onclick="location.href='<?= BASE_URL ?>index.php?controller=product&action=detail&id=<?= $p['id'] ?>'">
                            <?php if (!empty($p['badge'])): ?>
                                <div class="badge <?= $p['badge'] ?>">
                                    <?php 
                                        if($p['badge'] == 'hot') echo '🔥 Hot';
                                        elseif($p['badge'] == 'new') echo '✨ Mới';
                                        elseif($p['badge'] == 'sale' && $p['sale_price']) {
                                            $percent = round((($p['price'] - $p['sale_price']) / $p['price']) * 100);
                                            echo "Sale $percent%";
                                        }
                                    ?>
                                </div>
                            <?php endif; ?>

                            <div class="prod-img-wrap">
                                <span class="prod-img-placeholder"><i class="ti ti-book-2"></i></span>
                            </div>
                            
                            <div class="prod-info">
                                <div class="prod-rating">
                                    <?php 
                                        $rating = round($p['rating'] ?? 5); 
                                        for($i=1; $i<=5; $i++) { echo $i <= $rating ? '★' : '☆'; }
                                    ?>
                                </div>
                                <div class="prod-name"><?= e($p['name']) ?></div>
                                <div class="prod-author"><?= e($p['author'] ?? 'Đang cập nhật') ?></div>
                                
                                <div class="prod-price-wrap">
                                    <?php if (!empty($p['sale_price'])): ?>
                                        <span class="price-current"><?= formatPrice((int)$p['sale_price']) ?></span>
                                        <span class="price-old"><?= formatPrice((int)$p['price']) ?></span>
                                    <?php else: ?>
                                        <span class="price-current"><?= formatPrice((int)$p['price']) ?></span>
                                    <?php endif; ?>
                                    <button class="btn-add-cart" data-id="<?= $p['id'] ?>">
                                        <i class="ti ti-shopping-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <?php if ($banners): ?>
        <section class="section banner-section">
            <div class="container">
                <?php foreach ($banners as $b): ?>
                    <div class="banner-box">
                        <div class="banner-text">
                            <h3><?= e($b['title']) ?></h3>
                            <p><?= e($b['subtitle']) ?></p>
                        </div>
                        <a href="<?= e($b['btn_link']) ?>" class="btn-banner"><?= e($b['btn_text']) ?></a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>

    <?php include 'layouts/footer.php'; ?>
</body>
</html>