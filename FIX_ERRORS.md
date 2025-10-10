# 🔧 HƯỚNG DẪN SỬA LỖI: Undefined array key

## ❌ Lỗi vừa gặp:
```
Warning: Undefined array key "full_name" 
in C:\xamppp\htdocs\elearning\app\views\student\dashboard.php on line 16
```

## ✅ ĐÃ SỬA XONG!

### Nguyên nhân:
- Database chưa có dữ liệu user đầy đủ
- Profile chưa được tạo

### Giải pháp đã áp dụng:
1. Thêm fallback trong controller
2. Thêm check `isset()` trong view
3. Sử dụng null coalescing operator `??`

---

## 🚀 CẬP NHẬT CODE MỚI

Tôi đã sửa 2 file:

### File 1: `app/controllers/Student.php`
- Thêm kiểm tra user tồn tại
- Tạo fallback data nếu không có trong DB
- Đảm bảo luôn có đủ thông tin hiển thị

### File 2: `app/views/student/dashboard.php`
- Thêm `isset()` check cho tất cả biến
- Dùng `??` operator để có giá trị mặc định
- Tránh warning khi thiếu dữ liệu

---

## 📥 PULL CODE MỚI TỪ GITHUB

### Cách 1: Download lại toàn bộ
```bash
cd C:\xampp\htdocs
rm -rf elearning
git clone https://github.com/dzduu562-tech/code-web.git elearning
cd elearning
git checkout cursor/build-school-e-learning-website-c73d
```

### Cách 2: Update file đã sửa

**File 1: app/controllers/Student.php**
- Tải từ: https://github.com/dzduu562-tech/code-web/blob/cursor/build-school-e-learning-website-c73d/app/controllers/Student.php
- Copy vào: `C:\xampp\htdocs\elearning\app\controllers\Student.php`

**File 2: app/views/student/dashboard.php**
- Tải từ: https://github.com/dzduu562-tech/code-web/blob/cursor/build-school-e-learning-website-c73d/app/views/student/dashboard.php
- Copy vào: `C:\xampp\htdocs\elearning\app\views\student\dashboard.php`

---

## ⚠️ QUAN TRỌNG: Import Database!

Lỗi này xuất hiện vì **CHƯA IMPORT DATABASE**!

### Làm ngay bây giờ:

#### Bước 1: Tạo Database
1. Mở `http://localhost/phpmyadmin`
2. Click **"New"** (bên trái)
3. Tên database: `elearning_db`
4. Collation: `utf8mb4_unicode_ci`
5. Click **"Create"**

#### Bước 2: Import Schema
1. Chọn database `elearning_db` vừa tạo
2. Click tab **"Import"**
3. Click **"Choose File"**
4. Chọn file: `C:\xampp\htdocs\elearning\database\schema.sql`
5. Click **"Go"**
6. Đợi đến khi thấy: "Import has been successfully finished"

#### Bước 3: Import Data Mẫu
1. Click tab **"Import"** lại
2. Click **"Choose File"**
3. Chọn file: `C:\xampp\htdocs\elearning\database\seed.sql`
4. Click **"Go"**

#### Bước 4: Kiểm Tra
1. Click vào database `elearning_db`
2. Phải thấy **22 bảng**:
   - users
   - student_profiles
   - teacher_profiles
   - courses
   - ... (và 18 bảng khác)
3. Click bảng **"users"** → Browse
4. Phải thấy **6 users** (admin, teachers, students)

---

## 🧪 TEST LẠI

Sau khi import database và update code:

### Test 1: Login
```
URL: http://localhost/elearning/public/auth/login
Email: hs.an@school.edu.vn
Password: Admin@123
```

### Test 2: Dashboard
- Sau khi login thành công
- Tự động chuyển đến Student Dashboard
- **KHÔNG còn lỗi** "Undefined array key"!

---

## 🔍 CÁC LỖI KHÁC CÓ THỂ GẶP

### Lỗi: "Database connection failed"
**Giải pháp**: Kiểm tra file `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'elearning_db');
define('DB_USER', 'root');
define('DB_PASS', ''); // XAMPP mặc định để trống
```

### Lỗi: "Table doesn't exist"
**Giải pháp**: Chưa import schema.sql → Làm lại Bước 2 phía trên

### Lỗi: "Access denied for user"
**Giải pháp**: 
- Kiểm tra MySQL đã chạy chưa (màu xanh trong XAMPP)
- Kiểm tra user/pass trong config/database.php

---

## 📋 CHECKLIST

Tick khi hoàn thành:

- [ ] Đã update file `app/controllers/Student.php`
- [ ] Đã update file `app/views/student/dashboard.php`
- [ ] Database `elearning_db` đã tạo
- [ ] File `schema.sql` đã import (22 bảng)
- [ ] File `seed.sql` đã import (6 users)
- [ ] Test login với tài khoản: hs.an@school.edu.vn
- [ ] Dashboard hiện ra KHÔNG còn lỗi
- [ ] Thấy tên học sinh hiển thị đúng
- [ ] Thấy Level và XP

---

## 💡 TIP: Tránh lỗi trong tương lai

Trong PHP, khi không chắc biến có tồn tại, dùng:

### Cách 1: Null Coalescing Operator
```php
<?= $user['full_name'] ?? 'Học sinh' ?>
```

### Cách 2: isset() check
```php
<?= isset($user['full_name']) ? $user['full_name'] : 'Học sinh' ?>
```

### Cách 3: empty() check
```php
<?= !empty($user['full_name']) ? $user['full_name'] : 'Học sinh' ?>
```

---

## 🎯 KẾT QUẢ MONG ĐỢI

Sau khi làm xong tất cả:

✅ Login thành công  
✅ Dashboard hiển thị đầy đủ  
✅ Không còn warning/error  
✅ Thấy tên, level, XP  
✅ Thấy danh sách khóa học  

---

**Nếu vẫn gặp lỗi, chụp màn hình và gửi cho tôi!** 📸
