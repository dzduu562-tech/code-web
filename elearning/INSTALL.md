# Hướng dẫn cài đặt nhanh

## Bước 1: Chuẩn bị

1. **Tải và cài đặt XAMPP**:
   - Tải từ: https://www.apachefriends.org/
   - Cài đặt và khởi động **Apache** và **MySQL**

2. **Copy mã nguồn**:
   - Copy thư mục `elearning` vào `C:\xampp\htdocs\`

## Bước 2: Tạo Database

1. Mở trình duyệt, truy cập: `http://localhost/phpmyadmin`

2. Click **New** để tạo database mới

3. Nhập tên: `elearning_db`

4. Click **Create**

## Bước 3: Import Database

1. Chọn database `elearning_db` vừa tạo

2. Click tab **Import**

3. Click **Choose File**, chọn file: `elearning/sql/schema.sql`

4. Click **Go** để import

5. Sau khi thành công, click **Import** lại

6. Chọn file: `elearning/sql/seed.sql`

7. Click **Go** để import dữ liệu mẫu

## Bước 4: Cấu hình

1. Mở file: `elearning/config/config.php`

2. Kiểm tra thông tin database (thường không cần sửa):
   ```php
   'db_host' => 'localhost',
   'db_name' => 'elearning_db',
   'db_user' => 'root',
   'db_pass' => '',
   ```

## Bước 5: Truy cập

1. Mở trình duyệt

2. Truy cập: `http://localhost/elearning/public/index.php`

3. Đăng nhập bằng một trong các tài khoản sau:

   **Admin:**
   - Email: admin@elearning.vn
   - Password: password123

   **Giáo viên:**
   - Email: teacher1@elearning.vn
   - Password: password123

   **Học sinh:**
   - Email: student1@elearning.vn
   - Password: password123

## ✅ Hoàn tất!

Bạn đã cài đặt thành công E-Learning Platform!

## 🔧 Xử lý lỗi thường gặp

### Lỗi: "Access denied for user 'root'@'localhost'"
- Mở `config/config.php` và sửa thông tin database cho đúng

### Lỗi: "Table doesn't exist"
- Chắc chắn bạn đã import cả 2 file SQL (schema.sql và seed.sql)

### Lỗi: "404 Not Found"
- Kiểm tra đường dẫn: `http://localhost/elearning/public/index.php`
- Đảm bảo thư mục nằm đúng trong `htdocs`

### Lỗi: Upload file không hoạt động
- Right-click thư mục `elearning/public/uploads`
- Properties → Security → Edit → Cho phép Full Control

## 📞 Cần hỗ trợ?

Đọc file `README.md` để biết thêm chi tiết!
