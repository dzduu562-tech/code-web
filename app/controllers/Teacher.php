<?php
/**
 * Teacher Controller - Teacher Dashboard & Features
 */

class Teacher extends Controller {
    private $userModel;
    private $courseModel;

    public function __construct() {
        $this->requireRole('teacher');
        $this->userModel = $this->model('User');
        $this->courseModel = $this->model('Course');
    }

    /**
     * Default index - redirect to dashboard
     */
    public function index() {
        redirect('teacher/dashboard');
    }

    /**
     * Teacher Dashboard
     */
    public function dashboard() {
        $teacherId = $_SESSION['user_id'];
        
        // Get user with fallback
        $user = $this->userModel->getUserWithProfile($teacherId);
        
        // Fallback if user data is incomplete
        if (!$user || !isset($user['full_name'])) {
            $user = [
                'id' => $teacherId,
                'full_name' => $_SESSION['full_name'] ?? 'Giáo viên',
                'email' => $_SESSION['email'] ?? '',
                'role' => 'teacher',
                'avatar' => null
            ];
        }
        
        $data = [
            'title' => 'Dashboard - Giáo viên',
            'user' => $user,
            'my_courses' => $this->courseModel->getTeacherCourses($teacherId),
            'stats' => $this->getTeacherStats($teacherId)
        ];

        $this->view('teacher/dashboard', $data);
    }

    /**
     * My Courses
     */
    public function courses() {
        $teacherId = $_SESSION['user_id'];
        
        $data = [
            'title' => 'Khóa học của tôi',
            'courses' => $this->courseModel->getTeacherCourses($teacherId)
        ];

        $this->view('teacher/courses', $data);
    }

