# 🚀 HƯỚNG DẪN TẢI CODE TỪ GITHUB

## ❌ LỖI:
```
fatal: not a git repository (or any of the parent directories): .git
```

## ✅ GIẢI PHÁP: CLONE LẠI TOÀN BỘ

---

## 🎯 BƯỚC 1: XÓA FOLDER CŨ

Mở **Command Prompt (CMD)** với quyền Admin:

```cmd
cd C:\xampp\htdocs
rmdir /s /q elearning
```

Hoặc xóa thủ công folder `C:\xampp\htdocs\elearning`

---

## 🎯 BƯỚC 2: CLONE TỪ GITHUB

```cmd
cd C:\xampp\htdocs
git clone https://github.com/dzduu562-tech/code-web.git elearning
cd elearning
git checkout cursor/build-school-e-learning-website-c73d
```

### Nếu chưa cài Git:
1. Tải Git: https://git-scm.com/download/win
2. Cài đặt với mọi tùy chọn mặc định
3. Khởi động lại CMD
4. Chạy lại lệnh trên

---

## 🎯 BƯỚC 3: IMPORT DATABASE

### A. Tạo Database:
1. Mở: http://localhost/phpmyadmin
2. Click tab **"Databases"**
3. Tạo database mới:
   - Tên: `elearning_db`
   - Collation: `utf8mb4_unicode_ci`
4. Click **Create**

### B. Import Schema:
1. Click vào database `elearning_db` (bên trái)
2. Click tab **"Import"**
3. Click **"Choose File"**
4. Chọn file: `C:\xampp\htdocs\elearning\database\schema.sql`
5. Click **"Import"** (nút dưới cùng)
6. ✅ Thành công nếu thấy: "Import has been successfully finished"

### C. Import Data mẫu:
1. Vẫn ở tab **"Import"**
2. Click **"Choose File"**
3. Chọn file: `C:\xampp\htdocs\elearning\database\seed.sql`
4. Click **"Import"**
5. ✅ Thành công!

### D. Kiểm tra:
1. Click vào database `elearning_db`
2. Phải thấy **22 bảng**:
   - users
   - student_profiles
   - teacher_profiles
   - courses
   - lessons
   - quizzes
   - quiz_questions
   - assignments
   - ... (và 14 bảng khác)

---

## 🎯 BƯỚC 4: CẤU HÌNH APACHE

### A. Bật mod_rewrite:
1. Mở XAMPP Control Panel
2. Bên cạnh **Apache**, click **"Config"** → **"httpd.conf"**
3. Tìm dòng (Ctrl + F):
   ```
   #LoadModule rewrite_module modules/mod_rewrite.so
   ```
