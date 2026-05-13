<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký — <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/base.css">
    <style>
        .auth-page { padding: 60px 0; background: var(--cream); min-height: 70vh; display: flex; align-items: center; justify-content: center; }
        .auth-container { background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 40px; width: 100%; max-width: 450px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .auth-title { font-family: 'Playfair Display', serif; font-size: 28px; color: var(--brown-dark); text-align: center; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: var(--text-main); }
        .form-group input { width: 100%; padding: 12px 15px; border: 1px solid var(--border); border-radius: var(--radius-md); outline: none; transition: 0.3s; font-family: 'DM Sans', sans-serif;}
        .form-group input:focus { border-color: var(--amber-dark); }
        .btn-auth { display: block; width: 100%; padding: 12px; background: var(--brown-dark); color: var(--amber); border: none; border-radius: var(--radius-md); font-size: 16px; font-weight: 600; cursor: pointer; transition: 0.3s; text-align: center;}
        .btn-auth:hover { background: var(--amber); color: var(--brown-dark); }
        .auth-links { margin-top: 20px; text-align: center; font-size: 14px; }
        .auth-links a { color: var(--amber-dark); font-weight: 600; text-decoration: none; }
        .auth-links a:hover { text-decoration: underline; }
        .error-msg { color: #D32F2F; background: #FFEBEE; padding: 10px; border-radius: var(--radius-sm); margin-bottom: 20px; text-align: center; font-size: 14px; }
    </style>
</head>
<body>
    <?php include '../app/Views/client/layouts/header.php'; ?>

    <main class="auth-page">
        <div class="auth-container">
            <h1 class="auth-title">Đăng ký tài khoản</h1>
            <?php if (isset($error)): ?>
                <div class="error-msg"><?= $error ?></div>
            <?php endif; ?>
            <form action="<?= BASE_URL ?>index.php?controller=auth&action=postRegister" method="POST">
                <div class="form-group">
                    <label for="name">Họ và tên</label>
                    <input type="text" id="name" name="name" required placeholder="Nhập họ và tên">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="Nhập email của bạn">
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" required placeholder="Nhập mật khẩu (ít nhất 6 ký tự)" minlength="6">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu</label>
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Nhập lại mật khẩu">
                </div>
                <button type="submit" class="btn-auth">Đăng ký</button>
            </form>
            <div class="auth-links">
                Đã có tài khoản? <a href="<?= BASE_URL ?>index.php?controller=auth&action=login">Đăng nhập</a>
            </div>
        </div>
    </main>

    <?php include '../app/Views/client/layouts/footer.php'; ?>
</body>
</html>