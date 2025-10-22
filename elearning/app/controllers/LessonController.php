<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/DB.php';
require_once __DIR__ . '/../core/Helpers.php';

class LessonController extends BaseController {
    public function show(): string {
        $id = (int)($_GET['id'] ?? 0);
        $pdo = DB::getConnection();
        $lesson = $pdo->prepare('SELECT l.*, c.id AS course_id, c.title AS course_title FROM lessons l JOIN chapters ch ON ch.id=l.chapter_id JOIN courses c ON c.id=ch.course_id WHERE l.id=?');
        $lesson->execute([$id]);
        $lesson = $lesson->fetch();
        if (!$lesson) { http_response_code(404); return 'Lesson not found'; }
        $resources = $pdo->prepare('SELECT * FROM resources WHERE lesson_id=?');
        $resources->execute([$id]);
        $resources = $resources->fetchAll();
        $quiz = $pdo->prepare('SELECT * FROM quizzes WHERE lesson_id=?');
        $quiz->execute([$id]);
        $quiz = $quiz->fetch();
        return $this->render('lessons/show', compact('lesson','resources','quiz'));
    }

    public function markDone(): void {
        Auth::startSecureSession();
        if (!Auth::check()) { http_response_code(401); exit; }
        if (!Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(400); exit; }
        $lessonId = (int)($_POST['lesson_id'] ?? 0);
        $pdo = DB::getConnection();
        $courseId = (int)$pdo->query('SELECT c.id FROM courses c JOIN chapters ch ON ch.course_id=c.id JOIN lessons l ON l.chapter_id=ch.id WHERE l.id=' . $lessonId)->fetchColumn();
        // Ensure enrollment exists
        $pdo->prepare('INSERT IGNORE INTO enrollments(user_id, course_id, progress_percent, last_view_at) VALUES(?,?,0,NOW())')
            ->execute([Auth::id(), $courseId]);
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM lessons l JOIN chapters ch ON ch.id=l.chapter_id WHERE ch.course_id = ?');
        $stmt->execute([$courseId]);
        $totalLessons = (int)$stmt->fetchColumn();
        $viewedCountStmt = $pdo->prepare('SELECT COUNT(*) FROM lesson_views WHERE user_id=? AND lesson_id IN (SELECT l.id FROM lessons l JOIN chapters ch ON ch.id=l.chapter_id WHERE ch.course_id=?)');
        $viewedCountStmt->execute([Auth::id(), $courseId]);
        $viewed = (int)$viewedCountStmt->fetchColumn();
        $pdo->prepare('INSERT IGNORE INTO lesson_views(user_id, lesson_id, viewed_at) VALUES(?,?,NOW())')->execute([Auth::id(), $lessonId]);
        $viewed = min($totalLessons, $viewed + 1);
        $progress = $totalLessons > 0 ? (int)floor(($viewed / $totalLessons) * 100) : 0;
        $pdo->prepare('UPDATE enrollments SET progress_percent=?, last_view_at=NOW() WHERE user_id=? AND course_id=?')->execute([$progress, Auth::id(), $courseId]);
        Helpers::json(['ok'=>true,'progress'=>$progress]);
    }
}
