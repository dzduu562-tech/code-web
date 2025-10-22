<?php

class Auth {
    
    public static function login($userId, $userRole, $userName) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Regenerate session ID to prevent session fixation
        session_regenerate_id(true);
        
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_role'] = $userRole;
        $_SESSION['user_name'] = $userName;
        $_SESSION['logged_in'] = true;
        $_SESSION['last_activity'] = time();
    }
    
    public static function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        session_unset();
        session_destroy();
    }
    
    public static function check() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if session is valid
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            return false;
        }
        
        // Check session timeout (30 minutes)
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
            self::logout();
            return false;
        }
        
        $_SESSION['last_activity'] = time();
        return true;
    }
    
    public static function user() {
        if (!self::check()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'role' => $_SESSION['user_role'] ?? null,
            'name' => $_SESSION['user_name'] ?? null,
        ];
    }
    
    public static function id() {
        return $_SESSION['user_id'] ?? null;
    }
    
    public static function role() {
        return $_SESSION['user_role'] ?? null;
    }
    
    public static function isAdmin() {
        return self::role() === 'admin';
    }
    
    public static function isTeacher() {
        return self::role() === 'teacher';
    }
    
    public static function isStudent() {
        return self::role() === 'student';
    }
    
    public static function requireLogin() {
        if (!self::check()) {
            header('Location: /elearning/public/index.php?route=login');
            exit;
        }
    }
    
    public static function requireRole($role) {
        self::requireLogin();
        if (self::role() !== $role) {
            http_response_code(403);
            die('Access denied');
        }
    }
    
    public static function requireRoles($roles) {
        self::requireLogin();
        if (!in_array(self::role(), $roles)) {
            http_response_code(403);
            die('Access denied');
        }
    }
    
    public static function generateCSRFToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }
    
    public static function verifyCSRFToken($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
