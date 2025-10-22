<?php
require_once __DIR__ . '/../core/DB.php';

class Assignment {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    public function find($id) {
        return $this->db->fetch(
            "SELECT a.*, c.title as course_title, u.name as teacher_name
             FROM assignments a
             LEFT JOIN courses c ON a.course_id = c.id
             LEFT JOIN users u ON c.teacher_id = u.id
             WHERE a.id = ?",
            [$id]
        );
    }
    
    public function getByCourse($courseId, $page = 1, $limit = ITEMS_PER_PAGE) {
        $offset = ($page - 1) * $limit;
        return $this->db->fetchAll(
            "SELECT * FROM assignments WHERE course_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [$courseId, $limit, $offset]
        );
    }
    
    public function getByStudent($userId, $page = 1, $limit = ITEMS_PER_PAGE) {
        $offset = ($page - 1) * $limit;
        return $this->db->fetchAll(
            "SELECT a.*, c.title as course_title, s.score, s.submitted_at, s.graded_at
             FROM assignments a
             LEFT JOIN courses c ON a.course_id = c.id
             LEFT JOIN enrollments e ON c.id = e.course_id AND e.user_id = ?
             LEFT JOIN submissions s ON a.id = s.assignment_id AND s.student_id = ?
             WHERE e.user_id = ?
             ORDER BY a.due_at ASC, a.created_at DESC
             LIMIT ? OFFSET ?",
            [$userId, $userId, $userId, $limit, $offset]
        );
    }
    
    public function getByTeacher($teacherId, $page = 1, $limit = ITEMS_PER_PAGE) {
        $offset = ($page - 1) * $limit;
        return $this->db->fetchAll(
            "SELECT a.*, c.title as course_title, COUNT(s.id) as submission_count
             FROM assignments a
             LEFT JOIN courses c ON a.course_id = c.id
             LEFT JOIN submissions s ON a.id = s.assignment_id
             WHERE c.teacher_id = ?
             GROUP BY a.id
             ORDER BY a.created_at DESC
             LIMIT ? OFFSET ?",
            [$teacherId, $limit, $offset]
        );
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
    
    public function getSubmissions($assignmentId) {
        return $this->db->fetchAll(
            "SELECT s.*, u.name as student_name, u.email as student_email
             FROM submissions s
             LEFT JOIN users u ON s.student_id = u.id
             WHERE s.assignment_id = ?
             ORDER BY s.submitted_at DESC",
            [$assignmentId]
        );
    }
    
    public function getSubmission($assignmentId, $studentId) {
        return $this->db->fetch(
            "SELECT * FROM submissions WHERE assignment_id = ? AND student_id = ?",
            [$assignmentId, $studentId]
        );
    }
    
    public function submit($assignmentId, $studentId, $filePath = null, $note = null) {
        // Check if already submitted
        $existing = $this->getSubmission($assignmentId, $studentId);
        
        $data = [
            'assignment_id' => $assignmentId,
            'student_id' => $studentId,
            'note' => $note,
            'submitted_at' => date('Y-m-d H:i:s')
        ];
        
        if ($filePath) {
            $data['file_path'] = $filePath;
        }
        
        if ($existing) {
            return $this->db->update('submissions', $data, 'assignment_id = ? AND student_id = ?', [$assignmentId, $studentId]);
        } else {
            return $this->db->insert('submissions', $data);
        }
    }
    
    public function grade($assignmentId, $studentId, $score, $feedback = null) {
        return $this->db->update('submissions', [
            'score' => $score,
            'feedback' => $feedback,
            'graded_at' => date('Y-m-d H:i:s')
        ], 'assignment_id = ? AND student_id = ?', [$assignmentId, $studentId]);
    }
    
    public function isSubmitted($assignmentId, $studentId) {
        $result = $this->db->fetch(
            "SELECT id FROM submissions WHERE assignment_id = ? AND student_id = ?",
            [$assignmentId, $studentId]
        );
        return $result !== false;
    }
    
    public function isGraded($assignmentId, $studentId) {
        $result = $this->db->fetch(
            "SELECT score FROM submissions WHERE assignment_id = ? AND student_id = ? AND score IS NOT NULL",
            [$assignmentId, $studentId]
        );
        return $result !== false;
    }
    
    public function getSubmissionCount($assignmentId) {
        $result = $this->db->fetch(
            "SELECT COUNT(*) as total FROM submissions WHERE assignment_id = ?",
            [$assignmentId]
        );
        return $result['total'];
    }
    
    public function getGradedCount($assignmentId) {
        $result = $this->db->fetch(
            "SELECT COUNT(*) as total FROM submissions WHERE assignment_id = ? AND score IS NOT NULL",
            [$assignmentId]
        );
        return $result['total'];
    }
    
    public function getAverageScore($assignmentId) {
        $result = $this->db->fetch(
            "SELECT AVG(score) as avg_score FROM submissions WHERE assignment_id = ? AND score IS NOT NULL",
            [$assignmentId]
        );
        return round($result['avg_score'] ?? 0, 2);
    }
    
    public function getUpcomingAssignments($userId, $limit = 10) {
        return $this->db->fetchAll(
            "SELECT a.*, c.title as course_title
             FROM assignments a
             LEFT JOIN courses c ON a.course_id = c.id
             LEFT JOIN enrollments e ON c.id = e.course_id AND e.user_id = ?
             WHERE e.user_id = ? AND a.due_at > NOW()
             ORDER BY a.due_at ASC
             LIMIT ?",
            [$userId, $userId, $limit]
        );
    }
    
    public function getOverdueAssignments($userId) {
        return $this->db->fetchAll(
            "SELECT a.*, c.title as course_title, s.id as submission_id
             FROM assignments a
             LEFT JOIN courses c ON a.course_id = c.id
             LEFT JOIN enrollments e ON c.id = e.course_id AND e.user_id = ?
             LEFT JOIN submissions s ON a.id = s.assignment_id AND s.student_id = ?
             WHERE e.user_id = ? AND a.due_at < NOW() AND s.id IS NULL
             ORDER BY a.due_at ASC",
            [$userId, $userId, $userId]
        );
    }
    
    public function search($query, $courseId = null) {
        $sql = "SELECT a.*, c.title as course_title, u.name as teacher_name
                FROM assignments a
                LEFT JOIN courses c ON a.course_id = c.id
                LEFT JOIN users u ON c.teacher_id = u.id
                WHERE (a.title LIKE ? OR a.description LIKE ?)";
        $params = ["%{$query}%", "%{$query}%"];
        
        if ($courseId) {
            $sql .= " AND a.course_id = ?";
            $params[] = $courseId;
        }
        
        $sql .= " ORDER BY a.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
}