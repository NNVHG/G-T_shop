// ============================================================
//  G&T Shop — main.js
//  Chức năng: giỏ hàng, tìm kiếm AJAX, wishlist, toast, scroll
// ============================================================

// ── Toast notification (thông báo nhanh) ─────────────────────
function showToast(msg, icon = 'ti-check') {
    let t = document.getElementById('toast');
    if (!t) {
        t = document.createElement('div');
        t.id = 'toast';
        document.body.appendChild(t);
    }
    t.innerHTML = `<i class="ti ${icon}"></i> ${msg}`;
    t.classList.add('show');
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => t.classList.remove('show'), 2800);
}

// ── Thêm vào giỏ hàng (gọi API add_to_cart.php) ──────────────
document.addEventListener('click', async function (e) {
    const btn = e.target.closest('.btn-add-cart');
    if (!btn) return;

    const id  = btn.dataset.id;
    const qty = 1;

    try {
        const res  = await fetch(`/gt_shop/cart_action.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=add&product_id=${id}&qty=${qty}`
        });
        const data = await res.json();

        if (data.success) {
            showToast(`Đã thêm vào giỏ hàng!`, 'ti-shopping-cart');
            // Cập nhật badge giỏ hàng
            const badge = document.querySelector('.cart-badge');
            if (badge) {
                badge.textContent = data.cart_count;
            } else {
                const cartBtn = document.querySelector('.cart-btn');
                if (cartBtn) {
                    const span = document.createElement('span');
                    span.className = 'cart-badge';
                    span.textContent = data.cart_count;
                    cartBtn.appendChild(span);
                }
            }
            // Hiệu ứng nút
            btn.innerHTML = '<i class="ti ti-check"></i>';
            setTimeout(() => btn.innerHTML = '<i class="ti ti-plus"></i>', 1500);
        } else {
            showToast(data.message || 'Có lỗi xảy ra', 'ti-alert-circle');
        }
    } catch {
        showToast('Không thể kết nối máy chủ', 'ti-wifi-off');
    }
});

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
