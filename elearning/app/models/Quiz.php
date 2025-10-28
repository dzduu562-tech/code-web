<?php

class Quiz {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function find($id) {
        $sql = "SELECT q.*, l.title as lesson_title, ch.title as chapter_title, c.title as course_title
                FROM quizzes q
                JOIN lessons l ON q.lesson_id = l.id
                JOIN chapters ch ON l.chapter_id = ch.id
                JOIN courses c ON ch.course_id = c.id
                WHERE q.id = ?";
        return $this->db->fetch($sql, [$id]);
    }

    public function create($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('quizzes', $data);
    }

    public function update($id, $data) {
        return $this->db->update('quizzes', $data, 'id = ?', [$id]);
    }

    public function delete($id) {
        // Delete all related data
        $this->deleteQuizData($id);
        return $this->db->delete('quizzes', 'id = ?', [$id]);
    }

    private function deleteQuizData($quizId) {
        // Delete answers
        $attempts = $this->db->fetchAll("SELECT id FROM quiz_attempts WHERE quiz_id = ?", [$quizId]);
        foreach ($attempts as $attempt) {
            $this->db->delete('answers', 'attempt_id = ?', [$attempt['id']]);
        }
        
        // Delete attempts
        $this->db->delete('quiz_attempts', 'quiz_id = ?', [$quizId]);
        
        // Delete options
        $questions = $this->db->fetchAll("SELECT id FROM questions WHERE quiz_id = ?", [$quizId]);
        foreach ($questions as $question) {
            $this->db->delete('options', 'question_id = ?', [$question['id']]);
        }
        
        // Delete questions
        $this->db->delete('questions', 'quiz_id = ?', [$quizId]);
    }

    public function getQuestions($quizId) {
        $sql = "SELECT * FROM questions WHERE quiz_id = ? ORDER BY position ASC";
        $questions = $this->db->fetchAll($sql, [$quizId]);
        
        foreach ($questions as &$question) {
            $question['options'] = $this->getQuestionOptions($question['id']);
        }
        
        return $questions;
    }

    public function getQuestionOptions($questionId) {
        return $this->db->fetchAll(
            "SELECT * FROM options WHERE question_id = ? ORDER BY position ASC",
            [$questionId]
        );
    }

    public function addQuestion($quizId, $data) {
        // Auto-set position if not provided
        if (!isset($data['position'])) {
            $maxPosition = $this->db->fetch(
                "SELECT MAX(position) as max_pos FROM questions WHERE quiz_id = ?",
                [$quizId]
            );
            $data['position'] = ($maxPosition['max_pos'] ?? 0) + 1;
        }

        $data['quiz_id'] = $quizId;
        return $this->db->insert('questions', $data);
    }

    public function updateQuestion($questionId, $data) {
        return $this->db->update('questions', $data, 'id = ?', [$questionId]);
    }

    public function deleteQuestion($questionId) {
        // Delete options first
        $this->db->delete('options', 'question_id = ?', [$questionId]);
        
        // Delete answers that reference this question
        $this->db->delete('answers', 'question_id = ?', [$questionId]);
        
        return $this->db->delete('questions', 'id = ?', [$questionId]);
    }

    public function addOption($questionId, $data) {
        // Auto-set position if not provided
        if (!isset($data['position'])) {
            $maxPosition = $this->db->fetch(
                "SELECT MAX(position) as max_pos FROM options WHERE question_id = ?",
                [$questionId]
            );
            $data['position'] = ($maxPosition['max_pos'] ?? 0) + 1;
        }

        $data['question_id'] = $questionId;
        return $this->db->insert('options', $data);
    }

    public function updateOption($optionId, $data) {
        return $this->db->update('options', $data, 'id = ?', [$optionId]);
    }

    public function deleteOption($optionId) {
        // Delete answers that reference this option
        $this->db->delete('answers', 'option_id = ?', [$optionId]);
        
        return $this->db->delete('options', 'id = ?', [$optionId]);
    }

    public function startAttempt($quizId, $userId) {
        // Check if user has remaining attempts
        $attemptCount = $this->db->count('quiz_attempts', 'quiz_id = ? AND user_id = ?', [$quizId, $userId]);
        $quiz = $this->find($quizId);
        
        if ($quiz['max_attempts'] > 0 && $attemptCount >= $quiz['max_attempts']) {
            return ['success' => false, 'message' => 'Bạn đã hết lượt làm bài'];
        }

        // Create new attempt
        $attemptId = $this->db->insert('quiz_attempts', [
            'quiz_id' => $quizId,
            'user_id' => $userId,
            'started_at' => date('Y-m-d H:i:s')
        ]);

        return ['success' => true, 'attempt_id' => $attemptId];
    }

