<?php
namespace Controllers;

use Core\Helpers;
use Core\Auth;
use PDO;

class QuizController extends BaseController
{
    public function show(): void
    {
        $quizId = (int)($_GET['id'] ?? 0);
        $qz = $this->db->prepare('SELECT * FROM quizzes WHERE id=?');
        $qz->execute([$quizId]);
        $quiz = $qz->fetch(PDO::FETCH_ASSOC);
        if (!$quiz) { http_response_code(404); echo 'Quiz not found'; return; }
        $qs = $this->db->prepare('SELECT * FROM questions WHERE quiz_id=?');
        $qs->execute([$quizId]);
        $questions = $qs->fetchAll(PDO::FETCH_ASSOC);
        $opts = $this->db->prepare('SELECT * FROM options WHERE question_id IN (SELECT id FROM questions WHERE quiz_id=?)');
        $opts->execute([$quizId]);
        $options = $opts->fetchAll(PDO::FETCH_ASSOC);
        $grouped = [];
        foreach ($options as $o) { $grouped[$o['question_id']][] = $o; }
        $this->render('quizzes/show', ['quiz'=>$quiz,'questions'=>$questions,'options'=>$grouped]);
    }

    public function submit(): void
    {
        $this->requireLogin();
        if (!\Core\Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(400); echo 'Bad CSRF'; return; }
        $quizId = (int)($_POST['quiz_id'] ?? 0);
        $answers = $_POST['answers'] ?? [];
        if ($quizId <= 0) { http_response_code(400); echo 'Invalid'; return; }
        $start = date('Y-m-d H:i:s');
        $ins = $this->db->prepare('INSERT INTO quiz_attempts(quiz_id,user_id,score,started_at,finished_at) VALUES(?,?,?,?,?)');
        $ins->execute([$quizId, Auth::id(), 0, $start, null]);
        $attemptId = (int)$this->db->lastInsertId();
        $score = 0; $total = 0;
        foreach ($answers as $qid => $optId) {
            $total++;
            $ok = $this->db->prepare('SELECT is_correct FROM options WHERE id=? AND question_id=?');
            $ok->execute([(int)$optId, (int)$qid]);
            $isCorrect = (int)$ok->fetchColumn() === 1;
            if ($isCorrect) $score++;
            $this->db->prepare('INSERT INTO answers(attempt_id,question_id,option_id) VALUES(?,?,?)')->execute([$attemptId,(int)$qid,(int)$optId]);
        }
        $final = $total>0 ? round(($score/$total)*100,2) : 0;
        $this->db->prepare('UPDATE quiz_attempts SET score=?, finished_at=NOW() WHERE id=?')->execute([$final,$attemptId]);
        $this->render('quizzes/result', ['score' => $final]);
    }
}
