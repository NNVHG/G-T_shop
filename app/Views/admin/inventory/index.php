<?php
/**
 * @var array $products
 * @var array $categories
 * @var int $total_skus
 * @var int $total_stock_items
 * @var int $out_of_stock_count
 * @var int $low_stock_count
 * @var int $cat_filter
 * @var string $stock_filter
 * @var string $search
 * @var string $title
 */
?>
<div class="inventory-wrap">
    <!-- STATS OVERVIEW CARDS -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="ti ti-box"></i></div>
            <div class="stat-info">
                <h3>Tổng dòng sản phẩm</h3>
                <p><?= number_format($total_skus) ?> dòng</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="ti ti-packages"></i></div>
            <div class="stat-info">
                <h3>Tổng số lượng tồn kho</h3>
                <p><?= number_format($total_stock_items) ?> cuốn</p>
            </div>
        </div>
        <div class="stat-card <?= $out_of_stock_count > 0 ? 'alert-danger' : '' ?>">
            <div class="stat-icon"><i class="ti ti-alert-triangle"></i></div>
            <div class="stat-info">
                <h3>Sản phẩm hết hàng</h3>
                <p class="stat-value"><?= number_format($out_of_stock_count) ?> sản phẩm</p>
            </div>
        </div>
        <div class="stat-card <?= $low_stock_count > 0 ? 'alert-warning' : '' ?>">
            <div class="stat-icon"><i class="ti ti-bell"></i></div>
            <div class="stat-info">
                <h3>Sắp hết hàng (≤ 5)</h3>
                <p class="stat-value"><?= number_format($low_stock_count) ?> sản phẩm</p>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH PANEL -->
    <div class="admin-card" style="margin-bottom: 25px; padding: 20px;">
        <form method="GET" action="<?= BASE_URL ?>index.php" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
            <input type="hidden" name="controller" value="admin">
            <input type="hidden" name="action" value="inventory">

            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 8px; color: var(--text-2);">Tìm kiếm sản phẩm</label>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Nhập tên sách hoặc tác giả..." style="width: 100%; padding: 10px 15px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; background: white;">
            </div>

            <div style="width: 200px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 8px; color: var(--text-2);">Danh mục sản phẩm</label>
                <select name="cat" style="width: 100%; padding: 10px 15px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; background: white; cursor: pointer;">
                    <option value="0">--- Tất cả danh mục ---</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $cat_filter === (int)$cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="width: 200px;">
                <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 8px; color: var(--text-2);">Trạng thái tồn kho</label>
                <select name="stock_status" style="width: 100%; padding: 10px 15px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; background: white; cursor: pointer;">
                    <option value="all" <?= $stock_filter === 'all' ? 'selected' : '' ?>>Tất cả trạng thái</option>
                    <option value="out" <?= $stock_filter === 'out' ? 'selected' : '' ?>>Đã hết hàng (0)</option>
                    <option value="low" <?= $stock_filter === 'low' ? 'selected' : '' ?>>Sắp hết hàng (1 - 5)</option>
                    <option value="safe" <?= $stock_filter === 'safe' ? 'selected' : '' ?>>An toàn (> 5)</option>
                </select>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-gold" style="padding: 10px 20px; font-size: 14px; border-radius: 8px; font-weight: 600;">
                    <i class="ti ti-search" style="margin-right: 5px;"></i> Lọc kho
                </button>
                <a href="<?= BASE_URL ?>index.php?controller=admin&action=inventory" class="btn" style="padding: 10px 20px; font-size: 14px; border-radius: 8px; border: 1px solid var(--border); font-weight: 600; background: var(--border); color: var(--text-1);">
                    Đặt lại
                </a>
            </div>
        </form>
    </div>

    <!-- INVENTORY TABLE CARD -->
    <div class="admin-card">
        <div class="card-header" style="padding: 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-size: 18px; font-weight: 700; color: var(--dark);"><i class="ti ti-box" style="margin-right: 8px; color: var(--gold);"></i>Danh sách tồn kho sản phẩm</h2>
            <span style="font-size: 13px; color: var(--text-2);">Tổng cộng: <strong><?= count($products) ?></strong> sản phẩm được hiển thị</span>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Mã số</th>
                        <th>Sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá bán</th>
                        <th style="width: 180px;">Mức độ tồn kho</th>
                        <th style="width: 100px; text-align: center;">Đã bán</th>
                        <th style="width: 250px; text-align: center;">Điều chỉnh nhanh tồn kho</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-2);">
                                <i class="ti ti-box-off" style="font-size: 40px; display: block; margin-bottom: 10px; color: var(--border);"></i>
                                Không tìm thấy sản phẩm nào khớp với bộ lọc kho!
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td><strong>#<?= $p['id'] ?></strong></td>
                                <td>
                                    <div class="product-info-cell" style="display: flex; align-items: center; gap: 12px;">
                                        <img src="<?= BASE_URL ?>images/<?= htmlspecialchars($p['image']) ?>" alt="" style="width: 45px; height: 55px; border-radius: 4px; object-fit: cover; border: 1px solid var(--border);">
                                        <div>
                                            <div style="font-weight: 700; color: var(--dark); margin-bottom: 2px;"><?= htmlspecialchars($p['name']) ?></div>
                                            <div style="font-size: 11px; color: var(--text-2);">Tác giả: <?= htmlspecialchars($p['author'] ?? 'Chưa rõ') ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge" style="background: rgba(44, 26, 14, 0.05); color: var(--dark);"><?= htmlspecialchars($p['category_name']) ?></span></td>
                                <td>
                                    <?php if ($p['sale_price'] !== null): ?>
                                        <div style="font-weight: 700; color: var(--gold);"><?= formatPrice($p['sale_price']) ?></div>
                                        <div style="text-decoration: line-through; font-size: 11px; color: var(--text-2);"><?= formatPrice($p['price']) ?></div>
                                    <?php else: ?>
                                        <div style="font-weight: 700; color: var(--dark);"><?= formatPrice($p['price']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <!-- PROGRESS UNIT METER -->
                                    <?php 
                                    $stock = (int)$p['stock'];
                                    if ($stock === 0) {
                                        $bar_color = '#d9534f'; // red
                                        $text_color = '#d9534f';
                                        $label = 'HẾT HÀNG';
                                        $percentage = 0;
                                    } elseif ($stock <= 5) {
                                        $bar_color = '#f0ad4e'; // orange
                                        $text_color = '#f0ad4e';
                                        $label = 'CẢNH BÁO';
                                        $percentage = ($stock / 200) * 100;
                                    } else {
                                        $bar_color = '#5cb85c'; // green
                                        $text_color = '#5cb85c';
                                        $label = 'AN TOÀN';
                                        $percentage = min(($stock / 200) * 100, 100);
                                    }
                                    ?>
                                    <div style="margin-bottom: 5px; display: flex; justify-content: space-between; font-size: 11px; font-weight: 700;">
                                        <span style="color: <?= $text_color ?>;"><?= $label ?></span>
                                        <span><?= $stock ?> chiếc</span>
                                    </div>
                                    <div style="width: 100%; height: 6px; background: rgba(0,0,0,0.05); border-radius: 10px; overflow: hidden;">
                                        <div style="width: <?= $percentage ?>%; height: 100%; background: <?= $bar_color ?>; border-radius: 10px;"></div>
                                    </div>
                                </td>
                                <td style="text-align: center; font-weight: 700; color: var(--text-1);"><?= number_format($p['sold_count']) ?></td>
                                <td>
                                    <!-- QUICK STOCK UPDATE FORM -->
                                    <form method="POST" action="<?= BASE_URL ?>index.php?controller=admin&action=inventoryUpdate" style="display: flex; align-items: center; justify-content: center; gap: 5px;">
                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                        <input type="hidden" name="cat" value="<?= $cat_filter ?>">
                                        <input type="hidden" name="stock_status" value="<?= $stock_filter ?>">
                                        <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">

                                        <!-- Decrement -->
                                        <button type="button" class="btn-qty" onclick="changeQty(this, -1)" style="width: 32px; height: 32px; border-radius: 6px; border: 1px solid var(--border); background: #f9f9f9; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; cursor: pointer; color: var(--text-1); transition: all 0.2s;">-</button>
                                        
                                        <!-- Stock Input -->
                                        <input type="number" name="stock" value="<?= $stock ?>" min="0" style="width: 60px; height: 32px; text-align: center; border: 1px solid var(--border); border-radius: 6px; font-size: 13px; font-weight: 700; color: var(--dark);">
                                        
                                        <!-- Increment -->
                                        <button type="button" class="btn-qty" onclick="changeQty(this, 1)" style="width: 32px; height: 32px; border-radius: 6px; border: 1px solid var(--border); background: #f9f9f9; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; cursor: pointer; color: var(--text-1); transition: all 0.2s;">+</button>
                                        
                                        <!-- Save Button -->
                                        <button type="submit" class="btn btn-gold" style="padding: 0 12px; height: 32px; font-size: 12px; border-radius: 6px; font-weight: 700; display: flex; align-items: center; gap: 4px;">
                                            <i class="ti ti-device-floppy"></i> Lưu
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Add extra local styling to enhance details */
    .btn-qty:hover {
        background: var(--gold) !important;
        color: white !important;
        border-color: var(--gold) !important;
    }
    .alert-danger {
        border-left: 4px solid #d9534f !important;
    }
    .alert-danger .stat-value {
        color: #d9534f !important;
        font-weight: 700;
    }
    .alert-warning {
        border-left: 4px solid #f0ad4e !important;
    }
    .alert-warning .stat-value {
        color: #f0ad4e !important;
        font-weight: 700;
    }
</style>

<script>
    function changeQty(button, delta) {
        // Tìm thẻ input nằm cùng form với nút bấm
        const form = button.closest('form');
        const input = form.querySelector('input[name="stock"]');
        let val = parseInt(input.value) || 0;
        val += delta;
        if (val < 0) val = 0;
        input.value = val;
    }
</script>
