<?php

class User {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        return $this->db->fetchOne($sql, ['email' => $email]);
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    public function create($data) {
        return $this->db->insert('users', $data);
    }
    
    public function update($id, $data) {
        return $this->db->update('users', $data, 'id = :id', ['id' => $id]);
    }
    
    public function delete($id) {
        return $this->db->delete('users', 'id = :id', ['id' => $id]);
    }
    
    public function getAll($limit = 100, $offset = 0) {
        $sql = "SELECT * FROM users ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        return $this->db->fetchAll($sql, ['limit' => $limit, 'offset' => $offset]);
    }
    
    public function getAllByRole($role) {
        $sql = "SELECT * FROM users WHERE role = :role ORDER BY name ASC";
        return $this->db->fetchAll($sql, ['role' => $role]);
    }
    
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM users";
        $result = $this->db->fetchOne($sql);
        return $result['total'] ?? 0;
    }
    
    public function countByRole($role) {
        $sql = "SELECT COUNT(*) as total FROM users WHERE role = :role";
        $result = $this->db->fetchOne($sql, ['role' => $role]);
        return $result['total'] ?? 0;
    }
    
    public function verifyPassword($plain, $hash) {
        return password_verify($plain, $hash);
    }
    
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}
