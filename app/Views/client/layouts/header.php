<header class="site-header">
    <div class="header-top">
        <div class="container header-inner">
            <a href="<?= BASE_URL ?>index.php" class="logo">
                <span class="logo-gt">G&amp;T</span><span class="logo-shop">shop</span>
            </a>

            <form class="search-bar" action="<?= BASE_URL ?>index.php" method="GET">
                <input type="hidden" name="controller" value="product">
                <input type="hidden" name="action" value="search">
                <i class="ti ti-search search-icon"></i>
                <input type="text" name="q" class="search-input" placeholder="Tìm sách, đồ dùng, snack...">
            </form>

            <div class="header-actions">
                <a href="#" class="action-btn text-link">
                    <i class="ti ti-user"></i> <span>Đăng nhập</span>
                </a>
                <a href="<?= BASE_URL ?>index.php?controller=cart" class="action-btn cart-btn">
                    <i class="ti ti-shopping-cart"></i>
                    <?php if (isset($cart_count) && $cart_count > 0): ?>
                        <span class="cart-badge"><?= $cart_count ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </div>

    <nav class="header-nav">
        <div class="container nav-inner">
            <a href="<?= BASE_URL ?>index.php" class="nav-item <?= (!isset($_GET['cat']) && !isset($_GET['action'])) ? 'active' : '' ?>">Trang chủ</a>
            <?php if(isset($nav_categories)): foreach ($nav_categories as $c): ?>
                <a href="<?= BASE_URL ?>index.php?controller=product&action=category&slug=<?= e($c['slug']) ?>" class="nav-item">
                    <i class="ti <?= e($c['icon']) ?>"></i> <?= e($c['name']) ?>
                </a>
            <?php endforeach; endif; ?>
        </div>
    </nav>
</header>