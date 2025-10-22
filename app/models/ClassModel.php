<?php
/**
 * Class Model
 */

class ClassModel extends Model {
    protected $table = 'classes';

    /**
     * Get active classes
     */
    public function getActiveClasses() {
        $sql = "SELECT c.*, u.full_name as teacher_name 
                FROM {$this->table} c
                LEFT JOIN users u ON c.teacher_id = u.id
                WHERE c.status = 'active' 
                ORDER BY c.name ASC";
        return $this->query($sql)->fetchAll();
    }

    /**
     * Get class with students count
     */
    public function getClassWithStats($classId) {
        $sql = "SELECT c.*, u.full_name as teacher_name,
                COUNT(sp.id) as student_count
                FROM {$this->table} c
                LEFT JOIN users u ON c.teacher_id = u.id
                LEFT JOIN student_profiles sp ON c.id = sp.class_id
                WHERE c.id = :id
                GROUP BY c.id";
        
        return $this->query($sql, ['id' => $classId])->fetch();
    }
}
