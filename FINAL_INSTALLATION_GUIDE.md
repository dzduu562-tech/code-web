# 🎯 HƯỚNG DẪN CÀI ĐẶT FINAL - E-LEARNING PLATFORM

## ⚠️ QUAN TRỌNG: ĐỌC KỸ TRƯỚC KHI CÀI!

Đây là **PHIÊN BẢN FINAL** đã sửa **TẤT CẢ** các lỗi!

---

## 📦 BƯỚC 1: XÓA CODE CŨ - TẢI LẠI MỚI

### ❌ KHÔNG update từng file! 
### ✅ PHẢI tải lại TOÀN BỘ code mới!

#### Cách 1: Download ZIP (KHUYÊN DÙNG - DỄ NHẤT)

1. **Xóa folder cũ**:
   ```
   Xóa hoàn toàn: C:\xampp\htdocs\elearning
   ```

2. **Download code mới**:
   - Vào: https://github.com/dzduu562-tech/code-web
   - Chọn branch: `cursor/build-school-e-learning-website-c73d`
   - Click nút **"Code"** → **"Download ZIP"**
   - Giải nén vào: `C:\xampp\htdocs\`
   - Đổi tên folder thành: `elearning`

3. **Kết quả**:
   ```
   C:\xampp\htdocs\elearning\
   ├── app\
   ├── config\
   ├── database\
   ├── public\
   └── ...
   ```

#### Cách 2: Git Clone

```cmd
cd C:\xampp\htdocs
rmdir /s /q elearning
git clone https://github.com/dzduu562-tech/code-web.git elearning
cd elearning
git checkout cursor/build-school-e-learning-website-c73d
```

---

## 🗄️ BƯỚC 2: IMPORT DATABASE

### 2.1. Mở phpMyAdmin
```
http://localhost/phpmyadmin
```

### 2.2. Xóa database cũ (nếu có)
- Click database `elearning_db` (nếu có)
- Click **"Drop"** → Xác nhận

### 2.3. Tạo database mới
- Click **"New"**
- Tên: `elearning_db`
- Collation: `utf8mb4_unicode_ci`
- Click **"Create"**

### 2.4. Import schema.sql
- Chọn database `elearning_db`
- Click tab **"Import"**
- Click **"Choose File"**
- Chọn: `C:\xampp\htdocs\elearning\database\schema.sql`
- Click **"Go"**
- Đợi thành công (phải thấy chữ "Import has been successfully finished")

### 2.5. Import seed.sql
- Click tab **"Import"** lại
- Click **"Choose File"**
- Chọn: `C:\xampp\htdocs\elearning\database\seed.sql`
- Click **"Go"**

### 2.6. Kiểm tra
- Click database `elearning_db`
- Phải có **22 bảng**:
  - users
  - student_profiles
  - teacher_profiles
  - courses
  - lessons
  - ... (và 17 bảng khác)
- Click bảng **"users"** → **"Browse"**
- Phải thấy **6 users**

---

## ⚙️ BƯỚC 3: CẤU HÌNH APACHE

### 3.1. Bật mod_rewrite

1. Mở XAMPP Control Panel
2. Click **"Config"** bên Apache
3. Chọn **"httpd.conf"**
4. Nhấn **Ctrl+F** tìm: `mod_rewrite`
5. Tìm dòng:
   ```apache
   #LoadModule rewrite_module modules/mod_rewrite.so
   ```
6. **Bỏ dấu #** thành:
   ```apache
   LoadModule rewrite_module modules/mod_rewrite.so
   ```

### 3.2. Cho phép .htaccess

1. Vẫn trong file **httpd.conf**
2. Nhấn **Ctrl+F** tìm: `AllowOverride None`
3. Tìm đoạn:
   ```apache
   <Directory "C:/xampp/htdocs">
       AllowOverride None
       Require all granted
   </Directory>
   ```
4. Sửa **None** thành **All**:
   ```apache
   <Directory "C:/xampp/htdocs">
       AllowOverride All
       Require all granted
   </Directory>
   ```

### 3.3. Save và Restart

1. **Save** file (Ctrl+S)
2. Đóng file
3. Quay lại XAMPP Control Panel
4. Click **"Stop"** Apache
5. Đợi 3 giây
6. Click **"Start"** lại
7. Đảm bảo Apache màu **XANH**

---

## 🔧 BƯỚC 4: KIỂM TRA CẤU HÌNH

### 4.1. Kiểm tra BASE_URL

Mở file: `C:\xampp\htdocs\elearning\config\config.php`

Dòng 12, đảm bảo:
```php
define('BASE_URL', 'http://localhost/elearning/public');
```

⚠️ **Chú ý**: Nếu folder KHÔNG phải tên `elearning`, sửa cho đúng!

### 4.2. Kiểm tra Database config

Mở file: `C:\xampp\htdocs\elearning\config\database.php`

Đảm bảo:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'elearning_db');
define('DB_USER', 'root');
define('DB_PASS', ''); // XAMPP mặc định để TRỐNG
```

---

## 🧪 BƯỚC 5: TEST WEBSITE

### Test 1: Trang chủ
```
http://localhost/elearning/public/
```
✅ Kết quả mong đợi: Hiện trang chủ với hero section

### Test 2: Trang login
```
http://localhost/elearning/public/auth/login
```
✅ Kết quả mong đợi: Hiện form đăng nhập

### Test 3: Trang register
```
http://localhost/elearning/public/auth/register
```
✅ Kết quả mong đợi: Hiện form đăng ký

