<?php
require_once __DIR__ . '/../core/DB.php';

class Quiz {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    public function find($id) {
        return $this->db->fetch("SELECT * FROM quizzes WHERE id = ?", [$id]);
    }
    
    public function getByLesson($lessonId) {
        return $this->db->fetch("SELECT * FROM quizzes WHERE lesson_id = ?", [$lessonId]);
    }
    
    public function create($data) {
        return $this->db->insert('quizzes', $data);
    }
    
    public function update($id, $data) {
        return $this->db->update('quizzes', $data, 'id = ?', [$id]);
    }
    
    public function delete($id) {
        return $this->db->delete('quizzes', 'id = ?', [$id]);
    }
    
    public function getQuestions($quizId) {
        return $this->db->fetchAll(
            "SELECT * FROM questions WHERE quiz_id = ? ORDER BY position ASC",
            [$quizId]
        );
    }
    
    public function getQuestionWithOptions($questionId) {
        $question = $this->db->fetch("SELECT * FROM questions WHERE id = ?", [$questionId]);
        if (!$question) {
            return null;
        }
        
        $question['options'] = $this->db->fetchAll(
            "SELECT * FROM options WHERE question_id = ? ORDER BY position ASC",
            [$questionId]
        );
        
        return $question;
    }
    
    public function getQuizWithQuestions($quizId) {
        $quiz = $this->find($quizId);
        if (!$quiz) {
            return null;
        }
        
        $questions = $this->getQuestions($quizId);
        foreach ($questions as &$question) {
            $question['options'] = $this->db->fetchAll(
                "SELECT * FROM options WHERE question_id = ? ORDER BY position ASC",
                [$question['id']]
            );
        }
        
        $quiz['questions'] = $questions;
        return $quiz;
    }
    
    public function createQuestion($quizId, $text, $points = 1.0, $position = 1) {
        return $this->db->insert('questions', [
            'quiz_id' => $quizId,
            'text' => $text,
            'points' => $points,
            'position' => $position
        ]);
    }
    
    public function updateQuestion($questionId, $data) {
        return $this->db->update('questions', $data, 'id = ?', [$questionId]);
    }
    
    public function deleteQuestion($questionId) {
        return $this->db->delete('questions', 'id = ?', [$questionId]);
    }
    
    public function addOption($questionId, $text, $isCorrect = false, $position = 1) {
        return $this->db->insert('options', [
            'question_id' => $questionId,
            'text' => $text,
            'is_correct' => $isCorrect,
            'position' => $position
        ]);
    }
    
    public function updateOption($optionId, $data) {
        return $this->db->update('options', $data, 'id = ?', [$optionId]);
    }
    
    public function deleteOption($optionId) {
        return $this->db->delete('options', 'id = ?', [$optionId]);
    }
    
    public function startAttempt($quizId, $userId) {
        $quiz = $this->find($quizId);
        if (!$quiz) {
            return false;
        }
        
        // Check if there's an unfinished attempt
        $existingAttempt = $this->db->fetch(
            "SELECT * FROM quiz_attempts WHERE quiz_id = ? AND user_id = ? AND finished_at IS NULL",
            [$quizId, $userId]
        );
        
        if ($existingAttempt) {
            return $existingAttempt['id'];
        }
        
        // Calculate total points
        $totalPoints = $this->db->fetch(
            "SELECT SUM(points) as total FROM questions WHERE quiz_id = ?",
            [$quizId]
        )['total'] ?? 0;
        
        return $this->db->insert('quiz_attempts', [
            'quiz_id' => $quizId,
            'user_id' => $userId,
            'total_points' => $totalPoints
        ]);
    }
    
    public function finishAttempt($attemptId, $answers) {
        $attempt = $this->db->fetch("SELECT * FROM quiz_attempts WHERE id = ?", [$attemptId]);
        if (!$attempt || $attempt['finished_at']) {
            return false;
        }
        
        $score = 0;
        $totalPoints = $attempt['total_points'];
        
        // Calculate score based on answers
        foreach ($answers as $questionId => $optionId) {
            $option = $this->db->fetch(
                "SELECT * FROM options WHERE id = ? AND question_id = ?",
                [$optionId, $questionId]
            );
            
            if ($option && $option['is_correct']) {
                $question = $this->db->fetch("SELECT points FROM questions WHERE id = ?", [$questionId]);
                $score += $question['points'];
            }
            
            // Save answer
            $this->db->insert('answers', [
                'attempt_id' => $attemptId,
                'question_id' => $questionId,
                'option_id' => $optionId
            ]);
        }
        
        // Update attempt with score
        $this->db->update('quiz_attempts', [
            'score' => $score,
            'finished_at' => date('Y-m-d H:i:s')
        ], 'id = ?', [$attemptId]);
        
        return $score;
    }
    
    public function getAttempts($quizId, $userId = null) {
        $sql = "SELECT qa.*, u.name as user_name 
                FROM quiz_attempts qa
                LEFT JOIN users u ON qa.user_id = u.id
                WHERE qa.quiz_id = ?";
        $params = [$quizId];
        
        if ($userId) {
            $sql .= " AND qa.user_id = ?";
            $params[] = $userId;
        }
        
        $sql .= " ORDER BY qa.started_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getAttempt($attemptId) {
        return $this->db->fetch(
            "SELECT qa.*, q.title as quiz_title, u.name as user_name
             FROM quiz_attempts qa
             LEFT JOIN quizzes q ON qa.quiz_id = q.id
             LEFT JOIN users u ON qa.user_id = u.id
             WHERE qa.id = ?",
            [$attemptId]
        );
    }
    
    public function getAttemptAnswers($attemptId) {
        return $this->db->fetchAll(
            "SELECT a.*, q.text as question_text, o.text as option_text, o.is_correct
             FROM answers a
             LEFT JOIN questions q ON a.question_id = q.id
             LEFT JOIN options o ON a.option_id = o.id
             WHERE a.attempt_id = ?
             ORDER BY q.position ASC",
            [$attemptId]
        );
    }
    
    public function getBestScore($quizId, $userId) {
        $result = $this->db->fetch(
            "SELECT MAX(score) as best_score FROM quiz_attempts 
             WHERE quiz_id = ? AND user_id = ? AND finished_at IS NOT NULL",
            [$quizId, $userId]
        );
        
        return $result['best_score'] ?? 0;
    }
    
    public function getAverageScore($quizId) {
        $result = $this->db->fetch(
            "SELECT AVG(score) as avg_score FROM quiz_attempts 
             WHERE quiz_id = ? AND finished_at IS NOT NULL",
            [$quizId]
        );
        
        return round($result['avg_score'] ?? 0, 2);
    }
    
    public function getAttemptCount($quizId, $userId = null) {
        $sql = "SELECT COUNT(*) as total FROM quiz_attempts WHERE quiz_id = ?";
        $params = [$quizId];
        
        if ($userId) {
            $sql .= " AND user_id = ?";
            $params[] = $userId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['total'];
    }
    
    public function isTimeUp($attemptId) {
        $attempt = $this->db->fetch("SELECT * FROM quiz_attempts WHERE id = ?", [$attemptId]);
        if (!$attempt || $attempt['finished_at']) {
            return true;
        }
        
        $quiz = $this->find($attempt['quiz_id']);
        if (!$quiz || !$quiz['time_limit']) {
            return false;
        }
        
        $timeLimit = $quiz['time_limit'] * 60; // Convert to seconds
        $elapsed = time() - strtotime($attempt['started_at']);
        
        return $elapsed >= $timeLimit;
    }
}