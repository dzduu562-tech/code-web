<?php

class Quiz {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM quizzes WHERE id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    public function create($data) {
        return $this->db->insert('quizzes', $data);
    }
    
    public function update($id, $data) {
        return $this->db->update('quizzes', $data, 'id = :id', ['id' => $id]);
    }
    
    public function delete($id) {
        return $this->db->delete('quizzes', 'id = :id', ['id' => $id]);
    }
    
    public function getByLesson($lessonId) {
        $sql = "SELECT * FROM quizzes WHERE lesson_id = :lesson_id";
        return $this->db->fetchAll($sql, ['lesson_id' => $lessonId]);
    }
    
    public function getQuestions($quizId) {
        $sql = "SELECT * FROM questions WHERE quiz_id = :quiz_id ORDER BY position ASC";
        return $this->db->fetchAll($sql, ['quiz_id' => $quizId]);
    }
    
    public function getQuestionsWithOptions($quizId) {
        $questions = $this->getQuestions($quizId);
        
        foreach ($questions as &$question) {
            $sql = "SELECT * FROM options WHERE question_id = :question_id";
            $question['options'] = $this->db->fetchAll($sql, ['question_id' => $question['id']]);
        }
        
        return $questions;
    }
    
    public function addQuestion($data) {
        return $this->db->insert('questions', $data);
    }
    
    public function updateQuestion($id, $data) {
        return $this->db->update('questions', $data, 'id = :id', ['id' => $id]);
    }
    
    public function deleteQuestion($id) {
        return $this->db->delete('questions', 'id = :id', ['id' => $id]);
    }
    
    public function addOption($data) {
        return $this->db->insert('options', $data);
    }
    
    public function deleteOption($id) {
        return $this->db->delete('options', 'id = :id', ['id' => $id]);
    }
    
    public function startAttempt($quizId, $userId) {
        return $this->db->insert('quiz_attempts', [
            'quiz_id' => $quizId,
            'user_id' => $userId,
            'score' => 0,
            'total_questions' => 0,
            'correct_answers' => 0
        ]);
    }
    
    public function submitAttempt($attemptId, $answers) {
        // Save each answer
        foreach ($answers as $questionId => $optionId) {
            $this->db->insert('answers', [
                'attempt_id' => $attemptId,
                'question_id' => $questionId,
                'option_id' => $optionId
            ]);
        }
        
        // Calculate score
        $sql = "SELECT COUNT(*) as total FROM answers a
                JOIN options o ON a.option_id = o.id
                WHERE a.attempt_id = :attempt_id";
        $totalResult = $this->db->fetchOne($sql, ['attempt_id' => $attemptId]);
        $totalQuestions = $totalResult['total'] ?? 0;
        
        $sql = "SELECT COUNT(*) as correct FROM answers a
                JOIN options o ON a.option_id = o.id
                WHERE a.attempt_id = :attempt_id AND o.is_correct = 1";
        $correctResult = $this->db->fetchOne($sql, ['attempt_id' => $attemptId]);
        $correctAnswers = $correctResult['correct'] ?? 0;
        
        $score = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
        
        // Update attempt
        $this->db->update('quiz_attempts',
            [
                'score' => $score,
                'total_questions' => $totalQuestions,
                'correct_answers' => $correctAnswers,
                'finished_at' => date('Y-m-d H:i:s')
            ],
            'id = :id',
            ['id' => $attemptId]
        );
        
        return [
            'score' => $score,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers
        ];
    }
    
    public function getAttempts($quizId, $userId) {
        $sql = "SELECT * FROM quiz_attempts 
                WHERE quiz_id = :quiz_id AND user_id = :user_id 
                ORDER BY started_at DESC";
        return $this->db->fetchAll($sql, ['quiz_id' => $quizId, 'user_id' => $userId]);
    }
    
    public function getAttemptById($attemptId) {
        $sql = "SELECT * FROM quiz_attempts WHERE id = :id";
        return $this->db->fetchOne($sql, ['id' => $attemptId]);
    }
}
