# E-Learning Platform

Nền tảng học tập trực tuyến (E-Learning) nhẹ, hiện đại, dễ sử dụng cho nhà trường. Được xây dựng bằng PHP thuần (không framework) và MySQL/MariaDB, tương thích hoàn toàn với XAMPP.

## ✨ Tính năng chính

### 🔐 Phân quyền người dùng
- **Admin**: Quản lý toàn hệ thống, người dùng, khóa học
- **Giáo viên**: Tạo/sửa/xóa khóa học, chương, bài học, giao bài tập, chấm điểm
- **Học sinh**: Đăng ký khóa học, học bài, làm bài tập, quiz, thảo luận

### 📚 Quản lý khóa học
- Tạo khóa học với chương và bài học
- Hỗ trợ nội dung văn bản, hình ảnh, video (YouTube embed)
- Đính kèm tài liệu tải về
- Theo dõi tiến độ học tập tự động (%)

### ✅ Bài tập & Chấm điểm
- Giáo viên giao bài tập (nộp file hoặc URL)
- Học sinh nộp bài trực tuyến
- Giáo viên chấm điểm và phản hồi
- Thông báo tự động khi có điểm

### 📝 Quiz trắc nghiệm
- Tạo bài kiểm tra với nhiều câu hỏi
- Hỗ trợ đáp án đúng/sai
- Tự động chấm điểm và hiển thị kết quả
- Lịch sử làm bài và điểm số

### 💬 Diễn đàn thảo luận
- Thảo luận theo khóa học và bài học
- Học sinh đặt câu hỏi
- Giáo viên và bạn bè trả lời
- Phân biệt vai trò (Admin/Giáo viên/Học sinh)

### 🔔 Thông báo
- Thông báo khi có bài học mới
- Thông báo khi bài tập được chấm điểm
- Thông báo khi có phản hồi trong diễn đàn
- Tự động làm mới mỗi 30 giây (AJAX)

### 🔍 Tìm kiếm & Lọc
- Tìm kiếm khóa học theo từ khóa
- Lọc theo môn học
- Lọc theo giáo viên

### 🎨 Giao diện hiện đại
- Dark/Light mode toggle
- Responsive mobile-first design
- Font dễ đọc, khoảng cách thoáng
- Accessibility cơ bản (contrast, focus, labels)

## 🛠️ Công nghệ sử dụng

- **Frontend**: HTML5, CSS3 (thuần), Vanilla JavaScript
- **Backend**: PHP 8.x (thuần, MVC đơn giản)
- **Database**: MySQL/MariaDB
- **Server**: XAMPP (Apache + MySQL + PHP)

## 📋 Yêu cầu hệ thống

- XAMPP 8.x trở lên (hoặc PHP 8.x + MySQL/MariaDB)
- Trình duyệt web hiện đại (Chrome, Firefox, Safari, Edge)

## 🚀 Hướng dẫn cài đặt

### Bước 1: Tải và giải nén

1. Tải mã nguồn về
2. Giải nén vào thư mục `htdocs` của XAMPP (ví dụ: `C:\xampp\htdocs\elearning`)

### Bước 2: Tạo cơ sở dữ liệu

1. Mở XAMPP Control Panel và khởi động **Apache** và **MySQL**
2. Truy cập phpMyAdmin: `http://localhost/phpmyadmin`
3. Tạo database mới tên `elearning_db` (hoặc import trực tiếp file SQL)

### Bước 3: Import dữ liệu

1. Trong phpMyAdmin, chọn database `elearning_db`
2. Click tab **Import**
3. Chọn file `sql/schema.sql` → Click **Go**
4. Sau khi hoàn tất, import tiếp file `sql/seed.sql` để có dữ liệu mẫu

### Bước 4: Cấu hình

1. Mở file `config/config.php`
2. Kiểm tra và điều chỉnh thông tin kết nối database nếu cần:
   ```php
   'db_host' => 'localhost',
   'db_name' => 'elearning_db',
   'db_user' => 'root',
   'db_pass' => '',
   ```

### Bước 5: Truy cập

1. Mở trình duyệt và truy cập: `http://localhost/elearning/public/index.php`
2. Sử dụng tài khoản mẫu để đăng nhập (xem bên dưới)

## 👤 Tài khoản mẫu

### Admin
- **Email**: admin@elearning.vn
- **Mật khẩu**: password123

### Giáo viên
- **Email**: teacher1@elearning.vn
- **Mật khẩu**: password123

### Học sinh
- **Email**: student1@elearning.vn
- **Mật khẩu**: password123

hoặc
- **Email**: student2@elearning.vn
- **Mật khẩu**: password123

## 📁 Cấu trúc thư mục

