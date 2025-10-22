<?php

class Course {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    public function findById($id) {
        $sql = "SELECT c.*, u.name as teacher_name 
                FROM courses c 
                LEFT JOIN users u ON c.teacher_id = u.id 
                WHERE c.id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    public function create($data) {
        return $this->db->insert('courses', $data);
    }
    
    public function update($id, $data) {
        return $this->db->update('courses', $data, 'id = :id', ['id' => $id]);
    }
    
    public function delete($id) {
        return $this->db->delete('courses', 'id = :id', ['id' => $id]);
    }
    
    public function getAll($limit = 20, $offset = 0) {
        $sql = "SELECT c.*, u.name as teacher_name,
                (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as student_count
                FROM courses c 
                LEFT JOIN users u ON c.teacher_id = u.id 
                ORDER BY c.created_at DESC 
                LIMIT :limit OFFSET :offset";
        return $this->db->fetchAll($sql, ['limit' => $limit, 'offset' => $offset]);
    }
    
    public function getByTeacher($teacherId, $limit = 20, $offset = 0) {
        $sql = "SELECT c.*,
                (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as student_count
                FROM courses c 
                WHERE c.teacher_id = :teacher_id 
                ORDER BY c.created_at DESC 
                LIMIT :limit OFFSET :offset";
        return $this->db->fetchAll($sql, ['teacher_id' => $teacherId, 'limit' => $limit, 'offset' => $offset]);
    }
    
    public function search($keyword, $subject = null, $limit = 20, $offset = 0) {
        $params = ['keyword' => "%$keyword%", 'limit' => $limit, 'offset' => $offset];
        $subjectCondition = '';
        
        if ($subject) {
            $subjectCondition = ' AND c.subject = :subject';
            $params['subject'] = $subject;
        }
        
        $sql = "SELECT c.*, u.name as teacher_name,
                (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as student_count
                FROM courses c 
                LEFT JOIN users u ON c.teacher_id = u.id 
                WHERE (c.title LIKE :keyword OR c.description LIKE :keyword)
                $subjectCondition
                ORDER BY c.created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM courses";
        $result = $this->db->fetchOne($sql);
        return $result['total'] ?? 0;
    }
    
    public function getEnrolledCourses($userId) {
        $sql = "SELECT c.*, u.name as teacher_name, e.progress_percent, e.last_view_at
                FROM courses c
                JOIN enrollments e ON c.id = e.course_id
                LEFT JOIN users u ON c.teacher_id = u.id
                WHERE e.user_id = :user_id
                ORDER BY e.last_view_at DESC";
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }
    
    public function isEnrolled($userId, $courseId) {
        $sql = "SELECT COUNT(*) as count FROM enrollments 
                WHERE user_id = :user_id AND course_id = :course_id";
        $result = $this->db->fetchOne($sql, ['user_id' => $userId, 'course_id' => $courseId]);
        return ($result['count'] ?? 0) > 0;
    }
    
    public function enroll($userId, $courseId) {
        return $this->db->insert('enrollments', [
            'user_id' => $userId,
            'course_id' => $courseId,
            'progress_percent' => 0
        ]);
    }
    
    public function updateProgress($userId, $courseId) {
        // Calculate progress based on completed lessons
        $sql = "SELECT 
                    (SELECT COUNT(*) FROM lessons l 
                     JOIN chapters ch ON l.chapter_id = ch.id 
                     WHERE ch.course_id = :course_id) as total_lessons,
                    (SELECT COUNT(*) FROM lesson_progress lp
                     JOIN lessons l ON lp.lesson_id = l.id
                     JOIN chapters ch ON l.chapter_id = ch.id
                     WHERE ch.course_id = :course_id AND lp.user_id = :user_id AND lp.completed = 1) as completed_lessons";
        
        $result = $this->db->fetchOne($sql, ['course_id' => $courseId, 'user_id' => $userId]);
        
        $totalLessons = $result['total_lessons'] ?? 0;
        $completedLessons = $result['completed_lessons'] ?? 0;
        
        $progress = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
        
        $this->db->update('enrollments', 
            ['progress_percent' => $progress, 'last_view_at' => date('Y-m-d H:i:s')],
            'user_id = :user_id AND course_id = :course_id',
            ['user_id' => $userId, 'course_id' => $courseId]
        );
        
        return $progress;
    }
}
