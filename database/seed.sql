-- ============================================
-- E-LEARNING PLATFORM - SAMPLE DATA
-- Dữ liệu mẫu cho testing
-- ============================================

USE elearning_db;

-- ============================================
-- DEFAULT ADMIN & USERS
-- ============================================

-- Admin account (email: admin@elearning.com, password: Admin@123)
INSERT INTO users (email, password, full_name, role, status, email_verified) VALUES
('admin@elearning.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Quản Trị Viên', 'admin', 'active', 1),
('gv.nguyen@school.edu.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nguyễn Văn Giáo', 'teacher', 'active', 1),
('gv.tran@school.edu.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Trần Thị Lan', 'teacher', 'active', 1),
('hs.an@school.edu.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Lê Minh An', 'student', 'active', 1),
('hs.binh@school.edu.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Phạm Thu Bình', 'student', 'active', 1),
('hs.cuong@school.edu.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Hoàng Văn Cường', 'student', 'active', 1);

-- Teacher profiles
INSERT INTO teacher_profiles (user_id, teacher_code, specialization, bio) VALUES
(2, 'GV001', 'Toán học', 'Giáo viên Toán với 10 năm kinh nghiệm giảng dạy'),
(3, 'GV002', 'Tiếng Anh', 'Giáo viên Tiếng Anh, Thạc sỹ Ngôn ngữ Anh');

-- Student profiles
INSERT INTO student_profiles (user_id, student_code, date_of_birth, gender, total_xp, level) VALUES
(4, 'HS001', '2010-05-15', 'male', 500, 3),
(5, 'HS002', '2010-08-22', 'female', 800, 4),
(6, 'HS003', '2010-03-10', 'male', 300, 2);

-- ============================================
-- CLASSES & SUBJECTS
-- ============================================

-- Subjects
INSERT INTO subjects (name, code, description, color, icon) VALUES
('Toán học', 'MATH', 'Môn Toán học cơ bản và nâng cao', '#10B981', 'calculator'),
('Tiếng Anh', 'ENG', 'Ngôn ngữ Tiếng Anh giao tiếp và học thuật', '#3B82F6', 'language'),
('Vật lý', 'PHY', 'Vật lý đại cương', '#8B5CF6', 'atom'),
('Hóa học', 'CHEM', 'Hóa học cơ bản', '#F59E0B', 'flask'),
('Tin học', 'IT', 'Tin học và Công nghệ thông tin', '#EF4444', 'computer');

-- Classes
INSERT INTO classes (name, code, grade_level, academic_year, teacher_id, description, status) VALUES
('Lớp 10A1', '10A1', 10, '2024-2025', 2, 'Lớp chuyên Toán', 'active'),
('Lớp 10A2', '10A2', 10, '2024-2025', 3, 'Lớp chuyên Anh', 'active'),
('Lớp 11A1', '11A1', 11, '2024-2025', NULL, 'Lớp khối 11', 'active');

-- ============================================
-- COURSES
-- ============================================

-- Courses
INSERT INTO courses (title, slug, description, subject_id, teacher_id, class_id, level, duration_hours, is_published, status) VALUES
('Toán học lớp 10 - Đại số', 'toan-hoc-lop-10-dai-so', 'Khóa học Đại số cơ bản dành cho học sinh lớp 10', 1, 2, 1, 'beginner', 40, 1, 'published'),
('Tiếng Anh giao tiếp cơ bản', 'tieng-anh-giao-tiep-co-ban', 'Khóa học Tiếng Anh giao tiếp cho người mới bắt đầu', 2, 3, 2, 'beginner', 30, 1, 'published'),
('Lập trình Python căn bản', 'lap-trinh-python-can-ban', 'Học lập trình Python từ cơ bản đến nâng cao', 5, 2, NULL, 'beginner', 50, 1, 'published');

-- Lessons
INSERT INTO lessons (course_id, title, slug, content, lesson_type, order_index, is_published) VALUES
(1, 'Chương 1: Mệnh đề và tập hợp', 'chuong-1-menh-de-va-tap-hop', '<h2>Mệnh đề</h2><p>Mệnh đề là một câu khẳng định đúng hoặc sai...</p>', 'text', 1, 1),
(1, 'Bài tập chương 1', 'bai-tap-chuong-1', '<p>Làm các bài tập sau...</p>', 'text', 2, 1),
(2, 'Lesson 1: Greetings', 'lesson-1-greetings', '<h2>How to greet people</h2><p>Hello, Hi, Good morning...</p>', 'text', 1, 1),
(2, 'Lesson 2: Introduce yourself', 'lesson-2-introduce-yourself', '<h2>Self Introduction</h2><p>My name is... I am from...</p>', 'text', 2, 1),
(3, 'Bài 1: Giới thiệu Python', 'bai-1-gioi-thieu-python', '<h2>Python là gì?</h2><p>Python là ngôn ngữ lập trình...</p>', 'text', 1, 1);

