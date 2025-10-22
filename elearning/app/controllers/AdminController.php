<?php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Helpers.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/../models/Assignment.php';
require_once __DIR__ . '/../models/Notification.php';

class AdminController {
    private $auth;
    private $userModel;
    private $courseModel;
    private $lessonModel;
    private $assignmentModel;
    private $notificationModel;
    
    public function __construct() {
        $this->auth = new Auth();
        $this->userModel = new User();
        $this->courseModel = new Course();
        $this->lessonModel = new Lesson();
        $this->assignmentModel = new Assignment();
        $this->notificationModel = new Notification();
    }
    
    public function dashboard() {
        $this->auth->requireAdmin();
        
        // Get statistics
        $totalUsers = $this->userModel->getTotalCount();
        $totalTeachers = $this->userModel->getCountByRole('teacher');
        $totalStudents = $this->userModel->getCountByRole('student');
        $totalCourses = $this->courseModel->getTotalCount(true);
        
        // Get recent activities
        $recentUsers = $this->userModel->getRecentUsers(5);
        $recentCourses = $this->courseModel->getRecentCourses(5);
        
        $data = [
            'title' => 'Bảng điều khiển Admin',
            'stats' => [
                'total_users' => $totalUsers,
                'total_teachers' => $totalTeachers,
                'total_students' => $totalStudents,
                'total_courses' => $totalCourses
            ],
            'recent_users' => $recentUsers,
            'recent_courses' => $recentCourses
        ];
        
        include __DIR__ . '/../views/admin/dashboard.php';
    }
    
    public function users() {
        $this->auth->requireAdmin();
        
        $page = (int)($_GET['page'] ?? 1);
        $role = $_GET['role'] ?? null;
        $search = $_GET['search'] ?? null;
        
        if ($search) {
            $users = $this->userModel->search($search, $role);
            $pagination = null;
        } else {
            if ($role) {
                $users = $this->userModel->getByRole($role, $page);
                $totalUsers = $this->userModel->getCountByRole($role);
            } else {
                $users = $this->userModel->getAll($page);
                $totalUsers = $this->userModel->getTotalCount();
            }
            $pagination = Helpers::paginate($totalUsers, $page);
        }
        
        $data = [
            'title' => 'Quản lý người dùng',
            'users' => $users,
            'pagination' => $pagination,
            'current_role' => $role,
            'search_query' => $search
        ];
        
        include __DIR__ . '/../views/admin/users.php';
    }
    
    public function courses() {
        $this->auth->requireAdmin();
        
        $page = (int)($_GET['page'] ?? 1);
        $published = $_GET['published'] ?? null;
        $search = $_GET['search'] ?? null;
        
        if ($search) {
            $courses = $this->courseModel->search($search);
            $pagination = null;
        } else {
            $courses = $this->courseModel->getAll($page, ITEMS_PER_PAGE, $published);
            $totalCourses = $this->courseModel->getTotalCount($published);
            $pagination = Helpers::paginate($totalCourses, $page);
        }
        
        $data = [
            'title' => 'Quản lý khóa học',
            'courses' => $courses,
            'pagination' => $pagination,
            'current_published' => $published,
            'search_query' => $search
        ];
        
        include __DIR__ . '/../views/admin/courses.php';
    }
    
    public function createUser() {
        $this->auth->requireAdmin();
        
        $data = [
            'title' => 'Tạo người dùng mới',
            'error' => Helpers::getFlash('error'),
            'success' => Helpers::getFlash('success'),
            'old' => [
                'name' => Helpers::old('name'),
                'email' => Helpers::old('email'),
                'role' => Helpers::old('role')
            ]
        ];
        
        include __DIR__ . '/../views/admin/create-user.php';
    }
    
    public function storeUser() {
        $this->auth->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('/admin/users');
        }
        
        if (!$this->auth->verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            Helpers::setFlash('error', 'Token không hợp lệ');
            Helpers::redirect('/admin/users');
        }
        
        $name = Helpers::sanitize($_POST['name'] ?? '');
        $email = Helpers::sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = Helpers::sanitize($_POST['role'] ?? 'student');
        
        // Validation
        $errors = [];
        
        if (empty($name)) {
            $errors[] = 'Vui lòng nhập họ tên';
        }
        
        if (empty($email) || !Helpers::validateEmail($email)) {
            $errors[] = 'Vui lòng nhập email hợp lệ';
        }
        
        if (empty($password) || !Helpers::validatePassword($password)) {
            $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
        }
        
        if (!in_array($role, ['admin', 'teacher', 'student'])) {
            $errors[] = 'Vai trò không hợp lệ';
        }
        
        if (!empty($errors)) {
            Helpers::setFlash('error', implode('<br>', $errors));
            Helpers::setOld(['name' => $name, 'email' => $email, 'role' => $role]);
            Helpers::redirect('/admin/users/create');
        }
        
        $userData = [
            'name' => $name,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role
        ];
        
        if ($this->userModel->create($userData)) {
            Helpers::setFlash('success', 'Tạo người dùng thành công');
        } else {
            Helpers::setFlash('error', 'Email đã được sử dụng');
        }
        
