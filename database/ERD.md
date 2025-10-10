# 🗄️ DATABASE SCHEMA - E-LEARNING PLATFORM

## Tổng quan

Database gồm **22 bảng** được chia thành 8 nhóm chức năng chính:

1. **Users & Authentication** - Quản lý người dùng
2. **Academic Structure** - Cấu trúc học tập (lớp, môn)
3. **Courses & Content** - Khóa học và nội dung
4. **Assessments** - Quiz và kiểm tra
5. **Assignments** - Bài tập
6. **Communication** - Tương tác & giao tiếp
7. **Gamification** - Hệ thống động lực học tập
8. **System** - Hệ thống và cấu hình

---

## 📊 Entity Relationship Diagram

### 1. USERS & AUTHENTICATION

#### `users` - Bảng người dùng chính
```
users
├── id (PK)
├── email (UNIQUE)
├── password
├── full_name
├── avatar
├── phone
├── address
├── role (ENUM: admin, teacher, student, parent)
├── status (ENUM: active, inactive, suspended)
├── email_verified
├── verification_token
├── reset_token
├── reset_expires
├── last_login
├── created_at
└── updated_at
```

#### `student_profiles` - Hồ sơ học sinh
```
student_profiles
├── id (PK)
├── user_id (FK → users.id)
├── student_code (UNIQUE)
├── date_of_birth
├── gender
├── parent_id (FK → users.id)
├── class_id (FK → classes.id)
├── enrollment_date
├── total_xp
├── level
├── created_at
└── updated_at
```

#### `teacher_profiles` - Hồ sơ giáo viên
```
teacher_profiles
├── id (PK)
├── user_id (FK → users.id)
├── teacher_code (UNIQUE)
├── specialization
├── bio
├── qualifications
├── hire_date
├── created_at
└── updated_at
```

---

### 2. ACADEMIC STRUCTURE

#### `classes` - Lớp học
```
classes
├── id (PK)
├── name
├── code (UNIQUE)
├── grade_level
├── academic_year
├── teacher_id (FK → users.id)
├── description
├── max_students
├── status
├── created_at
└── updated_at
```

#### `subjects` - Môn học
```
subjects
├── id (PK)
├── name
├── code (UNIQUE)
├── description
├── color
├── icon
├── status
├── created_at
└── updated_at
```

---

### 3. COURSES & CONTENT

#### `courses` - Khóa học
```
courses
├── id (PK)
├── title
├── slug (UNIQUE)
├── description
├── thumbnail
├── subject_id (FK → subjects.id)
├── teacher_id (FK → users.id)
├── class_id (FK → classes.id)
├── level (ENUM: beginner, intermediate, advanced)
├── duration_hours
├── price
├── is_featured
├── is_published
├── max_students
├── start_date
├── end_date
├── views_count
├── enrollment_count
├── status
├── created_at
└── updated_at
```

#### `enrollments` - Đăng ký khóa học
```
enrollments
├── id (PK)
├── course_id (FK → courses.id)
├── student_id (FK → users.id)
├── enrolled_at
├── completed_at
├── progress (%)
├── status (ENUM: active, completed, dropped)
├── final_grade
├── certificate_issued
└── last_accessed
```

#### `lessons` - Bài giảng
```
lessons
├── id (PK)
├── course_id (FK → courses.id)
├── title
├── slug
├── content (LONGTEXT)
├── lesson_type (ENUM: video, text, pdf, quiz, assignment, link)
├── video_url
├── video_duration
├── file_path
├── external_link
├── order_index
├── is_preview
├── is_published
├── duration_minutes
├── created_at
└── updated_at
```

#### `lesson_progress` - Tiến độ bài giảng
```
lesson_progress
├── id (PK)
├── lesson_id (FK → lessons.id)
├── student_id (FK → users.id)
├── completed
├── time_spent
├── last_position
├── completed_at
├── created_at
└── updated_at
```

---

### 4. ASSESSMENTS (QUIZ)

#### `quizzes` - Bài kiểm tra
```
quizzes
├── id (PK)
├── course_id (FK → courses.id)
├── lesson_id (FK → lessons.id)
├── title
├── description
├── quiz_type (ENUM: practice, graded, exam)
├── time_limit
├── passing_score
├── max_attempts
├── shuffle_questions
├── shuffle_answers
├── show_correct_answers
├── is_published
├── available_from
├── available_until
├── created_at
└── updated_at
```

#### `quiz_questions` - Câu hỏi
```
quiz_questions
├── id (PK)
├── quiz_id (FK → quizzes.id)
├── question_text
├── question_type (ENUM: multiple_choice, true_false, short_answer, essay)
├── points
├── order_index
├── explanation
├── created_at
└── updated_at
```

#### `quiz_answers` - Đáp án
```
quiz_answers
├── id (PK)
├── question_id (FK → quiz_questions.id)
├── answer_text
├── is_correct
├── order_index
└── created_at
```

