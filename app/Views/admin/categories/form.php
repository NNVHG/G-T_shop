<?php
/**
 * @var array|null $category
 * @var string $title
 */
$isEdit = !empty($category);
$action = $isEdit ? BASE_URL . 'index.php?controller=admin&action=categoryUpdate' : BASE_URL . 'index.php?controller=admin&action=categoryStore';
$content_view = '../app/Views/admin/categories/form.php';
?>
<div style="margin-bottom: 20px;">
    <a href="<?= BASE_URL ?>index.php?controller=admin&action=categories" class="btn">
        <i class="ti ti-arrow-left"></i> Quay lại danh sách
    </a>
</div>

<div class="admin-table-wrap" style="padding: 30px; background: var(--bg-card); max-width: 700px; margin: 0 auto 30px;">
    <div style="border-bottom: 1px solid var(--border); padding-bottom: 15px; margin-bottom: 25px;">
        <h3 style="font-family: var(--font-d); font-size: 20px; color: var(--brown-dark); font-weight: 700;">
            <i class="ti ti-folder"></i> <?= htmlspecialchars($title) ?>
        </h3>
    </div>

    <form action="<?= $action ?>" method="POST">
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?= $category['id'] ?>">
        <?php endif; ?>

        <div class="form-grid" style="grid-template-columns: 1fr;">
            <!-- Tên danh mục -->
            <div class="form-group">
                <label for="name">Tên danh mục *</label>
                <input type="text" id="name" name="name" required value="<?= htmlspecialchars($category['name'] ?? '') ?>" placeholder="Ví dụ: Sách văn học, Đồ chơi học tập...">
            </div>

            <!-- Slug -->
            <div class="form-group">
                <label for="slug">Đường dẫn thân thiện (Slug)</label>
                <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($category['slug'] ?? '') ?>" placeholder="Tự động tạo từ tên nếu để trống...">
            </div>

            <!-- Icon -->
            <div class="form-group">
                <label for="icon">Tên lớp biểu tượng (Tabler Icon Class)</label>
                <input type="text" id="icon" name="icon" value="<?= htmlspecialchars($category['icon'] ?? 'ti-tag') ?>" placeholder="Ví dụ: ti-book, ti-pencil, ti-device-gamepad-2...">
                <p style="font-size: 11px; color: var(--text-3); margin-top: 3px;">
                    Tham khảo các lớp biểu tượng từ thư viện Tabler Icons. Gợi ý: 
                    <code>ti-book</code> (Sách), <code>ti-pencil</code> (Văn phòng phẩm), 
                    <code>ti-candy</code> (Ăn vặt), <code>ti-puzzle</code> (Đồ chơi), 
                    <code>ti-tag</code> (Mặc định).
                </p>
            </div>

            <!-- Thứ tự sắp xếp -->
            <div class="form-group">
                <label for="sort_order">Thứ tự sắp xếp (Số nhỏ đứng trước)</label>
                <input type="number" id="sort_order" name="sort_order" min="0" value="<?= $category['sort_order'] ?? 0 ?>">
            </div>
        </div>

        <div class="form-actions" style="border-top: 1px solid var(--border); padding-top: 20px; margin-top: 20px;">
            <a href="<?= BASE_URL ?>index.php?controller=admin&action=categories" class="btn">Hủy</a>
            <button type="submit" class="btn btn-gold"><i class="ti ti-device-floppy"></i> Lưu thay đổi</button>
        </div>
    </form>
</div>

<script>
// Tự động tạo slug khi gõ tên danh mục (Chỉ hoạt động khi tạo mới)
const nameInput = document.querySelector('#name');
const slugInput = document.querySelector('#slug');

if (nameInput && slugInput && !<?= $isEdit ? 'true' : 'false' ?>) {
    nameInput.addEventListener('input', function() {
        let slug = nameInput.value.toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // Loại bỏ dấu tiếng Việt
            .replace(/[đĐ]/g, 'd')
            .replace(/([^0-9a-z-\s])/g, '') // Xóa ký tự đặc biệt
            .replace(/(\s+)/g, '-') // Đổi khoảng trắng thành -
            .replace(/-+/g, '-') // Gom nhiều - thành một -
            .replace(/^-+|-+$/g, ''); // Cắt - ở đầu và cuối
        slugInput.value = slug;
    });
}
</script>

<?php
if (!isset($layout_loaded)) {
    $layout_loaded = true;
    require_once '../app/Views/layouts/admin.php';
}
?>
