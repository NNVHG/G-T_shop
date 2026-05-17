<?php
/**
 * @var array $products
 * @var array $categories
 * @var string $search
 * @var int $category_id
 * @var string $status
 * @var string $title
 */
$content_view = '../app/Views/admin/products/index.php';
?>

<!-- FILTER, SEARCH & CREATE PANEL -->
<div style="background: var(--bg-panel); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 22px; margin-bottom: 25px; box-shadow: var(--shadow-sm);">
    <form method="GET" action="<?= BASE_URL ?>index.php" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; width: 100%;">
        <div style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end; flex: 1;">
            <input type="hidden" name="controller" value="admin">
            <input type="hidden" name="action" value="products">

            <!-- Search Input -->
            <div style="flex: 1; min-width: 250px;">
                <label style="display: block; font-size: 11px; font-weight: 700; margin-bottom: 8px; color: var(--text-2); text-transform: uppercase; letter-spacing: .05em;">Tìm kiếm sản phẩm</label>
                <div style="position: relative; display: flex; align-items: center;">
                    <i class="ti ti-search" style="position: absolute; left: 14px; color: var(--text-3); font-size: 16px;"></i>
                    <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Nhập tên sản phẩm, tác giả hoặc ID..." style="width: 100%; padding: 10px 14px 10px 40px; background: var(--bg-panel); border: 1px solid var(--border); border-radius: var(--radius-md); font-size: 13.5px; color: var(--text-1); outline: none;">
                </div>
            </div>

            <!-- Category Filter -->
            <div style="width: 200px; min-width: 150px;">
                <label style="display: block; font-size: 11px; font-weight: 700; margin-bottom: 8px; color: var(--text-2); text-transform: uppercase; letter-spacing: .05em;">Danh mục</label>
                <select name="category_id" style="width: 100%; padding: 10px 14px; background: var(--bg-panel); border: 1px solid var(--border); border-radius: var(--radius-md); font-size: 13.5px; color: var(--text-1); outline: none; cursor: pointer;">
                    <option value="0">--- Tất cả danh mục ---</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= (isset($category_id) && $category_id === (int)$cat['id']) ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Status Filter -->
            <div style="width: 180px; min-width: 130px;">
                <label style="display: block; font-size: 11px; font-weight: 700; margin-bottom: 8px; color: var(--text-2); text-transform: uppercase; letter-spacing: .05em;">Trạng thái hiển thị</label>
                <select name="status" style="width: 100%; padding: 10px 14px; background: var(--bg-panel); border: 1px solid var(--border); border-radius: var(--radius-md); font-size: 13.5px; color: var(--text-1); outline: none; cursor: pointer;">
                    <option value="all" <?= (!isset($status) || $status === 'all') ? 'selected' : '' ?>>Tất cả trạng thái</option>
                    <option value="active" <?= (isset($status) && $status === 'active') ? 'selected' : '' ?>>Hiển thị (Hiển thị)</option>
                    <option value="inactive" <?= (isset($status) && $status === 'inactive') ? 'selected' : '' ?>>Ẩn (Ẩn)</option>
                </select>
            </div>

            <!-- Actions Buttons -->
            <div style="display: flex; gap: 8px; height: 40px; align-items: flex-end;">
                <button type="submit" class="btn btn-gold" style="padding: 10px 20px; font-size: 13px; font-weight: 700; border-radius: var(--radius-md); height: 40px; display: flex; align-items: center; gap: 6px;">
                    <i class="ti ti-filter" style="font-size: 15px;"></i> Tìm kiếm
                </button>
                <?php if (!empty($search) || (isset($category_id) && $category_id > 0) || (isset($status) && $status !== 'all')): ?>
                    <a href="<?= BASE_URL ?>index.php?controller=admin&action=products" class="btn" style="padding: 10px 20px; font-size: 13px; font-weight: 600; border-radius: var(--radius-md); height: 40px; display: flex; align-items: center; background: var(--border); border-color: var(--border); color: var(--text-1);">
                        Đặt lại
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Add Product Button (Aligned to the right / bottom-right on wrap) -->
        <div style="height: 40px; display: flex; align-items: flex-end; margin-left: auto;">
            <a href="<?= BASE_URL ?>index.php?controller=admin&action=productCreate" class="btn btn-gold" style="padding: 10px 20px; font-size: 13px; font-weight: 700; border-radius: var(--radius-md); height: 40px; display: flex; align-items: center; gap: 6px; background: var(--brown-dark); color: var(--gold-bright); border-color: var(--brown-dark);">
                <i class="ti ti-plus" style="font-size: 15px;"></i> Thêm sản phẩm mới
            </a>
        </div>
    </form>
</div>

