<?php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Helpers.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/../models/User.php';

class CourseController {
    private $auth;
    private $courseModel;
    private $lessonModel;
    private $userModel;
    
    public function __construct() {
        $this->auth = new Auth();
        $this->courseModel = new Course();
        $this->lessonModel = new Lesson();
        $this->userModel = new User();
    }
    
    public function index() {
        $page = (int)($_GET['page'] ?? 1);
        $subject = $_GET['subject'] ?? null;
        $search = $_GET['search'] ?? null;
        
        if ($search) {
            $courses = $this->courseModel->search($search, $subject);
            $pagination = null;
        } else {
            $courses = $this->courseModel->getAll($page, ITEMS_PER_PAGE, true);
            $totalCourses = $this->courseModel->getTotalCount(true);
            $pagination = Helpers::paginate($totalCourses, $page);
        }
        
        $subjects = $this->courseModel->getSubjects();
        
        $data = [
            'title' => 'Khóa học',
            'courses' => $courses,
            'subjects' => $subjects,
            'pagination' => $pagination,
            'current_subject' => $subject,
            'search_query' => $search
        ];
        
        include __DIR__ . '/../views/courses/index.php';
    }
    
    public function show() {
        $id = (int)($_GET['id'] ?? 0);
        $course = $this->courseModel->find($id);
        
        if (!$course) {
            http_response_code(404);
            include __DIR__ . '/../views/errors/404.php';
            return;
        }
        
        $this->auth->requireAuth();
        $user = $this->auth->user();
        
        $isEnrolled = $this->courseModel->isEnrolled($user['id'], $id);
        $chapters = $this->courseModel->getChapters($id);
        $lessons = $this->courseModel->getLessons($id);
        $enrollmentCount = $this->courseModel->getEnrollmentCount($id);
        
        $data = [
            'title' => $course['title'],
            'course' => $course,
            'chapters' => $chapters,
            'lessons' => $lessons,
            'is_enrolled' => $isEnrolled,
            'enrollment_count' => $enrollmentCount
        ];
        
        include __DIR__ . '/../views/courses/show.php';
    }
    
    public function enroll() {
        $this->auth->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('/courses');
        }
        
        $courseId = (int)($_POST['course_id'] ?? 0);
        $user = $this->auth->user();
        