#### `quiz_attempts` - Lần làm bài
```
quiz_attempts
├── id (PK)
├── quiz_id (FK → quizzes.id)
├── student_id (FK → users.id)
├── started_at
├── submitted_at
├── score
├── total_points
├── passed
├── time_taken
├── attempt_number
├── status (ENUM: in_progress, completed, abandoned)
└── created_at
```

#### `quiz_student_answers` - Câu trả lời của học sinh
```
quiz_student_answers
├── id (PK)
├── attempt_id (FK → quiz_attempts.id)
├── question_id (FK → quiz_questions.id)
├── answer_id (FK → quiz_answers.id)
├── answer_text
├── is_correct
├── points_earned
├── teacher_feedback
└── created_at
```

---

### 5. ASSIGNMENTS

#### `assignments` - Bài tập
```
assignments
├── id (PK)
├── course_id (FK → courses.id)
├── lesson_id (FK → lessons.id)
├── title
├── description
├── instructions
├── attachment
├── max_score
├── due_date
├── allow_late
├── late_penalty
├── is_published
├── created_at
└── updated_at
```

#### `assignment_submissions` - Bài nộp
```
assignment_submissions
├── id (PK)
├── assignment_id (FK → assignments.id)
├── student_id (FK → users.id)
├── submission_text
├── attachment
├── submitted_at
├── graded_at
├── score
├── feedback
├── status (ENUM: submitted, graded, late, resubmit)
├── attempt_number
├── created_at
└── updated_at
```

---

### 6. RESOURCES

#### `resources` - Thư viện tài nguyên
```
resources
├── id (PK)
├── title
├── description
├── file_name
├── file_path
├── file_type
├── file_size
├── category (ENUM: document, video, image, other)
├── subject_id (FK → subjects.id)
├── course_id (FK → courses.id)
├── uploaded_by (FK → users.id)
├── download_count
├── is_public
├── created_at
└── updated_at
```

---

### 7. COMMUNICATION

#### `discussions` - Diễn đàn thảo luận
```
discussions
├── id (PK)
├── course_id (FK → courses.id)
├── user_id (FK → users.id)
├── title
├── content
├── is_pinned
├── is_locked
├── views_count
├── replies_count
├── created_at
└── updated_at
```

#### `discussion_replies` - Phản hồi
```
discussion_replies
├── id (PK)
├── discussion_id (FK → discussions.id)
├── user_id (FK → users.id)
├── content
├── is_answer
├── created_at
└── updated_at
```

#### `notifications` - Thông báo
```
notifications
├── id (PK)
├── user_id (FK → users.id)
├── title
├── message
├── type (ENUM: info, success, warning, error)
├── link
├── is_read
└── created_at
```

#### `announcements` - Thông báo chung
```
announcements
├── id (PK)
├── title
├── content
├── target_role
├── course_id (FK → courses.id)
├── class_id (FK → classes.id)
├── created_by (FK → users.id)
├── is_published
├── publish_at
├── expire_at
├── created_at
└── updated_at
```

---

### 8. GAMIFICATION

#### `badges` - Huy hiệu
```
badges
├── id (PK)
├── name
├── description
├── icon
├── color
├── criteria
├── xp_required
├── type (ENUM: achievement, milestone, special)
├── is_active
└── created_at
```

#### `user_badges` - Huy hiệu của user
```
user_badges
├── id (PK)
├── user_id (FK → users.id)
├── badge_id (FK → badges.id)
└── earned_at
```

#### `xp_transactions` - Giao dịch XP
```
xp_transactions
├── id (PK)
├── user_id (FK → users.id)
├── amount
├── reason
├── reference_type
├── reference_id
└── created_at
```

---

### 9. CALENDAR

#### `events` - Sự kiện/Lịch
```
events
├── id (PK)
├── title
├── description
├── event_type (ENUM: class, exam, assignment, holiday, other)
├── start_date
├── end_date
├── location
├── course_id (FK → courses.id)
├── class_id (FK → classes.id)
├── created_by (FK → users.id)
├── is_all_day
├── color
├── created_at
└── updated_at
```

---

### 10. SYSTEM

#### `settings` - Cấu hình hệ thống
```
settings
├── id (PK)
├── setting_key (UNIQUE)
├── setting_value
├── setting_type
├── description
└── updated_at
```

#### `activity_logs` - Nhật ký hoạt động
```
activity_logs
├── id (PK)
├── user_id (FK → users.id)
├── action
├── table_name
├── record_id
├── ip_address
├── user_agent
└── created_at
```

---

## 🔗 Quan hệ chính

### One-to-Many (1:N)

