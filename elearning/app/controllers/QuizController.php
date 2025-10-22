<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/DB.php';
require_once __DIR__ . '/../core/Helpers.php';

class QuizController extends BaseController {
    public function take(): string {
        Auth::startSecureSession();
        if (!Auth::check()) { header('Location: index.php?route=/login'); exit; }
        $quizId = (int)($_GET['id'] ?? 0);
        $pdo = DB::getConnection();
        $quiz = $pdo->prepare('SELECT * FROM quizzes WHERE id=?');
        $quiz->execute([$quizId]);
        $quiz = $quiz->fetch();
        if (!$quiz) { http_response_code(404); return 'Quiz not found'; }
        $qs = $pdo->prepare('SELECT * FROM questions WHERE quiz_id=?');
        $qs->execute([$quizId]);
        $questions = $qs->fetchAll();
        $optsStmt = $pdo->prepare('SELECT * FROM options WHERE question_id=?');
        $options = [];
        foreach ($questions as $q) { $optsStmt->execute([$q['id']]); $options[$q['id']] = $optsStmt->fetchAll(); }
        return $this->render('quizzes/take', compact('quiz','questions','options'));
    }

    public function submit(): void {
        Auth::startSecureSession();
        if (!Auth::check() || !Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(400); exit; }
        $quizId = (int)($_POST['quiz_id'] ?? 0);
        $answers = $_POST['answers'] ?? [];
        $pdo = DB::getConnection();
        $pdo->beginTransaction();
        try {
            $pdo->prepare('INSERT INTO quiz_attempts(quiz_id, user_id, score, started_at, finished_at) VALUES(?, ?, 0, NOW(), NOW())')->execute([$quizId, Auth::id()]);
            $attemptId = (int)$pdo->lastInsertId();
            $score = 0; $total = 0;
            foreach ($answers as $questionId => $optionId) {
                $total++;
                $isCorrect = (int)$pdo->query('SELECT is_correct FROM options WHERE id='.(int)$optionId)->fetchColumn();
                if ($isCorrect === 1) { $score++; }
                $pdo->prepare('INSERT INTO answers(attempt_id, question_id, option_id) VALUES(?,?,?)')->execute([$attemptId, (int)$questionId, (int)$optionId]);
            }
            $percent = $total > 0 ? (int)floor(($score/$total)*100) : 0;
            $pdo->prepare('UPDATE quiz_attempts SET score=? WHERE id=?')->execute([$percent, $attemptId]);
            $pdo->commit();
            $_SESSION['flash_success'] = 'Điểm của bạn: ' . $percent . '%';
            Helpers::redirect('index.php?route=/lesson&id=' . (int)($_POST['lesson_id'] ?? 0));
        } catch (Throwable $e) {
            $pdo->rollBack();
            $_SESSION['flash_error'] = 'Lỗi khi nộp bài';
            Helpers::redirect('index.php?route=/lesson&id=' . (int)($_POST['lesson_id'] ?? 0));
        }
    }
}
