// ============================================================
//  G&T Shop — main.js
//  Chức năng: giỏ hàng, tìm kiếm AJAX, wishlist, toast, scroll
// ============================================================

// ── Toast notification (thông báo nhanh) ─────────────────────
function showToast(msg, icon = 'success') {
    // Chuyển đổi icon class cũ sang dạng của SweetAlert2 nếu cần
    let swalIcon = 'info';
    if (icon.includes('check') || icon === 'success' || icon.includes('shopping-cart') || icon.includes('heart')) swalIcon = 'success';
    if (icon.includes('alert') || icon === 'error') swalIcon = 'error';
    if (icon.includes('wifi')) swalIcon = 'warning';

    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: swalIcon,
        title: msg,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        customClass: {
            popup: 'colored-toast'
        }
    });
}

// (Legacy add_to_cart event listener removed to avoid duplicate network intercepts)

// ── Wishlist (yêu thích) ──────────────────────────────────────
document.addEventListener('click', async function (e) {
    const btn = e.target.closest('.prod-wish');
    if (!btn) return;

    const id = btn.dataset.id;
    const isActive = btn.classList.toggle('active');

    await fetch(`/gt_shop/wishlist_action.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=${isActive ? 'add' : 'remove'}&product_id=${id}`
    });

    showToast(
        isActive ? 'Đã thêm vào yêu thích' : 'Đã xóa khỏi yêu thích',
        isActive ? 'ti-heart-filled' : 'ti-heart'
    );
});

// ── Tìm kiếm AJAX (gợi ý real-time) MVC ─────────────────────────
(function () {
    const input   = document.querySelector('.search-input');
    const suggest = document.getElementById('searchSuggest');
    if (!input || !suggest) return;

    let timer;

    input.addEventListener('input', function () {
        clearTimeout(timer);
        const q = this.value.trim();

        if (q.length < 2) { suggest.classList.remove('open'); return; }

        timer = setTimeout(async () => {
            try {
                // Gọi API vào Controller MVC
                const res  = await fetch(`/G&T_shop/public/index.php?controller=product&action=suggest&q=${encodeURIComponent(q)}`);
                const data = await res.json();

                if (!data.length) { suggest.classList.remove('open'); return; }

                // Hiển thị dữ liệu và tạo liên kết đến Trang Chi Tiết
                suggest.innerHTML = data.map(p => `
                    <div class="suggest-item" onclick="location.href='/G&T_shop/public/index.php?controller=product&action=detail&id=${p.id}'">
                        <i class="ti ti-search"></i>
                        <span>${p.name}</span>
                        <span class="suggest-price">${p.price_fmt}</span>
                    </div>
                `).join('');

                suggest.classList.add('open');
            } catch { /* Bỏ qua nếu lỗi mạng */ }
        }, 280); // Debounce 280ms để chống spam Server
    });

    // Ẩn bảng gợi ý khi click ra ngoài
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.search-bar')) {
            suggest.classList.remove('open');
        }
    });
})();

// ── Scroll to top ─────────────────────────────────────────────
(function () {
    const btn = document.createElement('button');
    btn.id = 'scrollTop';
    btn.innerHTML = '<i class="ti ti-arrow-up"></i>';
    btn.title = 'Lên đầu trang';
    document.body.appendChild(btn);

    window.addEventListener('scroll', () => {
        btn.classList.toggle('show', window.scrollY > 400);
    });
    btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
})();

// ── XỬ LÝ GIỎ HÀNG (AJAX - MVC) ──────────────────────────────
document.addEventListener('click', async function (e) {
    // 1. Nút Thêm vào giỏ hàng (Từ trang chủ hoặc chi tiết)
    const btnAdd = e.target.closest('.btn-add-cart');
    if (btnAdd) {
        // NGĂN chặn hành vi chuyển hướng của .prod-card khi bấm vào nút thêm giỏ hàng
        e.stopPropagation();
        e.preventDefault();

        const id = btnAdd.dataset.id;
        const form = btnAdd.closest('form');
        
        let qty = 1;
        let productId = id;

        // Nếu người dùng bấm từ form chi tiết sản phẩm
        if (form && form.classList.contains('add-to-cart-form')) {
            productId = form.querySelector('input[name="product_id"]').value;
            qty = form.querySelector('input[name="quantity"]').value;
        }

        if (!productId) return;

        try {
            const res = await fetch(`/G&T_shop/public/index.php?controller=cart&action=add`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `product_id=${productId}&qty=${qty}`
            });
            const data = await res.json();

            if (data.success) {
                showToast(data.message, 'ti-shopping-cart');
                const badge = document.querySelector('.cart-badge');
                if (badge) {
                    badge.textContent = data.cart_count;
                }
                
                // Hiệu ứng đổi icon tạm thời sang dấu tick thành công
                const oldHTML = btnAdd.innerHTML;
                btnAdd.innerHTML = '<i class="ti ti-check"></i>';
                setTimeout(() => btnAdd.innerHTML = oldHTML, 1500);
            } else {
                if (data.redirect) {
                    // Hiển thị hộp thoại SweetAlert2 yêu cầu đăng nhập sang trọng
                    Swal.fire({
                        title: 'Yêu cầu đăng nhập',
                        text: data.message + "\nBạn có muốn chuyển đến trang đăng nhập không?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#5c4033',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Đăng nhập',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = data.redirect;
                        }
                    });
                } else {
                    showToast(data.message || 'Có lỗi xảy ra', 'ti-alert-circle');
                }
            }
        } catch { showToast('Lỗi kết nối máy chủ', 'ti-wifi-off'); }
    }

    // 2. Nút Tăng/Giảm/Xóa (Tại trang Giỏ Hàng)
    const isMinus = e.target.closest('.btn-minus');
    const isPlus = e.target.closest('.btn-plus');
    const isRemove = e.target.closest('.btn-remove');

    if (isMinus || isPlus || isRemove) {
        const itemEl = e.target.closest('.cart-item');
        const productId = itemEl.dataset.id;
        const input = itemEl.querySelector('.qty-input');
        let newQty = parseInt(input.value);

        if (isMinus) newQty--;
        if (isPlus) newQty++;

        if (isRemove || newQty < 1) {
            // Thực hiện xóa
            const res = await fetch(`/G&T_shop/public/index.php?controller=cart&action=remove`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `product_id=${productId}`
            });
            const data = await res.json();
            if (data.success) {
                itemEl.remove();
                updateCartUI(data);
                if (data.cart_count === 0) location.reload(); // Tải lại để hiện chữ "Giỏ rỗng"
            }
        } else {
            // Kiểm tra tồn kho trước khi gửi
            if (newQty > parseInt(input.max)) {
                showToast('Đã vượt quá số lượng tồn kho!', 'ti-alert-circle');
                return;
            }
            // Gửi cập nhật số lượng
            const res = await fetch(`/G&T_shop/public/index.php?controller=cart&action=update`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `product_id=${productId}&qty=${newQty}`
            });
            const data = await res.json();
            if (data.success) {
                input.value = newQty;
                itemEl.querySelector('.item-total-price').textContent = data.item_total_fmt;
                updateCartUI(data);
            }
        }
    }
});

// Hàm hỗ trợ cập nhật số tiền
function updateCartUI(data) {
    document.getElementById('summarySubtotal').textContent = data.total_fmt;
    document.getElementById('summaryTotal').textContent = data.total_fmt;
    document.getElementById('cartCountTitle').textContent = data.cart_count;
    const badge = document.querySelector('.cart-badge');
    if (badge) badge.textContent = data.cart_count;
}