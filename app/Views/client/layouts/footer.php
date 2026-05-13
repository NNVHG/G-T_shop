<?php // includes/footer.php ?>
</main><!-- /site-main -->

<!-- ═══════════════════════════════════════════════════════════
     FOOTER
════════════════════════════════════════════════════════════ -->
<footer class="site-footer">
    <div class="container footer-grid">

        <!-- Cột 1: Giới thiệu -->
        <div class="footer-col">
            <div class="footer-logo">G&amp;T<span>shop</span></div>
            <p class="footer-desc">Hiệu sách yêu thích của bạn — sách, văn phòng phẩm, đồ ăn vặt và nhiều hơn nữa tại Thủ Dầu Một, Bình Dương.</p>
            <div class="footer-socials">
                <a href="#" title="Facebook"><i class="ti ti-brand-facebook"></i></a>
                <a href="#" title="Instagram"><i class="ti ti-brand-instagram"></i></a>
                <a href="#" title="TikTok"><i class="ti ti-brand-tiktok"></i></a>
                <a href="#" title="Zalo"><i class="ti ti-message-circle"></i></a>
            </div>
        </div>

        <!-- Cột 2: Danh mục -->
        <div class="footer-col">
            <h4 class="footer-title">Danh mục</h4>
            <ul class="footer-links">
                <?php foreach ($nav_categories as $c): ?>
                    <li><a href="<?= BASE_URL ?>/?cat=<?= e($c['slug']) ?>"><?= e($c['name']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Cột 3: Hỗ trợ -->
        <div class="footer-col">
            <h4 class="footer-title">Hỗ trợ khách hàng</h4>
            <ul class="footer-links">
                <li><a href="#">Hướng dẫn mua hàng</a></li>
                <li><a href="#">Chính sách đổi trả</a></li>
                <li><a href="#">Chính sách vận chuyển</a></li>
                <li><a href="#">Câu hỏi thường gặp</a></li>
                <li><a href="#">Liên hệ</a></li>
            </ul>
        </div>

        <!-- Cột 4: Liên hệ -->
        <div class="footer-col">
            <h4 class="footer-title">Liên hệ</h4>
            <ul class="footer-contact">
                <li><i class="ti ti-map-pin"></i> Thủ Dầu Một, Bình Dương</li>
                <li><i class="ti ti-phone"></i> 0901 234 567</li>
                <li><i class="ti ti-mail"></i> hello@gtshop.vn</li>
                <li><i class="ti ti-clock"></i> 8:00 – 21:00 mỗi ngày</li>
            </ul>
        </div>

    </div><!-- /footer-grid -->

    <div class="footer-bottom">
        <div class="container footer-bottom-inner">
            <p>© <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved.</p>
            <div class="footer-payment">
                <span>Thanh toán:</span>
                <img src="<?= BASE_URL ?>/assets/images/momo.png"   alt="MoMo"   onerror="this.style.display='none'">
                <img src="<?= BASE_URL ?>/assets/images/vnpay.png"  alt="VNPay"  onerror="this.style.display='none'">
                <span class="pay-tag">COD</span>
                <span class="pay-tag">Tiền mặt</span>
            </div>
        </div>
    </div>
</footer>

<!-- JavaScript chính -->
<script src="<?= BASE_URL ?>js/main.js"></script>
<?= isset($extra_scripts) ? $extra_scripts : '' ?>
</body>
</html>
