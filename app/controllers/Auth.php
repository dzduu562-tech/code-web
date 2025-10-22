<?php
/**
 * Auth Controller - Authentication & Authorization
 */

class Auth extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('User');
    }

    /**
     * Login page
     */
    public function login() {
        // Redirect if already logged in
        if ($this->isLoggedIn()) {
            $this->redirectToDashboard();
        }

        // Handle POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processLogin();
        }

        // Show login form
        $data = [
            'title' => 'Đăng nhập',
            'errors' => []
        ];

        $this->view('auth/login', $data);
    }

    /**
     * Process login
     */
    private function processLogin() {
        $email = clean($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        $errors = [];

        // Validation
        if (empty($email)) {
            $errors['email'] = 'Vui lòng nhập email';
        }
        if (empty($password)) {
            $errors['password'] = 'Vui lòng nhập mật khẩu';
        }

        if (!empty($errors)) {
            $this->view('auth/login', ['title' => 'Đăng nhập', 'errors' => $errors, 'old' => $_POST]);
            return;
        }

        // Find user
        $user = $this->userModel->findByEmail($email);

        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            $errors['login'] = 'Email hoặc mật khẩu không đúng';
            $this->view('auth/login', ['title' => 'Đăng nhập', 'errors' => $errors, 'old' => $_POST]);
            return;
        }

        // Check if account is active
        if ($user['status'] !== 'active') {
            $errors['login'] = 'Tài khoản của bạn đã bị khóa hoặc chưa được kích hoạt';
            $this->view('auth/login', ['title' => 'Đăng nhập', 'errors' => $errors, 'old' => $_POST]);
            return;
        }

        // Login successful - create session
        $this->createUserSession($user);

        // Update last login
        $this->userModel->updateLastLogin($user['id']);

        // Set remember me cookie
        if ($remember) {
            $token = generateToken();
            setcookie('remember_token', $token, time() + (86400 * 30), '/'); // 30 days
        }

        // Log activity
        $this->logActivity($user['id'], 'Đăng nhập hệ thống');

        // Redirect to dashboard
        flash('login_success', 'Đăng nhập thành công!', 'success');
        $this->redirectToDashboard();
    }

    /**
     * Register page
     */
    public function register() {
        // Redirect if already logged in
        if ($this->isLoggedIn()) {
            $this->redirectToDashboard();
        }

        // Handle POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processRegister();
        }

        // Show register form
        $data = [
            'title' => 'Đăng ký tài khoản',
            'errors' => []
        ];

        $this->view('auth/register', $data);
    }

    /**
     * Process registration
     */
    private function processRegister() {
        $data = [
            'full_name' => clean($_POST['full_name'] ?? ''),
            'email' => clean($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? '',
            'role' => 'student' // Default role
        ];

        $errors = [];

        // Validation
        if (empty($data['full_name'])) {
            $errors['full_name'] = 'Vui lòng nhập họ tên';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Vui lòng nhập email';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        } elseif ($this->userModel->findByEmail($data['email'])) {
            $errors['email'] = 'Email đã được sử dụng';
        }

        if (empty($data['password'])) {
            $errors['password'] = 'Vui lòng nhập mật khẩu';
        } elseif (strlen($data['password']) < 6) {
            $errors['password'] = 'Mật khẩu phải có ít nhất 6 ký tự';
        }

        if ($data['password'] !== $data['confirm_password']) {
            $errors['confirm_password'] = 'Mật khẩu xác nhận không khớp';
        }

        if (!empty($errors)) {
            $this->view('auth/register', ['title' => 'Đăng ký', 'errors' => $errors, 'old' => $_POST]);
            return;
        }

        // Create user
        unset($data['confirm_password']);
        
        try {
            $this->userModel->create($data);
            
            // Create student profile
            $user = $this->userModel->findByEmail($data['email']);
            $this->createStudentProfile($user['id']);
            
            flash('register_success', 'Đăng ký thành công! Vui lòng đăng nhập.', 'success');
            redirect('auth/login');
        } catch (Exception $e) {
            $errors['register'] = 'Đã xảy ra lỗi khi đăng ký. Vui lòng thử lại.';
            $this->view('auth/register', ['title' => 'Đăng ký', 'errors' => $errors, 'old' => $_POST]);
        }
    }

    /**
     * Logout
     */
    public function logout() {
        // Log activity
        if ($this->isLoggedIn()) {
            $this->logActivity($_SESSION['user_id'], 'Đăng xuất hệ thống');
        }

        // Clear session
        session_unset();
        session_destroy();

        // Clear cookies
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
        }

        flash('logout_success', 'Đã đăng xuất thành công!', 'success');
        redirect('auth/login');
    }

    /**
     * Create user session
     */
    private function createUserSession($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['avatar'] = $user['avatar'];
        $_SESSION['status'] = $user['status'];
    }

    /**
     * Create student profile
     */
    private function createStudentProfile($userId) {
        $db = Database::getInstance();
        $studentCode = 'HS' . str_pad($userId, 5, '0', STR_PAD_LEFT);
        
        $sql = "INSERT INTO student_profiles (user_id, student_code) VALUES (:user_id, :student_code)";
        $db->query($sql, [
            'user_id' => $userId,
            'student_code' => $studentCode
        ]);
    }

    /**
     * Redirect to appropriate dashboard
     */
    private function redirectToDashboard() {
        $role = $_SESSION['role'] ?? 'student';
        
        switch ($role) {
            case 'admin':
                redirect('admin/dashboard');
                break;
            case 'teacher':
                redirect('teacher/dashboard');
                break;
            case 'student':
                redirect('student/dashboard');
                break;
            default:
                redirect('home');
        }
    }

    /**
     * Log activity
     */
    private function logActivity($userId, $action) {
        $db = Database::getInstance();
        $sql = "INSERT INTO activity_logs (user_id, action, ip_address, user_agent) 
                VALUES (:user_id, :action, :ip, :ua)";
        
        $db->query($sql, [
            'user_id' => $userId,
            'action' => $action,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
            'ua' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    }
}
