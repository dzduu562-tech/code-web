# 📚 HƯỚNG DẪN CÀI ĐẶT E-LEARNING PLATFORM

## Yêu cầu hệ thống

### Phần mềm cần thiết:
- **XAMPP** (phiên bản 8.0 trở lên)
  - PHP 8.0+
  - MySQL/MariaDB 10.4+
  - Apache 2.4+
- Trình duyệt web hiện đại (Chrome, Firefox, Edge, Safari)

## 📥 Các bước cài đặt

### Bước 1: Tải và giải nén source code

1. Tải source code về máy
2. Giải nén vào thư mục `htdocs` của XAMPP
   - Windows: `C:\xampp\htdocs\elearning`
   - Linux/Mac: `/opt/lampp/htdocs/elearning`

### Bước 2: Khởi động XAMPP

1. Mở **XAMPP Control Panel**
2. Khởi động **Apache** và **MySQL**
3. Chờ đến khi cả hai service chuyển sang màu xanh

### Bước 3: Tạo database

#### Cách 1: Sử dụng phpMyAdmin (Khuyến nghị)

1. Mở trình duyệt và truy cập: `http://localhost/phpmyadmin`
2. Click vào tab **SQL**
3. Mở file `database/schema.sql` bằng text editor
4. Copy toàn bộ nội dung và paste vào ô SQL
5. Click **Go** để thực thi

#### Cách 2: Import file SQL trực tiếp

1. Truy cập `http://localhost/phpmyadmin`
2. Click **New** để tạo database mới
3. Đặt tên database: `elearning_db`
4. Chọn **Collation**: `utf8mb4_unicode_ci`
5. Click **Create**
6. Chọn database `elearning_db` vừa tạo
7. Click tab **Import**
8. Click **Choose File** và chọn file `database/schema.sql`
9. Click **Go**

### Bước 4: Import dữ liệu mẫu (Optional)

1. Trong phpMyAdmin, với database `elearning_db` đã chọn
2. Click tab **SQL**
3. Mở file `database/seed.sql`
4. Copy nội dung và paste vào ô SQL
5. Click **Go**

### Bước 5: Cấu hình kết nối database

1. Mở file `config/database.php`
2. Kiểm tra các thông tin kết nối:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'elearning_db');
define('DB_USER', 'root');
define('DB_PASS', ''); // Mặc định XAMPP không có password
```

3. **Lưu ý**: Nếu bạn đã đặt password cho MySQL, hãy cập nhật `DB_PASS`

### Bước 6: Cấu hình Base URL

1. Mở file `config/config.php`
2. Tìm dòng `BASE_URL` và cập nhật theo đường dẫn của bạn:

```php
// Nếu đặt trong thư mục gốc htdocs/elearning
define('BASE_URL', 'http://localhost/elearning/public');