    public function submitAttempt($attemptId, $answers) {
        $attempt = $this->db->fetch("SELECT * FROM quiz_attempts WHERE id = ?", [$attemptId]);
        if (!$attempt || $attempt['finished_at']) {
            return ['success' => false, 'message' => 'Bài làm không hợp lệ'];
        }

        $quiz = $this->find($attempt['quiz_id']);
        $questions = $this->getQuestions($attempt['quiz_id']);

        $this->db->beginTransaction();
        
        try {
            $totalScore = 0;
            $maxScore = 0;

            // Process each answer
            foreach ($answers as $questionId => $selectedOptions) {
                $question = null;
                foreach ($questions as $q) {
                    if ($q['id'] == $questionId) {
                        $question = $q;
                        break;
                    }
                }

                if (!$question) continue;

                $maxScore += $question['points'];

                // Handle multiple selection answers
                if (!is_array($selectedOptions)) {
                    $selectedOptions = [$selectedOptions];
                }

                $correctOptions = [];
                $totalOptions = count($question['options']);
                
                foreach ($question['options'] as $option) {
                    if ($option['is_correct']) {
                        $correctOptions[] = $option['id'];
                    }
                }

                // Save answers
                foreach ($selectedOptions as $optionId) {
                    $this->db->insert('answers', [
                        'attempt_id' => $attemptId,
                        'question_id' => $questionId,
                        'option_id' => $optionId
                    ]);
                }

                // Calculate score for this question
                if ($question['question_type'] === 'single') {
                    // Single choice: full points if correct, 0 if wrong
                    if (count($selectedOptions) === 1 && in_array($selectedOptions[0], $correctOptions)) {
                        $totalScore += $question['points'];
                    }
                } else {
                    // Multiple choice: partial scoring
                    $correctSelected = array_intersect($selectedOptions, $correctOptions);
                    $incorrectSelected = array_diff($selectedOptions, $correctOptions);
                    $missedCorrect = array_diff($correctOptions, $selectedOptions);

                    if (count($incorrectSelected) === 0 && count($missedCorrect) === 0) {
                        // All correct, no incorrect
                        $totalScore += $question['points'];
                    } else if (count($correctSelected) > 0) {
                        // Partial credit
                        $partialScore = ($question['points'] * count($correctSelected)) / count($correctOptions);
                        $penalty = ($question['points'] * count($incorrectSelected)) / $totalOptions;
                        $totalScore += max(0, $partialScore - $penalty);
                    }
                }
            }

            // Calculate time spent
            $timeSpent = (strtotime('now') - strtotime($attempt['started_at'])) / 60; // minutes

            // Update attempt
            $this->db->update('quiz_attempts', [
                'score' => $totalScore,
                'max_score' => $maxScore,
                'finished_at' => date('Y-m-d H:i:s'),
                'time_spent_minutes' => $timeSpent
            ], 'id = ?', [$attemptId]);

            $this->db->commit();

            $percentage = $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;
            $passed = $percentage >= $quiz['pass_score'];

            return [
                'success' => true,
                'score' => $totalScore,
                'max_score' => $maxScore,
                'percentage' => $percentage,
                'passed' => $passed,
                'time_spent' => $timeSpent
            ];

        } catch (Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Có lỗi xảy ra khi nộp bài'];
        }
    }

    public function getAttempts($quizId, $userId = null) {
        $where = "quiz_id = ?";
        $params = [$quizId];

        if ($userId) {
            $where .= " AND user_id = ?";
            $params[] = $userId;
        }

        $sql = "SELECT qa.*, u.name as user_name
                FROM quiz_attempts qa
                JOIN users u ON qa.user_id = u.id
                WHERE {$where}
                ORDER BY qa.started_at DESC";

        return $this->db->fetchAll($sql, $params);
    }

    public function getAttempt($attemptId) {
        $sql = "SELECT qa.*, u.name as user_name, q.title as quiz_title
                FROM quiz_attempts qa
                JOIN users u ON qa.user_id = u.id
                JOIN quizzes q ON qa.quiz_id = q.id
                WHERE qa.id = ?";
        return $this->db->fetch($sql, [$attemptId]);
    }

