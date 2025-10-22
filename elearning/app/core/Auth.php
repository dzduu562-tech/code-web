<?php

class Auth {
    private static $user = null;
    
    public static function init() {
        session_start();
        if (isset($_SESSION['user_id'])) {
            self::$user = self::getUserById($_SESSION['user_id']);
        }
    }
    
    public static function login($email, $password) {
        $db = DB::getInstance();
        $user = $db->fetch(
            "SELECT * FROM users WHERE email = ?",
            [$email]
        );
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            self::$user = $user;
            return true;
        }
        
        return false;
    }
    
    public static function register($name, $email, $password, $role = 'student') {
        $db = DB::getInstance();
        
        // Check if email already exists
        if ($db->fetch("SELECT id FROM users WHERE email = ?", [$email])) {
            return false;
        }
        
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $userId = $db->insert('users', [
                'name' => $name,
                'email' => $email,
                'password_hash' => $passwordHash,
                'role' => $role
            ]);
            
            return $userId;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public static function logout() {
        session_destroy();
        self::$user = null;
    }
    
    public static function user() {
        return self::$user;
    }
    
    public static function id() {
        return self::$user ? self::$user['id'] : null;
    }
    
    public static function role() {
        return self::$user ? self::$user['role'] : null;
    }
    
    public static function check() {
        return self::$user !== null;
    }
    
    public static function guest() {
        return !self::check();
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
    
    public static function canAccess($resource, $action = null) {
        if (!self::check()) {
            return false;
        }
        
        $role = self::role();
        
        switch ($resource) {
            case 'admin':
                return $role === 'admin';
            case 'course_manage':
                return in_array($role, ['admin', 'teacher']);
            case 'course_enroll':
                return in_array($role, ['student']);
            case 'assignment_submit':
                return in_array($role, ['student']);
            case 'assignment_grade':
                return in_array($role, ['admin', 'teacher']);
            default:
                return true;
        }
    }
    
    public static function requireAuth() {
        if (!self::check()) {
            Router::getInstance()->redirect('/login');
        }
    }
    
    public static function requireRole($roles) {
        if (!self::check() || !in_array(self::role(), (array)$roles)) {
            Router::getInstance()->redirect('/');
        }
    }
    
    private static function getUserById($id) {
        $db = DB::getInstance();
        return $db->fetch("SELECT * FROM users WHERE id = ?", [$id]);
    }
    
    public static function updateProfile($data) {
        if (!self::check()) {
            return false;
        }
        
        $db = DB::getInstance();
        $userId = self::id();
        
        $allowedFields = ['name', 'email'];
        $updateData = [];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }
        
        if (empty($updateData)) {
            return false;
        }
        
        return $db->update('users', $updateData, 'id = ?', [$userId]);
    }
    
    public static function changePassword($currentPassword, $newPassword) {
        if (!self::check()) {
            return false;
        }
        
        $user = self::user();
        if (!password_verify($currentPassword, $user['password_hash'])) {
            return false;
        }
        
        $db = DB::getInstance();
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        
        return $db->update('users', ['password_hash' => $newPasswordHash], 'id = ?', [self::id()]);
    }
}