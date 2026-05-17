<?php
/**
 * @var array|null $product
 * @var array $categories
 * @var string $title
 */
$isEdit = !empty($product);
$action = $isEdit ? BASE_URL . 'index.php?controller=admin&action=productUpdate' : BASE_URL . 'index.php?controller=admin&action=productStore';
$content_view = '../app/Views/admin/products/form.php';
?>
<div style="margin-bottom: 20px;">
    <a href="<?= BASE_URL ?>index.php?controller=admin&action=products" class="btn">
        <i class="ti ti-arrow-left"></i> Quay lại danh sách
    </a>
</div>

<div class="admin-table-wrap" style="padding: 30px; background: var(--bg-card);">
    <div style="border-bottom: 1px solid var(--border); padding-bottom: 15px; margin-bottom: 25px;">
        <h3 style="font-family: var(--font-d); font-size: 20px; color: var(--brown-dark); font-weight: 700;">
            <i class="ti ti-edit"></i> <?= htmlspecialchars($title) ?>
        </h3>
    </div>

    <form action="<?= $action ?>" method="POST" enctype="multipart/form-data">
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?= $product['id'] ?>">
        <?php endif; ?>

        <div class="form-grid">
            <!-- Tên sản phẩm -->
            <div class="form-group">
                <label for="name">Tên sản phẩm *</label>
                <input type="text" id="name" name="name" required value="<?= htmlspecialchars($product['name'] ?? '') ?>" placeholder="Nhập tên sách/văn phòng phẩm...">
            </div>

            <!-- Slug -->
            <div class="form-group">
                <label for="slug">Đường dẫn thân thiện (Slug)</label>
                <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($product['slug'] ?? '') ?>" placeholder="Tự động tạo từ tên nếu để trống...">
            </div>

            <!-- Tác giả / Thương hiệu -->
            <div class="form-group">
                <label for="author">Tác giả / Nhà sản xuất</label>
                <input type="text" id="author" name="author" value="<?= htmlspecialchars($product['author'] ?? '') ?>" placeholder="Tác giả hoặc thương hiệu...">
            </div>

            <!-- Danh mục -->
            <div class="form-group">
                <label for="category_id">Danh mục *</label>
                <select id="category_id" name="category_id" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= (($product['category_id'] ?? 0) == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Giá gốc -->
            <div class="form-group">
                <label for="price">Giá gốc (đ) *</label>
                <input type="number" id="price" name="price" required min="0" value="<?= $product['price'] ?? 0 ?>">
            </div>

            <!-- Giá khuyến mãi -->
            <div class="form-group">
                <label for="sale_price">Giá sale (đ) (Không bắt buộc)</label>
                <input type="number" id="sale_price" name="sale_price" min="0" value="<?= $product['sale_price'] ?? '' ?>" placeholder="Để trống nếu không giảm giá...">
            </div>

            <!-- Số lượng tồn kho -->
            <div class="form-group">
                <label for="stock">Số lượng trong kho *</label>
                <input type="number" id="stock" name="stock" required min="0" value="<?= $product['stock'] ?? 0 ?>">
            </div>

            <!-- Nhãn sản phẩm (Badge) -->
            <div class="form-group">
                <label for="badge">Nhãn đính kèm (Badge)</label>
                <select id="badge" name="badge">
                    <option value="" <?= (($product['badge'] ?? '') === '') ? 'selected' : '' ?>>Không có</option>
                    <option value="new" <?= (($product['badge'] ?? '') === 'new') ? 'selected' : '' ?>>Mới (New)</option>
                    <option value="hot" <?= (($product['badge'] ?? '') === 'hot') ? 'selected' : '' ?>>Bán chạy (Hot)</option>
                    <option value="sale" <?= (($product['badge'] ?? '') === 'sale') ? 'selected' : '' ?>>Giảm giá (Sale)</option>
                </select>
            </div>

            <!-- Ảnh sản phẩm -->
            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="image">Ảnh sản phẩm</label>
                <div style="display: flex; align-items: center; gap: 20px; margin-top: 5px;">
                    <?php if ($isEdit && !empty($product['image'])): ?>
                        <div style="text-align: center;">
                            <img src="<?= BASE_URL ?>images/<?= htmlspecialchars($product['image']) ?>" 
                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border);"
                                 id="current-preview"
                                 onerror="this.src='<?= BASE_URL ?>/public/images/<?= htmlspecialchars($product['image']) ?>'; this.onerror=function(){this.src='<?= BASE_URL ?>/assets/images/default.jpg'}">
                            <div style="font-size: 11px; color: var(--text-3); margin-top: 4px;">Ảnh hiện tại</div>
                        </div>
                    <?php endif; ?>
                    <div style="flex: 1;">
                        <input type="file" id="image" name="image" accept="image/*" onchange="previewFile()">
                        <p style="font-size: 11px; color: var(--text-3); margin-top: 5px;">Hỗ trợ định dạng JPG, PNG, WEBP. Dung lượng tối đa 2MB.</p>
                    </div>
                </div>
            </div>

            <!-- Mô tả chi tiết -->
            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="description">Mô tả sản phẩm</label>
                <textarea id="description" name="description" rows="6" placeholder="Nhập mô tả sản phẩm tại đây..."><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
            </div>

            <!-- Các nút bật tắt trạng thái -->
            <div class="form-group" style="grid-column: 1 / -1; display: flex; flex-direction: row; gap: 30px; margin: 10px 0;">
                <!-- Nổi bật -->
                <div style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1" <?= (($product['is_featured'] ?? 0) == 1) ? 'checked' : '' ?> style="width: 18px; height: 18px; cursor: pointer;">
                    <label for="is_featured" style="cursor: pointer; text-transform: none; font-size: 13.5px; font-weight: 500;">Đặt làm sản phẩm Nổi bật (Hiển thị trang chủ)</label>
                </div>

                <!-- Hiển thị công khai -->
                <div style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" id="is_active" name="is_active" value="1" <?= (($product['is_active'] ?? 1) == 1) ? 'checked' : '' ?> style="width: 18px; height: 18px; cursor: pointer;">
                    <label for="is_active" style="cursor: pointer; text-transform: none; font-size: 13.5px; font-weight: 500;">Hiển thị công khai sản phẩm trên cửa hàng</label>
                </div>
            </div>
        </div>

        <div class="form-actions" style="border-top: 1px solid var(--border); padding-top: 20px; margin-top: 20px;">
            <a href="<?= BASE_URL ?>index.php?controller=admin&action=products" class="btn">Hủy</a>
            <button type="submit" class="btn btn-gold"><i class="ti ti-device-floppy"></i> Lưu thay đổi</button>
        </div>
    </form>
</div>

<script>
function previewFile() {
    const preview = document.querySelector('#current-preview');
    const file = document.querySelector('#image').files[0];
    const reader = new FileReader();

    reader.addEventListener("load", function () {
        if (preview) {
            preview.src = reader.result;
        }
    }, false);

    if (file) {
        reader.readAsDataURL(file);
    }
}

// Tự động tạo slug khi gõ tên sản phẩm (Chỉ hoạt động khi tạo mới)
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
