# E-Learning Platform

Hệ thống học tập trực tuyến hiện đại, nhẹ và dễ sử dụng được xây dựng bằng PHP thuần và MySQL.

## 🌟 Tính năng chính

### 👥 Quản lý người dùng
- **Phân quyền 3 cấp**: Admin, Giáo viên, Học sinh
- **Đăng ký/Đăng nhập** với bảo mật cao (password hashing, CSRF protection)
- **Quản lý hồ sơ** cá nhân và đổi mật khẩu
- **Session management** an toàn

### 📚 Hệ thống khóa học
- **Tạo và quản lý khóa học** (Giáo viên)
- **Tổ chức theo chương và bài học**
- **Đăng ký khóa học** (Học sinh)
- **Theo dõi tiến độ học tập** với % hoàn thành
- **Upload tài liệu** đính kèm (PDF, DOC, PPT, v.v.)
- **Nhúng video** từ YouTube/Vimeo

### 📝 Bài tập và chấm điểm
- **Giao bài tập** với deadline
- **Nộp bài** qua file upload hoặc URL
- **Chấm điểm và phản hồi** từ giáo viên
- **Thống kê điểm số** và báo cáo

### 🧠 Quiz trắc nghiệm
- **Tạo câu hỏi** đơn/đa lựa chọn
- **Giới hạn thời gian** làm bài
- **Tự động chấm điểm** và hiển thị kết quả
- **Giới hạn số lần làm bài**
- **Thống kê kết quả** chi tiết

### 💬 Diễn đàn thảo luận
- **Thảo luận theo khóa học** và bài học
- **Đặt câu hỏi** và nhận trả lời
- **Đánh dấu giải pháp** cho câu hỏi
- **Phân quyền moderator** cho giáo viên

### 🔔 Hệ thống thông báo
- **Thông báo real-time** về bài học mới, điểm số, bình luận
- **Lưu trữ trong database** và hiển thị badge
- **Refresh tự động** qua AJAX (30s/lần)
- **Đánh dấu đã đọc**

### 🔍 Tìm kiếm và lọc
- **Tìm kiếm khóa học** theo từ khóa
- **Lọc theo môn học** và giáo viên
- **Tìm kiếm trong diễn đàn**

### 👨‍💼 Quản trị hệ thống
- **Dashboard admin** với thống kê tổng quan
- **Quản lý người dùng** (kích hoạt/vô hiệu hóa)
- **Quản lý khóa học** (duyệt/từ chối)
- **Báo cáo hoạt động** hệ thống

## 🎨 Giao diện hiện đại

### ✨ UI/UX Features
- **Responsive design** mobile-first với Bootstrap 5
- **Dark/Light theme** tự động theo system preference
- **Navbar gọn gàng** với search và notifications
- **Typography dễ đọc** với font system
- **Animations mượt mà** và micro-interactions
- **Accessibility** cơ bản (contrast, focus, labels)

### 🌓 Theme System
- **CSS Variables** cho easy theming
- **localStorage** lưu preference
- **Smooth transitions** khi chuyển theme
- **System preference detection**

## 🔒 Bảo mật

### 🛡️ Security Features
- **Password hashing** với PHP password_hash()
- **CSRF protection** cho tất cả forms
- **Input validation** và sanitization
- **PDO prepared statements** chống SQL injection
- **Session security** với regeneration
- **File upload validation** (type, size)
- **XSS protection** với htmlspecialchars

### 🔐 Access Control
- **Role-based permissions**
- **Course ownership validation**
- **Resource access control**
- **Admin-only areas protection**

## ⚡ Hiệu năng

### 🚀 Performance Features
- **Phân trang** cho danh sách dài
- **AJAX loading** cho notifications
- **CSS/JS minification** ready
- **Image optimization** guidelines
- **Database indexing** tối ưu
- **Lazy loading** cho images

## 📋 Yêu cầu hệ thống

### 🖥️ Server Requirements
- **PHP 8.0+** (khuyến nghị 8.1+)
- **MySQL 5.7+** hoặc **MariaDB 10.3+**
- **Apache/Nginx** với mod_rewrite
- **50MB+** disk space
- **128MB+** PHP memory limit

### 📦 XAMPP Compatibility
- **XAMPP 8.0+** (Windows/Mac/Linux)
- **Portable** - chạy trực tiếp từ htdocs
- **No Composer** dependencies
- **Pure PHP** - không cần Node.js

## 🚀 Hướng dẫn cài đặt

### 1️⃣ Chuẩn bị môi trường

```bash
# Tải và cài đặt XAMPP
# https://www.apachefriends.org/download.html

# Khởi động Apache và MySQL trong XAMPP Control Panel
```

### 2️⃣ Cài đặt source code

```bash
# Copy thư mục elearning vào htdocs
cp -r elearning/ C:/xampp/htdocs/

# Hoặc clone từ git (nếu có)
cd C:/xampp/htdocs/
git clone <repository-url> elearning
```

### 3️⃣ Tạo database

```bash
# Mở phpMyAdmin: http://localhost/phpmyadmin
# Tạo database mới tên 'elearning_db'
# Import file sql/schema.sql để tạo bảng
# Import file sql/seed.sql để có dữ liệu mẫu
```

### 4️⃣ Cấu hình

```bash
# Copy file config
cp config/config.php.sample config/config.php

# Chỉnh sửa config/config.php nếu cần:
# - Database credentials
# - App URL
# - Upload settings
```

