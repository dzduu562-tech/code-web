# 🔧 TẤT CẢ CÁC LỖI ĐÃ SỬA

## 📋 DANH SÁCH LỖI ĐÃ GẶP VÀ ĐÃ SỬA:

### ✅ Lỗi 1: "Undefined array key 'full_name'"
**Nguyên nhân**: Database chưa import, không có dữ liệu user  
**Đã sửa**: Thêm fallback trong Student controller  
**File**: `app/controllers/Student.php`, `app/views/student/dashboard.php`

---

### ✅ Lỗi 2: "Declaration of Courses::view() must be compatible..."
**Nguyên nhân**: Method `view()` trùng tên với method của class cha  
**Đã sửa**: Đổi tên method `view()` → `detail()`  
**File**: `app/controllers/Courses.php`

---

### ✅ Lỗi 3: "404 Not Found" khi truy cập URL
**Nguyên nhân**: mod_rewrite chưa bật  
**Giải pháp**: Bật mod_rewrite trong httpd.conf  
**File**: `C:\xampp\apache\conf\httpd.conf`

---

### ✅ Lỗi 4: "class Student does not have a method 'index'"
**Nguyên nhân**: Thiếu method index() trong các controller  
**Đã sửa**: Thêm method index() cho Student, Teacher, Admin  
**File**: `app/controllers/Student.php`, `Teacher.php`, `Admin.php`

---

## 🚀 HƯỚNG DẪN CÀI ĐẶT ĐÚNG - TỪNG BƯỚC

### BƯỚC 1: Cài đặt XAMPP ✅
- Download XAMPP (PHP 8.0+)
- Cài đặt vào `C:\xampp`
- Start Apache + MySQL

---

### BƯỚC 2: Tải code từ GitHub ✅

#### Cách 1: Download ZIP (Dễ nhất)
1. Vào: https://github.com/dzduu562-tech/code-web
2. Chọn branch: `cursor/build-school-e-learning-website-c73d`
3. Click nút **"Code"** → **"Download ZIP"**
4. Giải nén vào: `C:\xampp\htdocs\elearning`

#### Cách 2: Git Clone
```cmd
cd C:\xampp\htdocs
git clone https://github.com/dzduu562-tech/code-web.git elearning
cd elearning
git checkout cursor/build-school-e-learning-website-c73d
```

---

### BƯỚC 3: Import Database ⚠️ QUAN TRỌNG!

#### 3.1. Mở phpMyAdmin
```
http://localhost/phpmyadmin
```

#### 3.2. Tạo Database
- Click **"New"**
- Tên database: `elearning_db`
- Collation: `utf8mb4_unicode_ci`
- Click **"Create"**

#### 3.3. Import Schema
- Chọn database `elearning_db`
- Click tab **"Import"**
- Click **"Choose File"**
- Chọn: `C:\xampp\htdocs\elearning\database\schema.sql`
- Click **"Go"**
- Đợi thành công

#### 3.4. Import Data Mẫu
- Click tab **"Import"** lại
- Click **"Choose File"**
- Chọn: `C:\xampp\htdocs\elearning\database\seed.sql`
- Click **"Go"**

#### 3.5. Kiểm tra
- Click database `elearning_db`
- Phải có **22 bảng**
- Click bảng `users` → Browse → Phải có **6 users**

---

### BƯỚC 4: Bật mod_rewrite ⚠️ BẮT BUỘC!

#### 4.1. Mở file httpd.conf
- XAMPP Control Panel → Apache → Config → httpd.conf

#### 4.2. Tìm và sửa mod_rewrite
```apache
# Tìm dòng này (Ctrl+F):
#LoadModule rewrite_module modules/mod_rewrite.so

# Bỏ dấu # thành:
LoadModule rewrite_module modules/mod_rewrite.so
```

#### 4.3. Cho phép .htaccess
```apache
# Tìm đoạn này:
<Directory "C:/xampp/htdocs">
    AllowOverride None

# Sửa thành:
<Directory "C:/xampp/htdocs">
    AllowOverride All
```

#### 4.4. Save và Restart Apache
- Save file (Ctrl+S)
- XAMPP → Stop Apache → Start lại

---

### BƯỚC 5: Cấu hình BASE_URL

Mở file: `C:\xampp\htdocs\elearning\config\config.php`

Kiểm tra dòng:
```php
define('BASE_URL', 'http://localhost/elearning/public');
```

