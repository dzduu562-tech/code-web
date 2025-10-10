<?php
/**
 * Base Model Class - Database operations
 */

class Model {
    protected $db;
    protected $table;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Get all records
     */
    public function getAll($orderBy = 'id DESC', $limit = null) {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy}";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Find by ID
     */
    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        return $this->db->query($sql, ['id' => $id])->fetch();
    }

    /**
     * Find by column
     */
    public function findBy($column, $value) {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :value";
        return $this->db->query($sql, ['value' => $value])->fetchAll();
    }

    /**
     * Find one by column
     */
    public function findOneBy($column, $value) {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :value LIMIT 1";
        return $this->db->query($sql, ['value' => $value])->fetch();
    }

    /**
     * Insert record
     */
    public function insert($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        return $this->db->query($sql, $data);
    }

    /**
     * Update record
     */
    public function update($id, $data) {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "{$key} = :{$key}";
        }
        $set = implode(', ', $set);
        
        $data['id'] = $id;
        $sql = "UPDATE {$this->table} SET {$set} WHERE id = :id";
        return $this->db->query($sql, $data);
    }

    /**
     * Delete record
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->db->query($sql, ['id' => $id]);
    }

    /**
     * Count records
     */
    public function count($where = '') {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        $result = $this->db->query($sql)->fetch();
        return $result['total'] ?? 0;
    }

    /**
     * Execute custom query
     */
    public function query($sql, $params = []) {
        return $this->db->query($sql, $params);
    }
}
