<?php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Helpers.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $auth;
    private $userModel;
    
    public function __construct() {
        $this->auth = new Auth();
        $this->userModel = new User();
    }
    
    public function showLogin() {
        if ($this->auth->check()) {
            Helpers::redirect('/dashboard');
        }
        
        $data = [
            'title' => 'Đăng nhập',
            'error' => Helpers::getFlash('error'),
            'old' => [
                'email' => Helpers::old('email'),
                'remember' => Helpers::old('remember')
            ]
        ];
        
        include __DIR__ . '/../views/auth/login.php';
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('/login');
        }
        
        $email = Helpers::sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        
        if (empty($email) || empty($password)) {
            Helpers::setFlash('error', 'Vui lòng nhập đầy đủ thông tin');
            Helpers::setOld(['email' => $email, 'remember' => $remember]);
            Helpers::redirect('/login');
        }
        
        if (!$this->auth->login($email, $password)) {
            Helpers::setFlash('error', 'Email hoặc mật khẩu không đúng');
            Helpers::setOld(['email' => $email, 'remember' => $remember]);
            Helpers::redirect('/login');
        }
        
        // Update last login
        $user = $this->auth->user();
        $this->userModel->updateLastLogin($user['id']);
        
        Helpers::redirect('/dashboard');
    }
    
    public function showRegister() {
        if ($this->auth->check()) {
            Helpers::redirect('/dashboard');
        }
        
        $data = [
            'title' => 'Đăng ký',
            'error' => Helpers::getFlash('error'),
            'old' => [
                'name' => Helpers::old('name'),
                'email' => Helpers::old('email'),
                'role' => Helpers::old('role')
            ]
        ];
        
        include __DIR__ . '/../views/auth/register.php';
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('/register');
        }
        
        $name = Helpers::sanitize($_POST['name'] ?? '');
        $email = Helpers::sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
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
        
        if ($password !== $confirmPassword) {
            $errors[] = 'Mật khẩu xác nhận không khớp';
        }
        
        if (!in_array($role, ['student', 'teacher'])) {
            $errors[] = 'Vai trò không hợp lệ';
        }
        
        if (!empty($errors)) {
            Helpers::setFlash('error', implode('<br>', $errors));
            Helpers::setOld(['name' => $name, 'email' => $email, 'role' => $role]);
            Helpers::redirect('/register');
        }
        
        if (!$this->auth->register($name, $email, $password, $role)) {
            Helpers::setFlash('error', 'Email đã được sử dụng');
            Helpers::setOld(['name' => $name, 'email' => $email, 'role' => $role]);
            Helpers::redirect('/register');
        }
        
        Helpers::setFlash('success', 'Đăng ký thành công! Chào mừng bạn đến với ' . APP_NAME);
        Helpers::redirect('/dashboard');
    }
    
    public function logout() {
        $this->auth->logout();
        Helpers::redirect('/');
    }
    
    public function showProfile() {
        $this->auth->requireAuth();
        
        $user = $this->auth->user();
        
        $data = [
            'title' => 'Thông tin cá nhân',
            'user' => $user,
            'error' => Helpers::getFlash('error'),
            'success' => Helpers::getFlash('success'),
            'old' => [
                'name' => Helpers::old('name', $user['name']),
                'email' => Helpers::old('email', $user['email'])
            ]
        ];
        
        include __DIR__ . '/../views/auth/profile.php';
    }
    
    public function updateProfile() {
        $this->auth->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('/profile');
        }
        
        $user = $this->auth->user();
        $name = Helpers::sanitize($_POST['name'] ?? '');
        $email = Helpers::sanitize($_POST['email'] ?? '');
        
        // Validation
        $errors = [];
        
        if (empty($name)) {
            $errors[] = 'Vui lòng nhập họ tên';
        }
        
        if (empty($email) || !Helpers::validateEmail($email)) {
            $errors[] = 'Vui lòng nhập email hợp lệ';
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
            Helpers::setOld(['name' => $name, 'email' => $email]);
            Helpers::redirect('/profile');
        }
        
        $updateData = ['name' => $name, 'email' => $email];
        
        // Handle avatar upload
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = Helpers::uploadFile($_FILES['avatar']);
            if ($uploadResult['success']) {
                // Delete old avatar if exists
                if ($user['avatar'] && file_exists($user['avatar'])) {
                    Helpers::deleteFile($user['avatar']);
                }
                $updateData['avatar'] = $uploadResult['file_path'];
            } else {
                Helpers::setFlash('error', $uploadResult['message']);
                Helpers::setOld(['name' => $name, 'email' => $email]);
                Helpers::redirect('/profile');
            }
        }
        
        if ($this->auth->updateProfile($user['id'], $updateData)) {
            Helpers::setFlash('success', 'Cập nhật thông tin thành công');
        } else {
            Helpers::setFlash('error', 'Có lỗi xảy ra khi cập nhật thông tin');
        }
        
        Helpers::redirect('/profile');
    }
    
    public function showChangePassword() {
        $this->auth->requireAuth();
        
        $data = [
            'title' => 'Đổi mật khẩu',
            'error' => Helpers::getFlash('error'),
            'success' => Helpers::getFlash('success')
        ];
        
        include __DIR__ . '/../views/auth/change-password.php';
    }
    
    public function changePassword() {
        $this->auth->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('/change-password');
        }
        
        $user = $this->auth->user();
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validation
        $errors = [];
        
        if (empty($currentPassword)) {
            $errors[] = 'Vui lòng nhập mật khẩu hiện tại';
        }
        
        if (empty($newPassword) || !Helpers::validatePassword($newPassword)) {
            $errors[] = 'Mật khẩu mới phải có ít nhất 6 ký tự';
        }
        
        if ($newPassword !== $confirmPassword) {
            $errors[] = 'Mật khẩu xác nhận không khớp';
        }
        
        if (!empty($errors)) {
            Helpers::setFlash('error', implode('<br>', $errors));
            Helpers::redirect('/change-password');
        }
        
        if ($this->auth->changePassword($user['id'], $currentPassword, $newPassword)) {
            Helpers::setFlash('success', 'Đổi mật khẩu thành công');
        } else {
            Helpers::setFlash('error', 'Mật khẩu hiện tại không đúng');
        }
        
        Helpers::redirect('/change-password');
    }
}