4. Xóa dấu `#` ở đầu:
   ```
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
5. Save file

### B. Cho phép .htaccess:
1. Vẫn trong file `httpd.conf`
2. Tìm:
   ```
   <Directory "C:/xampp/htdocs">
       AllowOverride None
   ```
3. Đổi `None` → `All`:
   ```
   <Directory "C:/xampp/htdocs">
       AllowOverride All
   ```
4. Save file

### C. Restart Apache:
1. Về XAMPP Control Panel
2. Click **"Stop"** Apache
3. Click **"Start"** Apache
4. ✅ Xong!

---

## 🎯 BƯỚC 5: TEST WEBSITE

### A. Truy cập:
```
http://localhost/elearning/public/
```

Phải thấy **trang chủ đẹp** với:
- Navbar (Login/Register)
- Hero section
- Featured courses
- Footer

### B. Login Admin:
```
URL: http://localhost/elearning/public/auth/login
Email: admin@elearning.com
Password: Admin@123
```

### C. Login Giáo viên:
```
Email: gv.nguyen@school.edu.vn
Password: Admin@123
```

### D. Login Học sinh:
```
Email: hs.an@school.edu.vn
Password: Admin@123
```

---

## 🎯 BƯỚC 6: TEST CHỨC NĂNG

### Test Giáo viên:

#### 1. Tạo Khóa học:
```
Login GV → Khóa học → Tạo khóa học mới
- Tiêu đề: "Toán 10"
- Mô tả: "Test"
→ Tạo
✅ Thành công!
```

#### 2. Tạo Quiz:
```
Quiz → Tạo Quiz mới
- Chọn khóa học
- Tiêu đề: "Test Quiz"
- Thời gian: 30
→ Tạo Quiz
✅ Tự động chuyển đến Quản lý Quiz
```

#### 3. Thêm câu hỏi:
```
Nút "Thêm câu hỏi"
- Chọn "Trắc nghiệm"
- Nhập câu hỏi và 4 đáp án
- Chọn đáp án đúng
→ Thêm
✅ Thấy câu hỏi trong danh sách!
```

#### 4. Tạo Bài tập:
```
Bài tập → Tạo bài tập mới
- Chọn khóa học
- Tiêu đề: "Bài tập 1"
- Điểm: 100
→ Tạo
✅ Thành công!
```

---

## 🆘 TROUBLESHOOTING

### Lỗi 1: "Access forbidden"
**Nguyên nhân**: Chưa bật `AllowOverride All`
**Giải pháp**: Làm lại BƯỚC 4B

### Lỗi 2: "404 Not Found"
**Nguyên nhân**: Chưa bật `mod_rewrite`
**Giải pháp**: Làm lại BƯỚC 4A

### Lỗi 3: Trang trắng
**Nguyên nhân**: Lỗi PHP
**Giải pháp**:
1. Mở file: `C:\xampp\htdocs\elearning\config\config.php`
2. Đảm bảo có dòng:
   ```php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```
3. Refresh trang → Thấy lỗi cụ thể

### Lỗi 4: "Column not found"
**Nguyên nhân**: Database chưa đúng
**Giải pháp**:
1. Drop database cũ trong phpMyAdmin
2. Tạo lại `elearning_db`
3. Import lại `schema.sql` và `seed.sql`

### Lỗi 5: "Class not found"
**Nguyên nhân**: File chưa đầy đủ
**Giải pháp**: Clone lại từ đầu (BƯỚC 1-2)

---

## 📁 CẤU TRÚC FOLDER ĐÚNG:

```
C:\xampp\htdocs\elearning\
├── app/
│   ├── controllers/
│   │   ├── Admin.php
│   │   ├── Auth.php
│   │   ├── Student.php
│   │   ├── Teacher.php
│   │   └── ...
│   ├── models/
│   ├── views/
│   ├── core/
│   └── helpers/
├── config/
│   ├── config.php
│   └── database.php
├── database/
│   ├── schema.sql
│   └── seed.sql
├── public/
│   ├── index.php
│   ├── .htaccess
│   └── assets/
└── .htaccess
```

---

## ✅ CHECKLIST HOÀN THÀNH:

- [ ] Đã xóa folder cũ
- [ ] Đã clone code mới từ GitHub
- [ ] Đã checkout đúng branch
- [ ] Đã tạo database `elearning_db`
- [ ] Đã import `schema.sql` (22 bảng)
- [ ] Đã import `seed.sql` (6 users)
- [ ] Đã bật `mod_rewrite`
- [ ] Đã đổi `AllowOverride All`
- [ ] Đã restart Apache
- [ ] Truy cập được: http://localhost/elearning/public/
- [ ] Login được với Admin/GV/HS
- [ ] Tạo được Quiz không lỗi
- [ ] Tạo được Bài tập không lỗi
- [ ] Tạo được Khóa học không lỗi

---

## 🎉 XONG!

Nếu tất cả checklist ✅, bạn đã cài đặt thành công!

**Tất cả chức năng đều hoạt động hoàn hảo!** 🚀🎓

---

## 📞 LINKS QUAN TRỌNG:

- **GitHub**: https://github.com/dzduu562-tech/code-web
- **Branch**: cursor/build-school-e-learning-website-c73d
- **Commit mới nhất**: 7dce2ad

---

**CÓ VẤN ĐỀ GÌ? QUAY LẠI BƯỚC 1 VÀ LÀM LẠI!** 💪