-- Enrollments
INSERT INTO enrollments (course_id, student_id, progress, status) VALUES
(1, 4, 45.50, 'active'),
(1, 5, 78.20, 'active'),
(2, 4, 30.00, 'active'),
(2, 6, 55.80, 'active'),
(3, 5, 90.00, 'active');

-- ============================================
-- QUIZZES
-- ============================================

-- Quiz
INSERT INTO quizzes (course_id, title, description, quiz_type, time_limit, passing_score, max_attempts, is_published) VALUES
(1, 'Kiểm tra chương 1', 'Kiểm tra kiến thức chương mệnh đề và tập hợp', 'graded', 30, 60.00, 2, 1),
(2, 'English Quiz - Lesson 1 & 2', 'Test your knowledge about greetings and introductions', 'practice', 15, 70.00, 0, 1);

-- Questions
INSERT INTO quiz_questions (quiz_id, question_text, question_type, points, order_index) VALUES
(1, 'Cho tập hợp A = {1, 2, 3, 4, 5}. Số phần tử của tập A là?', 'multiple_choice', 2.00, 1),
(1, 'Mệnh đề phủ định của "Mọi số tự nhiên đều lớn hơn 0" là gì?', 'multiple_choice', 2.00, 2),
(2, 'What is the correct response to "How are you?"', 'multiple_choice', 1.00, 1),
(2, '"Nice to meet you" có nghĩa là gì?', 'multiple_choice', 1.00, 2);

-- Answers
INSERT INTO quiz_answers (question_id, answer_text, is_correct, order_index) VALUES
-- Question 1
(1, '4', 0, 1),
(1, '5', 1, 2),
(1, '6', 0, 3),
(1, 'Vô số', 0, 4),
-- Question 2
(2, 'Tồn tại số tự nhiên không lớn hơn 0', 1, 1),
(2, 'Mọi số tự nhiên đều nhỏ hơn 0', 0, 2),
(2, 'Không có số tự nhiên nào lớn hơn 0', 0, 3),
-- Question 3
(3, 'I am fine, thank you', 1, 1),
(3, 'My name is John', 0, 2),
(3, 'I am student', 0, 3),
-- Question 4
(4, 'Rất vui được gặp bạn', 1, 1),
(4, 'Tạm biệt', 0, 2),
(4, 'Chào buổi sáng', 0, 3);

-- ============================================
-- GAMIFICATION
-- ============================================

-- Badges
INSERT INTO badges (name, description, icon, color, xp_required, type) VALUES
('Người mới', 'Hoàn thành khóa học đầu tiên', '🎖️', '#10B981', 0, 'milestone'),
('Học sinh chăm chỉ', 'Hoàn thành 5 khóa học', '⭐', '#F59E0B', 500, 'achievement'),
('Bậc thầy Quiz', 'Đạt điểm tối đa trong 10 bài quiz', '🏆', '#EF4444', 1000, 'achievement'),
('Siêu sao', 'Đạt top 1 bảng xếp hạng', '👑', '#FFD700', 2000, 'special');

-- User badges
INSERT INTO user_badges (user_id, badge_id) VALUES
(4, 1),
(5, 1),
(5, 2);

-- XP transactions
INSERT INTO xp_transactions (user_id, amount, reason, reference_type, reference_id) VALUES
(4, 100, 'Hoàn thành bài học', 'lesson', 1),
(4, 50, 'Tham gia khóa học', 'course', 1),
(5, 200, 'Hoàn thành quiz với điểm cao', 'quiz', 1),
(5, 150, 'Hoàn thành khóa học', 'course', 2);

-- ============================================
-- NOTIFICATIONS
-- ============================================

INSERT INTO notifications (user_id, title, message, type, link) VALUES
(4, 'Khóa học mới', 'Có khóa học mới: Lập trình Python căn bản', 'info', '/courses/3'),
(5, 'Bài kiểm tra mới', 'Giáo viên đã tạo bài kiểm tra mới cho khóa Toán học', 'warning', '/quizzes/1'),
(6, 'Chúc mừng!', 'Bạn đã nhận được huy hiệu "Người mới"', 'success', '/profile/badges');

-- ============================================
-- SETTINGS
-- ============================================

INSERT INTO settings (setting_key, setting_value, setting_type, description) VALUES
('site_name', 'E-Learning Platform', 'text', 'Tên website'),
('site_logo', '/assets/images/logo.png', 'text', 'Logo website'),
('site_description', 'Hệ thống học tập trực tuyến hiện đại', 'text', 'Mô tả website'),
('academic_year', '2024-2025', 'text', 'Năm học hiện tại'),
('timezone', 'Asia/Ho_Chi_Minh', 'text', 'Múi giờ'),
('language', 'vi', 'text', 'Ngôn ngữ mặc định'),
('allow_registration', '1', 'boolean', 'Cho phép đăng ký tài khoản'),
('require_email_verification', '0', 'boolean', 'Yêu cầu xác thực email'),
('theme_mode', 'light', 'text', 'Chế độ giao diện (light/dark)'),
('items_per_page', '10', 'number', 'Số item mỗi trang');

-- ============================================
-- END OF SEED DATA
-- ============================================
