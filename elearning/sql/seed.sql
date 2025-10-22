-- Seed data with admin, teacher, students and sample course
-- All sample accounts use password: 'password' (bcrypt below)
INSERT INTO users(name,email,password_hash,role,created_at) VALUES
('Admin','admin@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin',NOW()),
('Giáo viên A','teacher@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','teacher',NOW()),
('Học sinh 1','student1@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','student',NOW()),
('Học sinh 2','student2@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','student',NOW());

INSERT INTO courses(title,subject,teacher_id,description,created_at) VALUES
('Toán 9 - Hình học cơ bản','Toán',2,'Khóa học hình học cơ bản cho lớp 9',NOW());

INSERT INTO chapters(course_id,title,position) VALUES
(1,'Chương 1: Đường thẳng và góc',1),
(1,'Chương 2: Tam giác',2);

INSERT INTO lessons(chapter_id,title,content_html,video_url,position) VALUES
(1,'Bài 1: Khái niệm đường thẳng','<p>Đường thẳng là ...</p>','https://www.youtube.com/embed/dQw4w9WgXcQ',1),
(2,'Bài 2: Tam giác và tính chất','<p>Tam giác là ...</p>',NULL,1);

INSERT INTO resources(lesson_id,file_path,file_name) VALUES
(1,'sample.pdf','Tài liệu mẫu');

INSERT INTO enrollments(user_id,course_id,progress_percent,last_view_at) VALUES
(3,1,0,NOW()),(4,1,0,NOW());

INSERT INTO assignments(course_id,title,description,due_at) VALUES
(1,'BTVN 1','Giải các bài tập trang 12',DATE_ADD(NOW(), INTERVAL 7 DAY));

INSERT INTO quizzes(lesson_id,title) VALUES (1,'Quiz bài 1');
INSERT INTO questions(quiz_id,text) VALUES (1,'Đường thẳng là gì?');
INSERT INTO options(question_id,text,is_correct) VALUES
(1,'Một hình có ba cạnh',0),
(1,'Tập hợp các điểm theo một quy tắc',1),
(1,'Một đoạn thẳng có độ dài',0);

INSERT INTO forum_threads(course_id,lesson_id,author_id,title,created_at) VALUES
(1,1,3,'Em chưa hiểu phần định nghĩa',NOW());
INSERT INTO forum_posts(thread_id,author_id,content,created_at) VALUES
(1,3,'Thầy giải thích lại giúp em ạ',NOW());

INSERT INTO notifications(user_id,type,payload_json,is_read,created_at) VALUES
(2,'course_enrolled','{"course_id":1,"user_id":3}',0,NOW());
