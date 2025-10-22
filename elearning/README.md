# E-Learning nhẹ (PHP 8 + MySQL)

Triển khai đơn giản, chạy tốt trên XAMPP/Windows. Không dùng framework nặng. Giao diện Bootstrap 5 CDN + CSS nhẹ; JS thuần.

## Cài đặt nhanh (XAMPP/Windows)
1. Sao chép thư mục `elearning` vào `C:/xampp/htdocs/elearning`.
2. Tạo database `elearning` (utf8mb4) trong phpMyAdmin.
3. Import SQL:
   - Import `sql/schema.sql`
   - Import `sql/seed.sql`
4. Sao chép `config/config.php.sample` thành `config/config.php` và chỉnh `base_url` nếu cần (ví dụ `http://localhost/elearning/public`).
5. Mở trình duyệt: `http://localhost/elearning/public/index.php?route=/`

## Tài khoản mẫu (mật khẩu đều là `password`)
- Admin: admin@example.com
- Giáo viên: teacher@example.com
- Học sinh 1: student1@example.com
- Học sinh 2: student2@example.com

## Cấu trúc thư mục
```
/elearning
  /public        (index.php, assets, uploads)
  /app
    /controllers (AuthController.php, CourseController.php, ...)
    /models      (User.php, Course.php, ...)
    /views       (/layouts, /auth, /courses, /lessons, /forum, /admin)
    /core        (DB.php, Router.php, Auth.php, Helpers.php)
  /config        (config.php.sample, config.php)
  /sql           (schema.sql, seed.sql)
  /assets        (/css, /js, /img)
```

## Tính năng chính
- Phân quyền: Admin/Teacher/Student, session an toàn, mật khẩu hash.
- Khóa học, chương, bài học; tài nguyên; nhúng video URL.
- Bài tập: nộp tệp (PDF/ZIP/PNG/JPG), giới hạn 50MB; chấm điểm.
- Quiz trắc nghiệm: auto-chấm, hiển thị điểm.
- Diễn đàn nhẹ theo khóa học/bài học.
- Thông báo: badge qua AJAX poll 25s.
- Tìm kiếm & lọc, phân trang cơ bản (LIMIT 20 mẫu).
- Quản trị: thống kê nhanh, đổi vai trò người dùng.

## Bảo mật & hiệu năng
- PDO + prepared statements; escape output.
- CSRF token cho form POST; session cookie `httponly` + `samesite=Lax`.
- Upload kiểm MIME + kích thước.
- Caching tĩnh nhẹ: tự thêm `?v=1` cho CSS/JS.

## URL mẫu
- Landing: `index.php?route=/`
- Đăng nhập/đăng ký: `/login`, `/register`
- Bảng điều khiển: `/dashboard`
- Khóa học: `/courses`, chi tiết: `/course&id=1`
- Bài học: `/lesson&id=1` (đánh dấu hoàn thành POST `/lesson/complete`)
- Bài tập: `/assignments`
- Quiz: `/quiz&id=1`
- Forum: `/forum`, thread: `/thread&id=1`
- Admin: `/admin`, `/admin/users`

## Ghi chú triển khai
- Thư mục upload: `public/uploads/{resources,submissions,avatars}` cần quyền ghi.
- Nếu base URL khác, cập nhật `config/config.php`.
- Có thể thêm .htaccess để đẹp URL, nhưng mặc định dùng `index.php?route=` cho tương thích.

## Mở rộng đề xuất
- CRUD khóa học/chương/bài học cho giáo viên.
- Phân trang đầy đủ, báo cáo xuất PDF (CSS print).
- Sơ đồ CSDL (PNG) và trang mô tả test cases.
