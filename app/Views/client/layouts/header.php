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
                
                <input type="text" name="q" class="search-input" placeholder="Tìm sách, đồ dùng, snack..." autocomplete="off">
                
                <div id="searchSuggest" class="search-suggest"></div>
            </form>

            <div class="header-actions">
                <?php if(isset($_SESSION['user'])): ?>
                    <div class="action-btn text-link user-dropdown">
                        <i class="ti ti-user"></i> <span>Chào, <?= e($_SESSION['user']['name']) ?></span>
                        <div class="dropdown-content">
                            <a href="<?= BASE_URL ?>index.php?controller=profile">Trang cá nhân</a>
                            <a href="<?= BASE_URL ?>index.php?controller=auth&action=logout">Đăng xuất</a>
                        </div>
                    </div>
                    <style>
                    .user-dropdown { position: relative; display: inline-flex; align-items: center; cursor: pointer; }
                    .dropdown-content { display: none; position: absolute; top: 100%; right: 0; background-color: var(--white); min-width: 150px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.1); z-index: 1; border-radius: var(--radius-sm); border: 1px solid var(--border); overflow: hidden; }
                    .dropdown-content a { color: var(--text-main); padding: 12px 16px; text-decoration: none; display: block; font-size: 14px; }
                    .dropdown-content a:hover { background-color: var(--cream); color: var(--amber-dark); }
                    .user-dropdown:hover .dropdown-content { display: block; }
                    </style>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>index.php?controller=auth&action=login" class="action-btn text-link">
                        <i class="ti ti-user"></i> <span>Đăng nhập</span>
                    </a>
                <?php endif; ?>
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