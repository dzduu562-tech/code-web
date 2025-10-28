<?php

require_once __DIR__ . '/../core/DB.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Helpers.php';
require_once __DIR__ . '/../core/Router.php';

class AuthController {
    private $auth;
    private $router;

    public function __construct() {
        $this->auth = Auth::getInstance();
        $this->router = new Router();
    }

    public function index($params = []) {
        // Redirect to login by default
        $this->router->redirect($this->router->url('login'));
    }

    public function login($params = []) {
        // If already logged in, redirect to dashboard
        if ($this->auth->isLoggedIn()) {
            $this->router->redirect($this->router->url('dashboard'));
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleLogin();
        }

        $data = [
            'title' => 'Đăng nhập - E-Learning Platform'
        ];

        $this->render('auth/login', $data);
    }

    private function handleLogin() {
        // Verify CSRF token
        $token = $_POST['_token'] ?? '';
        if (!$this->auth->verifyCSRFToken($token)) {
            $data = [
                'title' => 'Đăng nhập - E-Learning Platform',
                'error' => 'Token bảo mật không hợp lệ'
            ];
            return $this->render('auth/login', $data);
        }

        $email = Helpers::clean($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        $errors = [];

        if (empty($email)) {
            $errors[] = 'Vui lòng nhập email';
        } elseif (!Helpers::validateEmail($email)) {
            $errors[] = 'Email không hợp lệ';
        }

        if (empty($password)) {
            $errors[] = 'Vui lòng nhập mật khẩu';
        }

        if (!empty($errors)) {
            $data = [
                'title' => 'Đăng nhập - E-Learning Platform',
                'errors' => $errors,
                'old' => ['email' => $email]
            ];
            return $this->render('auth/login', $data);
        }

        // Attempt login
        if ($this->auth->attempt($email, $password, $remember)) {
            // Redirect to intended page or dashboard
            $redirect = $_SESSION['intended_url'] ?? $this->router->url('dashboard');
            unset($_SESSION['intended_url']);
            $this->router->redirect($redirect);
        } else {
            $data = [
                'title' => 'Đăng nhập - E-Learning Platform',
                'error' => 'Email hoặc mật khẩu không đúng',
                'old' => ['email' => $email]
            ];
            $this->render('auth/login', $data);
        }
    }

    public function register($params = []) {
        // If already logged in, redirect to dashboard
        if ($this->auth->isLoggedIn()) {
            $this->router->redirect($this->router->url('dashboard'));
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleRegister();
        }

        $data = [
            'title' => 'Đăng ký - E-Learning Platform'
        ];

        $this->render('auth/register', $data);
    }

    private function handleRegister() {
        // Verify CSRF token
        $token = $_POST['_token'] ?? '';
        if (!$this->auth->verifyCSRFToken($token)) {
            $data = [
                'title' => 'Đăng ký - E-Learning Platform',
                'error' => 'Token bảo mật không hợp lệ'
            ];
            return $this->render('auth/register', $data);
        }

        $name = Helpers::clean($_POST['name'] ?? '');
        $email = Helpers::clean($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $phone = Helpers::clean($_POST['phone'] ?? '');
        $role = Helpers::clean($_POST['role'] ?? 'student');

        $errors = [];

        // Validate name
        if (empty($name)) {
            $errors[] = 'Vui lòng nhập họ tên';
        } elseif (strlen($name) < 2) {
            $errors[] = 'Họ tên phải có ít nhất 2 ký tự';
        }

        // Validate email
        if (empty($email)) {
            $errors[] = 'Vui lòng nhập email';
        } elseif (!Helpers::validateEmail($email)) {
            $errors[] = 'Email không hợp lệ';
        } elseif ($this->auth->emailExists($email)) {
            $errors[] = 'Email đã được sử dụng';
        }

        // Validate password
        if (empty($password)) {
            $errors[] = 'Vui lòng nhập mật khẩu';
        } elseif (strlen($password) < 6) {
            $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
        }

        if ($password !== $passwordConfirm) {
            $errors[] = 'Xác nhận mật khẩu không khớp';
        }

        // Validate role
        if (!in_array($role, ['student', 'teacher'])) {
            $role = 'student';
        }

        if (!empty($errors)) {
            $data = [
                'title' => 'Đăng ký - E-Learning Platform',
                'errors' => $errors,
                'old' => [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'role' => $role
                ]
            ];
            return $this->render('auth/register', $data);
        }

        // Register user
        $result = $this->auth->register([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'phone' => $phone,
            'role' => $role
        ]);

        if ($result['success']) {
            // Auto login after registration
            $this->auth->attempt($email, $password);
            
            $data = [
                'title' => 'Đăng ký thành công - E-Learning Platform',
                'success' => 'Đăng ký thành công! Chào mừng bạn đến với E-Learning Platform.'
            ];
            
            // Redirect to dashboard after 2 seconds
            header("refresh:2;url=" . $this->router->url('dashboard'));
            $this->render('auth/success', $data);
        } else {
            $data = [
                'title' => 'Đăng ký - E-Learning Platform',
                'error' => $result['message'],
                'old' => [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'role' => $role
                ]
            ];
            $this->render('auth/register', $data);
        }
    }

    public function logout($params = []) {
        $this->auth->logout();
        $this->router->redirect($this->router->url('home'));
    }

    public function forgotPassword($params = []) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleForgotPassword();
        }

        $data = [
            'title' => 'Quên mật khẩu - E-Learning Platform'
        ];

        $this->render('auth/forgot-password', $data);
    }