| Parent Table | Child Table | Description |
|-------------|-------------|-------------|
| users | student_profiles | Một user có một profile học sinh |
| users | teacher_profiles | Một user có một profile giáo viên |
| users | courses | Một giáo viên tạo nhiều khóa học |
| courses | lessons | Một khóa học có nhiều bài giảng |
| courses | enrollments | Một khóa học có nhiều đăng ký |
| courses | quizzes | Một khóa học có nhiều quiz |
| courses | assignments | Một khóa học có nhiều bài tập |
| quizzes | quiz_questions | Một quiz có nhiều câu hỏi |
| quiz_questions | quiz_answers | Một câu hỏi có nhiều đáp án |

### Many-to-Many (N:M) - Thông qua bảng trung gian

| Table 1 | Junction Table | Table 2 | Description |
|---------|---------------|---------|-------------|
| users (students) | enrollments | courses | Học sinh đăng ký nhiều khóa học |
| users | user_badges | badges | User đạt nhiều huy hiệu |
| lessons | lesson_progress | users (students) | Tiến độ học bài của học sinh |

---

## 📐 Indexes

### Primary Keys (PK)
Tất cả bảng đều có `id` làm primary key (AUTO_INCREMENT)

### Unique Keys
- `users.email`
- `courses.slug`
- `classes.code`
- `subjects.code`
- `student_profiles.student_code`
- `teacher_profiles.teacher_code`

### Foreign Keys (FK)
Tất cả foreign keys đều có `ON DELETE CASCADE` hoặc `SET NULL` tùy logic

### Composite Unique Keys
- `enrollments (course_id, student_id)` - Không đăng ký trùng
- `user_badges (user_id, badge_id)` - Không nhận huy hiệu trùng
- `lesson_progress (lesson_id, student_id)` - Không duplicate tiến độ

### Performance Indexes
```sql
INDEX idx_email ON users(email);
INDEX idx_role ON users(role);
INDEX idx_slug ON courses(slug);
INDEX idx_teacher ON courses(teacher_id);
INDEX idx_student ON enrollments(student_id);
INDEX idx_course ON lessons(course_id);
```

---

## 🔐 Security Features

### Password Hashing
- Sử dụng `PASSWORD_DEFAULT` (bcrypt)
- Cost factor: 10

### Token-based Authentication
- `verification_token` - Xác thực email
- `reset_token` - Reset password
- `reset_expires` - Hết hạn token

### Role-based Access Control (RBAC)
- Roles: admin, teacher, student, parent
- Kiểm tra quyền ở controller level
- Middleware authentication

---

## 📈 Data Flow Examples

### 1. Học sinh tham gia khóa học

```
1. Student browses courses
2. Click "Enroll" → Insert into enrollments
3. Update courses.enrollment_count++
4. Create notification for student
5. Log activity
```

### 2. Học sinh làm quiz

```
1. Start quiz → Insert quiz_attempts (status: in_progress)
2. Answer questions → Insert quiz_student_answers
3. Auto-save answers every 30s
4. Submit → Update quiz_attempts (status: completed)
5. Calculate score
6. Award XP → Insert xp_transactions
7. Update student_profiles.total_xp
8. Check for new badges
```

### 3. Giáo viên tạo khóa học

```
1. Create course → Insert courses (status: draft)
2. Add lessons → Insert lessons
3. Create quiz → Insert quizzes, quiz_questions, quiz_answers
4. Publish course → Update courses.is_published = 1
5. Notify students in class
```

---

## 🚀 Optimization Tips

### Query Optimization
1. Sử dụng JOIN thay vì multiple queries
2. Index các cột thường được WHERE/ORDER BY
3. Sử dụng LIMIT cho pagination
4. Cache kết quả query phổ biến

### Storage Optimization
1. Sử dụng ENUM cho fixed values
2. TEXT cho nội dung dài, VARCHAR cho ngắn
3. INT cho số, DECIMAL cho tiền/điểm
4. Timestamp cho ngày giờ

---

## 📊 Sample Queries

### Lấy top 10 học sinh XP cao nhất
```sql
SELECT u.full_name, sp.total_xp, sp.level
FROM users u
INNER JOIN student_profiles sp ON u.id = sp.user_id
WHERE u.role = 'student'
ORDER BY sp.total_xp DESC
LIMIT 10;
```

### Lấy khóa học phổ biến nhất
```sql
SELECT c.title, COUNT(e.id) as students
FROM courses c
LEFT JOIN enrollments e ON c.id = e.course_id
WHERE c.is_published = 1
GROUP BY c.id
ORDER BY students DESC
LIMIT 10;
```

### Tính điểm trung bình quiz của học sinh
```sql
SELECT AVG(score) as avg_score
FROM quiz_attempts
WHERE student_id = ? AND status = 'completed';
```

---

**Database Version**: 1.0.0  
**Last Updated**: 2024-10-10  
**Charset**: utf8mb4_unicode_ci  
**Engine**: InnoDB
