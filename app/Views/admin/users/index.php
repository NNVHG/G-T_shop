<?php
/**
 * @var array $users
 * @var string $title
 */
$content_view = '../app/Views/admin/users/index.php';
?>
<div class="admin-table-wrap">
    <div class="admin-table-head">
        <h3 class="admin-table-title"><i class="ti ti-users"></i> Quản lý tài khoản người dùng</h3>
        <span style="font-size: 13px; color: var(--text-3); font-weight: 500;">Tổng cộng: <strong><?= count($users) ?></strong> tài khoản</span>
    </div>
    <table style="width: 100%;">
        <thead>
            <tr>
                <th style="width: 80px;">Mã số</th>
                <th>Họ và tên</th>
                <th>Địa chỉ Email</th>
                <th>Số điện thoại</th>
                <th>Địa chỉ mặc định</th>
                <th>Vai trò</th>
                <th>Ngày tạo tài khoản</th>
                <th style="width: 180px; text-align: right;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-3); padding: 30px;">Không có tài khoản nào trên hệ thống.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td style="font-family: var(--font-m); font-weight: 700; color: var(--text-2);">#<?= $u['id'] ?></td>
                        <td style="font-weight: 700; color: var(--brown-dark);"><?= htmlspecialchars($u['name']) ?></td>
                        <td style="font-weight: 500;"><code><?= htmlspecialchars($u['email']) ?></code></td>
                        <td><?= htmlspecialchars($u['phone'] ?: 'Chưa cập nhật') ?></td>
                        <td style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($u['address'] ?? '') ?>">
                            <?= htmlspecialchars($u['address'] ?: 'Chưa cập nhật') ?>
                        </td>
                        <td>
                            <span class="badge" style="background: <?= $u['role'] === 'admin' ? 'rgba(189, 154, 95, 0.15)' : 'rgba(93, 64, 37, 0.1)' ?>; color: <?= $u['role'] === 'admin' ? 'var(--gold-dim)' : 'var(--text-2)' ?>; border: 1px solid <?= $u['role'] === 'admin' ? 'rgba(189, 154, 95, 0.3)' : 'var(--border)' ?>;">
                                <?= $u['role'] === 'admin' ? 'ADMIN' : 'MEMBER' ?>
                            </span>
                        </td>
                        <td style="color: var(--text-3);"><?= date('d/m/Y H:i', strtotime($u['created_at'])) ?></td>
                        <td style="text-align: right;">
                            <?php if ($u['id'] === ($_SESSION['user']['id'] ?? 0)): ?>
                                <span style="font-size: 11.5px; font-style: italic; color: var(--text-3);">Tài khoản hiện tại</span>
                            <?php else: ?>
                                <?php if ($u['role'] === 'admin'): ?>
                                    <a href="<?= BASE_URL ?>index.php?controller=admin&action=changeUserRole&id=<?= $u['id'] ?>&role=user" 
                                       class="btn btn-sm btn-danger" 
                                       style="background: rgba(198, 40, 40, 0.05);"
                                       onclick="return confirm('Bạn chắc chắn muốn hạ quyền Admin của tài khoản này?')">
                                        <i class="ti ti-user-minus"></i> Bỏ quyền Admin
                                    </a>
                                <?php else: ?>
                                    <a href="<?= BASE_URL ?>index.php?controller=admin&action=changeUserRole&id=<?= $u['id'] ?>&role=admin" 
                                       class="btn btn-sm btn-gold"
                                       onclick="return confirm('Bạn chắc chắn muốn phong quyền Admin cho tài khoản này?')">
                                        <i class="ti ti-user-plus"></i> Cấp quyền Admin
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
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
