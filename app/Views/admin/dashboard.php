<?php
/**
 * @var int $total_products
 * @var int $total_orders
 * @var float $total_sales
 * @var int $low_stock_count
 * @var array $latest_orders
 * @var array $low_stock_products
 */
$content_view = '../app/Views/admin/dashboard.php';
?>
<div class="stats-grid">
    <!-- Thống kê doanh thu -->
    <div class="stat-card">
        <span class="stat-card-label"><i class="ti ti-coin c-green"></i> Doanh thu (Đã thanh toán)</span>
        <span class="stat-card-val c-green"><?= number_format($total_sales) ?>đ</span>
        <span class="stat-card-sub">Từ tất cả các đơn hàng hoàn tất</span>
    </div>

    <!-- Thống kê đơn hàng -->
    <div class="stat-card">
        <span class="stat-card-label"><i class="ti ti-shopping-cart c-blue"></i> Tổng số đơn hàng</span>
        <span class="stat-card-val c-blue"><?= $total_orders ?></span>
        <span class="stat-card-sub">Bao gồm cả đang chờ xử lý</span>
    </div>

    <!-- Thống kê sản phẩm -->
    <div class="stat-card">
        <span class="stat-card-label"><i class="ti ti-books c-gold"></i> Tổng số sản phẩm</span>
        <span class="stat-card-val c-gold"><?= $total_products ?></span>
        <span class="stat-card-sub">Đầu sách & sản phẩm có sẵn</span>
    </div>

    <!-- Sắp hết hàng -->
    <div class="stat-card">
        <span class="stat-card-label"><i class="ti ti-alert-triangle c-red"></i> Sản phẩm sắp hết hàng</span>
        <span class="stat-card-val c-red"><?= $low_stock_count ?></span>
        <span class="stat-card-sub">Số lượng tồn kho <= 5</span>
    </div>
</div>

<div class="form-grid" style="grid-template-columns: 1.5fr 1fr;">
    <!-- ĐƠN HÀNG GẦN ĐÂY -->
    <div class="admin-table-wrap">
        <div class="admin-table-head">
            <h3 class="admin-table-title"><i class="ti ti-clock"></i> Đơn hàng gần đây</h3>
            <a href="<?= BASE_URL ?>index.php?controller=admin&action=orders" class="btn btn-sm">Xem tất cả</a>
        </div>
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th>Mã ĐH</th>
                    <th>Khách hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($latest_orders)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-3); padding: 20px;">Không có đơn hàng nào gần đây.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($latest_orders as $ord): ?>
                        <tr>
                            <td>
                                <a href="<?= BASE_URL ?>index.php?controller=admin&action=orderDetail&id=<?= $ord['id'] ?>" style="color: var(--gold-dim); font-weight: 700; text-decoration: underline;">
                                    #<?= $ord['id'] ?>
                                </a>
                            </td>
                            <td>
                                <div><strong><?= htmlspecialchars($ord['customer_name']) ?></strong></div>
                                <div style="font-size: 11px; color: var(--text-3);"><?= htmlspecialchars($ord['phone']) ?></div>
                            </td>
                            <td style="color: var(--text-2);"><?= date('H:i d/m/Y', strtotime($ord['created_at'])) ?></td>
                            <td style="font-weight: 700;"><?= number_format($ord['total']) ?>đ</td>
                            <td>
                                <span class="badge badge-<?= $ord['status'] ?>">
                                    <?php
                                    switch ($ord['status']) {
                                        case 'pending': echo 'Chờ xử lý'; break;
                                        case 'processing': echo 'Đang xử lý'; break;
                                        case 'shipped': echo 'Đang giao'; break;
                                        case 'done': echo 'Hoàn tất'; break;
                                        case 'cancelled': echo 'Đã hủy'; break;
                                    }
                                    ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- SẢN PHẨM SẮP HẾT HÀNG -->
    <div class="admin-table-wrap">
        <div class="admin-table-head">
            <h3 class="admin-table-title"><i class="ti ti-alert-triangle"></i> Cảnh báo tồn kho</h3>
            <a href="<?= BASE_URL ?>index.php?controller=admin&action=products" class="btn btn-sm">Quản lý</a>
        </div>
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th style="width: 130px;">Tồn kho</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($low_stock_products)): ?>
                    <tr>
                        <td colspan="2" style="text-align: center; color: var(--text-3); padding: 20px;">Tất cả sản phẩm đều đủ tồn kho!</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($low_stock_products as $p): ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <img src="<?= BASE_URL ?>/images/<?= htmlspecialchars($p['image'] ?: 'default.jpg') ?>" 
                                         style="width: 32px; height: 32px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border);"
                                         onerror="this.src='<?= BASE_URL ?>/public/images/<?= htmlspecialchars($p['image'] ?: 'default.jpg') ?>'; this.onerror=function(){this.src='<?= BASE_URL ?>/assets/images/default.jpg'}">
                                    <div>
                                        <div style="font-weight: 600; font-size: 12.5px;"><?= htmlspecialchars($p['name']) ?></div>
                                        <div style="font-size: 11px; color: var(--text-3);"><?= htmlspecialchars($p['category_name']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="stock-bar-wrap">
                                    <div class="stock-bar">
                                        <div class="stock-bar-fill" style="width: <?= min(100, $p['stock'] * 20) ?>%; background: <?= $p['stock'] <= 2 ? 'var(--red)' : 'var(--amber)' ?>;"></div>
                                    </div>
                                    <span class="stock-num <?= $p['stock'] <= 2 ? 'c-red' : 'c-amber' ?>" style="font-weight: 700;"><?= $p['stock'] ?></span>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
// Tự động load layout bằng cách nhúng tệp layout vào cuối nếu file này được require độc lập
if (!isset($layout_loaded)) {
    $layout_loaded = true;
    require_once '../app/Views/layouts/admin.php';
}
?>