        if (!$this->auth->verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            Helpers::setFlash('error', 'Token không hợp lệ');
            Helpers::redirect('/courses');
        }
        
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            Helpers::setFlash('error', 'Khóa học không tồn tại');
            Helpers::redirect('/courses');
        }
        
        if ($this->courseModel->enroll($user['id'], $courseId)) {
            Helpers::setFlash('success', 'Đăng ký khóa học thành công');
        } else {
            Helpers::setFlash('error', 'Bạn đã đăng ký khóa học này rồi');
        }
        
        Helpers::redirect("/course?id={$courseId}");
    }
    
    public function unenroll() {
        $this->auth->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('/courses');
        }
        
        $courseId = (int)($_POST['course_id'] ?? 0);
        $user = $this->auth->user();
        
        if (!$this->auth->verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            Helpers::setFlash('error', 'Token không hợp lệ');
            Helpers::redirect('/courses');
        }
        
        if ($this->courseModel->unenroll($user['id'], $courseId)) {
            Helpers::setFlash('success', 'Hủy đăng ký khóa học thành công');
        } else {
            Helpers::setFlash('error', 'Có lỗi xảy ra khi hủy đăng ký');
        }
        
        Helpers::redirect('/courses');
    }
    
    public function create() {
        $this->auth->requireTeacher();
        
        $data = [
            'title' => 'Tạo khóa học mới',
            'error' => Helpers::getFlash('error'),
            'success' => Helpers::getFlash('success'),
            'old' => [
                'title' => Helpers::old('title'),
                'subject' => Helpers::old('subject'),
                'description' => Helpers::old('description')
            ]
        ];
        
        include __DIR__ . '/../views/courses/create.php';
    }
    
    public function store() {
        $this->auth->requireTeacher();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('/course/create');
        }
        
        if (!$this->auth->verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            Helpers::setFlash('error', 'Token không hợp lệ');
            Helpers::redirect('/course/create');
        }
        
        $user = $this->auth->user();
        $title = Helpers::sanitize($_POST['title'] ?? '');
        $subject = Helpers::sanitize($_POST['subject'] ?? '');
        $description = Helpers::sanitize($_POST['description'] ?? '');
        $isPublished = isset($_POST['is_published']);
        
        // Validation
        $errors = [];
        
        if (empty($title)) {
            $errors[] = 'Vui lòng nhập tên khóa học';
        }
        
        if (empty($subject)) {
            $errors[] = 'Vui lòng chọn môn học';
        }
        
        if (!empty($errors)) {
            Helpers::setFlash('error', implode('<br>', $errors));
            Helpers::setOld(['title' => $title, 'subject' => $subject, 'description' => $description]);
            Helpers::redirect('/course/create');
        }
        
        $courseData = [
            'title' => $title,
            'subject' => $subject,
            'description' => $description,
            'teacher_id' => $user['id'],
            'is_published' => $isPublished ? 1 : 0
        ];
        
        // Handle thumbnail upload
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = Helpers::uploadFile($_FILES['thumbnail']);
            if ($uploadResult['success']) {
                $courseData['thumbnail'] = $uploadResult['file_path'];
            } else {
                Helpers::setFlash('error', $uploadResult['message']);
                Helpers::setOld(['title' => $title, 'subject' => $subject, 'description' => $description]);
                Helpers::redirect('/course/create');
            }
        }
        
        $courseId = $this->courseModel->create($courseData);
        
        if ($courseId) {
            Helpers::setFlash('success', 'Tạo khóa học thành công');
            Helpers::redirect("/course?id={$courseId}");
        } else {
            Helpers::setFlash('error', 'Có lỗi xảy ra khi tạo khóa học');
            Helpers::redirect('/course/create');
        }
    }
    
    public function edit() {
        $this->auth->requireTeacher();
        
        $id = (int)($_GET['id'] ?? 0);
        $course = $this->courseModel->find($id);
        $user = $this->auth->user();
        
        if (!$course || $course['teacher_id'] != $user['id']) {
            Helpers::setFlash('error', 'Khóa học không tồn tại hoặc bạn không có quyền chỉnh sửa');
            Helpers::redirect('/courses');
        }
        
        $data = [
            'title' => 'Chỉnh sửa khóa học',
            'course' => $course,
            'error' => Helpers::getFlash('error'),
            'success' => Helpers::setFlash('success'),
            'old' => [
                'title' => Helpers::old('title', $course['title']),
                'subject' => Helpers::old('subject', $course['subject']),
                'description' => Helpers::old('description', $course['description'])
            ]
        ];
        
        include __DIR__ . '/../views/courses/edit.php';
    }
    
    public function update() {
        $this->auth->requireTeacher();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('/courses');
        }
        
        if (!$this->auth->verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            Helpers::setFlash('error', 'Token không hợp lệ');
            Helpers::redirect('/courses');
        }
        
        $id = (int)($_POST['id'] ?? 0);
        $course = $this->courseModel->find($id);
        $user = $this->auth->user();
        
        if (!$course || $course['teacher_id'] != $user['id']) {
            Helpers::setFlash('error', 'Khóa học không tồn tại hoặc bạn không có quyền chỉnh sửa');
            Helpers::redirect('/courses');
        }
        
        $title = Helpers::sanitize($_POST['title'] ?? '');
        $subject = Helpers::sanitize($_POST['subject'] ?? '');
        $description = Helpers::sanitize($_POST['description'] ?? '');
        $isPublished = isset($_POST['is_published']);
        
        // Validation
        $errors = [];
        
        if (empty($title)) {
            $errors[] = 'Vui lòng nhập tên khóa học';
        }
        
        if (empty($subject)) {
            $errors[] = 'Vui lòng chọn môn học';
        }
        
        if (!empty($errors)) {
            Helpers::setFlash('error', implode('<br>', $errors));
            Helpers::redirect("/course/edit?id={$id}");
        }
        
        $courseData = [
            'title' => $title,
            'subject' => $subject,
            'description' => $description,
            'is_published' => $isPublished ? 1 : 0
        ];
        
        // Handle thumbnail upload
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = Helpers::uploadFile($_FILES['thumbnail']);
            if ($uploadResult['success']) {
                // Delete old thumbnail
                if ($course['thumbnail'] && file_exists($course['thumbnail'])) {
                    Helpers::deleteFile($course['thumbnail']);
                }
                $courseData['thumbnail'] = $uploadResult['file_path'];
            } else {
                Helpers::setFlash('error', $uploadResult['message']);
                Helpers::redirect("/course/edit?id={$id}");
            }
        }
        
        if ($this->courseModel->update($id, $courseData)) {
            Helpers::setFlash('success', 'Cập nhật khóa học thành công');
        } else {
            Helpers::setFlash('error', 'Có lỗi xảy ra khi cập nhật khóa học');
        }
        
        Helpers::redirect("/course?id={$id}");
    }
    
    public function delete() {
        $this->auth->requireTeacher();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('/courses');
        }
        
        if (!$this->auth->verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            Helpers::setFlash('error', 'Token không hợp lệ');
            Helpers::redirect('/courses');
        }
        
        $id = (int)($_POST['id'] ?? 0);
        $course = $this->courseModel->find($id);
        $user = $this->auth->user();
        
        if (!$course || $course['teacher_id'] != $user['id']) {
            Helpers::setFlash('error', 'Khóa học không tồn tại hoặc bạn không có quyền xóa');
            Helpers::redirect('/courses');
        }
        
        // Delete thumbnail if exists
        if ($course['thumbnail'] && file_exists($course['thumbnail'])) {
            Helpers::deleteFile($course['thumbnail']);
        }
        
        if ($this->courseModel->delete($id)) {
            Helpers::setFlash('success', 'Xóa khóa học thành công');
        } else {
            Helpers::setFlash('error', 'Có lỗi xảy ra khi xóa khóa học');
        }
        
        Helpers::redirect('/courses');
    }
}