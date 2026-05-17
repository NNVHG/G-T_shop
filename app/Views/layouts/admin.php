<?php
/**
 * @var string $title
 * @var string $content_view
 */
$layout_loaded = true;
$current_action = $_GET['action'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin') ?> — G&amp;T Shop Admin</title>
    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/admin.css?v=<?= time() ?>">
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="logo-main">G&amp;T <span>shop</span></div>
            <div class="logo-sub">ADMINISTRATION</div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-label">TỔNG QUAN</div>
            <a href="<?= BASE_URL ?>index.php?controller=admin&action=dashboard" class="nav-item <?= $current_action === 'dashboard' ? 'active' : '' ?>">
                <span class="nav-icon"><i class="ti ti-dashboard"></i></span> Dashboard
            </a>

            <div class="sidebar-label">CỬA HÀNG</div>
            <a href="<?= BASE_URL ?>index.php?controller=admin&action=categories" class="nav-item <?= str_contains($current_action, 'categor') ? 'active' : '' ?>">
                <span class="nav-icon"><i class="ti ti-folders"></i></span> Danh mục
            </a>
            <a href="<?= BASE_URL ?>index.php?controller=admin&action=products" class="nav-item <?= str_contains($current_action, 'product') ? 'active' : '' ?>">
                <span class="nav-icon"><i class="ti ti-books"></i></span> Sản phẩm
            </a>
            <a href="<?= BASE_URL ?>index.php?controller=admin&action=inventory" class="nav-item <?= str_contains($current_action, 'inventory') ? 'active' : '' ?>">
                <span class="nav-icon"><i class="ti ti-box"></i></span> Kho hàng
            </a>
            <a href="<?= BASE_URL ?>index.php?controller=admin&action=orders" class="nav-item <?= str_contains($current_action, 'order') ? 'active' : '' ?>">
                <span class="nav-icon"><i class="ti ti-shopping-cart"></i></span> Đơn hàng
            </a>
            <a href="<?= BASE_URL ?>index.php?controller=admin&action=users" class="nav-item <?= str_contains($current_action, 'user') ? 'active' : '' ?>">
                <span class="nav-icon"><i class="ti ti-users"></i></span> Khách hàng
            </a>

            <div class="sidebar-label">HỆ THỐNG</div>
            <a href="<?= BASE_URL ?>index.php" target="_blank" class="nav-item">
                <span class="nav-icon"><i class="ti ti-device-laptop"></i></span> Xem Website
            </a>
            <a href="<?= BASE_URL ?>index.php?controller=admin&action=logout" class="nav-item">
                <span class="nav-icon"><i class="ti ti-logout"></i></span> Đăng xuất
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-name"><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Quản trị viên') ?></div>
            <div class="user-role">Quyền: Administrator</div>
        </div>
    </aside>

    <!-- MAIN CONTENT WRAPPER -->
    <main class="main">
        <!-- TOPBAR -->
        <header class="topbar">
            <h2 class="page-title"><?= htmlspecialchars($title ?? 'Dashboard') ?></h2>
            <div class="topbar-user" style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 13px; color: var(--text-2);">Xin chào, <strong><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Admin') ?></strong>!</span>
                <span style="background: var(--gold); color: white; padding: 2px 8px; border-radius: 20px; font-size: 10px; font-weight: 700; text-transform: uppercase;">ADMIN</span>
            </div>
        </header>

        <!-- PAGE BODY -->
        <div class="page-body">
            <!-- Toast alerts via SweetAlert2 or standard Flash divs -->
            <?php if (isset($_SESSION['toast_msg'])): ?>
                <div class="flash <?= $_SESSION['toast_msg']['type'] === 'success' ? 'success' : 'error' ?>">
                    <i class="ti <?= $_SESSION['toast_msg']['type'] === 'success' ? 'ti-circle-check' : 'ti-alert-circle' ?>" style="font-size: 20px;"></i>
                    <span><?= htmlspecialchars($_SESSION['toast_msg']['text']) ?></span>
                </div>
                <?php unset($_SESSION['toast_msg']); ?>
            <?php endif; ?>

            <!-- Render the actual view content -->
            <?php require_once $content_view; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Optional custom scripts
    </script>
</body>
</html>
