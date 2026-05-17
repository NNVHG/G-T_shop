<?php
/**
 * @var array $categories
 * @var string $title
 */
$content_view = '../app/Views/admin/categories/index.php';
?>
<div style="margin-bottom: 20px; display: flex; justify-content: flex-end;">
    <a href="<?= BASE_URL ?>index.php?controller=admin&action=categoryCreate" class="btn btn-gold">
        <i class="ti ti-plus"></i> Thêm danh mục mới
    </a>
</div>

<div class="admin-table-wrap">
    <div class="admin-table-head">
        <h3 class="admin-table-title"><i class="ti ti-folders"></i> Quản lý danh mục</h3>
        <span style="font-size: 13px; color: var(--text-3); font-weight: 500;">Tổng cộng: <strong><?= count($categories) ?></strong> danh mục</span>
    </div>
    <table style="width: 100%;">
        <thead>
            <tr>
                <th style="width: 80px;">ID</th>
                <th>Tên danh mục</th>
                <th>Đường dẫn (Slug)</th>
                <th>Biểu tượng (Icon)</th>
                <th>Thứ tự sắp xếp</th>
                <th>Số sản phẩm</th>
                <th style="width: 150px; text-align: right;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($categories)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-3); padding: 30px;">Không có danh mục nào trên hệ thống.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td style="font-family: var(--font-m); font-weight: 700; color: var(--text-2);">#<?= $cat['id'] ?></td>
                        <td style="font-weight: 700; color: var(--brown-dark); font-size: 14.5px;">
                            <?= htmlspecialchars($cat['name']) ?>
                        </td>
                        <td><code><?= htmlspecialchars($cat['slug']) ?></code></td>
                        <td>
                            <span style="display: inline-flex; align-items: center; gap: 8px; font-weight: 500;">
                                <span style="background: var(--bg-hover); color: var(--gold-dim); width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; border: 1px solid var(--border);">
                                    <i class="ti <?= htmlspecialchars($cat['icon'] ?: 'ti-tag') ?>"></i>
                                </span>
                                <code><?= htmlspecialchars($cat['icon'] ?: 'ti-tag') ?></code>
                            </span>
                        </td>
                        <td style="font-weight: 600; color: var(--text-2);"><?= $cat['sort_order'] ?></td>
                        <td>
                            <span style="background: rgba(189, 154, 95, 0.1); color: var(--gold-dim); padding: 2px 10px; border-radius: 20px; font-weight: 700; font-size: 12px;">
                                <?= $cat['product_count'] ?> sản phẩm
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                <a href="<?= BASE_URL ?>index.php?controller=admin&action=categoryEdit&id=<?= $cat['id'] ?>" class="btn btn-sm" title="Sửa">
                                    <i class="ti ti-edit" style="font-size: 14px;"></i> Sửa
                                </a>
                                <a href="javascript:void(0)" onclick="confirmDelete(<?= $cat['id'] ?>, '<?= htmlspecialchars(addslashes($cat['name'])) ?>')" class="btn btn-sm btn-danger" title="Xóa">
                                    <i class="ti ti-trash" style="font-size: 14px;"></i> Xóa
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Bạn chắc chắn muốn xóa?',
        text: "Danh mục \"" + name + "\" và toàn bộ các sản phẩm trực thuộc sẽ bị xóa vĩnh viễn khỏi hệ thống!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#2c1a0e',
        cancelButtonColor: '#c62828',
        confirmButtonText: 'Đồng ý, xóa ngay!',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "<?= BASE_URL ?>index.php?controller=admin&action=categoryDelete&id=" + id;
        }
    })
}
</script>

<?php
if (!isset($layout_loaded)) {
    $layout_loaded = true;
    require_once '../app/Views/layouts/admin.php';
}
?>
