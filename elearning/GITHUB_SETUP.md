# 🚀 Hướng dẫn đưa E-Learning Platform lên GitHub

## 📋 Bước 1: Tạo Repository trên GitHub

1. **Đăng nhập GitHub**: Truy cập [github.com](https://github.com) và đăng nhập
2. **Tạo Repository mới**:
   - Click nút **"New"** (màu xanh) hoặc **"+"** → **"New repository"**
   - **Repository name**: `elearning-platform`
   - **Description**: `Modern E-Learning Platform with PHP & MySQL - Lightweight, Responsive, Feature-rich`
   - **Visibility**: Chọn **Public** (để mọi người có thể xem và sử dụng)
   - **⚠️ QUAN TRỌNG**: KHÔNG tick "Add a README file" (vì chúng ta đã có sẵn)
   - Click **"Create repository"**

3. **Copy URL Repository**: Sau khi tạo xong, copy URL có dạng:
   ```
   https://github.com/YOUR_USERNAME/elearning-platform.git
   ```

## 🔧 Bước 2: Push Code lên GitHub

### Cách 1: Sử dụng Script tự động (Khuyên dùng)

```bash
# Chạy script tự động
./push-to-github.sh
```

Script sẽ hỏi URL repository và tự động push code lên.

### Cách 2: Thủ công

```bash
# 1. Thêm remote repository
git remote add origin https://github.com/YOUR_USERNAME/elearning-platform.git

# 2. Đổi branch sang main
git branch -M main

# 3. Push code lên GitHub
git push -u origin main
```

## 📥 Bước 3: Hướng dẫn Clone cho người khác

Sau khi push thành công, chia sẻ với người khác:

### Clone Repository
```bash
git clone https://github.com/YOUR_USERNAME/elearning-platform.git
cd elearning-platform
```

### Setup Database
```bash
# 1. Tạo database trong MySQL/phpMyAdmin
# 2. Import schema
mysql -u root -p elearning_db < sql/schema.sql
mysql -u root -p elearning_db < sql/seed.sql
```

### Cấu hình
```bash
# Copy và chỉnh sửa config
cp config/config.php.sample config/config.php
# Chỉnh sửa thông tin database trong config.php
```

### Chạy trên XAMPP
```bash
# Copy vào htdocs
cp -r elearning-platform C:/xampp/htdocs/elearning
# Truy cập: http://localhost/elearning/public/
```

## 🎯 Tài khoản Demo

| Vai trò | Email | Mật khẩu |
|---------|-------|----------|
| **Admin** | admin@elearning.com | 123456 |
| **Giáo viên** | teacher1@elearning.com | 123456 |
| **Học sinh** | student1@elearning.com | 123456 |

## 📊 Repository Statistics

- **Language**: PHP (85%), CSS (10%), JavaScript (5%)
- **Files**: 35+ files
- **Features**: 100+ tính năng hoàn chỉnh
- **Database**: 15+ bảng với sample data
- **Documentation**: README, INSTALL, FEATURES guides

## 🔄 Cập nhật Code

### Để cập nhật repository sau khi có thay đổi:

```bash
# 1. Add changes
git add .

# 2. Commit với message mô tả
git commit -m "Update: Thêm tính năng XYZ"

# 3. Push lên GitHub
git push origin main
```

### Để pull thay đổi mới từ GitHub:

```bash
git pull origin main
```

## 🏷️ Tạo Release

Sau khi push, bạn có thể tạo release:

1. Vào repository trên GitHub
2. Click **"Releases"** → **"Create a new release"**
3. **Tag version**: `v1.0.0`
4. **Release title**: `E-Learning Platform v1.0.0`
5. **Description**:
   ```markdown
   🎉 **First Release - E-Learning Platform v1.0.0**
   
   ## ✨ Features
   - Complete user management (Admin/Teacher/Student)
   - Course management with chapters & lessons
   - Assignment system with auto-grading
   - Quiz system with multiple choice questions
   - Forum discussion system
   - Real-time notifications
   - Modern responsive UI with dark/light theme
   - XAMPP compatible - ready to run
   
   ## 🚀 Quick Start
   1. Download and extract
   2. Import SQL files to MySQL
   3. Copy to XAMPP htdocs
   4. Access via browser
   
   ## 🔑 Demo Accounts
   - Admin: admin@elearning.com / 123456
   - Teacher: teacher1@elearning.com / 123456
   - Student: student1@elearning.com / 123456
   ```

## 🌟 Repository Features

Để làm repository nổi bật hơn:

### 1. Thêm Topics/Tags
Trong repository settings, thêm topics:
- `php`
- `mysql`
- `elearning`
- `education`
- `bootstrap`
- `responsive`
- `xampp`
- `mvc`

### 2. Tạo GitHub Pages (Demo)
- Vào Settings → Pages
- Source: Deploy from branch `main`
- Folder: `/docs` (nếu có documentation site)

### 3. Thêm Badges vào README
Repository sẽ có badges hiển thị:
- Language
- License
- Version
- Downloads

## 🔗 Chia sẻ Repository

Sau khi hoàn thành, bạn có thể chia sẻ:

- **Repository URL**: `https://github.com/YOUR_USERNAME/elearning-platform`
- **Clone URL**: `git clone https://github.com/YOUR_USERNAME/elearning-platform.git`
- **Download ZIP**: Từ nút "Code" → "Download ZIP"

---

**🎉 Chúc mừng! Repository E-Learning Platform của bạn đã sẵn sàng trên GitHub!**