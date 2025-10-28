<?php

require_once __DIR__ . '/../core/DB.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Helpers.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/User.php';

class HomeController {
    private $auth;
    private $courseModel;
    private $userModel;

    public function __construct() {
        $this->auth = Auth::getInstance();
        $this->courseModel = new Course();
        $this->userModel = new User();
    }

    public function index($params = []) {
        // Get statistics for landing page
        $stats = [
            'total_courses' => $this->courseModel->count(['is_published' => 1]),
            'total_students' => $this->userModel->count(['role' => 'student', 'is_active' => 1]),
            'total_teachers' => $this->userModel->count(['role' => 'teacher', 'is_active' => 1])
        ];

        // Get popular and recent courses
        $popularCourses = $this->courseModel->getPopularCourses(6);
        $recentCourses = $this->courseModel->getRecentCourses(6);
        $subjects = $this->courseModel->getSubjects();

        // If user is logged in, redirect to dashboard
        if ($this->auth->isLoggedIn()) {
            $router = new Router();
            $router->redirect($router->url('dashboard'));
            return;
        }

        $data = [
            'title' => 'Trang chủ - E-Learning Platform',
            'stats' => $stats,
            'popular_courses' => $popularCourses,
            'recent_courses' => $recentCourses,
            'subjects' => $subjects
        ];

        $this->render('home/index', $data);
    }

    public function about($params = []) {
        $data = [
            'title' => 'Giới thiệu - E-Learning Platform'
        ];

        $this->render('home/about', $data);
    }

    public function contact($params = []) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleContactForm();
        }

        $data = [
            'title' => 'Liên hệ - E-Learning Platform'
        ];

        $this->render('home/contact', $data);
    }

    private function handleContactForm() {
        $name = Helpers::clean($_POST['name'] ?? '');
        $email = Helpers::clean($_POST['email'] ?? '');
        $subject = Helpers::clean($_POST['subject'] ?? '');
        $message = Helpers::clean($_POST['message'] ?? '');

        $errors = [];

        if (empty($name)) {
            $errors[] = 'Vui lòng nhập họ tên';
        }

        if (empty($email) || !Helpers::validateEmail($email)) {
            $errors[] = 'Vui lòng nhập email hợp lệ';
        }

        if (empty($subject)) {
            $errors[] = 'Vui lòng nhập tiêu đề';
        }

        if (empty($message)) {
            $errors[] = 'Vui lòng nhập nội dung';
        }

        if (!empty($errors)) {
            $data = [
                'title' => 'Liên hệ - E-Learning Platform',
                'errors' => $errors,
                'old' => $_POST
            ];
            return $this->render('home/contact', $data);
        }

        // In a real application, you would send email or save to database
        // For now, we'll just show a success message
        
        $data = [
            'title' => 'Liên hệ - E-Learning Platform',
            'success' => 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.'
        ];

        $this->render('home/contact', $data);
    }

    public function search($params = []) {
        $query = Helpers::clean($_GET['q'] ?? '');
        $subject = Helpers::clean($_GET['subject'] ?? '');
        $teacher = Helpers::clean($_GET['teacher'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 12;

        $filters = [
            'is_published' => 1,
            'limit' => $perPage,
            'offset' => ($page - 1) * $perPage
        ];

        if (!empty($query)) {
            $filters['search'] = $query;
        }

        if (!empty($subject)) {
            $filters['subject'] = $subject;
        }

        if (!empty($teacher)) {
            // Find teacher by name
            $teacherUser = $this->userModel->getAll([
                'role' => 'teacher',
                'search' => $teacher,
                'limit' => 1
            ]);
            if (!empty($teacherUser)) {
                $filters['teacher_id'] = $teacherUser[0]['id'];
            }
        }

        $courses = $this->courseModel->getAll($filters);
        $totalCourses = $this->courseModel->count($filters);

        $pagination = Helpers::paginate($totalCourses, $page, $perPage);
        $subjects = $this->courseModel->getSubjects();
        $teachers = $this->userModel->getTeachers();

        $data = [
            'title' => 'Tìm kiếm khóa học - E-Learning Platform',
            'courses' => $courses,
            'pagination' => $pagination,
            'subjects' => $subjects,
            'teachers' => $teachers,
            'filters' => [
                'query' => $query,
                'subject' => $subject,
                'teacher' => $teacher
            ]
        ];

        $this->render('home/search', $data);
    }

    public function privacy($params = []) {
        $data = [
            'title' => 'Chính sách bảo mật - E-Learning Platform'
        ];

        $this->render('home/privacy', $data);
    }

    public function terms($params = []) {
        $data = [
            'title' => 'Điều khoản sử dụng - E-Learning Platform'
        ];

        $this->render('home/terms', $data);
    }

    private function render($view, $data = []) {
        // Extract data to variables
        extract($data);

        // Include layout
        include __DIR__ . '/../views/layouts/main.php';
    }
}