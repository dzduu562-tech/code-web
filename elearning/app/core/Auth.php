<?php
require_once __DIR__ . '/DB.php';

class Auth {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    public function login($email, $password) {
        $user = $this->db->fetch(
            "SELECT * FROM users WHERE email = ?",
            [$email]
        );
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $this->setSession($user);
            return true;
        }
        
        return false;
    }
    
    public function register($name, $email, $password, $role = 'student') {
        // Check if email already exists
        $existingUser = $this->db->fetch(
            "SELECT id FROM users WHERE email = ?",
            [$email]
        );
        
        if ($existingUser) {
            return false; // Email already exists
        }
        
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        $userId = $this->db->insert('users', [
            'name' => $name,
            'email' => $email,
            'password_hash' => $passwordHash,
            'role' => $role
        ]);
        
        if ($userId) {
            $user = $this->db->fetch("SELECT * FROM users WHERE id = ?", [$userId]);
            $this->setSession($user);
            return true;
        }
        
        return false;
    }
    
    public function logout() {
        session_start();
        session_destroy();
        return true;
    }
    
    public function check() {
        session_start();
        return isset($_SESSION['user_id']);
    }
    
    public function user() {
        if (!$this->check()) {
            return null;
        }
        
        return $this->db->fetch(
            "SELECT * FROM users WHERE id = ?",
            [$_SESSION['user_id']]
        );
    }
    
    public function isAdmin() {
        $user = $this->user();
        return $user && $user['role'] === 'admin';
    }
    
    public function isTeacher() {
        $user = $this->user();
        return $user && $user['role'] === 'teacher';
    }
    
    public function isStudent() {
        $user = $this->user();
        return $user && $user['role'] === 'student';
    }
    
    public function requireAuth() {
        if (!$this->check()) {
            header('Location: /login');
            exit;
        }
    }
    
    public function requireRole($role) {
        $this->requireAuth();
        
        $user = $this->user();
        if (!$user || $user['role'] !== $role) {
            header('Location: /dashboard');
            exit;
        }
    }
    
    public function requireAdmin() {
        $this->requireRole('admin');
    }
    
    public function requireTeacher() {
        $this->requireRole('teacher');
    }
    
    public function requireStudent() {
        $this->requireRole('student');
    }
    
    private function setSession($user) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
    }
    
    public function generateCSRFToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }
    
    public function verifyCSRFToken($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    public function updateProfile($userId, $data) {
        $allowedFields = ['name', 'email', 'avatar'];
        $updateData = [];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }
        
        if (empty($updateData)) {
            return false;
        }
        
        return $this->db->update('users', $updateData, 'id = ?', [$userId]);
    }
    
    public function changePassword($userId, $currentPassword, $newPassword) {
        $user = $this->db->fetch("SELECT password_hash FROM users WHERE id = ?", [$userId]);
        
        if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
            return false;
        }
        
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        
        return $this->db->update('users', ['password_hash' => $newPasswordHash], 'id = ?', [$userId]);
    }
}