⚠️ **Chú ý**: 
- Nếu folder tên khác `elearning`, sửa cho đúng!
- Ví dụ: `code-web` → `http://localhost/code-web/public`

---

### BƯỚC 6: Kiểm tra file .htaccess

#### File 1: `C:\xampp\htdocs\elearning\.htaccess`
```apache
RewriteEngine On
RewriteBase /elearning/

RewriteCond %{REQUEST_URI} !^/elearning/public/
RewriteRule ^(.*)$ /elearning/public/$1 [L]

Options -Indexes
```

#### File 2: `C:\xampp\htdocs\elearning\public\.htaccess`
```apache
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

---

### BƯỚC 7: Test Website

#### Test 1: Trang chủ
```
http://localhost/elearning/public/
```
→ Phải hiện trang chủ

#### Test 2: Login
```
http://localhost/elearning/public/auth/login
```
→ Phải hiện form login

#### Test 3: Đăng nhập
```
Email: hs.an@school.edu.vn
Password: Admin@123
```
→ Phải vào được Student Dashboard

---

## 📥 UPDATE CODE MỚI NHẤT

Tôi vừa sửa thêm 4 file. Download các file này:

### File 1: Student.php
- Link: https://raw.githubusercontent.com/dzduu562-tech/code-web/cursor/build-school-e-learning-website-c73d/app/controllers/Student.php
- Lưu vào: `C:\xampp\htdocs\elearning\app\controllers\Student.php`

### File 2: Teacher.php
- Link: https://raw.githubusercontent.com/dzduu562-tech/code-web/cursor/build-school-e-learning-website-c73d/app/controllers/Teacher.php
- Lưu vào: `C:\xampp\htdocs\elearning\app\controllers\Teacher.php`

### File 3: Admin.php
- Link: https://raw.githubusercontent.com/dzduu562-tech/code-web/cursor/build-school-e-learning-website-c73d/app/controllers/Admin.php
- Lưu vào: `C:\xampp\htdocs\elearning\app\controllers\Admin.php`

### File 4: App.php
- Link: https://raw.githubusercontent.com/dzduu562-tech/code-web/cursor/build-school-e-learning-website-c73d/app/core/App.php
- Lưu vào: `C:\xampp\htdocs\elearning\app\core\App.php`

---

## 🎯 CHECKLIST ĐẦY ĐỦ

Đảm bảo đã làm TẤT CẢ các bước:

- [ ] XAMPP đã cài đặt
- [ ] Apache + MySQL đang chạy (màu xanh)
- [ ] Code đã tải về `C:\xampp\htdocs\elearning`
- [ ] Database `elearning_db` đã tạo
- [ ] File `schema.sql` đã import (22 bảng)
- [ ] File `seed.sql` đã import (6 users)
- [ ] mod_rewrite đã BẬT trong httpd.conf
- [ ] AllowOverride đã sửa thành All
- [ ] Apache đã RESTART sau khi sửa config
- [ ] File .htaccess ở root đã có
- [ ] File .htaccess ở public đã có
- [ ] BASE_URL trong config.php đã đúng
- [ ] 4 file controller mới đã update
- [ ] Browser cache đã clear (Ctrl+Shift+Delete)
- [ ] Test trang chủ: OK
- [ ] Test login: OK
- [ ] Login thành công vào dashboard: OK

---

## ❓ CÁC LỖI THƯỜNG GẶP

### "404 Not Found"
→ mod_rewrite chưa bật → Làm lại Bước 4

### "Database connection failed"
→ Chưa import database → Làm lại Bước 3

### "Call to undefined method"
→ Chưa update code mới → Download 4 file trên

### "Undefined array key"
→ Database rỗng → Import lại seed.sql

### Trang trắng
→ Lỗi PHP → Bật display_errors trong config.php:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

---

## 🆘 NẾU VẪN LỖI

Chụp màn hình gửi cho tôi:
1. **Lỗi đầy đủ** (toàn bộ màn hình)
2. **URL** đang truy cập
3. **Tên folder** trong htdocs
4. **Kết quả test.php** (http://localhost/elearning/test.php)

---

## ✅ KẾT QUẢ MONG ĐỢI

Sau khi làm đủ tất cả bước trên:

✅ Không còn lỗi!  
✅ Vào được trang chủ  
✅ Login được  
✅ Dashboard hiển thị đầy đủ  
✅ Không còn warning/error  

---

**🎉 LÀM ĐÚNG CÁC BƯỚC TRÊN LÀ CHẠY 100%!**
