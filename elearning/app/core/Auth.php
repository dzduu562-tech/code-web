<?php

class Auth {
    private static $instance = null;
    private $user = null;
    private $isLoggedIn = false;

    private function __construct() {
        $this->startSession();
        $this->checkAuth();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            $config = require __DIR__ . '/../../config/config.php';
            
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_secure', 0); // Set to 1 for HTTPS
            ini_set('session.use_strict_mode', 1);
            
            session_name($config['security']['session_name']);
            session_start();
            
            // Regenerate session ID periodically
            if (!isset($_SESSION['created'])) {
                $_SESSION['created'] = time();
            } else if (time() - $_SESSION['created'] > 1800) { // 30 minutes
                session_regenerate_id(true);
                $_SESSION['created'] = time();
            }
        }
    }

    private function checkAuth() {
        if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {
            $this->loadUser($_SESSION['user_id']);
        }
    }

    private function loadUser($userId) {
        $db = DB::getInstance();
        $user = $db->fetch(
            "SELECT * FROM users WHERE id = ? AND is_active = 1",
            [$userId]
        );

        if ($user) {
            $this->user = $user;
            $this->isLoggedIn = true;
        } else {
            $this->logout();
        }
    }

    public function attempt($email, $password, $remember = false) {
        $db = DB::getInstance();
        $user = $db->fetch(
            "SELECT * FROM users WHERE email = ? AND is_active = 1",
            [$email]
        );

        if ($user && password_verify($password, $user['password_hash'])) {
            $this->login($user, $remember);
            return true;
        }

        return false;
    }

    public function login($user, $remember = false) {
        $this->user = $user;
        $this->isLoggedIn = true;

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['created'] = time();

        // Update last login
        $db = DB::getInstance();
        $db->update('users', 
            ['updated_at' => date('Y-m-d H:i:s')], 
            'id = ?', 
            [$user['id']]
        );

        if ($remember) {
            // Set remember me cookie (implement if needed)
            // setcookie('remember_token', $token, time() + (86400 * 30), '/');
        }
    }

    public function logout() {
        $this->user = null;
        $this->isLoggedIn = false;

        // Clear session
        $_SESSION = [];
        
        // Delete session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        session_destroy();
    }

    public function register($userData) {
        $db = DB::getInstance();

        // Check if email already exists
        if ($this->emailExists($userData['email'])) {
            return ['success' => false, 'message' => 'Email đã được sử dụng'];
        }

        // Validate password
        if (strlen($userData['password']) < 6) {
            return ['success' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự'];
        }

        try {
            $userId = $db->insert('users', [
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password_hash' => password_hash($userData['password'], PASSWORD_DEFAULT),
                'role' => $userData['role'] ?? 'student',
                'phone' => $userData['phone'] ?? null,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return ['success' => true, 'user_id' => $userId];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Có lỗi xảy ra khi tạo tài khoản'];
        }
    }

    public function emailExists($email) {
        $db = DB::getInstance();
        return $db->exists('users', 'email = ?', [$email]);
    }

    public function changePassword($currentPassword, $newPassword) {
        if (!$this->isLoggedIn()) {
            return ['success' => false, 'message' => 'Bạn chưa đăng nhập'];
        }

        if (!password_verify($currentPassword, $this->user['password_hash'])) {
            return ['success' => false, 'message' => 'Mật khẩu hiện tại không đúng'];
        }

        if (strlen($newPassword) < 6) {
            return ['success' => false, 'message' => 'Mật khẩu mới phải có ít nhất 6 ký tự'];
        }

        try {
            $db = DB::getInstance();
            $db->update('users', 
                ['password_hash' => password_hash($newPassword, PASSWORD_DEFAULT)],
                'id = ?',
                [$this->user['id']]
            );

            return ['success' => true, 'message' => 'Đổi mật khẩu thành công'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Có lỗi xảy ra khi đổi mật khẩu'];
        }
    }

    public function updateProfile($data) {
        if (!$this->isLoggedIn()) {
            return ['success' => false, 'message' => 'Bạn chưa đăng nhập'];
        }

        try {
            $db = DB::getInstance();
            $updateData = [
                'name' => $data['name'],
                'phone' => $data['phone'] ?? null,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $db->update('users', $updateData, 'id = ?', [$this->user['id']]);
            
            // Reload user data
            $this->loadUser($this->user['id']);
            
            return ['success' => true, 'message' => 'Cập nhật thông tin thành công'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật thông tin'];
        }
    }

    public function isLoggedIn() {
        return $this->isLoggedIn;
    }

    public function user() {
        return $this->user;
    }

    public function id() {
        return $this->user['id'] ?? null;
    }

    public function name() {
        return $this->user['name'] ?? 'Guest';
    }

    public function email() {
        return $this->user['email'] ?? null;
    }

    public function role() {
        return $this->user['role'] ?? 'guest';
    }

    public function isAdmin() {
        return $this->role() === 'admin';
    }

    public function isTeacher() {
        return $this->role() === 'teacher';
    }

    public function isStudent() {
        return $this->role() === 'student';
    }

    public function can($permission) {
        // Simple permission check based on role
        $permissions = [
            'admin' => ['*'], // Admin can do everything
            'teacher' => [
                'create_course', 'edit_course', 'delete_course',
                'create_lesson', 'edit_lesson', 'delete_lesson',
                'grade_assignment', 'manage_forum'
            ],
            'student' => [
                'view_course', 'submit_assignment', 'take_quiz', 'forum_post'
            ]
        ];

        $userRole = $this->role();
        
        if (!isset($permissions[$userRole])) {
            return false;
        }

        return in_array('*', $permissions[$userRole]) || in_array($permission, $permissions[$userRole]);
    }

    public function requireAuth() {
        if (!$this->isLoggedIn()) {
            $router = new Router();
            $router->redirect($router->url('login'));
        }
    }

    public function requireRole($role) {
        $this->requireAuth();
        
        if ($this->role() !== $role && !$this->isAdmin()) {
            http_response_code(403);
            die('Access denied');
        }
    }

    public function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public function verifyCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}