<?php

class User {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    public function find($id) {
        return $this->db->fetch("SELECT * FROM users WHERE id = ?", [$id]);
    }
    
    public function findByEmail($email) {
        return $this->db->fetch("SELECT * FROM users WHERE email = ?", [$email]);
    }
    
    public function getAll($limit = null, $offset = 0) {
        $sql = "SELECT * FROM users ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        return $this->db->fetchAll($sql);
    }
    
    public function getByRole($role, $limit = null, $offset = 0) {
        $sql = "SELECT * FROM users WHERE role = ? ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        return $this->db->fetchAll($sql, [$role]);
    }
    
    public function create($data) {
        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password']);
        
        return $this->db->insert('users', $data);
    }
    
    public function update($id, $data) {
        if (isset($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            unset($data['password']);
        }
        
        return $this->db->update('users', $data, 'id = ?', [$id]);
    }
    
    public function delete($id) {
        return $this->db->delete('users', 'id = ?', [$id]);
    }
    
    public function count() {
        return $this->db->count('users');
    }
    
    public function countByRole($role) {
        return $this->db->count('users', 'role = ?', [$role]);
    }
    
    public function search($query, $limit = 20) {
        $sql = "SELECT * FROM users WHERE name LIKE ? OR email LIKE ? ORDER BY name LIMIT ?";
        $searchTerm = "%{$query}%";
        return $this->db->fetchAll($sql, [$searchTerm, $searchTerm, $limit]);
    }
    
    public function getTeachers() {
        return $this->getByRole('teacher');
    }
    
    public function getStudents() {
        return $this->getByRole('student');
    }
    
    public function getRecentUsers($days = 7) {
        $sql = "SELECT * FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, [$days]);
    }
    
    public function updateLastLogin($id) {
        return $this->db->update('users', ['updated_at' => date('Y-m-d H:i:s')], 'id = ?', [$id]);
    }
    
    public function getAvatarUrl($user) {
        if ($user['avatar']) {
            return APP_URL . '/public/uploads/avatars/' . $user['avatar'];
        }
        return APP_URL . '/assets/img/default-avatar.png';
    }
    
    public function getDisplayName($user) {
        return $user['name'] ?: 'Người dùng';
    }
    
    public function getRoleDisplayName($user) {
        return Helpers::getRoleDisplayName($user['role']);
    }
}