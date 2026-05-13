<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang cá nhân — <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/base.css">
    <style>
        .profile-page { padding: 40px 0; background: var(--cream); min-height: 70vh; }
        .profile-container { display: flex; gap: 30px; align-items: flex-start; }
        
        .sidebar { width: 280px; background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 20px; }
        .user-greeting { font-family: 'Playfair Display', serif; font-size: 20px; color: var(--brown-dark); margin-bottom: 20px; border-bottom: 1px solid var(--cream-2); padding-bottom: 15px;}
        .menu-list { list-style: none; padding: 0; margin: 0; }
        .menu-list li { margin-bottom: 5px; }
        .menu-list a { display: block; padding: 10px 15px; color: var(--text-main); text-decoration: none; border-radius: var(--radius-sm); transition: 0.2s; font-weight: 500;}
        .menu-list a:hover, .menu-list a.active { background: var(--cream-2); color: var(--brown-dark); }

        .content-area { flex: 1; display: flex; flex-direction: column; gap: 30px;}
        
        .section-box { background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 30px; }
        .section-title { font-family: 'Playfair Display', serif; font-size: 24px; color: var(--brown-dark); margin-bottom: 25px; border-bottom: 2px solid var(--cream-2); padding-bottom: 10px; display: inline-block;}
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: var(--text-main); }
        .form-group input { width: 100%; padding: 12px 15px; border: 1px solid var(--border); border-radius: var(--radius-md); outline: none; transition: 0.3s; font-family: 'DM Sans', sans-serif; background: var(--white);}
        .form-group input:focus { border-color: var(--amber-dark); }
        .form-group input[readonly] { background: var(--cream-2); cursor: not-allowed; color: var(--text-muted);}
        
        .btn-submit { padding: 12px 25px; background: var(--brown-dark); color: var(--amber); border: none; border-radius: var(--radius-md); font-size: 16px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn-submit:hover { background: var(--amber); color: var(--brown-dark); }

        .order-card { border: 1px solid var(--border); border-radius: var(--radius-md); margin-bottom: 20px; overflow: hidden; }
        .order-header { background: var(--cream-2); padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); }
        .order-id { font-weight: bold; color: var(--brown-dark); }
        .order-date { color: var(--text-muted); font-size: 14px;}
        .order-status { padding: 5px 10px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; }
        .status-pending { background: #FFF3E0; color: #D32F2F; }
        .status-processing { background: #E3F2FD; color: #F57C00; }
        .status-shipped { background: #E8F5E9; color: #1976D2; }
        .status-done { background: #E8F5E9; color: #388E3C; }
        .status-cancelled { background: #FFEBEE; color: #D32F2F; }

        .order-body { padding: 20px; }
        .order-item { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; border-bottom: 1px dashed var(--cream-2); padding-bottom: 15px;}
        .order-item:last-child { margin-bottom: 0; border-bottom: none; padding-bottom: 0;}
        .order-item-img { width: 60px; height: 60px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border); }
        .order-item-info { flex: 1; }
        .order-item-name { font-weight: 600; color: var(--brown-dark); display: block; margin-bottom: 5px;}
        .order-footer { padding: 15px 20px; background: var(--white); border-top: 1px solid var(--border); text-align: right; font-size: 18px; font-weight: bold; color: var(--amber-dark);}

        .msg-alert { padding: 15px; border-radius: var(--radius-md); margin-bottom: 20px; font-weight: 500; }
        .msg-success { background: #E8F5E9; color: #2E7D32; border: 1px solid #A5D6A7;}
        .msg-error { background: #FFEBEE; color: #C62828; border: 1px solid #EF9A9A;}
    </style>
</head>
<body>
    <?php include 'layouts/header.php'; ?>

    <main class="profile-page">
        <div class="container profile-container">
            <aside class="sidebar">
                <div class="user-greeting">
                    Chào, <?= e($user_info['name']) ?>!
                </div>
                <ul class="menu-list">
                    <li><a href="#info" class="active" onclick="switchTab(this, 'info')">Thông tin tài khoản</a></li>
                    <li><a href="#password" onclick="switchTab(this, 'password')">Đổi mật khẩu</a></li>
                    <li><a href="#orders" onclick="switchTab(this, 'orders')">Lịch sử đơn hàng</a></li>
                    <li><a href="<?= BASE_URL ?>index.php?controller=auth&action=logout" style="color: #D32F2F;">Đăng xuất</a></li>
                </ul>
            </aside>

            <div class="content-area">
                <?php if(isset($_SESSION['profile_msg'])): ?>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire({
                                icon: '<?= e($_SESSION['profile_msg']['type']) ?>',
                                title: 'Thông báo',
                                text: '<?= e($_SESSION['profile_msg']['text']) ?>',
                                confirmButtonColor: '#5c4033'
                            });
                        });
                    </script>
                    <?php unset($_SESSION['profile_msg']); ?>
                <?php endif; ?>

                <div id="info" class="section-box">
                    <h2 class="section-title">Thông tin tài khoản</h2>
                    <form action="<?= BASE_URL ?>index.php?controller=profile&action=updateInfo" method="POST">
                        <div class="form-group">
                            <label>Email (Không thể thay đổi)</label>
                            <input type="email" value="<?= e($user_info['email']) ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Họ và tên *</label>
                            <input type="text" name="name" value="<?= e($user_info['name']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="tel" name="phone" value="<?= e($user_info['phone'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Địa chỉ mặc định</label>
                            <input type="text" name="address" value="<?= e($user_info['address'] ?? '') ?>">
                        </div>
                        <button type="submit" class="btn-submit">Lưu thay đổi</button>
                    </form>
                </div>

                <div id="password" class="section-box" style="display: none;">
                    <h2 class="section-title">Đổi mật khẩu</h2>
                    <form action="<?= BASE_URL ?>index.php?controller=profile&action=updatePassword" method="POST">
                        <div class="form-group">
                            <label>Mật khẩu hiện tại *</label>
                            <input type="password" name="current_password" required>
                        </div>
                        <div class="form-group">
                            <label>Mật khẩu mới *</label>
                            <input type="password" name="new_password" required minlength="6">
                        </div>
                        <div class="form-group">
                            <label>Xác nhận mật khẩu mới *</label>
                            <input type="password" name="confirm_password" required minlength="6">
                        </div>
                        <button type="submit" class="btn-submit">Đổi mật khẩu</button>
                    </form>
                </div>

                <div id="orders" class="section-box" style="display: none;">
                    <h2 class="section-title">Lịch sử đơn hàng</h2>
                    <?php if(empty($orders)): ?>
                        <p style="color: var(--text-muted);">Bạn chưa có đơn hàng nào.</p>
                    <?php else: ?>
                        <?php foreach($orders as $order): ?>
                            <div class="order-card">
                                <div class="order-header">
                                    <div>
                                        <span class="order-id">Đơn hàng #<?= $order['id'] ?></span>
                                        <span class="order-date"> - <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span>
                                    </div>
                                    <span class="order-status status-<?= $order['status'] ?>">
                                        <?php 
                                            $status_labels = [
                                                'pending' => 'Chờ xử lý',
                                                'processing' => 'Đang xử lý',
                                                'shipped' => 'Đang giao',
                                                'done' => 'Hoàn thành',
                                                'cancelled' => 'Đã hủy'
                                            ];
                                            echo $status_labels[$order['status']] ?? 'Không rõ';
                                        ?>
                                    </span>
                                </div>
                                <div class="order-body">
                                    <?php foreach($order['items'] as $item): ?>
                                        <div class="order-item">
                                            <img src="<?= BASE_URL ?>images/<?= e($item['image']) ?>" class="order-item-img">
                                            <div class="order-item-info">
                                                <span class="order-item-name"><?= e($item['name']) ?></span>
                                                <span style="color: var(--text-muted); font-size: 14px;">SL: <?= $item['qty'] ?> x <?= formatPrice($item['price']) ?></span>
                                            </div>
                                            <div style="font-weight: bold; color: var(--brown-dark);">
                                                <?= formatPrice($item['price'] * $item['qty']) ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="order-footer">
                                    Tổng tiền: <?= formatPrice($order['total']) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include 'layouts/footer.php'; ?>

    <script>
        function switchTab(element, tabId) {
            // Xóa active class ở menu
            document.querySelectorAll('.menu-list a').forEach(el => el.classList.remove('active'));
            element.classList.add('active');

            // Ẩn tất cả section
            document.querySelectorAll('.section-box').forEach(el => el.style.display = 'none');
            
            // Hiển thị section được chọn
            document.getElementById(tabId).style.display = 'block';
        }

        // Kiểm tra hash trên URL để mở tab tương ứng (ví dụ: profile#orders)
        window.onload = function() {
            if(window.location.hash) {
                let tabId = window.location.hash.substring(1);
                let tabLink = document.querySelector(`.menu-list a[href="#${tabId}"]`);
                if(tabLink) {
                    switchTab(tabLink, tabId);
                }
            }
        }
    </script>
</body>
</html>