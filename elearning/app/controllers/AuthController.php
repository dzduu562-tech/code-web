<?php

class AuthController {
    
    public function showLogin() {
        if (Auth::check()) {
            Helpers::redirect('dashboard');
            return;
        }
        require __DIR__ . '/../views/auth/login.php';
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('login');
            return;
        }
        
        // Validate CSRF token
        if (!Auth::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token không hợp lệ';
            Helpers::redirect('login');
            return;
        }
        
        $email = Helpers::sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
            Helpers::redirect('login');
            return;
        }
        
        $userModel = new User();
        $user = $userModel->findByEmail($email);
        
        if (!$user || !$userModel->verifyPassword($password, $user['password_hash'])) {
            $_SESSION['error'] = 'Email hoặc mật khẩu không đúng';
            Helpers::redirect('login');
            return;
        }
        
        Auth::login($user['id'], $user['role'], $user['name']);
        Helpers::redirect('dashboard');
    }
    
    public function showRegister() {
        if (Auth::check()) {
            Helpers::redirect('dashboard');
            return;
        }
        require __DIR__ . '/../views/auth/register.php';
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('register');
            return;
        }
        
        // Validate CSRF token
        if (!Auth::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token không hợp lệ';
            Helpers::redirect('register');
            return;
        }
        
        $name = Helpers::sanitizeInput($_POST['name'] ?? '');
        $email = Helpers::sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $role = Helpers::sanitizeInput($_POST['role'] ?? 'student');
        
        // Validation
        if (empty($name) || empty($email) || empty($password)) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
            Helpers::redirect('register');
            return;
        }
        
        if (!Helpers::validateEmail($email)) {
            $_SESSION['error'] = 'Email không hợp lệ';
            Helpers::redirect('register');
            return;
        }
        
        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Mật khẩu xác nhận không khớp';
            Helpers::redirect('register');
            return;
        }
        
        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Mật khẩu phải có ít nhất 6 ký tự';
            Helpers::redirect('register');
            return;
        }
        
        // Only allow student and teacher registration
        if (!in_array($role, ['student', 'teacher'])) {
            $role = 'student';
        }
        
        $userModel = new User();
        
        // Check if email exists
        if ($userModel->findByEmail($email)) {
            $_SESSION['error'] = 'Email đã được sử dụng';
            Helpers::redirect('register');
            return;
        }
        
        // Create user
        $userId = $userModel->create([
            'name' => $name,
            'email' => $email,
            'password_hash' => $userModel->hashPassword($password),
            'role' => $role
        ]);
        
        if ($userId) {
            $_SESSION['success'] = 'Đăng ký thành công! Vui lòng đăng nhập.';
            Helpers::redirect('login');
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
            Helpers::redirect('register');
        }
    }
    
    public function logout() {
        Auth::logout();
        Helpers::redirect('home');
    }
}
