<?php
// Mặc định biến errorMessage nếu hệ thống không truyền vào
$errorMessage = $errorMessage ?? 'Trang bạn tìm kiếm không tồn tại hoặc đã bị di dời.';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Lỗi truy cập — <?= SITE_NAME ?? 'G&T Shop' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/base.css">
    <style>
        .error-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: var(--cream);
            text-align: center;
            padding: 20px;
        }
        .error-code {
            font-family: 'Playfair Display', serif;
            font-size: 150px;
            font-weight: 700;
            color: var(--amber);
            line-height: 1;
            margin-bottom: 20px;
            text-shadow: 4px 4px 0px rgba(239, 159, 39, 0.2);
        }
        .error-title {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: var(--brown-dark);
            margin-bottom: 15px;
        }
        .error-detail {
            background: var(--cream-2);
            color: #D32F2F; /* Màu đỏ nổi bật lỗi */
            padding: 15px 25px;
            border-radius: var(--radius-md);
            border: 1px dashed #D32F2F;
            margin-bottom: 30px;
            font-family: monospace; /* Font code để hiển thị chi tiết hệ thống */
            font-size: 14px;
            max-width: 600px;
            word-wrap: break-word;
        }
        .btn-home {
            background: var(--brown-dark);
            color: var(--amber);
            padding: 12px 30px;
        }
        .btn-home:hover {
            background: var(--brown-light);
            color: var(--amber-light);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <h1 class="error-title">Ôi hỏng! Không tìm thấy trang</h1>
        
        <div class="error-detail">
            <strong><i class="ti ti-bug"></i> Chi tiết lỗi hệ thống:</strong><br> 
            <?= htmlspecialchars($errorMessage) ?>
        </div>

        <a href="<?= BASE_URL ?>index.php" class="btn-primary btn-home">
            <i class="ti ti-arrow-left"></i> Quay về Trang chủ
        </a>
    </div>
</body>
</html>