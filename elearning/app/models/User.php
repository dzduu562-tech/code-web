<?php
require_once __DIR__ . '/../core/DB.php';

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
    
    public function getAll($page = 1, $limit = ITEMS_PER_PAGE) {
        $offset = ($page - 1) * $limit;
        return $this->db->fetchAll(
            "SELECT * FROM users ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [$limit, $offset]
        );
    }
    
    public function getByRole($role, $page = 1, $limit = ITEMS_PER_PAGE) {
        $offset = ($page - 1) * $limit;
        return $this->db->fetchAll(
            "SELECT * FROM users WHERE role = ? ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [$role, $limit, $offset]
        );
    }
    
    public function create($data) {
        return $this->db->insert('users', $data);
    }
    
    public function update($id, $data) {
        return $this->db->update('users', $data, 'id = ?', [$id]);
    }
    
    public function delete($id) {
        return $this->db->delete('users', 'id = ?', [$id]);
    }
    
    public function getTeachers() {
        return $this->db->fetchAll(
            "SELECT * FROM users WHERE role = 'teacher' ORDER BY name ASC"
        );
    }
    
    public function getStudents() {
        return $this->db->fetchAll(
            "SELECT * FROM users WHERE role = 'student' ORDER BY name ASC"
        );
    }
    
    public function getTotalCount() {
        $result = $this->db->fetch("SELECT COUNT(*) as total FROM users");
        return $result['total'];
    }
    
    public function getCountByRole($role) {
        $result = $this->db->fetch(
            "SELECT COUNT(*) as total FROM users WHERE role = ?",
            [$role]
        );
        return $result['total'];
    }
    
    public function search($query, $role = null) {
        $sql = "SELECT * FROM users WHERE (name LIKE ? OR email LIKE ?)";
        $params = ["%{$query}%", "%{$query}%"];
        
        if ($role) {
            $sql .= " AND role = ?";
            $params[] = $role;
        }
        
        $sql .= " ORDER BY name ASC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getRecentUsers($limit = 10) {
        return $this->db->fetchAll(
            "SELECT * FROM users ORDER BY created_at DESC LIMIT ?",
            [$limit]
        );
    }
    
    public function updateLastLogin($id) {
        return $this->db->update('users', ['updated_at' => date('Y-m-d H:i:s')], 'id = ?', [$id]);
    }
}