# E-Learning (Vanilla PHP + MySQL)

Một nền tảng E-Learning nhẹ, hiện đại, dễ dùng. Chạy tốt trên XAMPP (PHP 8.x + MySQL/MariaDB) không dùng framework nặng.

## Tính năng chính
- Phân quyền: Admin / Giáo viên / Học sinh
- Khóa học, chương, bài học; tài liệu; video nhúng
- Ghi danh, đánh dấu đã học, tính tiến độ
- Bài tập: nộp file, chấm điểm đơn giản
- Quiz trắc nghiệm tự chấm
- Diễn đàn theo khóa học/bài học
- Thông báo (badge) cập nhật định kỳ (AJAX), không WebSocket
- Tìm kiếm và lọc khóa học
- Trang quản trị thống kê nhanh

## Cấu trúc thư mục
```
/elearning
  /public        (index.php, assets, uploads)
  /app
    /controllers (AuthController.php, CourseController.php, ...)
    /models      (đơn giản dùng trực tiếp DB trong controller)
    /views       (/layouts, /auth, /courses, /lessons, /forum, /admin)
    /core        (DB.php, Router.php, Auth.php, Helpers.php)
  /config        (config.php.sample, config.php)
  /sql           (schema.sql, seed.sql)
  /assets        (/css, /js, /img)
```

## Yêu cầu hệ thống
- XAMPP (PHP 8.x + MySQL/MariaDB) trên Windows/macOS/Linux

## Hướng dẫn cài đặt nhanh trên XAMPP (Windows)
1. Giải nén folder `elearning` vào `C:/xampp/htdocs/elearning`.
2. Mở phpMyAdmin, tạo database tên `elearning` với charset `utf8mb4`.
3. Import file `sql/schema.sql`, sau đó import `sql/seed.sql`.
4. Sao chép `config/config.php.sample` thành `config/config.php` và chỉnh thông số kết nối DB nếu cần.
5. Mở trình duyệt: `http://localhost/elearning/public/index.php`

## Tài khoản mẫu
- Admin: `admin@example.com` / mật khẩu `Admin123`
- Giáo viên: `teacher@example.com` / mật khẩu `Teacher123`
- Học sinh 1: `student1@example.com` / mật khẩu `Student123`
- Học sinh 2: `student2@example.com` / mật khẩu `Student123`

Lưu ý: Nếu mật khẩu không đúng, bạn có thể tự đổi hash trong `sql/seed.sql` bằng cách tạo hash với PHP `password_hash()` (trên máy có PHP CLI hoặc tạo tạm script PHP).

## Cấu hình
Sao chép `config/config.php.sample` thành `config/config.php`. Ví dụ:
```php
<?php
return [
  'db_host' => '127.0.0.1',
  'db_name' => 'elearning',
  'db_user' => 'root',
  'db_pass' => '',
  'db_charset' => 'utf8mb4',
  'base_url' => '/elearning/public/index.php',
  'env' => 'production',
  'upload_max_mb' => 50,
];
```

## Ghi chú bảo mật & hiệu năng
- PDO + prepared statements, escape output bằng `Helpers::e()`
- CSRF token cho form POST, session cookie `SameSite=Lax`, `HttpOnly`
- Giới hạn upload 50MB; kiểm tra MIME
- Phân trang list (ví dụ khóa học lấy 20 bản ghi gần nhất)
- Bootstrap 5 CDN + 1 file `assets/css/main.css` gọn nhẹ
- Thông báo cập nhật mỗi ~25s bằng fetch

## URL và Routing
- Không dùng .htaccess. Dùng `index.php?route=/path`.
- Ví dụ: `/courses`, `/course?id=1`, `/lesson?id=1`, `/forum`.

## Developer Notes
- Kiến trúc MVC đơn giản, không dùng Composer/Framework
- Có thể mở rộng tạo Model class riêng nếu cần

## Bản quyền
- MIT License
