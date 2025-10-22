<?php

class QuizController {
    
    public function show() {
        Auth::requireLogin();
        
        $quizId = $_GET['id'] ?? 0;
        $quizModel = new Quiz();
        
        $quiz = $quizModel->findById($quizId);
        
        if (!$quiz) {
            http_response_code(404);
            die('Quiz không tồn tại');
        }
        
        $questions = $quizModel->getQuestionsWithOptions($quizId);
        $attempts = $quizModel->getAttempts($quizId, Auth::id());
        
        require __DIR__ . '/../views/quiz/show.php';
    }
    
    public function start() {
        Auth::requireRoles(['student']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('courses');
            return;
        }
        
        $quizId = $_POST['quiz_id'] ?? 0;
        $quizModel = new Quiz();
        
        $attemptId = $quizModel->startAttempt($quizId, Auth::id());
        
        Helpers::redirect("quiz/take&id=$attemptId");
    }
    
    public function take() {
        Auth::requireRoles(['student']);
        
        $attemptId = $_GET['id'] ?? 0;
        $quizModel = new Quiz();
        
        $attempt = $quizModel->getAttemptById($attemptId);
        
        if (!$attempt || $attempt['user_id'] != Auth::id()) {
            http_response_code(403);
            die('Access denied');
        }
        
        if ($attempt['finished_at']) {
            Helpers::redirect("quiz/result&id=$attemptId");
            return;
        }
        
        $quiz = $quizModel->findById($attempt['quiz_id']);
        $questions = $quizModel->getQuestionsWithOptions($attempt['quiz_id']);
        
        require __DIR__ . '/../views/quiz/take.php';
    }
    
    public function submit() {
        Auth::requireRoles(['student']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('courses');
            return;
        }
        
        if (!Auth::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token không hợp lệ';
            Helpers::redirect('courses');
            return;
        }
        
        $attemptId = $_POST['attempt_id'] ?? 0;
        $answers = $_POST['answers'] ?? [];
        
        $quizModel = new Quiz();
        $result = $quizModel->submitAttempt($attemptId, $answers);
        
        $_SESSION['success'] = 'Đã nộp bài!';
        Helpers::redirect("quiz/result&id=$attemptId");
    }
    
    public function result() {
        Auth::requireLogin();
        
        $attemptId = $_GET['id'] ?? 0;
        $quizModel = new Quiz();
        
        $attempt = $quizModel->getAttemptById($attemptId);
        
        if (!$attempt) {
            http_response_code(404);
            die('Không tìm thấy kết quả');
        }
        
        // Students can only view their own results
        if (Auth::isStudent() && $attempt['user_id'] != Auth::id()) {
            http_response_code(403);
            die('Access denied');
        }
        
        $quiz = $quizModel->findById($attempt['quiz_id']);
        
        require __DIR__ . '/../views/quiz/result.php';
    }
    
    public function create() {
        Auth::requireRoles(['teacher', 'admin']);
        
        $lessonId = $_GET['lesson_id'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require __DIR__ . '/../views/quiz/create.php';
            return;
        }
        
        if (!Auth::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Token không hợp lệ';
            Helpers::redirect("quiz/create&lesson_id=$lessonId");
            return;
        }
        
        $title = Helpers::sanitizeInput($_POST['title'] ?? '');
        $description = Helpers::sanitizeInput($_POST['description'] ?? '');
        
        $quizModel = new Quiz();
        $quizId = $quizModel->create([
            'lesson_id' => $lessonId,
            'title' => $title,
            'description' => $description
        ]);
        
        // Add questions
        if (isset($_POST['questions']) && is_array($_POST['questions'])) {
            foreach ($_POST['questions'] as $index => $questionData) {
                $questionId = $quizModel->addQuestion([
                    'quiz_id' => $quizId,
                    'text' => Helpers::sanitizeInput($questionData['text'] ?? ''),
                    'position' => $index
                ]);
                
                if (isset($questionData['options']) && is_array($questionData['options'])) {
                    foreach ($questionData['options'] as $optionData) {
                        $quizModel->addOption([
                            'question_id' => $questionId,
                            'text' => Helpers::sanitizeInput($optionData['text'] ?? ''),
                            'is_correct' => isset($optionData['is_correct']) ? 1 : 0
                        ]);
                    }
                }
            }
        }
        
        $_SESSION['success'] = 'Tạo quiz thành công!';
        Helpers::redirect("lesson&id=$lessonId");
    }
}