    /**
     * Create Course
     */
    public function createCourse() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processCreateCourse();
        }

        $db = Database::getInstance();
        $subjects = $db->query("SELECT * FROM subjects WHERE status = 'active' ORDER BY name")->fetchAll();

        $data = [
            'title' => 'Tạo khóa học mới',
            'subjects' => $subjects
        ];

        $this->view('teacher/create-course', $data);
    }

    /**
     * Students
     */
    public function students() {
        $data = [
            'title' => 'Quản lý học sinh'
        ];

        $this->view('teacher/students', $data);
    }

    /**
     * Assignments
     */
    public function assignments() {
        $teacherId = $_SESSION['user_id'];
        $assignmentModel = $this->model('Assignment');
        
        $data = [
            'title' => 'Quản lý bài tập',
            'assignments' => $assignmentModel->getTeacherAssignments($teacherId)
        ];

        $this->view('teacher/assignments', $data);
    }

    /**
     * Create Assignment
     */
    public function createAssignment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processCreateAssignment();
        }

        $teacherId = $_SESSION['user_id'];
        $courses = $this->courseModel->getTeacherCourses($teacherId);

        $data = [
            'title' => 'Tạo bài tập mới',
            'courses' => $courses
        ];

        $this->view('teacher/create-assignment', $data);
    }

    /**
     * Process Create Assignment
     */
    private function processCreateAssignment() {
        $assignmentModel = $this->model('Assignment');
        
        $data = [
            'course_id' => $_POST['course_id'] ?? null,
            'title' => clean($_POST['title'] ?? ''),
            'description' => clean($_POST['description'] ?? ''),
            'type' => $_POST['type'] ?? 'essay',
            'max_score' => intval($_POST['max_score'] ?? 100),
            'due_date' => $_POST['due_date'] ?? null,
            'allow_late' => isset($_POST['allow_late']) ? 1 : 0,
            'instructions' => clean($_POST['instructions'] ?? ''),
            'status' => 'published'
        ];

        try {
            $assignmentModel->create($data);
            flash('success', 'Tạo bài tập thành công!', 'success');
            redirect('teacher/assignments');
        } catch (Exception $e) {
            flash('error', 'Có lỗi xảy ra: ' . $e->getMessage(), 'danger');
            redirect('teacher/createAssignment');
        }
    }

    /**
     * Quizzes
     */
    public function quizzes() {
        $teacherId = $_SESSION['user_id'];
        $quizModel = $this->model('Quiz');
        
        $data = [
            'title' => 'Quản lý Quiz',
            'quizzes' => $quizModel->getTeacherQuizzes($teacherId)
        ];

        $this->view('teacher/quizzes', $data);
    }

    /**
     * Create Quiz
     */
    public function createQuiz() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processCreateQuiz();
        }

        $teacherId = $_SESSION['user_id'];
        $courses = $this->courseModel->getTeacherCourses($teacherId);

        $data = [
            'title' => 'Tạo Quiz mới',
            'courses' => $courses
        ];

        $this->view('teacher/create-quiz', $data);
    }

    /**
     * Process Create Quiz
     */
    private function processCreateQuiz() {
        $quizModel = $this->model('Quiz');
        
        $data = [
            'course_id' => $_POST['course_id'] ?? null,
            'title' => clean($_POST['title'] ?? ''),
            'description' => clean($_POST['description'] ?? ''),
            'time_limit' => intval($_POST['time_limit'] ?? 30),
            'pass_score' => intval($_POST['pass_score'] ?? 70),
            'max_attempts' => intval($_POST['max_attempts'] ?? 1),
            'shuffle_questions' => isset($_POST['shuffle_questions']) ? 1 : 0,
            'show_results' => isset($_POST['show_results']) ? 1 : 0,
            'available_from' => $_POST['available_from'] ?? null,
            'available_to' => $_POST['available_to'] ?? null,
            'status' => 'published'
        ];

        try {
            $quizId = $quizModel->create($data);
            flash('success', 'Tạo Quiz thành công! Hãy thêm câu hỏi.', 'success');
            redirect('teacher/quizzes');
        } catch (Exception $e) {
            flash('error', 'Có lỗi xảy ra: ' . $e->getMessage(), 'danger');
            redirect('teacher/createQuiz');
        }
    }

    /**
     * Reports
     */
    public function reports() {
        $data = [
            'title' => 'Báo cáo & Thống kê'
        ];

        $this->view('teacher/reports', $data);
    }

    /**
     * Process create course
     */
    private function processCreateCourse() {
        $teacherId = $_SESSION['user_id'];
        
        $courseData = [
            'title' => clean($_POST['title'] ?? ''),
            'slug' => slug($_POST['title'] ?? ''),
            'description' => clean($_POST['description'] ?? ''),
            'subject_id' => $_POST['subject_id'] ?? null,
            'teacher_id' => $teacherId,
            'level' => $_POST['level'] ?? 'beginner',
            'duration_hours' => intval($_POST['duration_hours'] ?? 0),
            'status' => 'draft'
        ];

        try {
            $this->courseModel->insert($courseData);
            flash('success', 'Tạo khóa học thành công!', 'success');
            redirect('teacher/courses');
        } catch (Exception $e) {
            flash('error', 'Có lỗi xảy ra khi tạo khóa học', 'danger');
            redirect('teacher/create-course');
        }
    }

    /**
     * Get teacher stats
     */
    private function getTeacherStats($teacherId) {
        $db = Database::getInstance();
        
        // Total courses
        $coursesQuery = $db->query("SELECT COUNT(*) as total FROM courses WHERE teacher_id = :id", 
            ['id' => $teacherId]);
        $totalCourses = $coursesQuery->fetch()['total'];
        
        // Total students
        $studentsQuery = $db->query("SELECT COUNT(DISTINCT e.student_id) as total 
                                      FROM enrollments e 
                                      INNER JOIN courses c ON e.course_id = c.id 
                                      WHERE c.teacher_id = :id AND e.status = 'active'", 
            ['id' => $teacherId]);
        $totalStudents = $studentsQuery->fetch()['total'];
        
        // Total lessons
        $lessonsQuery = $db->query("SELECT COUNT(*) as total 
                                     FROM lessons l 
                                     INNER JOIN courses c ON l.course_id = c.id 
                                     WHERE c.teacher_id = :id", 
            ['id' => $teacherId]);
        $totalLessons = $lessonsQuery->fetch()['total'];
        
        return [
            'total_courses' => $totalCourses,
            'total_students' => $totalStudents,
            'total_lessons' => $totalLessons
        ];
    }
}