    private function handleForgotPassword() {
        $email = Helpers::clean($_POST['email'] ?? '');

        if (empty($email) || !Helpers::validateEmail($email)) {
            $data = [
                'title' => 'Quên mật khẩu - E-Learning Platform',
                'error' => 'Vui lòng nhập email hợp lệ'
            ];
            return $this->render('auth/forgot-password', $data);
        }

        // Check if email exists
        if (!$this->auth->emailExists($email)) {
            $data = [
                'title' => 'Quên mật khẩu - E-Learning Platform',
                'error' => 'Email không tồn tại trong hệ thống'
            ];
            return $this->render('auth/forgot-password', $data);
        }

        // In a real application, you would:
        // 1. Generate a password reset token
        // 2. Save it to database with expiration
        // 3. Send email with reset link
        
        // For demo purposes, we'll just show a success message
        $data = [
            'title' => 'Quên mật khẩu - E-Learning Platform',
            'success' => 'Hướng dẫn đặt lại mật khẩu đã được gửi đến email của bạn.'
        ];

        $this->render('auth/forgot-password', $data);
    }

    public function resetPassword($params = []) {
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            $this->router->redirect($this->router->url('login'));
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleResetPassword($token);
        }

        $data = [
            'title' => 'Đặt lại mật khẩu - E-Learning Platform',
            'token' => $token
        ];

