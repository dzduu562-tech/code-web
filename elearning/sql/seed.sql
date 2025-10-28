-- E-Learning Platform Sample Data
-- Run this after schema.sql

USE elearning_db;

-- Insert sample users (password: 123456)
INSERT INTO users (name, email, password_hash, role, phone, is_active) VALUES
('Admin System', 'admin@elearning.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '0123456789', TRUE),
('Nguyễn Văn Giáo', 'teacher1@elearning.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', '0987654321', TRUE),
('Trần Thị Minh', 'teacher2@elearning.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', '0912345678', TRUE),
('Lê Văn Học', 'student1@elearning.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '0901234567', TRUE),
('Phạm Thị Sinh', 'student2@elearning.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '0898765432', TRUE),
('Hoàng Văn Nam', 'student3@elearning.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '0876543210', TRUE);

-- Insert sample courses
INSERT INTO courses (title, subject, teacher_id, description, is_published) VALUES
('Lập trình PHP cơ bản', 'Công nghệ thông tin', 2, 'Khóa học PHP từ cơ bản đến nâng cao, phù hợp cho người mới bắt đầu học lập trình web.', TRUE),
('Toán học lớp 12', 'Toán học', 3, 'Ôn tập và củng cố kiến thức toán học lớp 12, chuẩn bị cho kỳ thi THPT Quốc gia.', TRUE),
('Tiếng Anh giao tiếp', 'Ngoại ngữ', 2, 'Phát triển kỹ năng giao tiếp tiếng Anh trong cuộc sống hàng ngày và công việc.', TRUE),
('Vật lý đại cương', 'Khoa học tự nhiên', 3, 'Những kiến thức cơ bản về vật lý đại cương cho sinh viên năm nhất.', FALSE);

-- Insert sample chapters
INSERT INTO chapters (course_id, title, position) VALUES
(1, 'Giới thiệu về PHP', 1),
(1, 'Cú pháp cơ bản PHP', 2),
(1, 'Làm việc với Database', 3),
(2, 'Hàm số và đạo hàm', 1),
(2, 'Tích phân', 2),
(3, 'Ngữ pháp cơ bản', 1),
(3, 'Từ vựng thông dụng', 2);

-- Insert sample lessons
INSERT INTO lessons (chapter_id, title, content_html, video_url, position, duration_minutes, is_published) VALUES
(1, 'PHP là gì?', '<h3>Giới thiệu về PHP</h3><p>PHP (PHP: Hypertext Preprocessor) là một ngôn ngữ lập trình kịch bản phía máy chủ được thiết kế đặc biệt cho phát triển web.</p><h4>Đặc điểm của PHP:</h4><ul><li>Mã nguồn mở và miễn phí</li><li>Dễ học và sử dụng</li><li>Hỗ trợ nhiều hệ cơ sở dữ liệu</li><li>Chạy trên nhiều hệ điều hành</li></ul>', 'https://www.youtube.com/watch?v=example1', 1, 30, TRUE),
(1, 'Cài đặt môi trường PHP', '<h3>Cài đặt XAMPP</h3><p>XAMPP là một gói phần mềm miễn phí bao gồm Apache, MySQL, PHP và Perl.</p><h4>Các bước cài đặt:</h4><ol><li>Tải XAMPP từ trang chủ</li><li>Chạy file cài đặt</li><li>Khởi động Apache và MySQL</li><li>Kiểm tra bằng cách truy cập localhost</li></ol>', 'https://www.youtube.com/watch?v=example2', 2, 45, TRUE),
(2, 'Biến và kiểu dữ liệu', '<h3>Biến trong PHP</h3><p>Biến trong PHP bắt đầu bằng dấu $ và theo sau là tên biến.</p><pre><code>&lt;?php\n$name = "Xin chào";\n$age = 25;\n$price = 99.99;\n?&gt;</code></pre><h4>Các kiểu dữ liệu:</h4><ul><li>String (chuỗi)</li><li>Integer (số nguyên)</li><li>Float (số thực)</li><li>Boolean (logic)</li><li>Array (mảng)</li></ul>', NULL, 1, 40, TRUE),
(4, 'Khái niệm hàm số', '<h3>Hàm số</h3><p>Hàm số là một quy tắc tương ứng mỗi giá trị của biến độc lập x với một giá trị duy nhất của biến phụ thuộc y.</p><h4>Ký hiệu:</h4><p>y = f(x) hoặc f: x → y</p><h4>Tập xác định:</h4><p>Tập hợp tất cả các giá trị x mà hàm số có nghĩa.</p>', 'https://www.youtube.com/watch?v=math1', 1, 50, TRUE);

-- Insert sample enrollments
INSERT INTO enrollments (user_id, course_id, progress_percent, last_view_at) VALUES
(4, 1, 25.50, NOW()),
(4, 2, 10.00, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(5, 1, 75.00, DATE_SUB(NOW(), INTERVAL 1 HOUR)),
(5, 3, 30.00, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(6, 2, 50.00, DATE_SUB(NOW(), INTERVAL 1 DAY));

-- Insert lesson progress
INSERT INTO lesson_progress (user_id, lesson_id, is_completed, completed_at, time_spent_minutes) VALUES
(4, 1, TRUE, DATE_SUB(NOW(), INTERVAL 2 DAY), 35),
(4, 2, FALSE, NULL, 15),
(5, 1, TRUE, DATE_SUB(NOW(), INTERVAL 3 DAY), 30),
(5, 2, TRUE, DATE_SUB(NOW(), INTERVAL 2 DAY), 45),
(5, 3, TRUE, DATE_SUB(NOW(), INTERVAL 1 DAY), 40),
(6, 4, TRUE, DATE_SUB(NOW(), INTERVAL 1 DAY), 50);

-- Insert sample assignments
INSERT INTO assignments (course_id, title, description, due_at, max_score, is_published) VALUES
(1, 'Bài tập PHP cơ bản', 'Viết chương trình PHP hiển thị thông tin cá nhân sử dụng biến và echo.', DATE_ADD(NOW(), INTERVAL 7 DAY), 100.00, TRUE),
(2, 'Bài tập tính đạo hàm', 'Tính đạo hàm của các hàm số đã cho và vẽ đồ thị.', DATE_ADD(NOW(), INTERVAL 5 DAY), 100.00, TRUE),
(3, 'Viết đoạn hội thoại', 'Viết một đoạn hội thoại ngắn bằng tiếng Anh về chủ đề mua sắm.', DATE_ADD(NOW(), INTERVAL 10 DAY), 100.00, TRUE);

-- Insert sample submissions
INSERT INTO submissions (assignment_id, student_id, submission_url, note, score, feedback, graded_at) VALUES
(1, 4, 'https://github.com/student1/php-homework', 'Em đã hoàn thành bài tập theo yêu cầu.', 85.00, 'Bài làm tốt, cần chú ý thêm về format code.', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(2, 6, NULL, 'Bài tập được làm trên giấy và chụp ảnh gửi qua email.', 92.00, 'Excellent work! Giải rất chi tiết và chính xác.', DATE_SUB(NOW(), INTERVAL 2 HOUR));

-- Insert sample quizzes
INSERT INTO quizzes (lesson_id, title, description, time_limit_minutes, max_attempts, pass_score, is_published) VALUES
(1, 'Kiểm tra kiến thức PHP cơ bản', 'Bài kiểm tra về những kiến thức cơ bản của PHP', 15, 2, 70.00, TRUE),
(4, 'Quiz về hàm số', 'Kiểm tra hiểu biết về khái niệm hàm số', 20, 3, 60.00, TRUE);

-- Insert sample questions
INSERT INTO questions (quiz_id, question_text, question_type, points, position) VALUES
(1, 'PHP là viết tắt của gì?', 'single', 2.00, 1),
(1, 'Biến trong PHP bắt đầu bằng ký tự nào?', 'single', 2.00, 2),
(1, 'Những kiểu dữ liệu nào có trong PHP? (Chọn nhiều đáp án)', 'multiple', 3.00, 3),
(2, 'Hàm số y = f(x) có nghĩa là gì?', 'single', 5.00, 1);

-- Insert sample options
INSERT INTO options (question_id, option_text, is_correct, position) VALUES
(1, 'Personal Home Page', FALSE, 1),
(1, 'PHP: Hypertext Preprocessor', TRUE, 2),
(1, 'Private Home Page', FALSE, 3),
(1, 'Public Hypertext Processor', FALSE, 4),

(2, '&', FALSE, 1),
(2, '$', TRUE, 2),
(2, '#', FALSE, 3),
(2, '@', FALSE, 4),

(3, 'String', TRUE, 1),
(3, 'Integer', TRUE, 2),
(3, 'Boolean', TRUE, 3),
(3, 'Character', FALSE, 4),

(4, 'Một công thức toán học', FALSE, 1),
(4, 'Quy tắc tương ứng mỗi x với một y duy nhất', TRUE, 2),
(4, 'Một phương trình', FALSE, 3),
(4, 'Một bất đẳng thức', FALSE, 4);

-- Insert sample forum threads
INSERT INTO forum_threads (course_id, lesson_id, author_id, title, views) VALUES
(1, 1, 4, 'Khó hiểu về cú pháp PHP', 15),
(1, NULL, 5, 'Chia sẻ tài liệu học PHP hay', 8),
(2, 4, 6, 'Cách nhớ công thức đạo hàm', 22);

-- Insert sample forum posts
INSERT INTO forum_posts (thread_id, author_id, content) VALUES
(1, 4, 'Mình mới học PHP và thấy cú pháp hơi khó hiểu. Các bạn có thể giải thích thêm không?'),
(1, 2, 'Bạn có thể cho biết cụ thể phần nào khó hiểu không? Thầy sẽ giải thích chi tiết hơn.'),
(1, 4, 'Cảm ơn thầy! Mình thắc mắc về cách khai báo biến và sử dụng echo.'),

(2, 5, 'Mình tìm được một số tài liệu PHP rất hay, chia sẻ cho các bạn cùng học: [link]'),
(2, 4, 'Cảm ơn bạn! Tài liệu này rất hữu ích.'),

(3, 6, 'Các bạn có cách nào để nhớ công thức đạo hàm dễ hơn không?'),
(3, 3, 'Bạn nên luyện tập nhiều và ghi nhớ các công thức cơ bản trước.');

-- Insert sample notifications
INSERT INTO notifications (user_id, type, title, message, payload_json, is_read) VALUES
(4, 'assignment_graded', 'Bài tập đã được chấm điểm', 'Bài tập "Bài tập PHP cơ bản" của bạn đã được chấm điểm: 85/100', '{"assignment_id": 1, "score": 85}', FALSE),
(4, 'forum_reply', 'Có phản hồi mới', 'Giáo viên đã trả lời câu hỏi của bạn trong diễn đàn', '{"thread_id": 1}', TRUE),
(5, 'new_lesson', 'Bài học mới', 'Bài học "Biến và kiểu dữ liệu" đã được thêm vào khóa học', '{"lesson_id": 3, "course_id": 1}', FALSE),
(6, 'assignment_graded', 'Bài tập đã được chấm điểm', 'Bài tập "Bài tập tính đạo hàm" của bạn đã được chấm điểm: 92/100', '{"assignment_id": 2, "score": 92}', FALSE);

-- Insert system settings
INSERT INTO settings (setting_key, setting_value) VALUES
('site_name', 'E-Learning Platform'),
('site_description', 'Hệ thống học tập trực tuyến hiện đại'),
('max_upload_size', '52428800'),
('notification_interval', '30'),
('default_theme', 'light'),
('allow_registration', '1'),
('maintenance_mode', '0');