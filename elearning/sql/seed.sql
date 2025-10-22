-- Seed data for E-Learning Platform
USE elearning_db;

-- Insert sample users
INSERT INTO users (name, email, password_hash, role) VALUES
('Admin User', 'admin@elearning.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Nguyễn Văn Giáo', 'teacher1@elearning.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher'),
('Trần Thị Minh', 'teacher2@elearning.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher'),
('Lê Văn Học', 'student1@elearning.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('Phạm Thị Sinh', 'student2@elearning.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('Hoàng Văn Tài', 'student3@elearning.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student');

-- Insert sample courses
INSERT INTO courses (title, subject, teacher_id, description, is_published) VALUES
('Lập trình Web với PHP', 'Công nghệ thông tin', 2, 'Khóa học cơ bản về lập trình web sử dụng PHP, MySQL và HTML/CSS', TRUE),
('Toán học 12', 'Toán học', 2, 'Chương trình toán học lớp 12 theo chuẩn Bộ Giáo dục', TRUE),
('Tiếng Anh giao tiếp', 'Ngoại ngữ', 3, 'Khóa học tiếng Anh giao tiếp cơ bản cho người mới bắt đầu', TRUE),
('Vật lý 11', 'Vật lý', 2, 'Chương trình vật lý lớp 11', FALSE);

-- Insert sample chapters
INSERT INTO chapters (course_id, title, position) VALUES
-- PHP Course
(1, 'Giới thiệu về PHP', 1),
(1, 'Cú pháp cơ bản', 2),
(1, 'Làm việc với Database', 3),
(1, 'Xây dựng ứng dụng web', 4),

-- Math Course
(2, 'Hàm số', 1),
(2, 'Phương trình và bất phương trình', 2),
(2, 'Hình học không gian', 3),

-- English Course
(3, 'Từ vựng cơ bản', 1),
(3, 'Ngữ pháp cơ bản', 2),
(3, 'Luyện nghe', 3),
(3, 'Luyện nói', 4);

-- Insert sample lessons
INSERT INTO lessons (chapter_id, title, content_html, video_url, position, duration_minutes) VALUES
-- PHP Course Lessons
(1, 'PHP là gì?', '<h2>Giới thiệu về PHP</h2><p>PHP (PHP: Hypertext Preprocessor) là một ngôn ngữ lập trình phổ biến, đặc biệt thích hợp cho việc phát triển web.</p><h3>Đặc điểm của PHP:</h3><ul><li>Mã nguồn mở</li><li>Dễ học và sử dụng</li><li>Hỗ trợ nhiều database</li><li>Chạy trên nhiều hệ điều hành</li></ul>', 'https://www.youtube.com/watch?v=example1', 1, 30),
(1, 'Cài đặt môi trường', '<h2>Cài đặt XAMPP</h2><p>XAMPP là một gói phần mềm miễn phí chứa Apache, MySQL, PHP và Perl.</p><h3>Các bước cài đặt:</h3><ol><li>Tải XAMPP từ trang chủ</li><li>Chạy file cài đặt</li><li>Khởi động Apache và MySQL</li><li>Kiểm tra hoạt động</li></ol>', 'https://www.youtube.com/watch?v=example2', 2, 25),

(2, 'Cú pháp cơ bản', '<h2>Cú pháp PHP cơ bản</h2><p>PHP sử dụng thẻ mở &lt;?php và thẻ đóng ?&gt;</p><pre><code>&lt;?php<br>echo "Hello World!";<br>?&gt;</code></pre>', 'https://www.youtube.com/watch?v=example3', 1, 20),
(2, 'Biến và kiểu dữ liệu', '<h2>Biến trong PHP</h2><p>Biến trong PHP bắt đầu bằng ký tự $</p><pre><code>&lt;?php<br>$name = "John";<br>$age = 25;<br>?&gt;</code></pre>', 'https://www.youtube.com/watch?v=example4', 2, 35),

-- Math Course Lessons
(5, 'Hàm số bậc nhất', '<h2>Hàm số bậc nhất</h2><p>Hàm số bậc nhất có dạng y = ax + b (a ≠ 0)</p><h3>Đặc điểm:</h3><ul><li>Đồ thị là đường thẳng</li><li>Hệ số góc: a</li><li>Giao điểm với trục Oy: (0, b)</li></ul>', 'https://www.youtube.com/watch?v=math1', 1, 40),
(5, 'Hàm số bậc hai', '<h2>Hàm số bậc hai</h2><p>Hàm số bậc hai có dạng y = ax² + bx + c (a ≠ 0)</p><h3>Đặc điểm:</h3><ul><li>Đồ thị là parabol</li><li>Đỉnh: I(-b/2a, -Δ/4a)</li><li>Trục đối xứng: x = -b/2a</li></ul>', 'https://www.youtube.com/watch?v=math2', 2, 45),

-- English Course Lessons
(8, 'Từ vựng gia đình', '<h2>Family Vocabulary</h2><p>Học từ vựng về gia đình</p><ul><li>Father - Bố</li><li>Mother - Mẹ</li><li>Brother - Anh/em trai</li><li>Sister - Chị/em gái</li><li>Grandfather - Ông</li><li>Grandmother - Bà</li></ul>', 'https://www.youtube.com/watch?v=english1', 1, 30),
(8, 'Từ vựng màu sắc', '<h2>Color Vocabulary</h2><p>Học từ vựng về màu sắc</p><ul><li>Red - Đỏ</li><li>Blue - Xanh dương</li><li>Green - Xanh lá</li><li>Yellow - Vàng</li><li>Black - Đen</li><li>White - Trắng</li></ul>', 'https://www.youtube.com/watch?v=english2', 2, 25);

-- Insert sample enrollments
INSERT INTO enrollments (user_id, course_id, progress_percent, last_view_at) VALUES
(4, 1, 25.50, NOW()),
(4, 2, 60.00, NOW()),
(5, 1, 15.25, NOW()),
(5, 3, 40.00, NOW()),
(6, 1, 0.00, NULL),
(6, 2, 80.00, NOW()),
(6, 3, 10.00, NOW());

-- Insert sample assignments
INSERT INTO assignments (course_id, title, description, due_at, max_score) VALUES
(1, 'Bài tập PHP cơ bản', 'Viết chương trình PHP tính tổng hai số', DATE_ADD(NOW(), INTERVAL 7 DAY), 100),
(1, 'Dự án website đơn giản', 'Tạo website tĩnh với HTML/CSS', DATE_ADD(NOW(), INTERVAL 14 DAY), 150),
(2, 'Bài tập hàm số', 'Giải các bài tập về hàm số bậc nhất và bậc hai', DATE_ADD(NOW(), INTERVAL 5 DAY), 100),
(3, 'Bài tập từ vựng', 'Viết 10 câu sử dụng từ vựng đã học', DATE_ADD(NOW(), INTERVAL 3 DAY), 50);

-- Insert sample submissions
INSERT INTO submissions (assignment_id, student_id, file_path, note, score, feedback, graded_at) VALUES
(1, 4, '/uploads/submissions/php_basic_1.pdf', 'Em đã hoàn thành bài tập', 85.5, 'Làm tốt, cần chú ý thêm về cú pháp', NOW()),
(1, 5, '/uploads/submissions/php_basic_2.pdf', 'Bài tập của em', 92.0, 'Xuất sắc!', NOW()),
(3, 4, '/uploads/submissions/math_1.pdf', 'Em nộp bài tập toán', 78.0, 'Cần kiểm tra lại phép tính', NOW()),
(4, 5, '/uploads/submissions/english_1.pdf', 'Bài tập tiếng Anh', 88.0, 'Tốt, phát âm cần cải thiện', NOW());

-- Insert sample quiz
INSERT INTO quizzes (lesson_id, title, time_limit_minutes, passing_score) VALUES
(1, 'Quiz kiến thức PHP cơ bản', 15, 60),
(5, 'Quiz hàm số bậc nhất', 20, 70),
(8, 'Quiz từ vựng gia đình', 10, 80);

-- Insert sample questions for PHP quiz
INSERT INTO questions (quiz_id, text, question_type, points, position) VALUES
(1, 'PHP là viết tắt của gì?', 'single', 1, 1),
(1, 'Thẻ mở của PHP là gì?', 'single', 1, 2),
(1, 'Biến trong PHP bắt đầu bằng ký tự nào?', 'single', 1, 3),
(1, 'PHP có thể chạy trên những hệ điều hành nào?', 'multiple', 2, 4);

-- Insert options for questions
INSERT INTO options (question_id, text, is_correct, position) VALUES
-- Question 1 options
(1, 'Personal Home Page', TRUE, 1),
(1, 'PHP: Hypertext Preprocessor', TRUE, 2),
(1, 'Preprocessed HTML Page', FALSE, 3),
(1, 'Private HTML Processor', FALSE, 4),

-- Question 2 options
(2, '&lt;?php', TRUE, 1),
(2, '&lt;php&gt;', FALSE, 2),
(2, '&lt;?', FALSE, 3),
(2, '&lt;script&gt;', FALSE, 4),

-- Question 3 options
(3, '$', TRUE, 1),
(3, '@', FALSE, 2),
(3, '#', FALSE, 3),
(3, '&', FALSE, 4),

-- Question 4 options
(4, 'Windows', TRUE, 1),
(4, 'Linux', TRUE, 2),
(4, 'macOS', TRUE, 3),
(4, 'DOS', FALSE, 4);

-- Insert sample forum threads
INSERT INTO forum_threads (course_id, lesson_id, author_id, title, is_pinned) VALUES
(1, 1, 4, 'Câu hỏi về cài đặt XAMPP', FALSE),
(1, 2, 5, 'Lỗi khi chạy code PHP', FALSE),
(2, 5, 4, 'Bài tập hàm số khó quá', FALSE),
(3, 8, 6, 'Cách phát âm từ "family"', FALSE);

-- Insert sample forum posts
INSERT INTO forum_posts (thread_id, author_id, content) VALUES
(1, 4, 'Em không biết cài XAMPP như thế nào, thầy có thể hướng dẫn chi tiết không ạ?'),
(1, 2, 'Em có thể xem video hướng dẫn trong bài học, hoặc thầy sẽ làm video chi tiết hơn.'),
(2, 5, 'Em gặp lỗi "Parse error" khi chạy code, không biết tại sao.'),
(2, 2, 'Lỗi Parse error thường do cú pháp sai, em kiểm tra lại dấu ngoặc và dấu chấm phẩy nhé.'),
(3, 4, 'Bài tập này em không hiểu cách làm, thầy giải thích giúp em với.'),
(4, 6, 'Từ "family" phát âm như thế nào ạ? Em nghe không rõ.');

-- Insert sample notifications
INSERT INTO notifications (user_id, type, title, message, payload_json) VALUES
(4, 'assignment_graded', 'Bài tập đã được chấm điểm', 'Bài tập "PHP cơ bản" của bạn đã được chấm điểm: 85.5/100', '{"assignment_id": 1, "score": 85.5}'),
(5, 'assignment_graded', 'Bài tập đã được chấm điểm', 'Bài tập "PHP cơ bản" của bạn đã được chấm điểm: 92.0/100', '{"assignment_id": 1, "score": 92.0}'),
(4, 'new_lesson', 'Bài học mới', 'Có bài học mới: "Cú pháp cơ bản" trong khóa "Lập trình Web với PHP"', '{"course_id": 1, "lesson_id": 3}'),
(6, 'forum_reply', 'Có phản hồi mới', 'Có phản hồi mới trong chủ đề "Cách phát âm từ family"', '{"thread_id": 4}');