// Hoặc nếu dùng virtual host
define('BASE_URL', 'http://elearning.local/public');
```

### Bước 7: Tạo thư mục uploads

1. Trong thư mục `public`, tạo folder `uploads` nếu chưa có
2. Tạo các subfolder:
   ```
   public/
     └── uploads/
         ├── avatars/
         ├── courses/
         ├── lessons/
         ├── assignments/
         └── documents/
   ```

### Bước 8: Phân quyền thư mục (Linux/Mac)

Nếu sử dụng Linux/Mac, chạy lệnh sau trong terminal:

```bash
cd /opt/lampp/htdocs/elearning
chmod -R 755 public/uploads
chmod -R 777 public/uploads/*
```

### Bước 9: Truy cập website

1. Mở trình duyệt
2. Truy cập: `http://localhost/elearning/public`
3. Trang chủ sẽ hiển thị

## 🔐 Tài khoản mặc định

Sau khi import dữ liệu mẫu, bạn có thể đăng nhập với các tài khoản sau:

### Admin
- **Email**: `admin@elearning.com`
- **Password**: `Admin@123`
- **Quyền**: Toàn quyền quản trị hệ thống

### Giáo viên
- **Email**: `gv.nguyen@school.edu.vn`
- **Password**: `Admin@123`
- **Quyền**: Tạo khóa học, quản lý học sinh

### Học sinh
- **Email**: `hs.an@school.edu.vn`
- **Password**: `Admin@123`
- **Quyền**: Học tập, làm bài tập

## ⚙️ Cấu hình nâng cao

### Tạo Virtual Host (Optional)

#### Windows:

1. Mở file: `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
2. Thêm vào cuối file:

```apache
<VirtualHost *:80>
    ServerName elearning.local
    DocumentRoot "C:/xampp/htdocs/elearning/public"
    <Directory "C:/xampp/htdocs/elearning/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

3. Mở file `C:\Windows\System32\drivers\etc\hosts` với quyền Administrator
4. Thêm dòng:
```
127.0.0.1 elearning.local
```

5. Khởi động lại Apache
6. Truy cập: `http://elearning.local`

#### Linux/Mac:

1. Tạo file virtual host:
```bash
sudo nano /opt/lampp/etc/extra/httpd-vhosts.conf
```

2. Thêm cấu hình:
```apache
<VirtualHost *:80>
    ServerName elearning.local
    DocumentRoot "/opt/lampp/htdocs/elearning/public"
    <Directory "/opt/lampp/htdocs/elearning/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

3. Cập nhật hosts file:
```bash
sudo nano /etc/hosts
```

Thêm:
```
127.0.0.1 elearning.local
```

4. Khởi động lại Apache:
```bash
sudo /opt/lampp/lampp restart
```

### Bật mod_rewrite (nếu chưa có)

1. Mở `xampp/apache/conf/httpd.conf`
2. Tìm dòng:
```
#LoadModule rewrite_module modules/mod_rewrite.so
```
3. Bỏ dấu `#` ở đầu dòng
4. Khởi động lại Apache

### Tăng giới hạn upload file

1. Mở file `php.ini` (trong XAMPP Control Panel, click Config > PHP)
2. Tìm và sửa các dòng sau:

```ini
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
max_input_time = 300
memory_limit = 256M
```

3. Lưu file và khởi động lại Apache

## 🐛 Xử lý lỗi thường gặp

### Lỗi 1: "Database connection failed"

**Nguyên nhân**: Không kết nối được database

**Giải pháp**:
1. Kiểm tra MySQL đã khởi động chưa
2. Kiểm tra thông tin trong `config/database.php`
3. Đảm bảo database `elearning_db` đã được tạo

### Lỗi 2: "404 Not Found" khi truy cập

**Nguyên nhân**: Chưa bật mod_rewrite hoặc `.htaccess` không hoạt động

**Giải pháp**:
1. Bật mod_rewrite trong Apache (xem phần "Bật mod_rewrite")
2. Kiểm tra file `.htaccess` có trong thư mục `public`
3. Đảm bảo `AllowOverride All` trong cấu hình Apache

### Lỗi 3: "Permission denied" khi upload file

**Nguyên nhân**: Thư mục uploads không có quyền ghi

**Giải pháp**:
```bash
# Linux/Mac
chmod -R 777 public/uploads

# Windows: Right-click folder > Properties > Security > Edit > Everyone > Full control
```

### Lỗi 4: Trang CSS/JS không load

**Nguyên nhân**: BASE_URL không đúng

**Giải pháp**:
1. Kiểm tra `config/config.php`
2. Đảm bảo `BASE_URL` trỏ đúng đến thư mục `public`

### Lỗi 5: Session không hoạt động

**Nguyên nhân**: PHP session chưa được cấu hình

**Giải pháp**:
1. Mở `php.ini`
2. Tìm `session.save_path` và đảm bảo thư mục tồn tại
3. Khởi động lại Apache

## 📱 Kiểm tra Responsive

Để kiểm tra responsive design:

1. Mở Developer Tools (F12)
2. Bật chế độ Device Toolbar (Ctrl+Shift+M)
3. Thử nghiệm trên các kích thước:
   - Mobile: 375px
   - Tablet: 768px
   - Desktop: 1920px

## 🌙 Kiểm tra Dark Mode

1. Click vào biểu tượng mặt trăng/mặt trời trên navbar
2. Giao diện sẽ chuyển đổi giữa Light và Dark mode
3. Chế độ được lưu trong localStorage

## 🔄 Cập nhật hệ thống

Khi có phiên bản mới:

1. Backup database hiện tại (Export từ phpMyAdmin)
2. Backup thư mục `public/uploads`
3. Sao chép file mới vào thư mục
4. Chạy script migration nếu có
5. Clear browser cache (Ctrl+Shift+Delete)

## 📞 Hỗ trợ

Nếu gặp vấn đề:

1. Kiểm tra log lỗi tại: `xampp/apache/logs/error.log`
2. Bật debug mode trong `config/config.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## ✅ Checklist sau khi cài đặt

- [ ] XAMPP đã khởi động Apache và MySQL
- [ ] Database `elearning_db` đã được tạo và import
- [ ] Đã import dữ liệu mẫu từ `seed.sql`
- [ ] File `config/database.php` đã cấu hình đúng
- [ ] `BASE_URL` trong `config/config.php` đã đúng
- [ ] Thư mục `uploads` đã được tạo và có quyền ghi
- [ ] Có thể truy cập trang chủ
- [ ] Có thể đăng nhập với tài khoản demo
- [ ] Dark/Light mode hoạt động
- [ ] Responsive trên mobile

**Chúc bạn triển khai thành công! 🎉**
