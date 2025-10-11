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
        
        $data = [
            'title' => 'Dashboard - Giáo viên',
            'user' => $this->userModel->getUserWithProfile($teacherId),
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
        $data = [
            'title' => 'Quản lý bài tập'
        ];

        $this->view('teacher/assignments', $data);
    }

    /**
     * Quizzes
     */
    public function quizzes() {
        $data = [
            'title' => 'Quản lý Quiz'
        ];

        $this->view('teacher/quizzes', $data);
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
