<?php
/**
 * @var array $orders
 * @var string $title
 */
$content_view = '../app/Views/admin/orders/index.php';
?>
<div class="admin-table-wrap">
    <div class="admin-table-head">
        <h3 class="admin-table-title"><i class="ti ti-shopping-cart"></i> Danh sách đơn đặt hàng</h3>
        <span style="font-size: 13px; color: var(--text-3); font-weight: 500;">Tổng cộng: <strong><?= count($orders) ?></strong> đơn hàng</span>
    </div>
    <table style="width: 100%;">
        <thead>
            <tr>
                <th style="width: 80px;">Mã ĐH</th>
                <th>Khách hàng</th>
                <th>Thông tin liên hệ</th>
                <th>Ngày đặt</th>
                <th>Phương thức</th>
                <th>Trạng thái thanh toán</th>
                <th>Trạng thái đơn</th>
                <th style="font-weight: 700;">Tổng tiền</th>
                <th style="width: 120px; text-align: right;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="9" style="text-align: center; color: var(--text-3); padding: 30px;">Không có đơn hàng nào trên hệ thống.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($orders as $ord): ?>
                    <tr>
                        <td style="font-family: var(--font-m); font-weight: 700; color: var(--text-2);">
                            #<?= $ord['id'] ?>
                        </td>
                        <td style="font-weight: 700; color: var(--brown-dark);"><?= htmlspecialchars($ord['customer_name']) ?></td>
                        <td>
                            <div><i class="ti ti-phone" style="font-size: 12px;"></i> <?= htmlspecialchars($ord['phone']) ?></div>
                            <div style="font-size: 11px; color: var(--text-3); margin-top: 2px;"><i class="ti ti-map-pin" style="font-size: 11px;"></i> <?= htmlspecialchars($ord['address']) ?></div>
                        </td>
                        <td style="color: var(--text-2);"><?= date('H:i d/m/Y', strtotime($ord['created_at'])) ?></td>
                        <td style="text-transform: uppercase; font-family: var(--font-m); font-size: 11.5px; font-weight: 600; color: var(--text-2);">
                            <?= htmlspecialchars($ord['payment_method']) ?>
                        </td>
                        <td>
                            <span class="badge badge-<?= $ord['payment_status'] ?>">
                                <?php
                                switch ($ord['payment_status']) {
                                    case 'unpaid': echo 'Chưa thanh toán'; break;
                                    case 'paid': echo 'Đã thanh toán'; break;
                                    case 'failed': echo 'Thất bại'; break;
                                }
                                ?>
                            </span>
                        </td>
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
                        <td style="font-weight: 700; color: var(--brown-dark); font-size: 14.5px;"><?= number_format($ord['total']) ?>đ</td>
                        <td style="text-align: right;">
                            <a href="<?= BASE_URL ?>index.php?controller=admin&action=orderDetail&id=<?= $ord['id'] ?>" class="btn btn-sm btn-gold">
                                <i class="ti ti-eye"></i> Chi tiết
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
if (!isset($layout_loaded)) {
    $layout_loaded = true;
    require_once '../app/Views/layouts/admin.php';
}
?>
