# 🐛 DEBUG: Không hiện trang Login

## 📋 CHECKLIST - Làm từng bước:

### ✅ Bước 1: Test file test.php

1. Copy file `test.php` vào thư mục `htdocs/elearning/`
2. Truy cập: `http://localhost/elearning/test.php`
3. Xem kết quả và chụp màn hình gửi cho tôi

---

### ✅ Bước 2: Kiểm tra lỗi cụ thể

**Khi truy cập `http://localhost/elearning/public/auth/login`:**

#### ❓ Hiện gì?

**A) Lỗi 404 Not Found**
```
Nguyên nhân: mod_rewrite chưa bật hoặc .htaccess không hoạt động
```
→ Xem phần "Fix Lỗi 404" bên dưới

**B) Trang trắng (blank page)**
```
Nguyên nhân: Lỗi PHP
```
→ Xem phần "Fix Trang trắng" bên dưới

**C) Database connection failed**
```
Nguyên nhân: Chưa import database
```
→ Xem phần "Fix Database" bên dưới

**D) Hiện lỗi khác**
```
Chụp màn hình lỗi và gửi cho tôi!
```

---

## 🔧 FIX LỖI 404 NOT FOUND

### Cách 1: Bật mod_rewrite

1. Mở XAMPP Control Panel
2. Click **Config** bên Apache → Chọn **httpd.conf**
3. Nhấn **Ctrl+F** tìm:
   ```
   #LoadModule rewrite_module modules/mod_rewrite.so
   ```
4. **Bỏ dấu #** thành:
   ```
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
5. **Save** file
6. **Stop** và **Start** lại Apache

### Cách 2: Cho phép .htaccess

1. Vẫn trong file **httpd.conf**
2. Tìm đoạn:
   ```apache
   <Directory "C:/xampp/htdocs">
       AllowOverride None
   ```
3. Sửa **None** thành **All**:
   ```apache
   <Directory "C:/xampp/htdocs">
       AllowOverride All
   ```
4. **Save** và **Restart Apache**

### Cách 3: Kiểm tra file .htaccess

**File 1: elearning/.htaccess (root)**
```apache
RewriteEngine On
RewriteBase /elearning/

RewriteCond %{REQUEST_URI} !^/elearning/public/
RewriteRule ^(.*)$ /elearning/public/$1 [L]

Options -Indexes
```

**File 2: elearning/public/.htaccess**
```apache
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

⚠️ **Lưu ý**: Thay `elearning` bằng tên folder thực tế của bạn!

---

## 🔧 FIX TRANG TRẮNG

### Bật hiển thị lỗi PHP

1. Mở file `config/config.php`
2. Tìm hoặc thêm vào cuối file:
   ```php
   // Bật hiển thị lỗi
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ini_set('log_errors', 1);
   ```
3. **Save** file
4. **Refresh** trang login
5. Xem lỗi hiện ra và chụp màn hình gửi tôi

---

## 🔧 FIX DATABASE

### Import Database đầy đủ

1. Mở `http://localhost/phpmyadmin`

2. **Tạo database**:
   - Click **New** (bên trái)
   - Tên: `elearning_db`
   - Collation: `utf8mb4_unicode_ci`
   - Click **Create**

3. **Import schema**:
   - Chọn database `elearning_db`
   - Click tab **Import**
   - Click **Choose File**
   - Chọn file: `database/schema.sql`
   - Click **Go**
   - Đợi đến khi thấy: "Import has been successfully finished"

4. **Import dữ liệu mẫu**:
   - Click tab **Import** lại
   - Click **Choose File**
   - Chọn file: `database/seed.sql`
   - Click **Go**

5. **Kiểm tra**:
   - Click database `elearning_db`
   - Xem có 22 bảng không?
   - Click bảng `users` → Browse
   - Phải thấy 6 users (admin, teachers, students)

---

## 🔧 FIX BASE_URL

### Kiểm tra và sửa BASE_URL

1. Xác định tên folder trong htdocs:
   ```
   C:\xampp\htdocs\elearning  ← Tên folder là "elearning"
   hoặc
   C:\xampp\htdocs\code-web   ← Tên folder là "code-web"
   ```

2. Mở file `config/config.php`

3. Tìm dòng:
   ```php
   define('BASE_URL', 'http://localhost/elearning/public');
   ```

4. Sửa cho ĐÚNG tên folder:
   ```php
   // Nếu folder tên "elearning"
   define('BASE_URL', 'http://localhost/elearning/public');
   
   // Nếu folder tên "code-web"
   define('BASE_URL', 'http://localhost/code-web/public');
   
   // Nếu folder tên khác, thay tương ứng
   ```

5. **Save** file

---

## 🧪 TEST TỪNG BƯỚC

### Test 1: Trang chủ
```
http://localhost/elearning/public/
```
- ✅ Hiện trang chủ → OK, sang test 2
- ❌ Lỗi → Sửa BASE_URL hoặc mod_rewrite

### Test 2: Login
```
http://localhost/elearning/public/auth/login
```
- ✅ Hiện form login → OK, sang test 3
- ❌ Lỗi → Xem lỗi cụ thể

### Test 3: Register
```
http://localhost/elearning/public/auth/register
```
- ✅ Hiện form đăng ký → HOÀN HẢO!
- ❌ Lỗi → Xem lỗi cụ thể

---

## 📸 GỬI THÔNG TIN CHO TÔI

Nếu vẫn lỗi, chụp màn hình và gửi:

### 1. Kết quả test.php
```
http://localhost/elearning/test.php
```

### 2. Lỗi khi vào login
```
http://localhost/elearning/public/auth/login
```

### 3. Thông tin hệ thống
- Tên folder: `C:\xampp\htdocs\[TÊN-GÌ]`
- Nội dung BASE_URL trong config/config.php
- Screenshot error nếu có

---

## 🆘 CÁC LỆNH KIỂM TRA NHANH

### Kiểm tra folder structure
```
dir C:\xampp\htdocs\elearning\
dir C:\xampp\htdocs\elearning\public\
dir C:\xampp\htdocs\elearning\app\controllers\
```

### Kiểm tra file tồn tại
```
type C:\xampp\htdocs\elearning\config\config.php
type C:\xampp\htdocs\elearning\public\index.php
```

### Xem error log
```
C:\xampp\apache\logs\error.log
```

---

## ✅ CHECKLIST HOÀN CHỈNH

Tick khi hoàn thành:

- [ ] Đã chạy test.php và xem kết quả
- [ ] XAMPP đang chạy (Apache + MySQL màu xanh)
- [ ] Database `elearning_db` đã tạo
- [ ] File schema.sql đã import (có 22 bảng)
- [ ] File seed.sql đã import (có 6 users)
- [ ] mod_rewrite đã bật trong httpd.conf
- [ ] AllowOverride All trong httpd.conf
- [ ] File .htaccess ở root đã tạo/sửa
- [ ] File public/.htaccess đã có
- [ ] BASE_URL trong config/config.php đã đúng
- [ ] Đã restart Apache
- [ ] Đã clear browser cache (Ctrl+Shift+Delete)
- [ ] Đã bật display_errors trong config
- [ ] Test trang chủ: OK
- [ ] Test login: ???
- [ ] Test register: ???

---

**💬 Làm xong gửi kết quả test.php cho tôi để debug tiếp!**
