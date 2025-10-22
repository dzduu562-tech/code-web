# 🔧 HƯỚNG DẪN SỬA LỖI NHANH

## ❌ Lỗi: Không vào được trang register

### Nguyên nhân có thể:
1. Chưa import database
2. Chưa cấu hình đúng BASE_URL
3. Apache rewrite chưa bật
4. PHP chưa đủ version

---

## ✅ GIẢI PHÁP - Làm theo từng bước:

### Bước 1: Kiểm tra XAMPP đã chạy chưa
```
✓ Apache: RUNNING (màu xanh)
✓ MySQL: RUNNING (màu xanh)
```

### Bước 2: Import Database (QUAN TRỌNG!)

1. Mở trình duyệt: `http://localhost/phpmyadmin`

2. Click "New" tạo database mới:
   - Tên: `elearning_db`
   - Collation: `utf8mb4_unicode_ci`
   - Click "Create"

3. Import schema.sql:
   - Chọn database `elearning_db`
   - Click tab "Import"
   - Click "Choose File"
   - Chọn file: `database/schema.sql`
   - Click "Go"
   - Đợi đến khi hiện "Import has been successfully finished"

4. Import seed.sql (dữ liệu mẫu):
   - Click tab "Import" lại
   - Click "Choose File"
   - Chọn file: `database/seed.sql`
   - Click "Go"

### Bước 3: Kiểm tra config/database.php

Mở file `config/database.php` và đảm bảo:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'elearning_db');
define('DB_USER', 'root');
define('DB_PASS', ''); // XAMPP mặc định để trống
```

### Bước 4: Kiểm tra config/config.php

Mở file `config/config.php` và sửa BASE_URL cho đúng:

```php
// Nếu folder tên là "elearning"
define('BASE_URL', 'http://localhost/elearning/public');

// Nếu folder tên khác, ví dụ "code-web"
define('BASE_URL', 'http://localhost/code-web/public');
```

⚠️ **LƯU Ý**: Tên folder phải khớp với thực tế trong htdocs!

### Bước 5: Kiểm tra mod_rewrite

1. Mở XAMPP Control Panel
2. Click "Config" bên cạnh Apache
3. Chọn "httpd.conf"
4. Tìm dòng (Ctrl+F):
   ```
   #LoadModule rewrite_module modules/mod_rewrite.so
   ```
5. Bỏ dấu # ở đầu dòng thành:
   ```
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
6. Save file
7. Click "Stop" rồi "Start" lại Apache

### Bước 6: Kiểm tra file .htaccess

**File 1: .htaccess ở root (thư mục gốc)**

Tạo/Mở file `.htaccess` trong thư mục `elearning/` (hoặc tên folder của bạn):

```apache
RewriteEngine On
RewriteBase /elearning/

# Redirect to public folder
RewriteCond %{REQUEST_URI} !^/elearning/public/
RewriteRule ^(.*)$ /elearning/public/$1 [L]

# Prevent directory listing
Options -Indexes
```

⚠️ Thay `elearning` bằng tên folder thực tế của bạn!

**File 2: public/.htaccess**

File này đã có sẵn, kiểm tra nội dung:

```apache
RewriteEngine On

# Handle Front Controller
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

### Bước 7: Test lại

1. Mở trình duyệt
2. Xóa cache (Ctrl+Shift+Delete)
3. Truy cập: `http://localhost/elearning/public/auth/register`
4. Hoặc: `http://localhost/elearning/public` (trang chủ)

---

## 🔍 KIỂM TRA NHANH

### Test 1: Vào trang chủ trước
```
http://localhost/elearning/public
```
- Nếu hiện trang chủ → OK
- Nếu lỗi 404 → Sai BASE_URL hoặc chưa bật rewrite
- Nếu lỗi database → Chưa import database

### Test 2: Vào trang login
```
http://localhost/elearning/public/auth/login
```
- Nếu hiện form login → OK
- Nếu lỗi → Xem lỗi cụ thể

### Test 3: Vào trang register
```
http://localhost/elearning/public/auth/register
```
- Nếu hiện form đăng ký → OK
- Nếu lỗi → Xem lỗi cụ thể

---

## ❓ CÁC LỖI THƯỜNG GẶP

### Lỗi 1: "404 Not Found"
**Nguyên nhân**: 
- Chưa bật mod_rewrite
- File .htaccess không hoạt động
- BASE_URL sai

**Giải pháp**:
- Làm lại Bước 5 (bật mod_rewrite)
- Kiểm tra Bước 6 (file .htaccess)
- Sửa Bước 4 (BASE_URL)

### Lỗi 2: "Database connection failed"
**Nguyên nhân**: Chưa tạo database hoặc config sai

**Giải pháp**:
- Làm lại Bước 2 (import database)
- Kiểm tra Bước 3 (config database)

### Lỗi 3: Trang trắng (blank page)
**Nguyên nhân**: Lỗi PHP

**Giải pháp**:
1. Bật hiển thị lỗi trong `config/config.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```
2. Reload trang để xem lỗi cụ thể

### Lỗi 4: "Call to undefined function..."
**Nguyên nhân**: Thiếu file hoặc autoload không hoạt động

**Giải pháp**: 
- Đảm bảo tất cả file đã copy đầy đủ
- Kiểm tra file `public/index.php`

---

## 📞 CÁCH XEM LỖI CHI TIẾT

### Cách 1: Xem trong browser
1. Nhấn F12 (mở Developer Tools)
2. Tab "Console" - xem lỗi JavaScript
3. Tab "Network" - xem HTTP errors

### Cách 2: Xem error log của Apache
- Windows: `C:\xampp\apache\logs\error.log`
- Linux: `/opt/lampp/logs/error_log`

### Cách 3: Bật PHP error display
Trong `config/config.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
```

---

## ✅ CHECKLIST HOÀN CHỈNH

Tick vào khi hoàn thành:

- [ ] XAMPP đã chạy (Apache + MySQL màu xanh)
- [ ] Database `elearning_db` đã tạo
- [ ] File `schema.sql` đã import thành công
- [ ] File `seed.sql` đã import thành công
- [ ] File `config/database.php` đã cấu hình đúng
- [ ] File `config/config.php` - BASE_URL đã đúng
- [ ] mod_rewrite đã bật trong httpd.conf
- [ ] File `.htaccess` ở root đã tạo/sửa đúng
- [ ] File `public/.htaccess` đã có
- [ ] Đã restart Apache
- [ ] Đã clear browser cache
- [ ] Trang chủ hiện được: http://localhost/elearning/public
- [ ] Trang login hiện được: http://localhost/elearning/public/auth/login
- [ ] Trang register hiện được: http://localhost/elearning/public/auth/register

---

## 🚀 NẾU VẪN LỖI

Chụp màn hình lỗi và gửi cho tôi, bao gồm:
1. Lỗi hiện trên trình duyệt
2. URL đang truy cập
3. Nội dung file config/config.php (dòng BASE_URL)
4. Tên folder trong htdocs (ví dụ: `htdocs/elearning` hay `htdocs/code-web`)

---

**Chúc bạn sửa thành công!** 🎉
