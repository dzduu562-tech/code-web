<?php
/**
 * Quiz Model
 */

class Quiz extends Model {
    protected $table = 'quizzes';

    /**
     * Get quizzes by course
     */
    public function getByCourse($courseId) {
        $sql = "SELECT q.*, c.title as course_title,
                COUNT(DISTINCT qq.id) as question_count,
                COUNT(DISTINCT qa.id) as attempt_count
                FROM {$this->table} q
                LEFT JOIN courses c ON q.course_id = c.id
                LEFT JOIN quiz_questions qq ON q.id = qq.quiz_id
                LEFT JOIN quiz_attempts qa ON q.id = qa.quiz_id
                WHERE q.course_id = :course_id
                GROUP BY q.id
                ORDER BY q.created_at DESC";
        
        return $this->query($sql, ['course_id' => $courseId])->fetchAll();
    }

    /**
     * Get teacher quizzes
     */
    public function getTeacherQuizzes($teacherId) {
        $sql = "SELECT q.*, c.title as course_title,
                COUNT(DISTINCT qq.id) as question_count,
                COUNT(DISTINCT qa.id) as attempt_count,
                AVG(qa.score) as avg_score
                FROM {$this->table} q
                LEFT JOIN courses c ON q.course_id = c.id
                LEFT JOIN quiz_questions qq ON q.id = qq.quiz_id
                LEFT JOIN quiz_attempts qa ON q.id = qa.quiz_id
                WHERE c.teacher_id = :teacher_id
                GROUP BY q.id
                ORDER BY q.created_at DESC";
        
        return $this->query($sql, ['teacher_id' => $teacherId])->fetchAll();
    }

    /**
     * Create quiz
     */
    public function create($data) {
        return $this->insert($data);
    }

    /**
     * Add question to quiz
     */
    public function addQuestion($quizId, $questionData) {
        $questionData['quiz_id'] = $quizId;
        
        $db = Database::getInstance();
        $sql = "INSERT INTO quiz_questions (quiz_id, question_text, question_type, options, correct_answer, points, explanation)
                VALUES (:quiz_id, :question_text, :question_type, :options, :correct_answer, :points, :explanation)";
        
        return $db->query($sql, $questionData);
    }
}
