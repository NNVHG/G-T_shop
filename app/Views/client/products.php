<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? e($page_title) : 'Sản phẩm' ?> — <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/base.css">
    <style>
        .products-page { padding: 40px 0; background: var(--cream); min-height: 70vh; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid var(--border); padding-bottom: 15px; }
        .page-title { font-family: 'Playfair Display', serif; font-size: 28px; color: var(--brown-dark); }
        .sort-select { padding: 8px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); outline: none; font-family: 'DM Sans', sans-serif; background: var(--white);}
        
        .products-container { display: flex; gap: 30px; align-items: flex-start; }
        .sidebar { width: 250px; background: var(--white); border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 20px; position: sticky; top: 100px; }
        .filter-title { font-weight: bold; margin-bottom: 15px; color: var(--brown-dark); border-bottom: 1px solid var(--cream-2); padding-bottom: 10px;}
        .filter-group { margin-bottom: 20px; }
        .filter-group label { display: block; margin-bottom: 8px; font-size: 14px; color: var(--text-main); }
        .filter-group input { width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: var(--radius-sm); margin-bottom: 10px; }
        .btn-filter { width: 100%; padding: 10px; background: var(--brown-dark); color: var(--amber); border: none; border-radius: var(--radius-sm); cursor: pointer; transition: 0.3s; font-weight: 600;}
        .btn-filter:hover { background: var(--amber); color: var(--brown-dark); }

        .products-grid { flex: 1; display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; }
        
        .product-card { background: var(--white); border-radius: var(--radius-lg); padding: 15px; text-align: center; border: 1px solid var(--border); transition: 0.3s; display: flex; flex-direction: column; justify-content: space-between;}
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); border-color: var(--amber-dark); }
        .product-img { width: 100%; height: 200px; object-fit: contain; background: var(--cream-2); border-radius: var(--radius-md); margin-bottom: 15px; border: 1px solid var(--cream-2); display: block; }
        .product-name { font-size: 16px; font-weight: 600; color: var(--brown-dark); text-decoration: none; margin-bottom: 10px; display: block; line-height: 1.4; height: 44px; overflow: hidden; }
        .product-price { color: var(--amber-dark); font-weight: bold; font-size: 18px; margin-bottom: 15px; }
        .btn-add-cart { display: inline-block; padding: 10px 20px; background: var(--amber); color: var(--brown-dark); border-radius: var(--radius-sm); font-weight: 600; text-decoration: none; transition: 0.3s; border: none; cursor: pointer; width: 100%;}
        .btn-add-cart:hover { background: var(--brown-dark); color: var(--amber); }

        .empty-result { text-align: center; padding: 40px; color: var(--text-muted); font-size: 18px; width: 100%; grid-column: 1 / -1;}
    </style>
</head>
<body>
    <?php include 'layouts/header.php'; ?>

    <main class="products-page">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title"><?= isset($page_title) ? e($page_title) : 'Sản phẩm' ?></h1>
                
                <form id="sortForm" action="" method="GET">
                    <input type="hidden" name="controller" value="product">
                    <input type="hidden" name="action" value="<?= isset($_GET['action']) ? e($_GET['action']) : 'category' ?>">
                    <?php if(isset($_GET['slug'])): ?><input type="hidden" name="slug" value="<?= e($_GET['slug']) ?>"><?php endif; ?>
                    <?php if(isset($_GET['q'])): ?><input type="hidden" name="q" value="<?= e($_GET['q']) ?>"><?php endif; ?>
                    <?php if(isset($_GET['min_price'])): ?><input type="hidden" name="min_price" value="<?= e($_GET['min_price']) ?>"><?php endif; ?>
                    <?php if(isset($_GET['max_price'])): ?><input type="hidden" name="max_price" value="<?= e($_GET['max_price']) ?>"><?php endif; ?>

                    <select name="sort" class="sort-select" onchange="document.getElementById('sortForm').submit();">
                        <option value="">Sắp xếp theo...</option>
                        <option value="price_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : '' ?>>Giá tăng dần</option>
                        <option value="price_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : '' ?>>Giá giảm dần</option>
                        <option value="newest" <?= (isset($_GET['sort']) && $_GET['sort'] == 'newest') ? 'selected' : '' ?>>Mới nhất</option>
                        <option value="rating" <?= (isset($_GET['sort']) && $_GET['sort'] == 'rating') ? 'selected' : '' ?>>Đánh giá cao</option>
                    </select>
                </form>
            </div>

            <div class="products-container">
                <aside class="sidebar">
                    <h3 class="filter-title">Lọc sản phẩm</h3>
                    <form action="" method="GET">
                        <input type="hidden" name="controller" value="product">
                        <input type="hidden" name="action" value="<?= isset($_GET['action']) ? e($_GET['action']) : 'category' ?>">
                        <?php if(isset($_GET['slug'])): ?><input type="hidden" name="slug" value="<?= e($_GET['slug']) ?>"><?php endif; ?>
                        <?php if(isset($_GET['q'])): ?><input type="hidden" name="q" value="<?= e($_GET['q']) ?>"><?php endif; ?>
                        <?php if(isset($_GET['sort'])): ?><input type="hidden" name="sort" value="<?= e($_GET['sort']) ?>"><?php endif; ?>

                        <div class="filter-group">
                            <label>Khoảng giá</label>
                            <input type="number" name="min_price" placeholder="Giá thấp nhất" value="<?= isset($_GET['min_price']) ? e($_GET['min_price']) : '' ?>">
                            <input type="number" name="max_price" placeholder="Giá cao nhất" value="<?= isset($_GET['max_price']) ? e($_GET['max_price']) : '' ?>">
                        </div>
                        <button type="submit" class="btn-filter">Áp dụng lọc</button>
                    </form>
                </aside>

                <div class="products-grid">
                    <?php if (empty($products)): ?>
                        <div class="empty-result">Không tìm thấy sản phẩm nào phù hợp.</div>
                    <?php else: ?>
                        <?php foreach ($products as $p): ?>
                            <div class="product-card">
                                <a href="<?= BASE_URL ?>index.php?controller=product&action=detail&id=<?= $p['id'] ?>">
                                    <img src="<?= BASE_URL ?>images/<?= e($p['image'] ?? 'placeholder.png') ?>" alt="<?= e($p['name']) ?>" class="product-img">
                                </a>
                                <div>
                                    <a href="<?= BASE_URL ?>index.php?controller=product&action=detail&id=<?= $p['id'] ?>" class="product-name"><?= e($p['name']) ?></a>
                                    <div class="product-price">
                                        <?php if(isset($p['sale_price'])): ?>
                                            <span style="text-decoration: line-through; color: #999; font-size: 14px;"><?= formatPrice($p['price']) ?></span>
                                            <?= formatPrice($p['sale_price']) ?>
                                        <?php else: ?>
                                            <?= formatPrice($p['price']) ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <button class="btn-add-cart" data-id="<?= $p['id'] ?>">Thêm vào giỏ</button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include 'layouts/footer.php'; ?>
</body>
</html>