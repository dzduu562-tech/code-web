<?php
require_once __DIR__ . '/../core/DB.php';

class Course {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    public function find($id) {
        return $this->db->fetch(
            "SELECT c.*, u.name as teacher_name 
             FROM courses c 
             LEFT JOIN users u ON c.teacher_id = u.id 
             WHERE c.id = ?",
            [$id]
        );
    }
    
    public function getAll($page = 1, $limit = ITEMS_PER_PAGE, $published = null) {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT c.*, u.name as teacher_name 
                FROM courses c 
                LEFT JOIN users u ON c.teacher_id = u.id";
        
        $params = [];
        
        if ($published !== null) {
            $sql .= " WHERE c.is_published = ?";
            $params[] = $published;
        }
        
        $sql .= " ORDER BY c.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getByTeacher($teacherId, $page = 1, $limit = ITEMS_PER_PAGE) {
        $offset = ($page - 1) * $limit;
        return $this->db->fetchAll(
            "SELECT * FROM courses WHERE teacher_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [$teacherId, $limit, $offset]
        );
    }
    
    public function getBySubject($subject, $page = 1, $limit = ITEMS_PER_PAGE) {
        $offset = ($page - 1) * $limit;
        return $this->db->fetchAll(
            "SELECT c.*, u.name as teacher_name 
             FROM courses c 
             LEFT JOIN users u ON c.teacher_id = u.id 
             WHERE c.subject = ? AND c.is_published = 1 
             ORDER BY c.created_at DESC LIMIT ? OFFSET ?",
            [$subject, $limit, $offset]
        );
    }
    
    public function getEnrolledCourses($userId, $page = 1, $limit = ITEMS_PER_PAGE) {
        $offset = ($page - 1) * $limit;
        return $this->db->fetchAll(
            "SELECT c.*, u.name as teacher_name, e.progress_percent, e.last_view_at
             FROM courses c 
             LEFT JOIN users u ON c.teacher_id = u.id
             LEFT JOIN enrollments e ON c.id = e.course_id AND e.user_id = ?
             WHERE e.user_id = ? AND c.is_published = 1
             ORDER BY e.last_view_at DESC, c.created_at DESC 
             LIMIT ? OFFSET ?",
            [$userId, $userId, $limit, $offset]
        );
    }
    
    public function create($data) {
        return $this->db->insert('courses', $data);
    }
    
    public function update($id, $data) {
        return $this->db->update('courses', $data, 'id = ?', [$id]);
    }
    
    public function delete($id) {
        return $this->db->delete('courses', 'id = ?', [$id]);
    }
    
    public function publish($id) {
        return $this->db->update('courses', ['is_published' => 1], 'id = ?', [$id]);
    }
    
    public function unpublish($id) {
        return $this->db->update('courses', ['is_published' => 0], 'id = ?', [$id]);
    }
    
    public function getChapters($courseId) {
        return $this->db->fetchAll(
            "SELECT * FROM chapters WHERE course_id = ? ORDER BY position ASC",
            [$courseId]
        );
    }
    
    public function getLessons($courseId) {
        return $this->db->fetchAll(
            "SELECT l.*, c.title as chapter_title, c.position as chapter_position
             FROM lessons l
             LEFT JOIN chapters c ON l.chapter_id = c.id
             WHERE c.course_id = ? AND l.is_published = 1
             ORDER BY c.position ASC, l.position ASC",
            [$courseId]
        );
    }
    
    public function getEnrollmentCount($courseId) {
        $result = $this->db->fetch(
            "SELECT COUNT(*) as total FROM enrollments WHERE course_id = ?",
            [$courseId]
        );
        return $result['total'];
    }
    
    public function isEnrolled($userId, $courseId) {
        $result = $this->db->fetch(
            "SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?",
            [$userId, $courseId]
        );
        return $result !== false;
    }
    
    public function enroll($userId, $courseId) {
        if ($this->isEnrolled($userId, $courseId)) {
            return false; // Already enrolled
        }
        
        return $this->db->insert('enrollments', [
            'user_id' => $userId,
            'course_id' => $courseId,
            'progress_percent' => 0.00
        ]);
    }
    
    public function unenroll($userId, $courseId) {
        return $this->db->delete('enrollments', 'user_id = ? AND course_id = ?', [$userId, $courseId]);
    }
    
    public function updateProgress($userId, $courseId, $progress) {
        return $this->db->update(
            'enrollments',
            ['progress_percent' => $progress, 'last_view_at' => date('Y-m-d H:i:s')],
            'user_id = ? AND course_id = ?',
            [$userId, $courseId]
        );
    }
    
    public function search($query, $subject = null) {
        $sql = "SELECT c.*, u.name as teacher_name 
                FROM courses c 
                LEFT JOIN users u ON c.teacher_id = u.id 
                WHERE (c.title LIKE ? OR c.description LIKE ?) AND c.is_published = 1";
        $params = ["%{$query}%", "%{$query}%"];
        
        if ($subject) {
            $sql .= " AND c.subject = ?";
            $params[] = $subject;
        }
        
        $sql .= " ORDER BY c.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getSubjects() {
        return $this->db->fetchAll(
            "SELECT DISTINCT subject FROM courses WHERE is_published = 1 ORDER BY subject ASC"
        );
    }
    
    public function getTotalCount($published = null) {
        $sql = "SELECT COUNT(*) as total FROM courses";
        $params = [];
        
        if ($published !== null) {
            $sql .= " WHERE is_published = ?";
            $params[] = $published;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['total'];
    }
    
    public function getRecentCourses($limit = 10) {
        return $this->db->fetchAll(
            "SELECT c.*, u.name as teacher_name 
             FROM courses c 
             LEFT JOIN users u ON c.teacher_id = u.id 
             WHERE c.is_published = 1 
             ORDER BY c.created_at DESC 
             LIMIT ?",
            [$limit]
        );
    }
}