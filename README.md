# 🎓 E-LEARNING PLATFORM

[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange.svg)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

> Hệ thống E-Learning hiện đại, toàn diện dành cho trường học - Chạy trên XAMPP

## ✨ Tính năng nổi bật

### 🎯 Cho học sinh
- 📚 Tham gia khóa học đa dạng
- 📹 Học bài với video, PDF, slides
- ✅ Làm quiz & kiểm tra trực tuyến với auto-save
- 📊 Theo dõi tiến độ học tập chi tiết
- 🏆 Hệ thống Gamification (Badges, XP, Leaderboard)
- 💬 Diễn đàn Q&A tương tác
- 📅 Lịch học & nhắc deadline
- 📱 Responsive 100% - Học mọi lúc mọi nơi

### 👨‍🏫 Cho giáo viên
- ➕ Tạo & quản lý khóa học
- 📝 Thêm bài giảng (video, tài liệu, văn bản)
- 🧪 Tạo quiz trắc nghiệm/tự luận
- 📋 Giao bài tập & chấm điểm
- 📈 Theo dõi tiến độ học sinh
- 💬 Tương tác với học sinh qua forum
- 📊 Báo cáo & thống kê chi tiết

### 👨‍💼 Cho quản trị viên
- 👥 Quản lý người dùng (Admin/GV/HS)
- 📚 Quản lý khóa học, môn học, lớp học
- ⚙️ Cài đặt hệ thống
- 🔐 Phân quyền chi tiết
- 📊 Dashboard thống kê tổng quan
- 🗂️ Sao lưu & khôi phục dữ liệu
- 📝 Xem nhật ký hoạt động

## 🛠️ Công nghệ sử dụng

### Backend
- **PHP 8.0+** - Ngôn ngữ lập trình chính
- **MySQL/MariaDB** - Cơ sở dữ liệu
- **PDO** - Database abstraction layer
- **MVC Pattern** - Kiến trúc ứng dụng

### Frontend
- **HTML5, CSS3** - Cấu trúc & styling
- **Bootstrap 5.3** - UI Framework responsive
- **JavaScript (Vanilla)** - Tương tác động
- **Bootstrap Icons** - Icon set

### Server
- **Apache 2.4+** - Web server
- **XAMPP** - Development environment
- **mod_rewrite** - URL rewriting

## 📋 Yêu cầu hệ thống

- **XAMPP** 8.0 trở lên
- **PHP** 8.0+
- **MySQL/MariaDB** 10.4+
- **Apache** 2.4+
- **Dung lượng**: Tối thiểu 500MB
- **RAM**: Tối thiểu 2GB

## 🚀 Cài đặt nhanh

### 1. Tải source code
```bash
# Clone repository (hoặc download ZIP)
git clone https://github.com/your-repo/elearning.git

# Di chuyển vào thư mục htdocs của XAMPP
# Windows: C:\xampp\htdocs\elearning
# Linux: /opt/lampp/htdocs/elearning
```

### 2. Khởi động XAMPP
- Mở **XAMPP Control Panel**
- Start **Apache** và **MySQL**

### 3. Tạo database
1. Truy cập `http://localhost/phpmyadmin`
2. Tạo database mới: `elearning_db`
3. Import file `database/schema.sql`
4. Import dữ liệu mẫu: `database/seed.sql`

### 4. Cấu hình
Mở `config/config.php` và cập nhật `BASE_URL`:
```php
define('BASE_URL', 'http://localhost/elearning/public');
```

### 5. Truy cập website
Mở trình duyệt: `http://localhost/elearning/public`

## 🔑 Tài khoản demo

Sau khi import `seed.sql`:

| Vai trò | Email | Password |
|---------|-------|----------|
| **Admin** | admin@elearning.com | Admin@123 |
| **Giáo viên** | gv.nguyen@school.edu.vn | Admin@123 |
| **Học sinh** | hs.an@school.edu.vn | Admin@123 |

## 📚 Tài liệu

- [📖 Hướng dẫn cài đặt chi tiết](INSTALLATION.md)
- [👤 Hướng dẫn sử dụng](USER_GUIDE.md)
- [🗄️ Database Schema](database/ERD.md)

## 🎨 Screenshots

### Trang chủ
![Homepage](docs/screenshots/home.png)

### Dashboard học sinh
![Student Dashboard](docs/screenshots/student-dashboard.png)

### Làm quiz
![Quiz](docs/screenshots/quiz.png)

### Dashboard giáo viên
![Teacher Dashboard](docs/screenshots/teacher-dashboard.png)

## 🌙 Tính năng đặc biệt

### Dark/Light Mode
- Chuyển đổi linh hoạt
- Lưu preference tự động
- Tối ưu cho mắt

### Responsive Design
- Mobile-first approach
- Hoạt động hoàn hảo trên mọi thiết bị
- Touch-friendly interface

### Auto-save Quiz
- Tự động lưu câu trả lời
- Không lo mất dữ liệu
- Countdown timer

## 📊 Cấu trúc thư mục

```
elearning/
├── app/
│   ├── controllers/     # Controllers
│   ├── models/          # Models
│   ├── views/           # Views
│   ├── core/            # Core framework
│   └── helpers/         # Helper functions
├── config/              # Configuration files
├── database/            # SQL scripts
├── public/              # Public assets
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   ├── uploads/         # User uploads
│   └── index.php        # Entry point
├── .htaccess
├── README.md
├── INSTALLATION.md
└── USER_GUIDE.md
```

## 🔧 Cấu hình nâng cao

### Virtual Host (Optional)
Tạo virtual host để sử dụng domain tùy chỉnh:
```apache
<VirtualHost *:80>
    ServerName elearning.local
    DocumentRoot "C:/xampp/htdocs/elearning/public"
</VirtualHost>
```

### Upload Limits
Tăng giới hạn upload trong `php.ini`:
```ini
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
```

## 🐛 Troubleshooting

### Lỗi database connection
- Kiểm tra MySQL đã chạy
- Xem lại `config/database.php`

### 404 Not Found
- Bật `mod_rewrite` trong Apache
- Kiểm tra file `.htaccess`

### CSS/JS không load
- Kiểm tra `BASE_URL` trong config
- Clear browser cache

Xem thêm trong [INSTALLATION.md](INSTALLATION.md)

## 🤝 Đóng góp

Mọi đóng góp đều được chào đón! Vui lòng:

1. Fork project
2. Tạo branch mới (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Mở Pull Request

## 📝 Changelog

### Version 1.0.0 (2024-10-10)
- ✅ Hệ thống đăng nhập & phân quyền
- ✅ Quản lý khóa học & bài giảng
- ✅ Quiz & kiểm tra trực tuyến
- ✅ Gamification (Badges, XP)
- ✅ Dashboard cho Admin/GV/HS
- ✅ Responsive design
- ✅ Dark/Light mode

## 📄 License

Dự án này được phát hành dưới [MIT License](LICENSE).

## 👨‍💻 Tác giả

**E-Learning Platform Team**

- Website: [elearning.local](http://elearning.local)
- Email: admin@elearning.com

## 🙏 Lời cảm ơn

- [Bootstrap](https://getbootstrap.com) - UI Framework
- [Bootstrap Icons](https://icons.getbootstrap.com) - Icon library
- [PHP](https://php.net) - Programming language
- [MySQL](https://mysql.com) - Database

---

<p align="center">
  Made with ❤️ for education
</p>

<p align="center">
  <strong>⭐ Nếu thấy hữu ích, hãy cho project một star! ⭐</strong>
</p>
