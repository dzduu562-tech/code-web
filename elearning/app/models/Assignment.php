<?php

class Assignment {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    public function findById($id) {
        $sql = "SELECT a.*, c.title as course_title, c.teacher_id
                FROM assignments a
                LEFT JOIN courses c ON a.course_id = c.id
                WHERE a.id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    public function create($data) {
        return $this->db->insert('assignments', $data);
    }
    
    public function update($id, $data) {
        return $this->db->update('assignments', $data, 'id = :id', ['id' => $id]);
    }
    
    public function delete($id) {
        return $this->db->delete('assignments', 'id = :id', ['id' => $id]);
    }
    
    public function getByCourse($courseId) {
        $sql = "SELECT * FROM assignments WHERE course_id = :course_id ORDER BY due_at DESC";
        return $this->db->fetchAll($sql, ['course_id' => $courseId]);
    }
    
    public function getByStudent($studentId) {
        $sql = "SELECT a.*, c.title as course_title,
                       s.id as submission_id, s.score, s.submitted_at, s.graded_at
                FROM assignments a
                JOIN courses c ON a.course_id = c.id
                JOIN enrollments e ON c.id = e.course_id
                LEFT JOIN submissions s ON a.id = s.assignment_id AND s.student_id = :student_id
                WHERE e.user_id = :student_id
                ORDER BY a.due_at DESC";
        return $this->db->fetchAll($sql, ['student_id' => $studentId]);
    }
    
    public function getSubmission($assignmentId, $studentId) {
        $sql = "SELECT * FROM submissions 
                WHERE assignment_id = :assignment_id AND student_id = :student_id";
        return $this->db->fetchOne($sql, [
            'assignment_id' => $assignmentId,
            'student_id' => $studentId
        ]);
    }
    
    public function getSubmissions($assignmentId) {
        $sql = "SELECT s.*, u.name as student_name, u.email as student_email
                FROM submissions s
                LEFT JOIN users u ON s.student_id = u.id
                WHERE s.assignment_id = :assignment_id
                ORDER BY s.submitted_at DESC";
        return $this->db->fetchAll($sql, ['assignment_id' => $assignmentId]);
    }
    
    public function submitAssignment($data) {
        // Check if already submitted
        $existing = $this->getSubmission($data['assignment_id'], $data['student_id']);
        
        if ($existing) {
            unset($data['student_id']);
            unset($data['assignment_id']);
            return $this->db->update('submissions', $data,
                'assignment_id = :assignment_id AND student_id = :student_id',
                ['assignment_id' => $existing['assignment_id'], 'student_id' => $existing['student_id']]
            );
        } else {
            return $this->db->insert('submissions', $data);
        }
    }
    
    public function gradeSubmission($submissionId, $score, $feedback) {
        return $this->db->update('submissions',
            ['score' => $score, 'feedback' => $feedback, 'graded_at' => date('Y-m-d H:i:s')],
            'id = :id',
            ['id' => $submissionId]
        );
    }
}
