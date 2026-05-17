<?php
/**
 * @var array $order
 * @var array $items
 * @var string $title
 */
$content_view = '../app/Views/admin/orders/detail.php';
?>
<div style="margin-bottom: 20px;">
    <a href="<?= BASE_URL ?>index.php?controller=admin&action=orders" class="btn">
        <i class="ti ti-arrow-left"></i> Quay lại danh sách
    </a>
</div>

<div class="form-grid" style="grid-template-columns: 1.5fr 1fr; margin-bottom: 30px;">
    
    <!-- CHI TIẾT SẢN PHẨM TRONG ĐƠN HÀNG -->
    <div class="admin-table-wrap" style="height: fit-content;">
        <div class="admin-table-head">
            <h3 class="admin-table-title"><i class="ti ti-list"></i> Các mặt hàng đã đặt</h3>
        </div>
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th style="text-align: right;">Đơn giá</th>
                    <th style="text-align: center; width: 80px;">Số lượng</th>
                    <th style="text-align: right;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <img src="<?= BASE_URL ?>images/<?= htmlspecialchars($item['image'] ?: 'default.jpg') ?>" 
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border);"
                                     onerror="this.src='<?= BASE_URL ?>/public/images/<?= htmlspecialchars($item['image'] ?: 'default.jpg') ?>'; this.onerror=function(){this.src='<?= BASE_URL ?>/assets/images/default.jpg'}">
                                <div>
                                    <div style="font-weight: 700; color: var(--brown-dark);"><?= htmlspecialchars($item['product_name']) ?></div>
                                    <div style="font-size: 11px; color: var(--text-3); margin-top: 2px;">Mã SP: #<?= $item['product_id'] ?></div>
                                </div>
                            </div>
                        </td>
                        <td style="text-align: right; font-weight: 600;"><?= number_format($item['price']) ?>đ</td>
                        <td style="text-align: center; font-weight: 700; font-family: var(--font-m);"><?= $item['qty'] ?></td>
                        <td style="text-align: right; font-weight: 700; color: var(--brown-dark);"><?= number_format($item['price'] * $item['qty']) ?>đ</td>
                    </tr>
                <?php endforeach; ?>
                <!-- Tổng cộng -->
                <tr style="background: #FAF8F5;">
                    <td colspan="3" style="text-align: right; font-weight: 700; font-family: var(--font-d); font-size: 16px; color: var(--brown-dark);">Tổng giá trị đơn hàng:</td>
                    <td style="text-align: right; font-weight: 800; font-size: 18px; color: var(--red);"><?= number_format($order['total']) ?>đ</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- THÔNG TIN ĐƠN HÀNG & TRẠNG THÁI -->
    <div style="display: flex; flex-direction: column; gap: 20px;">
        
        <!-- THÔNG TIN KHÁCH HÀNG & GIAO HÀNG -->
        <div class="admin-table-wrap" style="padding: 24px; background: var(--bg-card);">
            <h4 style="font-family: var(--font-d); font-size: 16px; color: var(--brown-dark); border-bottom: 1px solid var(--border); padding-bottom: 10px; margin-bottom: 15px; font-weight: 700;">
                <i class="ti ti-user"></i> Thông tin khách hàng
            </h4>
            
            <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13.5px;">
                <div>Tên khách hàng: <strong><?= htmlspecialchars($order['customer_name']) ?></strong></div>
                <div>Số điện thoại: <strong><?= htmlspecialchars($order['phone']) ?></strong></div>
                <div>Địa chỉ nhận hàng: <strong style="color: var(--text-2);"><?= htmlspecialchars($order['address']) ?></strong></div>
                <div>Ghi chú đơn hàng: <span style="font-style: italic; color: var(--text-3);"><?= htmlspecialchars($order['note'] ?: 'Không có ghi chú') ?></span></div>
                <div>Ngày tạo đơn: <span style="color: var(--text-3);"><?= date('H:i:s d/m/Y', strtotime($order['created_at'])) ?></span></div>
            </div>
        </div>

        <!-- CẬP NHẬT TRẠNG THÁI -->
        <div class="admin-table-wrap" style="padding: 24px; background: var(--bg-card);">
            <h4 style="font-family: var(--font-d); font-size: 16px; color: var(--brown-dark); border-bottom: 1px solid var(--border); padding-bottom: 10px; margin-bottom: 15px; font-weight: 700;">
                <i class="ti ti-adjustments"></i> Trạng thái &amp; Thanh toán
            </h4>

            <!-- Tình trạng hiện tại -->
            <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px; font-size: 13.5px;">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <span>Trạng thái đơn:</span>
                    <span class="badge badge-<?= $order['status'] ?>">
                        <?php
                        switch ($order['status']) {
                            case 'pending': echo 'Chờ xử lý'; break;
                            case 'processing': echo 'Đang xử lý'; break;
                            case 'shipped': echo 'Đang giao'; break;
                            case 'done': echo 'Hoàn tất'; break;
                            case 'cancelled': echo 'Đã hủy'; break;
                        }
                        ?>
                    </span>
                </div>
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <span>Thanh toán:</span>
                    <span class="badge badge-<?= $order['payment_status'] ?>">
                        <?php
                        switch ($order['payment_status']) {
                            case 'unpaid': echo 'Chưa thanh toán'; break;
                            case 'paid': echo 'Đã thanh toán'; break;
                            case 'failed': echo 'Thất bại'; break;
                        }
                        ?>
                    </span>
                </div>
                <div>Hình thức: <span style="font-weight: 700; text-transform: uppercase;"><?= htmlspecialchars($order['payment_method']) ?></span></div>
                <?php if ($order['transaction_id']): ?>
                    <div style="font-size: 12px; color: var(--text-3);">Mã GD VNPay: <code><?= htmlspecialchars($order['transaction_id']) ?></code></div>
                <?php endif; ?>
            </div>

            <!-- Cập nhật Trạng thái đơn -->
            <form action="<?= BASE_URL ?>index.php?controller=admin&action=orderStatus" method="POST" style="border-top: 1px solid var(--border); padding-top: 15px;">
                <input type="hidden" name="id" value="<?= $order['id'] ?>">
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="status">Cập nhật trạng thái đơn</label>
                    <select id="status" name="status">
                        <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                        <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Đang xử lý</option>
                        <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Đang giao hàng</option>
                        <option value="done" <?= $order['status'] === 'done' ? 'selected' : '' ?>>Đã hoàn tất đơn</option>
                        <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Hủy đơn hàng</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-gold btn-sm" style="width: 100%; justify-content: center;">
                    <i class="ti ti-device-floppy"></i> Cập nhật trạng thái
                </button>
            </form>

            <!-- Nút xác nhận thanh toán thủ công -->
            <?php if ($order['payment_status'] !== 'paid'): ?>
                <div style="margin-top: 12px;">
                    <a href="<?= BASE_URL ?>index.php?controller=admin&action=markPaid&id=<?= $order['id'] ?>" class="btn btn-sm btn-danger" style="width: 100%; justify-content: center; background: rgba(198, 40, 40, 0.05);">
                        <i class="ti ti-circle-check"></i> Xác nhận Đã thanh toán
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>
<?php
if (!isset($layout_loaded)) {
    $layout_loaded = true;
    require_once '../app/Views/layouts/admin.php';
}
?>