<div class="admin-table-wrap">
    <div class="admin-table-head">
        <h3 class="admin-table-title"><i class="ti ti-books"></i> Danh sách sản phẩm</h3>
        <span style="font-size: 13px; color: var(--text-3); font-weight: 500;">Tổng cộng: <strong><?= count($products) ?></strong> sản phẩm</span>
    </div>
    <table style="width: 100%;">
        <thead>
            <tr>
                <th style="width: 60px;">Mã số</th>
                <th>Sản phẩm</th>
                <th>Tác giả</th>
                <th>Danh mục</th>
                <th>Giá bán</th>
                <th>Tồn kho</th>
                <th>Nhãn</th>
                <th>Trạng thái</th>
                <th style="width: 150px; text-align: right;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
                <tr>
                    <td colspan="9" style="text-align: center; color: var(--text-3); padding: 50px 30px;">
                        <i class="ti ti-search-off" style="font-size: 40px; display: block; margin-bottom: 12px; color: var(--border-mid);"></i>
                        <div style="font-size: 15px; font-weight: 700; color: var(--brown-dark); margin-bottom: 6px;">Không tìm thấy sản phẩm nào!</div>
                        <div style="font-size: 13px; color: var(--text-3);">Không có sản phẩm nào khớp với bộ lọc tìm kiếm hiện tại của bạn.</div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td style="font-family: var(--font-m); font-weight: 700; color: var(--text-2);">#<?= $p['id'] ?></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <img src="<?= BASE_URL ?>images/<?= htmlspecialchars($p['image'] ?: 'default.jpg') ?>" 
                                     style="width: 45px; height: 45px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border);"
                                     onerror="this.src='<?= BASE_URL ?>/public/images/<?= htmlspecialchars($p['image'] ?: 'default.jpg') ?>'; this.onerror=function(){this.src='<?= BASE_URL ?>/assets/images/default.jpg'}">
                                <div>
                                    <div style="font-weight: 700; font-size: 14px; color: var(--brown-dark);"><?= htmlspecialchars($p['name']) ?></div>
                                    <div style="font-size: 11px; color: var(--text-3); margin-top: 2px;">Slug: <code><?= htmlspecialchars($p['slug']) ?></code></div>
                                </div>
                            </div>
                        </td>
                        <td style="color: var(--text-2); font-weight: 500;"><?= htmlspecialchars($p['author'] ?: 'N/A') ?></td>
                        <td style="font-weight: 600; color: var(--gold-dim);"><?= htmlspecialchars($p['category_name']) ?></td>
                        <td>
                            <?php if ($p['sale_price'] !== null): ?>
                                <div style="font-weight: 700; color: var(--red);"><?= number_format($p['sale_price']) ?>đ</div>
                                <div style="font-size: 11px; text-decoration: line-through; color: var(--text-3);"><?= number_format($p['price']) ?>đ</div>
                            <?php else: ?>
                                <div style="font-weight: 700; color: var(--brown-dark);"><?= number_format($p['price']) ?>đ</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="stock-bar-wrap" style="min-width: 100px;">
                                <div class="stock-bar" style="height: 5px;">
                                    <div class="stock-bar-fill" style="width: <?= min(100, $p['stock'] * 10) ?>%; background: <?= $p['stock'] <= 5 ? 'var(--red)' : ($p['stock'] <= 15 ? 'var(--amber)' : 'var(--green)') ?>;"></div>
                                </div>
                                <span class="stock-num <?= $p['stock'] <= 5 ? 'c-red' : ($p['stock'] <= 15 ? 'c-amber' : 'c-green') ?>" style="font-weight: 700; font-family: var(--font-m);"><?= $p['stock'] ?></span>
                            </div>
                        </td>
                        <td>
                            <?php if (!empty($p['badge'])): ?>
                                <span style="background: var(--brown-dark); color: var(--gold-bright); font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 4px; text-transform: uppercase;">
                                    <?= htmlspecialchars($p['badge']) ?>
                                </span>
                            <?php else: ?>
                                <span style="color: var(--text-3); font-size: 11px;">Không</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge" style="background: <?= $p['is_active'] ? 'rgba(46, 125, 50, 0.1)' : 'rgba(198, 40, 40, 0.1)' ?>; color: <?= $p['is_active'] ? 'var(--green)' : 'var(--red)' ?>; border: 1px solid <?= $p['is_active'] ? 'rgba(46, 125, 50, 0.2)' : 'rgba(198, 40, 40, 0.2)' ?>;">
                                <?= $p['is_active'] ? 'Hiển thị' : 'Ẩn' ?>
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                <a href="<?= BASE_URL ?>index.php?controller=admin&action=productEdit&id=<?= $p['id'] ?>" class="btn btn-sm" title="Sửa">
                                    <i class="ti ti-edit" style="font-size: 14px;"></i> Sửa
                                </a>
                                <a href="javascript:void(0)" onclick="confirmDelete(<?= $p['id'] ?>, '<?= htmlspecialchars(addslashes($p['name'])) ?>')" class="btn btn-sm btn-danger" title="Xóa">
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
        text: "Sản phẩm \"" + name + "\" sẽ bị xóa vĩnh viễn khỏi cơ sở dữ liệu!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#2c1a0e',
        cancelButtonColor: '#c62828',
        confirmButtonText: 'Đồng ý, xóa ngay!',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "<?= BASE_URL ?>index.php?controller=admin&action=productDelete&id=" + id;
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
