<?php

require_once __DIR__ . '/../core/DB.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Helpers.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/User.php';

class CourseController {
    private $auth;
    private $courseModel;
    private $userModel;

    public function __construct() {
        $this->auth = Auth::getInstance();
        $this->courseModel = new Course();
        $this->userModel = new User();
    }

    public function index($params = []) {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 12;
        $search = Helpers::clean($_GET['search'] ?? '');
        $subject = Helpers::clean($_GET['subject'] ?? '');

        $filters = [
            'is_published' => 1,
            'limit' => $perPage,
            'offset' => ($page - 1) * $perPage
        ];

        if (!empty($search)) {
            $filters['search'] = $search;
        }

        if (!empty($subject)) {
            $filters['subject'] = $subject;
        }

        $courses = $this->courseModel->getAll($filters);
        $totalCourses = $this->courseModel->count($filters);
        $pagination = Helpers::paginate($totalCourses, $page, $perPage);
        $subjects = $this->courseModel->getSubjects();

        $data = [
            'title' => 'Danh sách khóa học - E-Learning Platform',
            'courses' => $courses,
            'pagination' => $pagination,
            'subjects' => $subjects,
            'filters' => ['search' => $search, 'subject' => $subject]
        ];

        $this->render('courses/index', $data);
    }

    public function show($params = []) {
        $courseId = (int)($_GET['id'] ?? 0);
        
        if (!$courseId) {
            $this->notFound();
            return;
        }

        $course = $this->courseModel->find($courseId);
        
        if (!$course) {
            $this->notFound();
            return;
        }

        // Check if course is published or user has permission
        if (!$course['is_published'] && !$this->canManageCourse($courseId)) {
            $this->accessDenied();
            return;
        }

        $chapters = $this->courseModel->getChapters($courseId);
        $assignments = $this->courseModel->getAssignments($courseId);
        $forumThreads = $this->courseModel->getForumThreads($courseId);
        
        $isEnrolled = false;
        $progress = 0;
        
        if ($this->auth->isLoggedIn()) {
            $isEnrolled = $this->courseModel->isEnrolled($courseId, $this->auth->id());
            if ($isEnrolled) {
                $progress = $this->courseModel->getProgress($courseId, $this->auth->id());
            }
        }

        $data = [
            'title' => $course['title'] . ' - E-Learning Platform',
            'course' => $course,
            'chapters' => $chapters,
            'assignments' => $assignments,
            'forum_threads' => $forumThreads,
            'is_enrolled' => $isEnrolled,
            'progress' => $progress,
            'can_manage' => $this->canManageCourse($courseId)
        ];

        $this->render('courses/show', $data);
    }

    public function enroll($params = []) {
        $this->auth->requireAuth();
        
        $courseId = (int)($_POST['course_id'] ?? 0);
        
        if (!$courseId) {
            Helpers::json(['success' => false, 'message' => 'Khóa học không tồn tại']);
            return;
        }

        $course = $this->courseModel->find($courseId);
        
        if (!$course || !$course['is_published']) {
            Helpers::json(['success' => false, 'message' => 'Khóa học không khả dụng']);
            return;
        }

        $userId = $this->auth->id();
        
        if ($this->courseModel->isEnrolled($courseId, $userId)) {
            Helpers::json(['success' => false, 'message' => 'Bạn đã đăng ký khóa học này']);
            return;
        }

        $enrollmentId = $this->courseModel->enroll($courseId, $userId);
        
        if ($enrollmentId) {
            Helpers::json(['success' => true, 'message' => 'Đăng ký khóa học thành công']);
        } else {
            Helpers::json(['success' => false, 'message' => 'Có lỗi xảy ra khi đăng ký']);
        }
    }

    public function create($params = []) {
        $this->auth->requireRole('teacher');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->store();
        }

        $data = [
            'title' => 'Tạo khóa học mới - E-Learning Platform'
        ];

