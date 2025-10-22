-- Seed data for E-Learning
INSERT INTO users(name,email,password_hash,role) VALUES
 ('Admin','admin@example.com','$2y$10$FfMPps/7jxV6Fq5JcqXKged6GE1XrK0SU.Uz3spMmqmMq96Qy4ms6','admin'),
 ('Thầy A','teacher@example.com','$2y$10$z1N3n0dnd2P4bWg8G2iea.Hm5Y/4sEUhSda2BfW3JZQ9g4r2W0G8W','teacher'),
 ('Học sinh 1','student1@example.com','$2y$10$Yl1wbyk6iI1qRkL3nZ0vzuZr5tXG2vQ8Gz8j6Ylnq9S02r8hWkq7i','student'),
 ('Học sinh 2','student2@example.com','$2y$10$Yl1wbyk6iI1qRkL3nZ0vzuZr5tXG2vQ8Gz8j6Ylnq9S02r8hWkq7i','student');

-- Create a sample course by teacher
INSERT INTO courses(title,subject,teacher_id,description) VALUES
 ('Toán 10 - Đại số cơ bản','Toán', 2, 'Khóa học đại số cơ bản cho lớp 10');

-- Chapters
INSERT INTO chapters(course_id,title,position) VALUES
 (1,'Đại số - Phần 1',1),
 (1,'Đại số - Phần 2',2);

-- Lessons
INSERT INTO lessons(chapter_id,title,content_html,video_url,position) VALUES
 (1,'Biểu thức đại số','<p>Giới thiệu biểu thức đại số.</p>','https://www.youtube.com/embed/dQw4w9WgXcQ',1),
 (1,'Phép biến đổi','<p>Phép biến đổi cơ bản.</p>',NULL,2),
 (2,'Hàm số bậc nhất','<p>Khái niệm hàm số bậc nhất.</p>',NULL,1);

-- Enroll students
INSERT INTO enrollments(user_id, course_id, progress_percent) VALUES
 (3,1,0),(4,1,0);

-- Resources
INSERT INTO resources(lesson_id,file_path,file_name) VALUES
 (1,'uploads/resources/sample.pdf','Tài liệu mẫu.pdf');

-- Assignment
INSERT INTO assignments(course_id,title,description,due_at) VALUES
 (1,'BTVN 1','Làm bài trong file và nộp lại', DATE_ADD(NOW(), INTERVAL 7 DAY));

-- Quiz
INSERT INTO quizzes(lesson_id,title) VALUES (1,'Quiz nhanh - Biểu thức đại số');
INSERT INTO questions(quiz_id, text) VALUES
 (1,'Biểu thức nào là đa thức bậc nhất?');
INSERT INTO options(question_id,text,is_correct) VALUES
 (1,'2x + 3',1),
 (1,'x^2 + 1',0),
 (1,'1/x',0);

-- Forum sample
INSERT INTO forum_threads(course_id,lesson_id,author_id,title) VALUES (1,1,3,'Câu hỏi về bài 1');
INSERT INTO forum_posts(thread_id,author_id,content) VALUES (1,3,'Em chưa hiểu biểu thức đại số là gì?');
