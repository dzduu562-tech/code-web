<?php

class AdminController {
    
    public function __construct() {
        Auth::requireRole('admin');
    }
    
    public function index() {
        Helpers::redirect('dashboard');
    }
    
    public function users() {
        $userModel = new User();
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        $users = $userModel->getAll($perPage, $offset);
        $totalUsers = $userModel->count();
        $pagination = Helpers::paginate($totalUsers, $page, $perPage);
        
        require __DIR__ . '/../views/admin/users.php';
    }
    
    public function editUser() {
        $userId = $_GET['id'] ?? 0;
        $userModel = new User();
        
        $user = $userModel->findById($userId);
        
        if (!$user) {
            http_response_code(404);
            die('Người dùng không tồn tại');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require __DIR__ . '/../views/admin/edit_user.php';
            return;
        }
        
        if (!Auth::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token không hợp lệ';
            Helpers::redirect("admin/users/edit&id=$userId");
            return;
        }
        
        $name = Helpers::sanitizeInput($_POST['name'] ?? '');
        $email = Helpers::sanitizeInput($_POST['email'] ?? '');
        $role = Helpers::sanitizeInput($_POST['role'] ?? '');
        
        $userModel->update($userId, [
            'name' => $name,
            'email' => $email,
            'role' => $role
        ]);
        
        $_SESSION['success'] = 'Cập nhật người dùng thành công!';
        Helpers::redirect('admin/users');
    }
    
    public function deleteUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('admin/users');
            return;
        }
        
        if (!Auth::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token không hợp lệ';
            Helpers::redirect('admin/users');
            return;
        }
        
        $userId = $_POST['user_id'] ?? 0;
        
        // Can't delete yourself
        if ($userId == Auth::id()) {
            $_SESSION['error'] = 'Không thể xóa chính mình';
            Helpers::redirect('admin/users');
            return;
        }
        
        $userModel = new User();
        $userModel->delete($userId);
        
        $_SESSION['success'] = 'Xóa người dùng thành công!';
        Helpers::redirect('admin/users');
    }
    
    public function courses() {
        $courseModel = new Course();
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        $courses = $courseModel->getAll($perPage, $offset);
        $totalCourses = $courseModel->count();
        $pagination = Helpers::paginate($totalCourses, $page, $perPage);
        
        require __DIR__ . '/../views/admin/courses.php';
    }
}