    public function getAttemptAnswers($attemptId) {
        $sql = "SELECT a.*, q.question_text, q.question_type, o.option_text, o.is_correct
                FROM answers a
                JOIN questions q ON a.question_id = q.id
                JOIN options o ON a.option_id = o.id
                WHERE a.attempt_id = ?
                ORDER BY q.position ASC, o.position ASC";
        
        $answers = $this->db->fetchAll($sql, [$attemptId]);
        
        // Group answers by question
        $groupedAnswers = [];
        foreach ($answers as $answer) {
            $questionId = $answer['question_id'];
            if (!isset($groupedAnswers[$questionId])) {
                $groupedAnswers[$questionId] = [
                    'question_text' => $answer['question_text'],
                    'question_type' => $answer['question_type'],
                    'answers' => []
                ];
            }
            $groupedAnswers[$questionId]['answers'][] = $answer;
        }
        
        return $groupedAnswers;
    }

    public function getBestAttempt($quizId, $userId) {
        return $this->db->fetch(
            "SELECT * FROM quiz_attempts 
             WHERE quiz_id = ? AND user_id = ? AND finished_at IS NOT NULL
             ORDER BY score DESC, finished_at ASC
             LIMIT 1",
            [$quizId, $userId]
        );
    }

    public function getAverageScore($quizId) {
        $result = $this->db->fetch(
            "SELECT AVG(score) as avg_score, AVG(max_score) as avg_max_score
             FROM quiz_attempts 
             WHERE quiz_id = ? AND finished_at IS NOT NULL",
            [$quizId]
        );
        
        if ($result['avg_max_score'] > 0) {
            return ($result['avg_score'] / $result['avg_max_score']) * 100;
        }
        
        return 0;
    }

    public function getQuizStats($quizId) {
        $quiz = $this->find($quizId);
        if (!$quiz) return null;

        $totalAttempts = $this->db->count('quiz_attempts', 'quiz_id = ?', [$quizId]);
        $completedAttempts = $this->db->count('quiz_attempts', 'quiz_id = ? AND finished_at IS NOT NULL', [$quizId]);
        $passedAttempts = $this->db->count(
            'quiz_attempts', 
            'quiz_id = ? AND finished_at IS NOT NULL AND (score / max_score * 100) >= ?', 
            [$quizId, $quiz['pass_score']]
        );

        $avgScore = $this->getAverageScore($quizId);
        
        $avgTime = $this->db->fetch(
            "SELECT AVG(time_spent_minutes) as avg_time 
             FROM quiz_attempts 
             WHERE quiz_id = ? AND finished_at IS NOT NULL",
            [$quizId]
        )['avg_time'] ?? 0;

        return [
            'total_attempts' => $totalAttempts,
            'completed_attempts' => $completedAttempts,
            'passed_attempts' => $passedAttempts,
            'pass_rate' => $completedAttempts > 0 ? ($passedAttempts / $completedAttempts) * 100 : 0,
            'average_score' => $avgScore,
            'average_time' => $avgTime,
            'question_count' => $this->db->count('questions', 'quiz_id = ?', [$quizId])
        ];
    }

    public function canUserTakeQuiz($quizId, $userId) {
        $quiz = $this->find($quizId);
        if (!$quiz || !$quiz['is_published']) {
            return ['can_take' => false, 'reason' => 'Quiz không khả dụng'];
        }

        // Check if user is enrolled in the course
        $courseModel = new Course();
        if (!$courseModel->isEnrolled($quiz['course_id'], $userId)) {
            return ['can_take' => false, 'reason' => 'Bạn chưa đăng ký khóa học này'];
        }

        // Check attempt limit
        $attemptCount = $this->db->count('quiz_attempts', 'quiz_id = ? AND user_id = ?', [$quizId, $userId]);
        if ($quiz['max_attempts'] > 0 && $attemptCount >= $quiz['max_attempts']) {
            return ['can_take' => false, 'reason' => 'Bạn đã hết lượt làm bài'];
        }

        // Check if there's an ongoing attempt
        $ongoingAttempt = $this->db->fetch(
            "SELECT * FROM quiz_attempts WHERE quiz_id = ? AND user_id = ? AND finished_at IS NULL",
            [$quizId, $userId]
        );

        if ($ongoingAttempt) {
            return [
                'can_take' => true, 
                'has_ongoing' => true, 
                'attempt_id' => $ongoingAttempt['id'],
                'remaining_attempts' => $quiz['max_attempts'] - $attemptCount
            ];
        }

        return [
            'can_take' => true, 
            'has_ongoing' => false,
            'remaining_attempts' => $quiz['max_attempts'] > 0 ? $quiz['max_attempts'] - $attemptCount : -1
        ];
    }

    public function reorderQuestions($quizId, $questionIds) {
        $this->db->beginTransaction();
        
        try {
            foreach ($questionIds as $position => $questionId) {
                $this->db->update('questions', 
                    ['position' => $position + 1], 
                    'id = ? AND quiz_id = ?', 
                    [$questionId, $quizId]
                );
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
}