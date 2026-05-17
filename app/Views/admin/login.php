<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập quản trị — G&amp;T Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/admin.css?v=<?= time() ?>">
</head>
<body class="login-page-body">

    <div class="login-wrap">
        <div class="brand">
            <div class="brand-dot"></div>
            <h1 class="brand-main">G&amp;T <span>shop</span></h1>
            <div class="brand-sub">Hệ thống quản trị</div>
        </div>

        <div class="login-card">
            <h2 class="login-card-title"><i class="ti ti-lock"></i> Đăng nhập Admin</h2>

            <?php if (!empty($error)): ?>
                <div class="flash error" style="padding: 10px; margin-bottom: 15px; font-size: 13px;">
                    <i class="ti ti-alert-circle" style="font-size: 16px;"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>index.php?controller=admin&action=login">
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="email"><i class="ti ti-mail"></i> Email Admin</label>
                    <input type="email" id="email" name="email" placeholder="admin@gtshop.vn" required autofocus>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="password"><i class="ti ti-key"></i> Mật khẩu</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-login">ĐĂNG NHẬP <i class="ti ti-arrow-right"></i></button>
            </form>
        </div>

        <a href="<?= BASE_URL ?>index.php" class="back-link"><i class="ti ti-arrow-left"></i> Quay lại trang chủ cửa hàng</a>
    </div>

</body>
</html>
