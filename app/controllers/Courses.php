<?php
/**
 * Courses Controller - Public course browsing
 */

class Courses extends Controller {
    private $courseModel;

    public function __construct() {
        $this->courseModel = $this->model('Course');
    }

    /**
     * List all courses
     */
    public function index() {
        $courses = $this->courseModel->getPublishedCourses();
        
        $data = [
            'title' => 'Khóa học',
            'courses' => $courses
        ];

        $this->view('courses/index', $data);
    }

    /**
     * View course details
     */
    public function detail($slug = null) {
        if (!$slug) {
            redirect('courses');
        }

        $course = $this->courseModel->getBySlug($slug);

        if (!$course) {
            flash('error', 'Khóa học không tồn tại', 'danger');
            redirect('courses');
        }

        // Increment views
        $this->courseModel->incrementViews($course['id']);

        // Check if enrolled
        $isEnrolled = false;
        if ($this->isLoggedIn() && hasRole('student')) {
            $isEnrolled = $this->courseModel->isStudentEnrolled($course['id'], $_SESSION['user_id']);
        }

        $data = [
            'title' => $course['title'],
            'course' => $course,
            'lessons' => $this->courseModel->getLessons($course['id']),
            'is_enrolled' => $isEnrolled
        ];

        $this->view('courses/detail', $data);
    }

    /**
     * Enroll in course
     */
    public function enroll($courseId = null) {
        if (!$this->isLoggedIn() || !hasRole('student')) {
            flash('error', 'Bạn cần đăng nhập với tài khoản học sinh', 'warning');
            redirect('auth/login');
        }

        if (!$courseId) {
            redirect('courses');
        }

        $course = $this->courseModel->findById($courseId);
        if (!$course) {
            flash('error', 'Khóa học không tồn tại', 'danger');
            redirect('courses');
        }

        $studentId = $_SESSION['user_id'];

        // Check if already enrolled
        if ($this->courseModel->isStudentEnrolled($courseId, $studentId)) {
            flash('error', 'Bạn đã đăng ký khóa học này rồi', 'info');
            redirect('student/course/' . $course['slug']);
        }

        // Enroll
        if ($this->courseModel->enrollStudent($courseId, $studentId)) {
            flash('success', 'Đăng ký khóa học thành công!', 'success');
            redirect('student/course/' . $course['slug']);
        } else {
            flash('error', 'Có lỗi xảy ra. Vui lòng thử lại!', 'danger');
            redirect('courses/detail/' . $course['slug']);
        }
    }
}
