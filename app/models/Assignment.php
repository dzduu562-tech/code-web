<?php
/**
 * Assignment Model
 */

class Assignment extends Model {
    protected $table = 'assignments';

    /**
     * Get assignments by course
     */
    public function getByCourse($courseId) {
        $sql = "SELECT a.*, c.title as course_title,
                COUNT(s.id) as total_submissions
                FROM {$this->table} a
                LEFT JOIN courses c ON a.course_id = c.id
                LEFT JOIN assignment_submissions s ON a.id = s.assignment_id
                WHERE a.course_id = :course_id
                GROUP BY a.id
                ORDER BY a.due_date DESC";
        
        return $this->query($sql, ['course_id' => $courseId])->fetchAll();
    }

    /**
     * Get teacher assignments
     */
    public function getTeacherAssignments($teacherId) {
        $sql = "SELECT a.*, c.title as course_title,
                COUNT(DISTINCT s.id) as total_submissions,
                COUNT(DISTINCT CASE WHEN s.status = 'submitted' THEN s.id END) as pending_count,
                COUNT(DISTINCT CASE WHEN s.status = 'graded' THEN s.id END) as graded_count
                FROM {$this->table} a
                LEFT JOIN courses c ON a.course_id = c.id
                LEFT JOIN assignment_submissions s ON a.id = s.assignment_id
                WHERE c.teacher_id = :teacher_id
                GROUP BY a.id
                ORDER BY a.created_at DESC";
        
        return $this->query($sql, ['teacher_id' => $teacherId])->fetchAll();
    }

    /**
     * Create assignment
     */
    public function create($data) {
        return $this->insert($data);
    }
}