### Test 4: Đăng nhập
```
URL: http://localhost/elearning/public/auth/login
Email: hs.an@school.edu.vn
Password: Admin@123
```
✅ Kết quả mong đợi: Chuyển đến Student Dashboard

### Test 5: Dashboard
```
Sau khi login thành công
```
✅ Kết quả mong đợi:
- Thấy tên: "Xin chào, Lê Minh An!"
- Thấy Level, XP
- Thấy thống kê khóa học
- KHÔNG có lỗi!

---

## 🎓 TẤT CẢ TÀI KHOẢN DEMO

### Admin
- Email: `admin@elearning.com`
- Password: `Admin@123`
- Quyền: Quản trị toàn bộ hệ thống

### Giáo viên 1
- Email: `gv.nguyen@school.edu.vn`
- Password: `Admin@123`
- Quyền: Tạo khóa học, chấm bài

### Giáo viên 2
- Email: `gv.tran@school.edu.vn`
- Password: `Admin@123`
- Quyền: Tạo khóa học, chấm bài

### Học sinh 1
- Email: `hs.an@school.edu.vn`
- Password: `Admin@123`
- Tên: Lê Minh An

### Học sinh 2
- Email: `hs.binh@school.edu.vn`
- Password: `Admin@123`
- Tên: Phạm Thu Bình

### Học sinh 3
- Email: `hs.cuong@school.edu.vn`
- Password: `Admin@123`
- Tên: Hoàng Văn Cường

---

## ✅ CHECKLIST HOÀN CHỈNH

Đánh dấu ✓ khi hoàn thành:

### Chuẩn bị
- [ ] XAMPP đã cài đặt
- [ ] Apache + MySQL đang chạy (cả 2 màu XANH)

### Cài đặt code
- [ ] Đã XÓA folder `elearning` cũ
- [ ] Đã TẢI code mới từ GitHub
- [ ] Folder đã đúng: `C:\xampp\htdocs\elearning`
- [ ] Có đủ các thư mục: app, config, database, public

### Database
- [ ] Đã xóa database `elearning_db` cũ (nếu có)
- [ ] Đã tạo database `elearning_db` mới
- [ ] Đã import `schema.sql` thành công
- [ ] Đã import `seed.sql` thành công
- [ ] Kiểm tra có 22 bảng
- [ ] Kiểm tra có 6 users

### Cấu hình Apache
- [ ] Đã bật mod_rewrite (bỏ dấu #)
- [ ] Đã sửa AllowOverride All
- [ ] Đã SAVE file httpd.conf
- [ ] Đã RESTART Apache
- [ ] Apache đang chạy (màu xanh)

### Cấu hình code
- [ ] BASE_URL trong config.php đúng
- [ ] DB_PASS trong database.php để TRỐNG
- [ ] File .htaccess ở root có
- [ ] File .htaccess ở public có

### Test
- [ ] Clear browser cache (Ctrl+Shift+Delete)
- [ ] Test trang chủ: OK
- [ ] Test login page: OK
- [ ] Test register page: OK
- [ ] Đăng nhập thành công
- [ ] Vào dashboard không lỗi
- [ ] Thấy tên user hiển thị
- [ ] KHÔNG còn error nào!

---

## 🐛 XỬ LÝ LỖI

### Lỗi: "404 Not Found"
→ mod_rewrite chưa bật  
→ Làm lại BƯỚC 3

### Lỗi: "Database connection failed"
→ Chưa import database hoặc config sai  
→ Làm lại BƯỚC 2 và kiểm tra BƯỚC 4.2

### Lỗi: "Fatal error: ... does not have a method 'index'"
→ Code CŨ chưa xóa  
→ Làm lại BƯỚC 1 (XÓA và TẢI LẠI)

### Lỗi: "Declaration of Courses::view()..."
→ Code CŨ chưa xóa  
→ Làm lại BƯỚC 1 (XÓA và TẢI LẠI)

### Lỗi: "View does not exist: student/progress"
→ Code CŨ chưa xóa  
→ Làm lại BƯỚC 1 (XÓA và TẢI LẠI)

### Trang trắng
→ Lỗi PHP, bật hiển thị lỗi:

Mở `config/config.php`, thêm vào cuối:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

---

## 🎯 KẾT QUẢ CUỐI CÙNG

Sau khi làm đủ 5 bước trên:

✅ Website chạy hoàn hảo  
✅ Login được tất cả tài khoản  
✅ Dashboard hiển thị đầy đủ  
✅ KHÔNG còn lỗi nào!  
✅ Responsive trên mobile  
✅ Dark/Light mode hoạt động  

---

## 📞 HỖ TRỢ

Nếu vẫn gặp lỗi sau khi làm đủ 5 bước:

Chụp màn hình gửi:
1. Toàn bộ lỗi hiển thị
2. URL đang truy cập
3. Screenshot phpMyAdmin (database elearning_db)
4. Nội dung file config/config.php (dòng BASE_URL)

---

## 🎉 HOÀN THÀNH!

**Chúc mừng! Bạn đã cài đặt thành công E-Learning Platform!**

Bây giờ bạn có thể:
- ✅ Đăng nhập với các tài khoản demo
- ✅ Khám phá dashboard của Admin/Teacher/Student
- ✅ Xem khóa học, bài giảng
- ✅ Làm quiz, nộp bài tập
- ✅ Theo dõi tiến độ học tập

**Hãy bắt đầu khám phá! 🚀**
