-- Seed data for E-Learning Platform
USE elearning_db;

-- Insert users (password is 'password123' for all users)
INSERT INTO users (name, email, password_hash, role) VALUES
('Admin System', 'admin@elearning.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Nguyễn Văn A', 'teacher1@elearning.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher'),
('Trần Thị B', 'teacher2@elearning.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher'),
('Lê Hoàng C', 'student1@elearning.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('Phạm Minh D', 'student2@elearning.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student');

-- Insert courses
INSERT INTO courses (title, subject, teacher_id, description) VALUES
('Lập trình Web cơ bản', 'Công nghệ thông tin', 2, 'Khóa học về HTML, CSS, JavaScript và PHP cơ bản cho người mới bắt đầu'),
('Cơ sở dữ liệu MySQL', 'Công nghệ thông tin', 2, 'Học cách thiết kế và quản lý cơ sở dữ liệu với MySQL'),
('Toán cao cấp A1', 'Toán học', 3, 'Giới hạn, đạo hàm, tích phân và ứng dụng'),
('Tiếng Anh giao tiếp', 'Ngoại ngữ', 3, 'Phát triển kỹ năng giao tiếp tiếng Anh trong cuộc sống hàng ngày');

-- Insert chapters for course 1 (Lập trình Web)
INSERT INTO chapters (course_id, title, position) VALUES
(1, 'Chương 1: HTML cơ bản', 1),
(1, 'Chương 2: CSS và Styling', 2),
(1, 'Chương 3: JavaScript căn bản', 3),
(1, 'Chương 4: PHP và MySQL', 4);

-- Insert chapters for course 2 (MySQL)
INSERT INTO chapters (course_id, title, position) VALUES
(2, 'Chương 1: Giới thiệu CSDL', 1),
(2, 'Chương 2: Thiết kế bảng', 2),
(2, 'Chương 3: Truy vấn SQL', 3);

-- Insert lessons for chapter 1
INSERT INTO lessons (chapter_id, title, content_html, video_url, position) VALUES
(1, 'Giới thiệu về HTML', '<h2>HTML là gì?</h2><p>HTML (HyperText Markup Language) là ngôn ngữ đánh dấu siêu văn bản, được sử dụng để tạo cấu trúc cho các trang web.</p><h3>Cấu trúc cơ bản</h3><pre>&lt;!DOCTYPE html&gt;\n&lt;html&gt;\n&lt;head&gt;\n  &lt;title&gt;Tiêu đề&lt;/title&gt;\n&lt;/head&gt;\n&lt;body&gt;\n  &lt;h1&gt;Nội dung&lt;/h1&gt;\n&lt;/body&gt;\n&lt;/html&gt;</pre>', 'https://www.youtube.com/embed/qz0aGYrrlhU', 1),
(1, 'Các thẻ HTML phổ biến', '<h2>Thẻ HTML thường dùng</h2><ul><li><strong>&lt;h1&gt; - &lt;h6&gt;</strong>: Tiêu đề</li><li><strong>&lt;p&gt;</strong>: Đoạn văn</li><li><strong>&lt;a&gt;</strong>: Liên kết</li><li><strong>&lt;img&gt;</strong>: Hình ảnh</li><li><strong>&lt;div&gt;</strong>: Container</li><li><strong>&lt;ul&gt;, &lt;ol&gt;, &lt;li&gt;</strong>: Danh sách</li></ul>', NULL, 2),
(1, 'Forms và Input', '<h2>Biểu mẫu HTML</h2><p>Form được sử dụng để thu thập dữ liệu từ người dùng.</p><pre>&lt;form action="/submit" method="post"&gt;\n  &lt;label&gt;Tên:&lt;/label&gt;\n  &lt;input type="text" name="name"&gt;\n  &lt;button type="submit"&gt;Gửi&lt;/button&gt;\n&lt;/form&gt;</pre>', NULL, 3);

-- Insert lessons for chapter 2
INSERT INTO lessons (chapter_id, title, content_html, position) VALUES
(2, 'CSS Selectors', '<h2>Bộ chọn CSS</h2><p>CSS Selectors cho phép chọn các phần tử HTML để áp dụng style.</p><ul><li><strong>Element selector</strong>: p { color: blue; }</li><li><strong>Class selector</strong>: .className { }</li><li><strong>ID selector</strong>: #idName { }</li></ul>', 1),
(2, 'Box Model và Layout', '<h2>Box Model</h2><p>Mọi phần tử HTML đều là một hộp bao gồm: content, padding, border, margin.</p><img src="https://via.placeholder.com/400x200?text=Box+Model" alt="Box Model" style="max-width:100%;">', 2);

-- Insert lessons for chapter 3
INSERT INTO lessons (chapter_id, title, content_html, position) VALUES
(3, 'Biến và kiểu dữ liệu', '<h2>JavaScript Variables</h2><p>Khai báo biến với let, const, var.</p><pre>let name = "John";\nconst PI = 3.14;\nvar age = 25;</pre>', 1),
(3, 'Hàm trong JavaScript', '<h2>Functions</h2><p>Hàm là khối mã có thể tái sử dụng.</p><pre>function greet(name) {\n  return "Hello, " + name;\n}\nconsole.log(greet("World"));</pre>', 2);

-- Insert enrollments
INSERT INTO enrollments (user_id, course_id, progress_percent) VALUES
(4, 1, 35.50),
(4, 2, 10.00),
(5, 1, 60.00),
(5, 3, 25.00);

-- Insert lesson progress
INSERT INTO lesson_progress (user_id, lesson_id, completed, completed_at) VALUES
(4, 1, TRUE, NOW()),
(4, 2, TRUE, NOW()),
(5, 1, TRUE, NOW()),
(5, 2, TRUE, NOW()),
(5, 3, TRUE, NOW());

-- Insert assignments
INSERT INTO assignments (course_id, title, description, due_at) VALUES
(1, 'Bài tập 1: Tạo trang web đơn giản', 'Tạo một trang web giới thiệu bản thân sử dụng HTML và CSS', DATE_ADD(NOW(), INTERVAL 7 DAY)),
(1, 'Bài tập 2: Form đăng ký', 'Tạo form đăng ký người dùng với validation', DATE_ADD(NOW(), INTERVAL 14 DAY)),
(2, 'Thiết kế CSDL', 'Thiết kế cơ sở dữ liệu cho một hệ thống quản lý thư viện', DATE_ADD(NOW(), INTERVAL 10 DAY));

-- Insert submissions
INSERT INTO submissions (assignment_id, student_id, note, score, feedback, graded_at) VALUES
(1, 4, 'Em đã hoàn thành bài tập theo yêu cầu', 8.5, 'Bài làm tốt! Cần cải thiện phần responsive.', NOW()),
(1, 5, 'Bài tập tuần 1', 9.0, 'Xuất sắc! Code rất clean.', NOW());

-- Insert quizzes
INSERT INTO quizzes (lesson_id, title, description) VALUES
(1, 'Kiểm tra HTML cơ bản', 'Bài kiểm tra kiến thức về HTML'),
(4, 'Quiz CSS Selectors', 'Kiểm tra hiểu biết về CSS Selectors');

-- Insert questions for quiz 1
INSERT INTO questions (quiz_id, text, position) VALUES
(1, 'HTML là viết tắt của gì?', 1),
(1, 'Thẻ nào dùng để tạo tiêu đề lớn nhất?', 2),
(1, 'Thẻ nào dùng để chèn hình ảnh?', 3);

-- Insert options for question 1
INSERT INTO options (question_id, text, is_correct) VALUES
(1, 'HyperText Markup Language', TRUE),
(1, 'High Text Markup Language', FALSE),
(1, 'Hyper Transfer Markup Language', FALSE),
(1, 'Home Tool Markup Language', FALSE);

-- Insert options for question 2
INSERT INTO options (question_id, text, is_correct) VALUES
(2, '<h1>', TRUE),
(2, '<h6>', FALSE),
(2, '<header>', FALSE),
(2, '<title>', FALSE);

-- Insert options for question 3
INSERT INTO options (question_id, text, is_correct) VALUES
(3, '<img>', TRUE),
(3, '<image>', FALSE),
(3, '<picture>', FALSE),
(3, '<src>', FALSE);

-- Insert questions for quiz 2
INSERT INTO questions (quiz_id, text, position) VALUES
(2, 'Selector nào chọn tất cả phần tử có class="container"?', 1),
(2, 'Thuộc tính nào của Box Model nằm ngoài cùng?', 2);

-- Insert options for quiz 2
INSERT INTO options (question_id, text, is_correct) VALUES
(4, '.container', TRUE),
(4, '#container', FALSE),
(4, 'container', FALSE),
(4, '*container', FALSE),
(5, 'margin', TRUE),
(5, 'padding', FALSE),
(5, 'border', FALSE),
(5, 'content', FALSE);

-- Insert quiz attempts
INSERT INTO quiz_attempts (quiz_id, user_id, score, total_questions, correct_answers, finished_at) VALUES
(1, 4, 66.67, 3, 2, NOW()),
(1, 5, 100.00, 3, 3, NOW());

-- Insert answers
INSERT INTO answers (attempt_id, question_id, option_id) VALUES
(1, 1, 1),
(1, 2, 1),
(1, 3, 2),
(2, 1, 1),
(2, 2, 1),
(2, 3, 1);

-- Insert forum threads
INSERT INTO forum_threads (course_id, lesson_id, author_id, title) VALUES
(1, 1, 4, 'Câu hỏi về cấu trúc HTML'),
(1, 2, 5, 'Làm thế nào để tạo table trong HTML?'),
(2, NULL, 4, 'Hỏi về thiết kế database');

-- Insert forum posts
INSERT INTO forum_posts (thread_id, author_id, content) VALUES
(1, 4, 'Em chưa hiểu rõ về thẻ <head> và <body>, thầy có thể giải thích thêm không ạ?'),
(1, 2, 'Thẻ <head> chứa metadata và thông tin về trang web (title, css, js...), còn <body> chứa nội dung hiển thị trên trang.'),
(2, 5, 'Để tạo bảng trong HTML, bạn sử dụng thẻ <table>, <tr> (table row), <td> (table data).'),
(2, 2, 'Đúng rồi, và nên thêm <thead>, <tbody> để cấu trúc rõ ràng hơn.'),
(3, 4, 'Khi thiết kế database cho hệ thống quản lý học sinh, em nên tạo bao nhiêu bảng?'),
(3, 2, 'Tùy vào yêu cầu, nhưng cơ bản nên có: bảng sinh viên, bảng khóa học, bảng đăng ký, bảng điểm...');

-- Insert notifications
INSERT INTO notifications (user_id, type, title, message, link, is_read) VALUES
(4, 'assignment_graded', 'Bài tập đã được chấm điểm', 'Bài tập "Tạo trang web đơn giản" đã được chấm: 8.5/10', 'submissions', FALSE),
(5, 'assignment_graded', 'Bài tập đã được chấm điểm', 'Bài tập "Tạo trang web đơn giản" đã được chấm: 9.0/10', 'submissions', TRUE),
(4, 'new_lesson', 'Bài học mới', 'Bài học "Forms và Input" vừa được thêm vào khóa học', 'lesson&id=3', FALSE),
(5, 'forum_reply', 'Có phản hồi mới', 'Giáo viên đã trả lời câu hỏi của bạn', 'forum/thread&id=1', FALSE);