```
elearning/
├── public/                  # Thư mục public (entry point)
│   ├── index.php           # Router chính
│   └── uploads/            # Thư mục lưu file upload
│       ├── assignments/    # File bài tập
│       └── resources/      # Tài liệu học tập
├── app/
│   ├── controllers/        # Controllers (xử lý logic)
│   ├── models/            # Models (truy vấn database)
│   ├── views/             # Views (giao diện)
│   │   ├── layouts/       # Layout chung (header, footer)
│   │   ├── auth/          # Trang đăng nhập, đăng ký
│   │   ├── courses/       # Trang khóa học
│   │   ├── lessons/       # Trang bài học
│   │   ├── assignments/   # Trang bài tập
│   │   ├── quiz/          # Trang quiz
│   │   ├── forum/         # Trang diễn đàn
│   │   ├── dashboard/     # Trang dashboard
│   │   └── admin/         # Trang quản trị
│   └── core/              # Core framework
│       ├── DB.php         # Database handler
│       ├── Router.php     # Router
│       ├── Auth.php       # Authentication
│       └── Helpers.php    # Helper functions
├── config/
│   ├── config.php         # Cấu hình chính
│   └── config.php.sample  # Mẫu cấu hình
├── sql/
│   ├── schema.sql         # Cấu trúc database
│   └── seed.sql           # Dữ liệu mẫu
├── assets/
│   ├── css/
│   │   └── main.css       # CSS chính (hỗ trợ dark mode)
│   └── js/
│       └── main.js        # JavaScript (AJAX, theme toggle)
└── README.md              # Hướng dẫn này
```

## 🔒 Bảo mật

- Mật khẩu được hash bằng bcrypt
- CSRF token cho tất cả form POST
- Prepared statements (PDO) để chống SQL injection
- XSS protection với htmlspecialchars
- Session timeout sau 30 phút không hoạt động
- Upload file được kiểm tra MIME type và size

## 🌐 Tính năng nổi bật

### 1. Dark/Light Mode
- Toggle giữa chế độ sáng và tối
- Lưu preference vào localStorage
- Tự động áp dụng khi load lại trang

### 2. Thông báo realtime (AJAX)
- Tự động làm mới mỗi 30 giây
- Badge hiển thị số thông báo chưa đọc
- Dropdown danh sách thông báo
- Đánh dấu đã đọc/chưa đọc

### 3. Tiến độ học tập
- Tự động tính toán % hoàn thành
- Progress bar trực quan
- Đánh dấu bài học đã hoàn thành

### 4. Responsive Design
- Hoạt động tốt trên mobile, tablet, desktop
- Mobile-first approach
- Touch-friendly UI

## 📊 Database Schema

### Bảng chính:
- `users`: Người dùng (admin, teacher, student)
- `courses`: Khóa học
- `chapters`: Chương học
- `lessons`: Bài học
- `resources`: Tài liệu đính kèm
- `enrollments`: Đăng ký khóa học
- `lesson_progress`: Tiến độ học bài
- `assignments`: Bài tập
- `submissions`: Bài nộp
- `quizzes`: Quiz
- `questions`: Câu hỏi
- `options`: Đáp án
- `quiz_attempts`: Lượt làm quiz
- `answers`: Câu trả lời
- `forum_threads`: Topic diễn đàn
- `forum_posts`: Bài viết diễn đàn
- `notifications`: Thông báo

## 🎯 Use Cases

### Học sinh
1. Đăng ký tài khoản
2. Đăng ký khóa học
3. Xem bài học, tải tài liệu
4. Làm quiz, xem điểm
5. Nộp bài tập
6. Đặt câu hỏi trong diễn đàn
7. Nhận thông báo

### Giáo viên
1. Tạo khóa học mới
2. Thêm chương và bài học
3. Upload tài liệu, nhúng video
4. Tạo quiz trắc nghiệm
5. Giao bài tập
6. Chấm điểm và phản hồi
7. Trả lời câu hỏi học sinh

### Admin
1. Quản lý người dùng
2. Quản lý tất cả khóa học
3. Xem báo cáo thống kê
4. Xóa/sửa dữ liệu

## ⚡ Hiệu năng

- Không sử dụng framework nặng → Load nhanh
- CSS/JS tối giản
- Chỉ load thư viện cần thiết
- Pagination cho danh sách dài
- Prepared statements cache

## 🐛 Troubleshooting

### Lỗi kết nối database
- Kiểm tra XAMPP MySQL đã chạy chưa
- Kiểm tra thông tin trong `config/config.php`
- Đảm bảo database `elearning_db` đã được tạo

### Lỗi 404 Not Found
- Kiểm tra đường dẫn: `http://localhost/elearning/public/index.php`
- Đảm bảo file nằm đúng trong `htdocs/elearning`

### Upload file không hoạt động
- Kiểm tra quyền ghi cho thư mục `public/uploads`
- Kiểm tra `upload_max_filesize` trong `php.ini`

### Session timeout quá nhanh
- Điều chỉnh `session_lifetime` trong `config/config.php`

## 📝 Lưu ý

- **Môi trường development**: Đã bật `display_errors`, nhớ tắt khi deploy production
- **Password mặc định**: Đổi password sau khi cài đặt
- **Upload path**: Đảm bảo thư mục `public/uploads` có quyền ghi
- **HTTPS**: Nên sử dụng HTTPS trong môi trường production

## 🤝 Đóng góp

Dự án này được phát triển cho mục đích học tập và demo. Bạn có thể tự do sử dụng, chỉnh sửa, và cải tiến.

## 📜 License

MIT License - Tự do sử dụng cho mục đích giáo dục và phi thương mại.

## 📞 Hỗ trợ

Nếu gặp vấn đề, vui lòng:
1. Kiểm tra lại các bước cài đặt
2. Xem phần Troubleshooting
3. Kiểm tra log lỗi trong `php_error.log` của XAMPP

---

**Phát triển bởi**: E-Learning Team  
**Phiên bản**: 1.0.0  
**Ngày cập nhật**: 2025

Chúc bạn có trải nghiệm học tập tuyệt vời! 🎓📚
