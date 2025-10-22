<?php

class Course {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    public function find($id) {
        return $this->db->fetch("SELECT * FROM courses WHERE id = ?", [$id]);
    }
    
    public function getAll($limit = null, $offset = 0, $published = true) {
        $sql = "SELECT c.*, u.name as teacher_name FROM courses c 
                LEFT JOIN users u ON c.teacher_id = u.id";
        
        if ($published) {
            $sql .= " WHERE c.is_published = 1";
        }
        
        $sql .= " ORDER BY c.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return $this->db->fetchAll($sql);
    }
    
    public function getByTeacher($teacherId, $limit = null, $offset = 0) {
        $sql = "SELECT * FROM courses WHERE teacher_id = ? ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        return $this->db->fetchAll($sql, [$teacherId]);
    }
    
    public function getPublished($limit = null, $offset = 0) {
        return $this->getAll($limit, $offset, true);
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
    
    public function count() {
        return $this->db->count('courses');
    }
    
    public function countPublished() {
        return $this->db->count('courses', 'is_published = 1');
    }
    
    public function countByTeacher($teacherId) {
        return $this->db->count('courses', 'teacher_id = ?', [$teacherId]);
    }
    
    public function search($query, $limit = 20) {
        $sql = "SELECT c.*, u.name as teacher_name FROM courses c 
                LEFT JOIN users u ON c.teacher_id = u.id 
                WHERE c.is_published = 1 AND (c.title LIKE ? OR c.subject LIKE ? OR c.description LIKE ?)
                ORDER BY c.title LIMIT ?";
        $searchTerm = "%{$query}%";
        return $this->db->fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm, $limit]);
    }
    
    public function getBySubject($subject, $limit = 20) {
        $sql = "SELECT c.*, u.name as teacher_name FROM courses c 
                LEFT JOIN users u ON c.teacher_id = u.id 
                WHERE c.is_published = 1 AND c.subject = ?
                ORDER BY c.created_at DESC LIMIT ?";
        return $this->db->fetchAll($sql, [$subject, $limit]);
    }
    
    public function getSubjects() {
        $sql = "SELECT DISTINCT subject FROM courses WHERE is_published = 1 ORDER BY subject";
        $results = $this->db->fetchAll($sql);
        return array_column($results, 'subject');
    }
    
    public function getEnrolledCourses($userId) {
        $sql = "SELECT c.*, u.name as teacher_name, e.progress_percent, e.last_view_at, e.enrolled_at
                FROM courses c
                LEFT JOIN users u ON c.teacher_id = u.id
                LEFT JOIN enrollments e ON c.id = e.course_id
                WHERE e.user_id = ? AND c.is_published = 1
                ORDER BY e.last_view_at DESC, e.enrolled_at DESC";
        return $this->db->fetchAll($sql, [$userId]);
    }
    
    public function getCourseWithDetails($id) {
        $course = $this->find($id);
        if (!$course) return null;
        
        // Get teacher info
        $teacher = $this->db->fetch("SELECT name, email FROM users WHERE id = ?", [$course['teacher_id']]);
        $course['teacher'] = $teacher;
        
        // Get chapters and lessons
        $chapters = $this->db->fetchAll(
            "SELECT * FROM chapters WHERE course_id = ? ORDER BY position",
            [$id]
        );
        
        foreach ($chapters as &$chapter) {
            $chapter['lessons'] = $this->db->fetchAll(
                "SELECT * FROM lessons WHERE chapter_id = ? ORDER BY position",
                [$chapter['id']]
            );
        }
        
        $course['chapters'] = $chapters;
        
        // Get enrollment count
        $course['enrollment_count'] = $this->db->count('enrollments', 'course_id = ?', [$id]);
        
        return $course;
    }
    
    public function getThumbnailUrl($course) {
        if ($course['thumbnail']) {
            return APP_URL . '/public/uploads/courses/' . $course['thumbnail'];
        }
        return APP_URL . '/assets/img/default-course.jpg';
    }
    
    public function getTotalLessons($courseId) {
        $sql = "SELECT COUNT(*) as count FROM lessons l 
                JOIN chapters c ON l.chapter_id = c.id 
                WHERE c.course_id = ?";
        $result = $this->db->fetch($sql, [$courseId]);
        return (int) $result['count'];
    }
    
    public function getTotalDuration($courseId) {
        $sql = "SELECT SUM(l.duration_minutes) as total FROM lessons l 
                JOIN chapters c ON l.chapter_id = c.id 
                WHERE c.course_id = ?";
        $result = $this->db->fetch($sql, [$courseId]);
        return (int) $result['total'];
    }
    
    public function getRecentCourses($limit = 5) {
        $sql = "SELECT c.*, u.name as teacher_name FROM courses c 
                LEFT JOIN users u ON c.teacher_id = u.id 
                WHERE c.is_published = 1 
                ORDER BY c.created_at DESC LIMIT ?";
        return $this->db->fetchAll($sql, [$limit]);
    }
}