        Helpers::redirect('/admin/users');
    }
    
    public function editUser() {
        $this->auth->requireAdmin();
        
        $id = (int)($_GET['id'] ?? 0);
        $user = $this->userModel->find($id);
        
        if (!$user) {
            Helpers::setFlash('error', 'Người dùng không tồn tại');
            Helpers::redirect('/admin/users');
        }
        
        $data = [
            'title' => 'Chỉnh sửa người dùng',
            'user' => $user,
            'error' => Helpers::getFlash('error'),
            'success' => Helpers::getFlash('success'),
            'old' => [
                'name' => Helpers::old('name', $user['name']),
                'email' => Helpers::old('email', $user['email']),
                'role' => Helpers::old('role', $user['role'])
            ]
        ];
        
        include __DIR__ . '/../views/admin/edit-user.php';
    }
    
    public function updateUser() {
        $this->auth->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('/admin/users');
        }
        
        if (!$this->auth->verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            Helpers::setFlash('error', 'Token không hợp lệ');
            Helpers::redirect('/admin/users');
        }
        
        $id = (int)($_POST['id'] ?? 0);
        $user = $this->userModel->find($id);
        
        if (!$user) {
            Helpers::setFlash('error', 'Người dùng không tồn tại');
            Helpers::redirect('/admin/users');
        }
        
        $name = Helpers::sanitize($_POST['name'] ?? '');
        $email = Helpers::sanitize($_POST['email'] ?? '');
        $role = Helpers::sanitize($_POST['role'] ?? 'student');
        $password = $_POST['password'] ?? '';
        
        // Validation
        $errors = [];
        
        if (empty($name)) {
            $errors[] = 'Vui lòng nhập họ tên';
        }
        
        if (empty($email) || !Helpers::validateEmail($email)) {
            $errors[] = 'Vui lòng nhập email hợp lệ';
        }
        
        if (!in_array($role, ['admin', 'teacher', 'student'])) {
            $errors[] = 'Vai trò không hợp lệ';
        }
        
        // Check if email is already used by another user
        if ($email !== $user['email']) {
            $existingUser = $this->userModel->findByEmail($email);
            if ($existingUser) {
                $errors[] = 'Email đã được sử dụng';
            }
        }
        
        if (!empty($errors)) {
            Helpers::setFlash('error', implode('<br>', $errors));
            Helpers::redirect("/admin/users/edit?id={$id}");
        }
        
        $userData = [
            'name' => $name,
            'email' => $email,
            'role' => $role
        ];
        
        // Update password if provided
        if (!empty($password)) {
            if (!Helpers::validatePassword($password)) {
                Helpers::setFlash('error', 'Mật khẩu phải có ít nhất 6 ký tự');
                Helpers::redirect("/admin/users/edit?id={$id}");
            }
            $userData['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }
        
        if ($this->userModel->update($id, $userData)) {
            Helpers::setFlash('success', 'Cập nhật người dùng thành công');
        } else {
            Helpers::setFlash('error', 'Có lỗi xảy ra khi cập nhật người dùng');
        }
        
        Helpers::redirect('/admin/users');
    }
    
    public function deleteUser() {
        $this->auth->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('/admin/users');
        }
        
        if (!$this->auth->verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            Helpers::setFlash('error', 'Token không hợp lệ');
            Helpers::redirect('/admin/users');
        }
        
        $id = (int)($_POST['id'] ?? 0);
        $user = $this->auth->user();
        
        // Prevent admin from deleting themselves
        if ($id == $user['id']) {
            Helpers::setFlash('error', 'Bạn không thể xóa chính mình');
            Helpers::redirect('/admin/users');
        }
        
        if ($this->userModel->delete($id)) {
            Helpers::setFlash('success', 'Xóa người dùng thành công');
        } else {
            Helpers::setFlash('error', 'Có lỗi xảy ra khi xóa người dùng');
        }
        
        Helpers::redirect('/admin/users');
    }
    
    public function publishCourse() {
        $this->auth->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('/admin/courses');
        }
        
        if (!$this->auth->verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            Helpers::setFlash('error', 'Token không hợp lệ');
            Helpers::redirect('/admin/courses');
        }
        
        $id = (int)($_POST['id'] ?? 0);
        $course = $this->courseModel->find($id);
        
        if (!$course) {
            Helpers::setFlash('error', 'Khóa học không tồn tại');
            Helpers::redirect('/admin/courses');
        }
        
        if ($this->courseModel->publish($id)) {
            Helpers::setFlash('success', 'Xuất bản khóa học thành công');
        } else {
            Helpers::setFlash('error', 'Có lỗi xảy ra khi xuất bản khóa học');
        }
        
        Helpers::redirect('/admin/courses');
    }
    
    public function unpublishCourse() {
        $this->auth->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('/admin/courses');
        }
        
        if (!$this->auth->verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            Helpers::setFlash('error', 'Token không hợp lệ');
            Helpers::redirect('/admin/courses');
        }
        
        $id = (int)($_POST['id'] ?? 0);
        $course = $this->courseModel->find($id);
        
        if (!$course) {
            Helpers::setFlash('error', 'Khóa học không tồn tại');
            Helpers::redirect('/admin/courses');
        }
        
        if ($this->courseModel->unpublish($id)) {
            Helpers::setFlash('success', 'Hủy xuất bản khóa học thành công');
        } else {
            Helpers::setFlash('error', 'Có lỗi xảy ra khi hủy xuất bản khóa học');
        }
        
        Helpers::redirect('/admin/courses');
    }
    
    public function deleteCourse() {
        $this->auth->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('/admin/courses');
        }
        
        if (!$this->auth->verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            Helpers::setFlash('error', 'Token không hợp lệ');
            Helpers::redirect('/admin/courses');
        }
        
        $id = (int)($_POST['id'] ?? 0);
        $course = $this->courseModel->find($id);
        
        if (!$course) {
            Helpers::setFlash('error', 'Khóa học không tồn tại');
            Helpers::redirect('/admin/courses');
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
        
        Helpers::redirect('/admin/courses');
    }
}