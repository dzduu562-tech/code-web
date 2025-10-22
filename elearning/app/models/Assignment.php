<?php

class Assignment {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    public function find($id) {
        return $this->db->fetch("SELECT * FROM assignments WHERE id = ?", [$id]);
    }
    
    public function getByCourse($courseId, $limit = null, $offset = 0) {
        $sql = "SELECT * FROM assignments WHERE course_id = ? ORDER BY due_at ASC, created_at DESC";
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        return $this->db->fetchAll($sql, [$courseId]);
    }
    
    public function getAll($limit = null, $offset = 0) {
        $sql = "SELECT a.*, c.title as course_title, u.name as teacher_name 
                FROM assignments a
                JOIN courses c ON a.course_id = c.id
                JOIN users u ON c.teacher_id = u.id
                ORDER BY a.due_at ASC, a.created_at DESC";
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        return $this->db->fetchAll($sql);
    }
    
    public function create($data) {
        return $this->db->insert('assignments', $data);
    }
    
    public function update($id, $data) {
        return $this->db->update('assignments', $data, 'id = ?', [$id]);
    }
    
    public function delete($id) {
        return $this->db->delete('assignments', 'id = ?', [$id]);
    }
    
    public function getAssignmentWithDetails($id) {
        $assignment = $this->find($id);
        if (!$assignment) return null;
        
        // Get course info
        $course = $this->db->fetch(
            "SELECT c.*, u.name as teacher_name FROM courses c 
             JOIN users u ON c.teacher_id = u.id 
             WHERE c.id = ?",
            [$assignment['course_id']]
        );
        $assignment['course'] = $course;
        
        // Get submissions
        $assignment['submissions'] = $this->db->fetchAll(
            "SELECT s.*, u.name as student_name FROM submissions s
             JOIN users u ON s.student_id = u.id
             WHERE s.assignment_id = ?
             ORDER BY s.submitted_at DESC",
            [$id]
        );
        
        // Get submission count
        $assignment['submission_count'] = count($assignment['submissions']);
        
        // Get graded count
        $assignment['graded_count'] = $this->db->count(
            'submissions', 
            'assignment_id = ? AND score IS NOT NULL', 
            [$id]
        );
        
        return $assignment;
    }
    
    public function getStudentAssignments($studentId, $limit = null, $offset = 0) {
        $sql = "SELECT a.*, c.title as course_title, u.name as teacher_name,
                       s.id as submission_id, s.score, s.graded_at, s.submitted_at
                FROM assignments a
                JOIN courses c ON a.course_id = c.id
                JOIN users u ON c.teacher_id = u.id
                LEFT JOIN submissions s ON a.id = s.assignment_id AND s.student_id = ?
                ORDER BY a.due_at ASC";
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        return $this->db->fetchAll($sql, [$studentId]);
    }
    
    public function getUpcomingAssignments($userId, $days = 7) {
        $sql = "SELECT a.*, c.title as course_title, u.name as teacher_name
                FROM assignments a
                JOIN courses c ON a.course_id = c.id
                JOIN users u ON c.teacher_id = u.id
                JOIN enrollments e ON c.id = e.course_id
                WHERE e.user_id = ? 
                AND a.due_at BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL ? DAY)
                ORDER BY a.due_at ASC";
        return $this->db->fetchAll($sql, [$userId, $days]);
    }
    
    public function getOverdueAssignments($userId) {
        $sql = "SELECT a.*, c.title as course_title, u.name as teacher_name
                FROM assignments a
                JOIN courses c ON a.course_id = c.id
                JOIN users u ON c.teacher_id = u.id
                JOIN enrollments e ON c.id = e.course_id
                WHERE e.user_id = ? 
                AND a.due_at < NOW()
                AND a.id NOT IN (
                    SELECT assignment_id FROM submissions WHERE student_id = ?
                )
                ORDER BY a.due_at ASC";
        return $this->db->fetchAll($sql, [$userId, $userId]);
    }
    
    public function isOverdue($assignment) {
        return $assignment['due_at'] && strtotime($assignment['due_at']) < time();
    }
    
    public function getTimeRemaining($assignment) {
        if (!$assignment['due_at']) return null;
        
        $dueTime = strtotime($assignment['due_at']);
        $now = time();
        $remaining = $dueTime - $now;
        
        if ($remaining <= 0) return 0;
        
        $days = floor($remaining / 86400);
        $hours = floor(($remaining % 86400) / 3600);
        $minutes = floor(($remaining % 3600) / 60);
        
        if ($days > 0) {
            return "{$days} ngày {$hours} giờ";
        } elseif ($hours > 0) {
            return "{$hours} giờ {$minutes} phút";
        } else {
            return "{$minutes} phút";
        }
    }
    
    public function getStatusText($assignment, $submission = null) {
        if ($submission) {
            if ($submission['score'] !== null) {
                return 'Đã chấm điểm';
            } else {
                return 'Đã nộp';
            }
        } else {
            if ($this->isOverdue($assignment)) {
                return 'Quá hạn';
            } else {
                return 'Chưa nộp';
            }
        }
    }
    
    public function getStatusClass($assignment, $submission = null) {
        if ($submission) {
            if ($submission['score'] !== null) {
                return 'success';
            } else {
                return 'warning';
            }
        } else {
            if ($this->isOverdue($assignment)) {
                return 'danger';
            } else {
                return 'secondary';
            }
        }
    }
    
    public function count() {
        return $this->db->count('assignments');
    }
    
    public function countByCourse($courseId) {
        return $this->db->count('assignments', 'course_id = ?', [$courseId]);
    }
    
    public function search($query, $limit = 20) {
        $sql = "SELECT a.*, c.title as course_title, u.name as teacher_name
                FROM assignments a
                JOIN courses c ON a.course_id = c.id
                JOIN users u ON c.teacher_id = u.id
                WHERE a.title LIKE ? OR a.description LIKE ? OR c.title LIKE ?
                ORDER BY a.due_at DESC LIMIT ?";
        $searchTerm = "%{$query}%";
        return $this->db->fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm, $limit]);
    }
}