        $this->render('courses/create', $data);
    }

    public function store($params = []) {
        $this->auth->requireRole('teacher');

        $title = Helpers::clean($_POST['title'] ?? '');
        $subject = Helpers::clean($_POST['subject'] ?? '');
        $description = Helpers::clean($_POST['description'] ?? '');
        $isPublished = isset($_POST['is_published']);

        $errors = [];

        if (empty($title)) {
            $errors[] = 'Vui lòng nhập tiêu đề khóa học';
        }

        if (empty($subject)) {
            $errors[] = 'Vui lòng nhập môn học';
        }

        if (!empty($errors)) {
            $data = [
                'title' => 'Tạo khóa học mới - E-Learning Platform',
                'errors' => $errors,
                'old' => $_POST
            ];
            return $this->render('courses/create', $data);
        }

        $courseData = [
            'title' => $title,
            'subject' => $subject,
            'description' => $description,
            'teacher_id' => $this->auth->id(),
            'is_published' => $isPublished
        ];

        $courseId = $this->courseModel->create($courseData);

        if ($courseId) {
            $router = new Router();
            $router->redirect($router->url('course', ['id' => $courseId]));
        } else {
            $data = [
                'title' => 'Tạo khóa học mới - E-Learning Platform',
                'error' => 'Có lỗi xảy ra khi tạo khóa học',
                'old' => $_POST
            ];
            $this->render('courses/create', $data);
        }
    }

    public function edit($params = []) {
        $courseId = (int)($_GET['id'] ?? 0);
        
        if (!$this->canManageCourse($courseId)) {
            $this->accessDenied();
            return;
        }

        $course = $this->courseModel->find($courseId);
        
        if (!$course) {
            $this->notFound();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->update($courseId);
        }

        $data = [
            'title' => 'Chỉnh sửa khóa học - E-Learning Platform',
            'course' => $course
        ];

        $this->render('courses/edit', $data);
    }

    public function update($courseId) {
        if (!$this->canManageCourse($courseId)) {
            $this->accessDenied();
            return;
        }

        $title = Helpers::clean($_POST['title'] ?? '');
        $subject = Helpers::clean($_POST['subject'] ?? '');
        $description = Helpers::clean($_POST['description'] ?? '');
        $isPublished = isset($_POST['is_published']);

        $errors = [];

        if (empty($title)) {
            $errors[] = 'Vui lòng nhập tiêu đề khóa học';
        }

        if (empty($subject)) {
            $errors[] = 'Vui lòng nhập môn học';
        }

        if (!empty($errors)) {
            $course = $this->courseModel->find($courseId);
            $data = [
                'title' => 'Chỉnh sửa khóa học - E-Learning Platform',
                'course' => $course,
                'errors' => $errors,
                'old' => $_POST
            ];
            return $this->render('courses/edit', $data);
        }

        $updateData = [
            'title' => $title,
            'subject' => $subject,
            'description' => $description,
            'is_published' => $isPublished
        ];

        $success = $this->courseModel->update($courseId, $updateData);

        if ($success) {
            $router = new Router();
            $router->redirect($router->url('course', ['id' => $courseId]));
        } else {
            $course = $this->courseModel->find($courseId);
            $data = [
                'title' => 'Chỉnh sửa khóa học - E-Learning Platform',
                'course' => $course,
                'error' => 'Có lỗi xảy ra khi cập nhật khóa học',
                'old' => $_POST
            ];
            $this->render('courses/edit', $data);
        }
    }

    public function delete($params = []) {
        $courseId = (int)($_POST['course_id'] ?? 0);
        
        if (!$this->canManageCourse($courseId)) {
            Helpers::json(['success' => false, 'message' => 'Không có quyền xóa khóa học này']);
            return;
        }

        $success = $this->courseModel->delete($courseId);
        
        if ($success) {
            Helpers::json(['success' => true, 'message' => 'Xóa khóa học thành công']);
        } else {
            Helpers::json(['success' => false, 'message' => 'Có lỗi xảy ra khi xóa khóa học']);
        }
    }

    public function students($params = []) {
        $courseId = (int)($_GET['id'] ?? 0);
        
        if (!$this->canManageCourse($courseId)) {
            $this->accessDenied();
            return;
        }

        $course = $this->courseModel->find($courseId);
        
        if (!$course) {
            $this->notFound();
            return;
        }

        $students = $this->courseModel->getEnrolledStudents($courseId);

        $data = [
            'title' => 'Học sinh - ' . $course['title'],
            'course' => $course,
            'students' => $students
        ];

        $this->render('courses/students', $data);
    }

    private function canManageCourse($courseId) {
        if ($this->auth->isAdmin()) {
            return true;
        }

        if (!$this->auth->isTeacher()) {
            return false;
        }

        $course = $this->courseModel->find($courseId);
        return $course && $course['teacher_id'] == $this->auth->id();
    }

    private function notFound() {
        http_response_code(404);
        $data = ['title' => 'Không tìm thấy - E-Learning Platform'];
        $this->render('errors/404', $data);
    }

    private function accessDenied() {
        http_response_code(403);
        $data = ['title' => 'Truy cập bị từ chối - E-Learning Platform'];
        $this->render('errors/403', $data);
    }

    private function render($view, $data = []) {
        extract($data);
        include __DIR__ . '/../views/layouts/main.php';
    }
}