### 5️⃣ Phân quyền thư mục

```bash
# Đảm bảo thư mục uploads có quyền ghi
chmod 755 public/uploads/
```

### 6️⃣ Truy cập hệ thống

```
URL: http://localhost/elearning/public/
```

## 👤 Tài khoản mẫu

### 🔑 Login Credentials

| Vai trò | Email | Mật khẩu | Mô tả |
|---------|-------|----------|--------|
| **Admin** | admin@elearning.com | 123456 | Quản trị viên hệ thống |
| **Giáo viên** | teacher1@elearning.com | 123456 | Nguyễn Văn Giáo |
| **Giáo viên** | teacher2@elearning.com | 123456 | Trần Thị Minh |
| **Học sinh** | student1@elearning.com | 123456 | Lê Văn Học |
| **Học sinh** | student2@elearning.com | 123456 | Phạm Thị Sinh |

## 📁 Cấu trúc thư mục

```
elearning/
├── public/                 # Web root
│   ├── index.php          # Entry point
│   ├── assets/            # CSS, JS, Images
│   └── uploads/           # User uploads
├── app/
│   ├── controllers/       # Route handlers
│   ├── models/           # Database models
│   ├── views/            # HTML templates
│   └── core/             # Core classes
├── config/               # Configuration
├── sql/                  # Database files
└── README.md
```

## 🗄️ Database Schema

### 📊 Main Tables

- **users** - Người dùng và phân quyền
- **courses** - Khóa học
- **chapters** - Chương học
- **lessons** - Bài học
- **resources** - Tài liệu đính kèm
- **enrollments** - Đăng ký khóa học
- **lesson_progress** - Tiến độ học tập
- **assignments** - Bài tập
- **submissions** - Bài nộp
- **quizzes** - Bài kiểm tra
- **questions** - Câu hỏi
- **options** - Đáp án
- **quiz_attempts** - Lần làm bài
- **answers** - Câu trả lời
- **forum_threads** - Chủ đề diễn đàn
- **forum_posts** - Bài viết diễn đàn
- **notifications** - Thông báo
- **settings** - Cài đặt hệ thống

## 🔧 Tùy chỉnh

### 🎨 Themes
```css
/* Chỉnh sửa public/assets/css/main.css */
:root {
  --bs-primary: #your-color;
  --primary-gradient: your-gradient;
}
```

### ⚙️ Settings
```php
// Chỉnh sửa config/config.php
'upload' => [
    'max_size' => 104857600, // 100MB
    'allowed_types' => ['jpg', 'png', 'pdf'],
],
```

### 🔌 Extensions
- Thêm controllers mới trong `app/controllers/`
- Tạo models tương ứng trong `app/models/`
- Thêm routes trong `public/index.php`

## 🐛 Troubleshooting

### ❌ Lỗi thường gặp

**Database connection failed**
```bash
# Kiểm tra MySQL đã chạy chưa
# Kiểm tra credentials trong config.php
# Đảm bảo database đã được tạo
```

**Permission denied on uploads**
```bash
chmod 755 public/uploads/
# Hoặc 777 nếu cần thiết (không khuyến nghị production)
```

**404 Not Found**
```bash
# Kiểm tra mod_rewrite đã enable chưa
# Đảm bảo .htaccess có quyền đọc
# Kiểm tra DocumentRoot trong Apache config
```

**Session errors**
```bash
# Kiểm tra session.save_path trong php.ini
# Đảm bảo thư mục session có quyền ghi
```

## 📈 Roadmap

### 🚀 Planned Features
- [ ] **Email notifications** với SMTP
- [ ] **Real-time chat** với WebSocket
- [ ] **Video conferencing** integration
- [ ] **Mobile app** với API
- [ ] **Advanced analytics** và reports
- [ ] **Multi-language** support
- [ ] **Payment integration** cho paid courses
- [ ] **Certificate generation** PDF
- [ ] **Advanced quiz types** (essay, code)
- [ ] **Gamification** (badges, points)

### 🔧 Technical Improvements
- [ ] **Caching layer** với Redis
- [ ] **API endpoints** cho mobile
- [ ] **Docker containerization**
- [ ] **Unit testing** với PHPUnit
- [ ] **CI/CD pipeline**
- [ ] **Performance monitoring**

## 🤝 Đóng góp

### 💡 How to Contribute
1. Fork repository
2. Tạo feature branch
3. Commit changes
4. Push và tạo Pull Request
5. Code review và merge

### 📋 Coding Standards
- **PSR-4** autoloading
- **PSR-12** coding style
- **Meaningful** variable names
- **Comment** complex logic
- **Security first** approach

## 📄 License

MIT License - Xem file LICENSE để biết chi tiết.

## 📞 Hỗ trợ

### 🆘 Getting Help
- **Email**: support@elearning.com
- **Documentation**: Xem README này
- **Issues**: Tạo GitHub issue
- **Community**: Join Discord/Telegram

### 🏆 Credits

Được phát triển bởi team E-Learning Platform với mục tiêu tạo ra một hệ thống học tập trực tuyến:
- ✅ **Nhẹ** - Không dependencies phức tạp
- ✅ **Hiện đại** - UI/UX đẹp và responsive  
- ✅ **Dễ dùng** - Intuitive cho mọi user
- ✅ **Ổn định** - Production ready
- ✅ **Bảo mật** - Security best practices

---

**Happy Learning! 🎓📚**