# 📚 G&T Shop - Website Thương mại Điện tử Sách & Tiện ích

[![PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg?style=flat-down&logo=php)](https://www.php.net/)
[![Database](https://img.shields.io/badge/database-MySQL--InnoDB-blue?style=flat-down&logo=mysql)](https://www.mysql.com/)
[![Architecture](https://img.shields.io/badge/architecture-MVC%20Vanilla-orange?style=flat-down)](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller)
[![Payment](https://img.shields.io/badge/payment-COD%20%7C%20VNPAY%20Sandbox-emerald?style=flat-down)](https://sandbox.vnpayment.vn/)

**G&T Shop** (hay *GT Bookstore*) là một nền tảng website thương mại điện tử chuyên nghiệp bán sách, truyện tranh, văn phòng phẩm, đồ ăn vặt và đồ chơi tiện ích. Dự án được phát triển dựa trên mô hình kiến trúc **PHP MVC thuần (Vanilla MVC)**, tuân thủ các nguyên tắc thiết kế mã nguồn sạch, bảo mật cao và tối ưu hóa hiệu năng tải trang.

Giao diện của hệ thống được thiết kế theo phong cách hiện đại, thanh lịch (Premium Aesthetics) với hệ thống phối màu hài hòa, mượt mà trên mọi thiết bị di động (Responsive Design), tạo trải nghiệm mua sắm tuyệt vời cho khách hàng.

---

## 🚀 Tính năng nổi bật

Dự án được phân chia thành 2 phân hệ cốt lõi với đầy đủ nghiệp vụ của một sàn thương mại điện tử:

### 1. Phân hệ Khách hàng (Client Portal)
*   **Trang chủ ấn tượng:** Banner trượt linh hoạt, hiển thị danh mục động theo biểu tượng tương ứng, hiển thị danh sách sản phẩm nổi bật (Featured), sản phẩm bán chạy (Best Sellers), và sản phẩm mới (New Arrivals).
*   **Bộ lọc & Tìm kiếm thông minh (Live Search):**
    *   Hệ thống tìm kiếm thời gian thực (Live Suggestion) hiển thị kết quả ngay khi người dùng gõ từ khóa.
    *   Phân loại sản phẩm trực quan theo danh mục (Sách văn học, Truyện tranh, Văn phòng phẩm, Đồ ăn vặt, Nước ngọt, Đồ chơi).
    *   Hiển thị huy hiệu (Badge) phân loại sản phẩm: `Mới (New)`, `Hot`, `Khuyến mãi (Sale)`.
*   **Giỏ hàng linh hoạt (Shopping Cart):**
    *   Thêm sản phẩm trực tiếp từ trang danh sách hoặc trang chi tiết.
    *   Cập nhật số lượng và tính toán tổng tiền tự động thông qua phiên làm việc (Session).
    *   Hiển thị số lượng sản phẩm trực quan trên thanh tiêu đề.
*   **Thanh toán tối ưu (Checkout Options):**
    *   **Thanh toán COD:** Nhận hàng và thanh toán bằng tiền mặt.
    *   **Thanh toán trực tuyến VNPAY:** Tích hợp Cổng thanh toán VNPAY Sandbox, tự động chuyển hướng và kiểm tra chữ ký số an toàn (Secure Hash SHA512), tự động cập nhật trạng thái đơn hàng khi giao dịch thành công.
*   **Quản lý tài khoản (Authentication & Profile):**
    *   Đăng ký và đăng nhập bảo mật (Mật khẩu được mã hóa an toàn bằng thuật toán `Bcrypt`).
    *   Cập nhật thông tin cá nhân và quản lý chi tiết lịch sử đơn hàng đã đặt cùng trạng thái tương ứng.

### 2. Phân hệ Quản trị viên (Admin Dashboard)
*   **Bảng điều khiển (Dashboard Thống kê):** Thống kê nhanh tổng doanh thu, tổng số đơn hàng đã hoàn thành, tổng số sản phẩm đang kinh doanh, và số lượng thành viên. Hiển thị danh sách các đơn hàng mới nhất cần xử lý.
*   **Quản lý Danh mục (Category Management):** CRUD (Thêm, sửa, xóa, hiển thị) danh mục sản phẩm kèm theo cấu hình thứ tự ưu tiên và biểu tượng đại diện (Tabler Icons).
*   **Quản lý Sản phẩm (Product Management):** 
    *   CRUD sản phẩm chuyên nghiệp.
    *   Tải lên hình ảnh sản phẩm mới lên thư mục lưu trữ hệ thống, quản lý đường dẫn chuẩn xác.
    *   Hỗ trợ cài đặt giá bán lẻ, giá khuyến mãi, số lượng tồn kho, trạng thái kích hoạt, tác giả và các nhãn đánh dấu đặc biệt.
*   **Quản lý Đơn hàng (Order Management):**
    *   Danh sách đơn hàng trực quan với các bộ lọc trạng thái.
    *   Cập nhật trạng thái đơn hàng theo chu kỳ: *Chờ xử lý (Pending) -> Đang giao (Shipped) -> Đã hoàn thành (Done) -> Đã hủy (Cancelled)*.
    *   Cập nhật trạng thái thanh toán đồng bộ.
*   **Quản lý Người dùng (User Management):** Danh sách thành viên hệ thống và phân quyền bảo mật cấp cao (Role: `admin` hoặc `user`).
---
## 📷 Ảnh chụp giao diện trang chủ
<img width="1857" height="986" alt="image" src="https://github.com/user-attachments/assets/a1968253-118d-47e6-8ba2-843b1c96d0c6" />
<img width="1856" height="980" alt="image" src="https://github.com/user-attachments/assets/57253348-9af3-4f35-9bb0-4bdcd6b12bd7" />
<img width="1858" height="983" alt="image" src="https://github.com/user-attachments/assets/bc9a10ca-e770-4514-bbf4-d8358911be80" />

---
## 🎦 video demo trang web

![Demo G&T Shop]([Video_Demo.gif](https://drive.google.com/file/d/1hpwK7AMF1RTHyo4oy6Pw_J73Ll9OQQo4/view?usp=drive_link))

---

## 🛠️ Công nghệ sử dụng

*   **Backend:** PHP 7.4 / 8.x thuần, cấu trúc hướng đối tượng (OOP), sử dụng mô hình MVC chia tách rõ rệt:
    *   **Model:** Tương tác trực tiếp với cơ sở dữ liệu qua PDO kết nối an toàn.
    *   **View:** File giao diện PHP sử dụng thẻ rút gọn an toàn để hiển thị dữ liệu đã được xử lý chống tấn công XSS (Xử lý thông qua hàm helper `e()`).
    *   **Controller:** Tiếp nhận yêu cầu, xử lý nghiệp vụ trung gian và điều phối dữ liệu.
*   **Database:** MySQL (InnoDB engine, hỗ trợ khóa ngoại đồng bộ liên kết dữ liệu và chỉ mục FULLTEXT tìm kiếm nhanh).
*   **Frontend:**
    *   HTML5 ngữ nghĩa (Semantic HTML) tốt cho SEO.
    *   **Vanilla CSS:** Thiết kế dựa trên hệ thống biến (CSS Variables / Tokens) giúp quản lý màu sắc, khoảng cách (spacing), và bo góc đồng bộ, đem lại cảm giác cao cấp (Premium Layout).
    *   **Vanilla JavaScript:** AJAX cập nhật giỏ hàng không cần tải lại trang, xử lý Live Search gợi ý thông minh, quản lý các hộp thoại tương tác.
*   **Cổng thanh toán:** API VNPAY Sandbox 2.1.0 kết nối trực tiếp đến môi trường thử nghiệm quốc gia.

---

## 📂 Cấu trúc thư mục dự án

```text
G&T_shop/
├── app/                        # Thư mục chứa mã nguồn cốt lõi của ứng dụng
│   ├── Controllers/            # Bộ điều khiển (Xử lý nghiệp vụ chính)
│   │   ├── AdminController.php     # Nghiệp vụ quản trị viên
│   │   ├── AuthController.php      # Xác thực người dùng (Đăng nhập, đăng ký)
│   │   ├── CartController.php      # Quản lý giỏ hàng
│   │   ├── CheckoutController.php  # Xử lý đặt hàng & VNPAY
│   │   ├── HomeController.php      # Hiển thị trang chủ & Banner
│   │   ├── ProductController.php   # Hiển thị và lọc sản phẩm
│   │   └── ProfileController.php   # Quản lý trang cá nhân & Lịch sử mua hàng
│   ├── Models/                 # Lớp tương tác CSDL (Database, Order, Product...)
│   └── Views/                  # Giao diện hiển thị (Phần Client & Admin)
│       ├── admin/                  # Giao diện trang quản trị
│       ├── client/                 # Giao diện trang bán hàng client
│       └── errors/                 # Trang lỗi hệ thống (404, 403...)
├── config/                     # Cấu hình dự án
│   └── config.php              # Chứa thông số CSDL, BASE_URL, cấu hình VNPAY
├── public/                     # Thư mục chứa tài nguyên tĩnh & Điểm chạy chính (Front Controller)
│   ├── css/                    # Các file Stylesheet tùy chỉnh (base, home, admin, product)
│   ├── images/                 # Chứa hình ảnh sản phẩm và biểu tượng
│   ├── js/                     # Các file Javascript xử lý tương tác client (main.js)
│   └── index.php               # Điểm đón đầu mọi request (Front Controller) của hệ thống
├── .htaccess                   # Cấu hình máy chủ Apache chuyển hướng truy cập an toàn
├── database.sql                # File CSDL mẫu đã được tạo sẵn cấu trúc và dữ liệu mẫu phong phú
└── README.md                   # Tài liệu hướng dẫn sử dụng dự án này
```

---

## ⚙️ Hướng dẫn cài đặt & Khởi chạy

Để chạy dự án G&T Shop trên máy tính cá nhân của bạn thông qua XAMPP, hãy làm theo các bước chi tiết dưới đây:

### Bước 1: Chuẩn bị môi trường
*   Cài đặt phần mềm giả lập máy chủ **XAMPP** (hỗ trợ PHP >= 7.4 và MySQL).
*   Khởi động dịch vụ **Apache** và **MySQL** trên XAMPP Control Panel.

### Bước 2: Tải dự án về thư mục htdocs
*   Tải toàn bộ thư mục dự án `G&T_shop` về máy.
*   Di chuyển thư mục này vào trong đường dẫn cài đặt của XAMPP:
    ```bash
    C:\xampp\htdocs\G&T_shop
    ```
    *(Đảm bảo tên thư mục là `G&T_shop` để tránh bị lệch đường dẫn cấu hình mặc định).*

### Bước 3: Tạo và Import Cơ sở dữ liệu
1.  Truy cập vào trình quản trị CSDL theo đường dẫn: [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
2.  Tạo mới một cơ sở dữ liệu có tên chính xác là:
    ```sql
    gtbookstore_db
    ```
    *(Lưu ý chọn bảng mã mã hóa là `utf8mb4_unicode_ci` để hiển thị tiếng Việt có dấu chuẩn xác).*
3.  Chọn cơ sở dữ liệu `gtbookstore_db` vừa tạo, nhấp chọn tab **Nhập (Import)** ở thanh công cụ phía trên.
4.  Chọn tệp tin [database.sql](file:///e:/XAMPP/htdocs/G&T_shop/database.sql) nằm ở thư mục gốc của dự án và nhấp **Thực hiện (Go)** để tiến hành nạp cấu trúc bảng và dữ liệu mẫu phong phú đã được chuẩn bị sẵn.

### Bước 4: Kiểm tra và Cấu hình Hệ thống
Mở tệp tin [config.php](file:///e:/XAMPP/htdocs/G&T_shop/config/config.php) tại đường dẫn `config/config.php` để kiểm tra hoặc điều chỉnh cấu hình kết nối nếu cần thiết:
```php
// config/config.php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Mật khẩu mặc định của XAMPP thường để trống
define('DB_NAME', 'gtbookstore_db'); // Tên CSDL vừa tạo

define('SITE_NAME', 'G&T Shop');
// Đường dẫn gốc trỏ thẳng vào thư mục public của dự án
define('BASE_URL', 'http://localhost/G&T_shop/public/');
```

### Bước 5: Truy cập và trải nghiệm ứng dụng
Mở trình duyệt web của bạn và truy cập trực tiếp vào đường dẫn Front Controller:
👉 **[http://localhost/G&T_shop/public/](http://localhost/G&T_shop/public/)**

Hệ thống sẽ tự động tải giao diện trang chủ cực kỳ bắt mắt của **G&T Shop**!

---

## 🔑 Thông tin tài khoản kiểm thử mặc định

Để thuận tiện cho quá trình đánh giá và thử nghiệm toàn bộ hệ thống, bạn có thể sử dụng các tài khoản có sẵn trong cơ sở dữ liệu mẫu:

### 1. Tài khoản Quản trị viên (Admin)
*   **Email:** `admin@gtshop.vn`
*   **Mật khẩu:** `admin123`
*   *Quyền hạn:* Truy cập vào Trang quản trị (Admin Dashboard) thông qua menu hiển thị ở góc trên bên phải khi đăng nhập thành công.

### 2. Tài khoản Khách hàng mẫu (Customer)
*   Bạn có thể tiến hành tạo tài khoản mới ngay trên giao diện Đăng ký hoặc sử dụng tính năng mua hàng không cần đăng nhập trực tiếp.

---

## 💳 Cấu hình và Kiểm thử thanh toán VNPAY Sandbox

Dự án đã tích hợp sẵn luồng thanh toán qua ví điện tử **VNPAY Sandbox** (Môi trường giả lập thử nghiệm). 

### 1. Thông số tích hợp mặc định (Đã cấu hình trong `config/config.php`)
```php
define('VNP_TMN_CODE', 'XLHKEVA9'); // Mã Website thử nghiệm
define('VNP_HASH_SECRET', 'POOO24CO2FUU8PF8YSJJQKML2G174Y8M'); // Chuỗi bí mật tạo chữ ký bảo mật
define('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'); // Đường dẫn cổng thanh toán giả lập
```

### 2. Hướng dẫn kiểm thử giao dịch VNPAY
Khi thực hiện bước thanh toán đơn hàng và chọn phương thức **Thanh toán trực tuyến qua VNPAY**, hệ thống sẽ chuyển hướng bạn đến cổng thanh toán an toàn của VNPAY Sandbox. Để thanh toán thành công, bạn hãy sử dụng thông tin thẻ ngân hàng thử nghiệm dưới đây:

*   **Ngân hàng:** Chọn ngân hàng bất kỳ (Ví dụ: `NCB`)
*   **Số thẻ:** `9704198526191432198`
*   **Tên chủ thẻ:** `NGUYEN VAN A`
*   **Ngày phát hành:** `07/15`
*   **Mã OTP:** `123456`

Sau khi xác thực thành công, VNPAY sẽ chuyển hướng bạn về lại đường dẫn callback `vnpay_return` của website. Hệ thống sẽ tự động kiểm tra chữ ký, xóa giỏ hàng, cập nhật trạng thái đơn hàng thành **Đã thanh toán (paid)** và hiển thị trang thông báo đặt hàng thành công vô cùng chuyên nghiệp.

---

## 📄 Bản quyền & Đóng góp

Dự án được xây dựng với mục tiêu cung cấp giải pháp bán hàng thương mại điện tử trực quan, tối ưu cho các hệ thống vừa và nhỏ bằng ngôn ngữ PHP thuần. Mọi đóng góp phát triển hệ thống vui lòng gửi pull request hoặc liên hệ trực tiếp đội ngũ sáng lập của **G&T Shop**.

*Chúc bạn có những trải nghiệm tuyệt vời cùng G&T Shop!* 🚀
