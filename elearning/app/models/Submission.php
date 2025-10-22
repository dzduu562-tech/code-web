<?php

class Submission {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    public function find($id) {
        return $this->db->fetch("SELECT * FROM submissions WHERE id = ?", [$id]);
    }
    
    public function getByAssignment($assignmentId) {
        $sql = "SELECT s.*, u.name as student_name, u.email as student_email
                FROM submissions s
                JOIN users u ON s.student_id = u.id
                WHERE s.assignment_id = ?
                ORDER BY s.submitted_at DESC";
        return $this->db->fetchAll($sql, [$assignmentId]);
    }
    
    public function getByStudent($studentId, $limit = null, $offset = 0) {
        $sql = "SELECT s.*, a.title as assignment_title, c.title as course_title
                FROM submissions s
                JOIN assignments a ON s.assignment_id = a.id
                JOIN courses c ON a.course_id = c.id
                WHERE s.student_id = ?
                ORDER BY s.submitted_at DESC";
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        return $this->db->fetchAll($sql, [$studentId]);
    }
    
    public function getByStudentAndAssignment($studentId, $assignmentId) {
        return $this->db->fetch(
            "SELECT * FROM submissions WHERE student_id = ? AND assignment_id = ?",
            [$studentId, $assignmentId]
        );
    }
    
    public function create($data) {
        return $this->db->insert('submissions', $data);
    }
    
    public function update($id, $data) {
        return $this->db->update('submissions', $data, 'id = ?', [$id]);
    }
    
    public function delete($id) {
        $submission = $this->find($id);
        if ($submission && $submission['file_path']) {
            // Delete file
            Helpers::deleteFile($submission['file_path']);
        }
        return $this->db->delete('submissions', 'id = ?', [$id]);
    }
    
    public function submit($assignmentId, $studentId, $filePath = null, $note = null) {
        // Check if submission already exists
        $existing = $this->getByStudentAndAssignment($studentId, $assignmentId);
        if ($existing) {
            // Update existing submission
            $data = ['submitted_at' => date('Y-m-d H:i:s')];
            if ($filePath) $data['file_path'] = $filePath;
            if ($note) $data['note'] = $note;
            
            return $this->update($existing['id'], $data);
        } else {
            // Create new submission
            return $this->create([
                'assignment_id' => $assignmentId,
                'student_id' => $studentId,
                'file_path' => $filePath,
                'note' => $note
            ]);
        }
    }
    
    public function grade($id, $score, $feedback = null) {
        return $this->update($id, [
            'score' => $score,
            'feedback' => $feedback,
            'graded_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function getSubmissionWithDetails($id) {
        $submission = $this->find($id);
        if (!$submission) return null;
        
        // Get assignment details
        $assignment = $this->db->fetch(
            "SELECT a.*, c.title as course_title, u.name as teacher_name
             FROM assignments a
             JOIN courses c ON a.course_id = c.id
             JOIN users u ON c.teacher_id = u.id
             WHERE a.id = ?",
            [$submission['assignment_id']]
        );
        $submission['assignment'] = $assignment;
        
        // Get student details
        $student = $this->db->fetch(
            "SELECT name, email FROM users WHERE id = ?",
            [$submission['student_id']]
        );
        $submission['student'] = $student;
        
        return $submission;
    }
    
    public function getFileUrl($submission) {
        if ($submission['file_path']) {
            return APP_URL . '/public/uploads/' . $submission['file_path'];
        }
        return null;
    }
    
    public function getFileName($submission) {
        if ($submission['file_path']) {
            return basename($submission['file_path']);
        }
        return 'Không có file';
    }
    
    public function getFileSize($submission) {
        if ($submission['file_path']) {
            $fullPath = UPLOAD_PATH . $submission['file_path'];
            if (file_exists($fullPath)) {
                return filesize($fullPath);
            }
        }
        return 0;
    }
    
    public function getFileExtension($submission) {
        if ($submission['file_path']) {
            return strtolower(pathinfo($submission['file_path'], PATHINFO_EXTENSION));
        }
        return '';
    }
    
    public function getGradeText($submission) {
        if ($submission['score'] === null) {
            return 'Chưa chấm điểm';
        }
        return $submission['score'] . '/' . ($submission['assignment']['max_score'] ?? 100);
    }
    
    public function getGradePercentage($submission) {
        if ($submission['score'] === null || !isset($submission['assignment']['max_score'])) {
            return 0;
        }
        $maxScore = $submission['assignment']['max_score'];
        return $maxScore > 0 ? round(($submission['score'] / $maxScore) * 100, 1) : 0;
    }
    
    public function getGradeClass($submission) {
        if ($submission['score'] === null) {
            return 'secondary';
        }
        
        $percentage = $this->getGradePercentage($submission);
        if ($percentage >= 80) return 'success';
        if ($percentage >= 60) return 'warning';
        return 'danger';
    }
    
    public function isGraded($submission) {
        return $submission['score'] !== null;
    }
    
    public function isLate($submission) {
        if (!$submission['assignment']['due_at']) return false;
        return strtotime($submission['submitted_at']) > strtotime($submission['assignment']['due_at']);
    }
    
    public function getLateText($submission) {
        if (!$this->isLate($submission)) return '';
        
        $submittedTime = strtotime($submission['submitted_at']);
        $dueTime = strtotime($submission['assignment']['due_at']);
        $lateMinutes = ($submittedTime - $dueTime) / 60;
        
        if ($lateMinutes < 60) {
            return 'Trễ ' . round($lateMinutes) . ' phút';
        } else {
            $lateHours = $lateMinutes / 60;
            if ($lateHours < 24) {
                return 'Trễ ' . round($lateHours, 1) . ' giờ';
            } else {
                $lateDays = $lateHours / 24;
                return 'Trễ ' . round($lateDays, 1) . ' ngày';
            }
        }
    }
    
    public function count() {
        return $this->db->count('submissions');
    }
    
    public function countByAssignment($assignmentId) {
        return $this->db->count('submissions', 'assignment_id = ?', [$assignmentId]);
    }
    
    public function countByStudent($studentId) {
        return $this->db->count('submissions', 'student_id = ?', [$studentId]);
    }
    
    public function countGraded($assignmentId = null) {
        $where = 'score IS NOT NULL';
        $params = [];
        
        if ($assignmentId) {
            $where .= ' AND assignment_id = ?';
            $params[] = $assignmentId;
        }
        
        return $this->db->count('submissions', $where, $params);
    }
    
    public function getAverageScore($assignmentId) {
        $result = $this->db->fetch(
            "SELECT AVG(score) as avg_score FROM submissions 
             WHERE assignment_id = ? AND score IS NOT NULL",
            [$assignmentId]
        );
        return $result ? round($result['avg_score'], 2) : 0;
    }
    
    public function getGradeDistribution($assignmentId) {
        $sql = "SELECT 
                    CASE 
                        WHEN score >= 90 THEN 'A (90-100)'
                        WHEN score >= 80 THEN 'B (80-89)'
                        WHEN score >= 70 THEN 'C (70-79)'
                        WHEN score >= 60 THEN 'D (60-69)'
                        ELSE 'F (0-59)'
                    END as grade_range,
                    COUNT(*) as count
                FROM submissions 
                WHERE assignment_id = ? AND score IS NOT NULL
                GROUP BY grade_range
                ORDER BY grade_range";
        
        return $this->db->fetchAll($sql, [$assignmentId]);
    }
}