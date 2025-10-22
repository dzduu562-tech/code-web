-- E-Learning Platform Seed Data
USE elearning_db;

-- Insert sample users
INSERT INTO users (name, email, password_hash, role) VALUES
('Admin User', 'admin@elearning.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Nguyễn Văn Giáo', 'teacher@elearning.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher'),
('Trần Thị Học', 'student1@elearning.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('Lê Văn Sinh', 'student2@elearning.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student');

-- Insert sample courses
INSERT INTO courses (title, subject, teacher_id, description, is_published) VALUES
('Lập trình Web cơ bản', 'Công nghệ thông tin', 2, 'Khóa học lập trình web từ cơ bản đến nâng cao với HTML, CSS, JavaScript và PHP', TRUE),
('Toán học lớp 12', 'Toán học', 2, 'Khóa học toán học lớp 12 bao gồm đại số, hình học và giải tích', TRUE),
('Tiếng Anh giao tiếp', 'Ngoại ngữ', 2, 'Khóa học tiếng Anh giao tiếp cơ bản cho người mới bắt đầu', FALSE);

-- Insert sample chapters
INSERT INTO chapters (course_id, title, position) VALUES
-- Web programming course chapters
(1, 'Giới thiệu về Web Development', 1),
(1, 'HTML cơ bản', 2),
(1, 'CSS và Styling', 3),
(1, 'JavaScript cơ bản', 4),
(1, 'PHP và Backend', 5),

-- Math course chapters
(2, 'Hàm số và đồ thị', 1),
(2, 'Phương trình và bất phương trình', 2),
(2, 'Lượng giác', 3),
(2, 'Hình học không gian', 4),

-- English course chapters
(3, 'Từ vựng cơ bản', 1),
(3, 'Ngữ pháp cơ bản', 2),
(3, 'Kỹ năng nghe', 3),
(3, 'Kỹ năng nói', 4);

-- Insert sample lessons
INSERT INTO lessons (chapter_id, title, content_html, video_url, position, is_published) VALUES
-- Web programming lessons
(1, 'Tổng quan về Web Development', '<h2>Giới thiệu</h2><p>Web development là quá trình xây dựng và duy trì các website...</p>', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 1, TRUE),
(1, 'Công cụ cần thiết', '<h2>Công cụ</h2><p>Để bắt đầu học lập trình web, bạn cần chuẩn bị...</p>', NULL, 2, TRUE),
(2, 'Cấu trúc HTML cơ bản', '<h2>HTML Structure</h2><p>HTML sử dụng các thẻ để định nghĩa cấu trúc...</p>', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 1, TRUE),
(2, 'Các thẻ HTML quan trọng', '<h2>HTML Tags</h2><p>Một số thẻ HTML quan trọng mà bạn cần biết...</p>', NULL, 2, TRUE),
(3, 'CSS Selectors', '<h2>CSS Selectors</h2><p>CSS selectors giúp bạn chọn các phần tử để style...</p>', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 1, TRUE),
(3, 'Flexbox và Grid', '<h2>Layout</h2><p>Flexbox và CSS Grid là hai công cụ mạnh mẽ để tạo layout...</p>', NULL, 2, TRUE),

-- Math lessons
(6, 'Hàm bậc nhất', '<h2>Hàm bậc nhất</h2><p>Hàm bậc nhất có dạng y = ax + b...</p>', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 1, TRUE),
(6, 'Hàm bậc hai', '<h2>Hàm bậc hai</h2><p>Hàm bậc hai có dạng y = ax² + bx + c...</p>', NULL, 2, TRUE),
(7, 'Phương trình bậc nhất', '<h2>Phương trình bậc nhất</h2><p>Phương trình bậc nhất có dạng ax + b = 0...</p>', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 1, TRUE),

-- English lessons
(10, 'Từ vựng gia đình', '<h2>Family Vocabulary</h2><p>Học từ vựng về gia đình...</p>', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 1, TRUE),
(10, 'Từ vựng màu sắc', '<h2>Colors</h2><p>Học từ vựng về màu sắc...</p>', NULL, 2, TRUE);

-- Insert sample enrollments
INSERT INTO enrollments (user_id, course_id, progress_percent) VALUES
(3, 1, 25.50),
(3, 2, 10.00),
(4, 1, 15.25),
(4, 2, 5.00);

-- Insert sample assignments
INSERT INTO assignments (course_id, title, description, due_at, max_score) VALUES
(1, 'Tạo trang web đầu tiên', 'Tạo một trang web đơn giản sử dụng HTML và CSS', DATE_ADD(NOW(), INTERVAL 7 DAY), 100.00),
(1, 'Bài tập JavaScript', 'Viết các hàm JavaScript cơ bản', DATE_ADD(NOW(), INTERVAL 14 DAY), 100.00),
(2, 'Giải phương trình', 'Giải các phương trình bậc nhất và bậc hai', DATE_ADD(NOW(), INTERVAL 10 DAY), 100.00);

-- Insert sample quizzes
INSERT INTO quizzes (lesson_id, title, description, time_limit) VALUES
(1, 'Quiz về Web Development', 'Kiểm tra kiến thức cơ bản về web development', 30),
(3, 'Quiz HTML cơ bản', 'Kiểm tra kiến thức về HTML', 20),
(7, 'Quiz hàm số', 'Kiểm tra kiến thức về hàm số', 25);

-- Insert sample questions for quiz 1
INSERT INTO questions (quiz_id, text, points, position) VALUES
(1, 'HTML là viết tắt của gì?', 1.00, 1),
(1, 'Thẻ nào được sử dụng để tạo tiêu đề chính?', 1.00, 2),
(1, 'CSS được sử dụng để làm gì?', 1.00, 3);

-- Insert options for quiz 1 questions
INSERT INTO options (question_id, text, is_correct, position) VALUES
-- Question 1 options
(1, 'HyperText Markup Language', TRUE, 1),
(1, 'Home Tool Markup Language', FALSE, 2),
(1, 'Hyperlinks and Text Markup Language', FALSE, 3),
(1, 'HyperText Markup Links', FALSE, 4),

-- Question 2 options
(2, '<h1>', TRUE, 1),
(2, '<head>', FALSE, 2),
(2, '<title>', FALSE, 3),
(2, '<header>', FALSE, 4),

-- Question 3 options
(3, 'Tạo cấu trúc trang web', FALSE, 1),
(3, 'Tạo nội dung trang web', FALSE, 2),
(3, 'Tạo style và layout cho trang web', TRUE, 3),
(3, 'Tạo chức năng tương tác', FALSE, 4);

-- Insert sample forum threads
INSERT INTO forum_threads (course_id, lesson_id, author_id, title) VALUES
(1, 1, 3, 'Làm thế nào để bắt đầu học lập trình web?'),
(1, 3, 4, 'Thắc mắc về thẻ HTML'),
(2, 7, 3, 'Cách vẽ đồ thị hàm số'),
(1, NULL, 2, 'Thông báo: Bài tập mới đã được đăng');

-- Insert sample forum posts
INSERT INTO forum_posts (thread_id, author_id, content) VALUES
(1, 2, 'Bạn nên bắt đầu với HTML cơ bản, sau đó học CSS và JavaScript. Hãy thực hành nhiều để nắm vững kiến thức.'),
(1, 3, 'Cảm ơn thầy! Em sẽ cố gắng học theo hướng dẫn.'),
(2, 2, 'Bạn có thể hỏi cụ thể về thẻ nào không? Tôi sẽ giải thích chi tiết.'),
(2, 4, 'Em muốn hỏi về thẻ <div> và <span> khác nhau như thế nào ạ?'),
(3, 2, 'Để vẽ đồ thị hàm số, bạn cần xác định tập xác định, tính đạo hàm, tìm cực trị...'),
(4, 2, 'Các em hãy làm bài tập JavaScript trong tuần này. Hạn nộp là thứ 6.');

-- Insert sample notifications
INSERT INTO notifications (user_id, type, title, message, payload_json) VALUES
(3, 'assignment', 'Bài tập mới', 'Có bài tập mới trong khóa học "Lập trình Web cơ bản"', '{"course_id": 1, "assignment_id": 1}'),
(4, 'assignment', 'Bài tập mới', 'Có bài tập mới trong khóa học "Lập trình Web cơ bản"', '{"course_id": 1, "assignment_id": 1}'),
(3, 'forum', 'Trả lời trong diễn đàn', 'Thầy đã trả lời câu hỏi của bạn', '{"thread_id": 1, "post_id": 2}'),
(4, 'forum', 'Trả lời trong diễn đàn', 'Thầy đã trả lời câu hỏi của bạn', '{"thread_id": 2, "post_id": 3}');

-- Insert sample lesson progress
INSERT INTO lesson_progress (user_id, lesson_id, is_completed, completed_at) VALUES
(3, 1, TRUE, NOW()),
(3, 2, TRUE, NOW()),
(3, 3, FALSE, NULL),
(4, 1, TRUE, NOW()),
(4, 2, FALSE, NULL);