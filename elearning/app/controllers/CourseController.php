<?php

class CourseController {
    
    public function index() {
        Auth::requireLogin();
        
        $courseModel = new Course();
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 12;
        $offset = ($page - 1) * $perPage;
        
        $keyword = $_GET['search'] ?? '';
        $subject = $_GET['subject'] ?? '';
        
        if ($keyword) {
            $courses = $courseModel->search($keyword, $subject ?: null, $perPage, $offset);
        } else {
            $courses = $courseModel->getAll($perPage, $offset);
        }
        
        $totalCourses = $courseModel->count();
        $pagination = Helpers::paginate($totalCourses, $page, $perPage);
        
        require __DIR__ . '/../views/courses/index.php';
    }
    
    public function show() {
        Auth::requireLogin();
        
        $courseId = $_GET['id'] ?? 0;
        $courseModel = new Course();
        $chapterModel = new Chapter();
        
        $course = $courseModel->findById($courseId);
        
        if (!$course) {
            http_response_code(404);
            die('Khóa học không tồn tại');
        }
        
        $chapters = $chapterModel->getWithLessons($courseId);
        $isEnrolled = $courseModel->isEnrolled(Auth::id(), $courseId);
        
        require __DIR__ . '/../views/courses/show.php';
    }
    
    public function enroll() {
        Auth::requireRoles(['student']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('courses');
            return;
        }
        
        if (!Auth::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token không hợp lệ';
            Helpers::redirect('courses');
            return;
        }
        
        $courseId = $_POST['course_id'] ?? 0;
        $courseModel = new Course();
        
        if ($courseModel->isEnrolled(Auth::id(), $courseId)) {
            $_SESSION['error'] = 'Bạn đã đăng ký khóa học này';
        } else {
            $courseModel->enroll(Auth::id(), $courseId);
            $_SESSION['success'] = 'Đăng ký khóa học thành công!';
        }
        
        Helpers::redirect("course&id=$courseId");
    }
    
    public function create() {
        Auth::requireRoles(['teacher', 'admin']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require __DIR__ . '/../views/courses/create.php';
            return;
        }
        
        if (!Auth::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token không hợp lệ';
            Helpers::redirect('courses/create');
            return;
        }
        
        $title = Helpers::sanitizeInput($_POST['title'] ?? '');
        $subject = Helpers::sanitizeInput($_POST['subject'] ?? '');
        $description = Helpers::sanitizeInput($_POST['description'] ?? '');
        
        if (empty($title)) {
            $_SESSION['error'] = 'Vui lòng nhập tên khóa học';
            Helpers::redirect('courses/create');
            return;
        }
        
        $courseModel = new Course();
        $courseId = $courseModel->create([
            'title' => $title,
            'subject' => $subject,
            'description' => $description,
            'teacher_id' => Auth::id()
        ]);
        
        $_SESSION['success'] = 'Tạo khóa học thành công!';
        Helpers::redirect("course&id=$courseId");
    }
    
    public function edit() {
        Auth::requireRoles(['teacher', 'admin']);
        
        $courseId = $_GET['id'] ?? 0;
        $courseModel = new Course();
        $course = $courseModel->findById($courseId);
        
        if (!$course) {
            http_response_code(404);
            die('Khóa học không tồn tại');
        }
        
        // Check permission
        if (Auth::role() === 'teacher' && $course['teacher_id'] != Auth::id()) {
            http_response_code(403);
            die('Bạn không có quyền chỉnh sửa khóa học này');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require __DIR__ . '/../views/courses/edit.php';
            return;
        }
        
        if (!Auth::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token không hợp lệ';
            Helpers::redirect("courses/edit&id=$courseId");
            return;
        }
        
        $title = Helpers::sanitizeInput($_POST['title'] ?? '');
        $subject = Helpers::sanitizeInput($_POST['subject'] ?? '');
        $description = Helpers::sanitizeInput($_POST['description'] ?? '');
        
        $courseModel->update($courseId, [
            'title' => $title,
            'subject' => $subject,
            'description' => $description
        ]);
        
        $_SESSION['success'] = 'Cập nhật khóa học thành công!';
        Helpers::redirect("course&id=$courseId");
    }
    
    public function delete() {
        Auth::requireRoles(['teacher', 'admin']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('courses');
            return;
        }
        
        if (!Auth::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token không hợp lệ';
            Helpers::redirect('courses');
            return;
        }
        
        $courseId = $_POST['course_id'] ?? 0;
        $courseModel = new Course();
        $course = $courseModel->findById($courseId);
        
        if (!$course) {
            $_SESSION['error'] = 'Khóa học không tồn tại';
            Helpers::redirect('courses');
            return;
        }
        
        // Check permission
        if (Auth::role() === 'teacher' && $course['teacher_id'] != Auth::id()) {
            $_SESSION['error'] = 'Bạn không có quyền xóa khóa học này';
            Helpers::redirect('courses');
            return;
        }
        
        $courseModel->delete($courseId);
        $_SESSION['success'] = 'Xóa khóa học thành công!';
        Helpers::redirect('courses');
    }
}
