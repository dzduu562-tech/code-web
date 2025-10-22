<?php

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
    
    public function getQuizWithQuestions($id) {
        $quiz = $this->find($id);
        if (!$quiz) return null;
        
        $questions = $this->db->fetchAll(
            "SELECT q.*, GROUP_CONCAT(
                JSON_OBJECT('id', o.id, 'text', o.text, 'is_correct', o.is_correct, 'position', o.position)
                ORDER BY o.position
            ) as options
            FROM questions q
            LEFT JOIN options o ON q.id = o.question_id
            WHERE q.quiz_id = ?
            GROUP BY q.id
            ORDER BY q.position",
            [$id]
        );
        
        // Parse JSON options
        foreach ($questions as &$question) {
            $question['options'] = json_decode($question['options'], true) ?: [];
        }
        
        $quiz['questions'] = $questions;
        return $quiz;
    }
    
    public function addQuestion($quizId, $text, $questionType = 'single', $points = 1.0, $position = 0) {
        return $this->db->insert('questions', [
            'quiz_id' => $quizId,
            'text' => $text,
            'question_type' => $questionType,
            'points' => $points,
            'position' => $position
        ]);
    }
    
    public function addOption($questionId, $text, $isCorrect = false, $position = 0) {
        return $this->db->insert('options', [
            'question_id' => $questionId,
            'text' => $text,
            'is_correct' => $isCorrect ? 1 : 0,
            'position' => $position
        ]);
    }
    
    public function updateQuestion($id, $data) {
        return $this->db->update('questions', $data, 'id = ?', [$id]);
    }
    
    public function updateOption($id, $data) {
        return $this->db->update('options', $data, 'id = ?', [$id]);
    }
    
    public function deleteQuestion($id) {
        return $this->db->delete('questions', 'id = ?', [$id]);
    }
    
    public function deleteOption($id) {
        return $this->db->delete('options', 'id = ?', [$id]);
    }
    
    public function startAttempt($quizId, $userId) {
        // Check if user already has an unfinished attempt
        $existingAttempt = $this->db->fetch(
            "SELECT * FROM quiz_attempts WHERE quiz_id = ? AND user_id = ? AND finished_at IS NULL",
            [$quizId, $userId]
        );
        
        if ($existingAttempt) {
            return $existingAttempt['id'];
        }
        
        // Get total questions count
        $totalQuestions = $this->db->count('questions', 'quiz_id = ?', [$quizId]);
        
        // Create new attempt
        return $this->db->insert('quiz_attempts', [
            'quiz_id' => $quizId,
            'user_id' => $userId,
            'total_questions' => $totalQuestions
        ]);
    }
    
    public function submitAnswer($attemptId, $questionId, $optionId) {
        // Get question and option details
        $question = $this->db->fetch("SELECT * FROM questions WHERE id = ?", [$questionId]);
        $option = $this->db->fetch("SELECT * FROM options WHERE id = ?", [$optionId]);
        
        if (!$question || !$option) {
            return false;
        }
        
        // Check if answer is correct
        $isCorrect = $option['is_correct'];
        
        // Check if answer already exists
        $existingAnswer = $this->db->fetch(
            "SELECT * FROM answers WHERE attempt_id = ? AND question_id = ?",
            [$attemptId, $questionId]
        );
        
        if ($existingAnswer) {
            // Update existing answer
            return $this->db->update('answers', [
                'option_id' => $optionId,
                'is_correct' => $isCorrect ? 1 : 0
            ], 'id = ?', [$existingAnswer['id']]);
        } else {
            // Create new answer
            return $this->db->insert('answers', [
                'attempt_id' => $attemptId,
                'question_id' => $questionId,
                'option_id' => $optionId,
                'is_correct' => $isCorrect ? 1 : 0
            ]);
        }
    }
    
    public function finishAttempt($attemptId) {
        // Calculate score
        $attempt = $this->db->fetch("SELECT * FROM quiz_attempts WHERE id = ?", [$attemptId]);
        if (!$attempt) return false;
        
        $correctAnswers = $this->db->count('answers', 'attempt_id = ? AND is_correct = 1', [$attemptId]);
        $totalQuestions = $attempt['total_questions'];
        $score = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
        
        // Update attempt
        return $this->db->update('quiz_attempts', [
            'score' => $score,
            'correct_answers' => $correctAnswers,
            'finished_at' => date('Y-m-d H:i:s')
        ], 'id = ?', [$attemptId]);
    }
    
    public function getAttempt($attemptId) {
        return $this->db->fetch("SELECT * FROM quiz_attempts WHERE id = ?", [$attemptId]);
    }
    
    public function getUserAttempts($quizId, $userId) {
        return $this->db->fetchAll(
            "SELECT * FROM quiz_attempts WHERE quiz_id = ? AND user_id = ? ORDER BY started_at DESC",
            [$quizId, $userId]
        );
    }
    
    public function getAttemptResults($attemptId) {
        $attempt = $this->getAttempt($attemptId);
        if (!$attempt) return null;
        
        $answers = $this->db->fetchAll(
            "SELECT a.*, q.text as question_text, o.text as option_text, o.is_correct
             FROM answers a
             JOIN questions q ON a.question_id = q.id
             LEFT JOIN options o ON a.option_id = o.id
             WHERE a.attempt_id = ?
             ORDER BY q.position",
            [$attemptId]
        );
        
        $attempt['answers'] = $answers;
        return $attempt;
    }
    
    public function getBestScore($quizId, $userId) {
        $result = $this->db->fetch(
            "SELECT MAX(score) as best_score FROM quiz_attempts 
             WHERE quiz_id = ? AND user_id = ? AND finished_at IS NOT NULL",
            [$quizId, $userId]
        );
        return $result ? $result['best_score'] : 0;
    }
    
    public function getAverageScore($quizId) {
        $result = $this->db->fetch(
            "SELECT AVG(score) as avg_score FROM quiz_attempts 
             WHERE quiz_id = ? AND finished_at IS NOT NULL",
            [$quizId]
        );
        return $result ? round($result['avg_score'], 2) : 0;
    }
    
    public function getAttemptCount($quizId) {
        return $this->db->count('quiz_attempts', 'quiz_id = ? AND finished_at IS NOT NULL', [$quizId]);
    }
    
    public function isPassed($score, $passingScore) {
        return $score >= $passingScore;
    }
    
    public function getTimeRemaining($attemptId, $timeLimitMinutes) {
        $attempt = $this->getAttempt($attemptId);
        if (!$attempt || $attempt['finished_at']) {
            return 0;
        }
        
        $startTime = strtotime($attempt['started_at']);
        $timeLimit = $timeLimitMinutes * 60;
        $elapsed = time() - $startTime;
        $remaining = $timeLimit - $elapsed;
        
        return max(0, $remaining);
    }
}