        $this->render('auth/reset-password', $data);
    }

    private function handleResetPassword($token) {
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        $errors = [];

        if (empty($password)) {
            $errors[] = 'Vui lòng nhập mật khẩu mới';
        } elseif (strlen($password) < 6) {
            $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
        }

        if ($password !== $passwordConfirm) {
            $errors[] = 'Xác nhận mật khẩu không khớp';
        }

        if (!empty($errors)) {
            $data = [
                'title' => 'Đặt lại mật khẩu - E-Learning Platform',
                'errors' => $errors,
                'token' => $token
            ];
            return $this->render('auth/reset-password', $data);
        }

        // In a real application, you would:
        // 1. Verify the token is valid and not expired
        // 2. Update the user's password
        // 3. Invalidate the token
        
        // For demo purposes, we'll just show success
        $data = [
            'title' => 'Đặt lại mật khẩu thành công - E-Learning Platform',
            'success' => 'Mật khẩu đã được đặt lại thành công. Bạn có thể đăng nhập với mật khẩu mới.'
        ];

        // Redirect to login after 3 seconds
        header("refresh:3;url=" . $this->router->url('login'));
        $this->render('auth/success', $data);
    }

    public function profile($params = []) {
        $this->auth->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleUpdateProfile();
        }

        $data = [
            'title' => 'Thông tin cá nhân - E-Learning Platform',
            'user' => $this->auth->user()
        ];

        $this->render('auth/profile', $data);
    }

    private function handleUpdateProfile() {
        // Verify CSRF token
        $token = $_POST['_token'] ?? '';
        if (!$this->auth->verifyCSRFToken($token)) {
            $data = [
                'title' => 'Thông tin cá nhân - E-Learning Platform',
                'error' => 'Token bảo mật không hợp lệ',
                'user' => $this->auth->user()
            ];
            return $this->render('auth/profile', $data);
        }

        $name = Helpers::clean($_POST['name'] ?? '');
        $phone = Helpers::clean($_POST['phone'] ?? '');

        $errors = [];

        if (empty($name)) {
            $errors[] = 'Vui lòng nhập họ tên';
        } elseif (strlen($name) < 2) {
            $errors[] = 'Họ tên phải có ít nhất 2 ký tự';
        }

        if (!empty($errors)) {
            $data = [
                'title' => 'Thông tin cá nhân - E-Learning Platform',
                'errors' => $errors,
                'user' => $this->auth->user(),
                'old' => ['name' => $name, 'phone' => $phone]
            ];
            return $this->render('auth/profile', $data);
        }

        $result = $this->auth->updateProfile([
            'name' => $name,
            'phone' => $phone
        ]);

        $data = [
            'title' => 'Thông tin cá nhân - E-Learning Platform',
            'user' => $this->auth->user()
        ];

        if ($result['success']) {
            $data['success'] = $result['message'];
        } else {
            $data['error'] = $result['message'];
        }

        $this->render('auth/profile', $data);
    }

    public function changePassword($params = []) {
        $this->auth->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleChangePassword();
        }

        $data = [
            'title' => 'Đổi mật khẩu - E-Learning Platform'
        ];

        $this->render('auth/change-password', $data);
    }

    private function handleChangePassword() {
        // Verify CSRF token
        $token = $_POST['_token'] ?? '';
        if (!$this->auth->verifyCSRFToken($token)) {
            $data = [
                'title' => 'Đổi mật khẩu - E-Learning Platform',
                'error' => 'Token bảo mật không hợp lệ'
            ];
            return $this->render('auth/change-password', $data);
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $errors = [];

        if (empty($currentPassword)) {
            $errors[] = 'Vui lòng nhập mật khẩu hiện tại';
        }

        if (empty($newPassword)) {
            $errors[] = 'Vui lòng nhập mật khẩu mới';
        } elseif (strlen($newPassword) < 6) {
            $errors[] = 'Mật khẩu mới phải có ít nhất 6 ký tự';
        }

        if ($newPassword !== $confirmPassword) {
            $errors[] = 'Xác nhận mật khẩu mới không khớp';
        }

        if (!empty($errors)) {
            $data = [
                'title' => 'Đổi mật khẩu - E-Learning Platform',
                'errors' => $errors
            ];
            return $this->render('auth/change-password', $data);
        }

        $result = $this->auth->changePassword($currentPassword, $newPassword);

        $data = [
            'title' => 'Đổi mật khẩu - E-Learning Platform'
        ];

        if ($result['success']) {
            $data['success'] = $result['message'];
        } else {
            $data['error'] = $result['message'];
        }

        $this->render('auth/change-password', $data);
    }

    private function render($view, $data = []) {
        // Extract data to variables
        extract($data);

        // Include layout
        include __DIR__ . '/../views/layouts/auth.php';
    }
}