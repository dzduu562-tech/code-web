<?php
namespace Controllers;

use PDO;
use Core\Helpers;

class LessonController extends BaseController
{
    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->db->prepare('SELECT l.*, ch.title AS chapter_title, ch.course_id, c.title AS course_title FROM lessons l JOIN chapters ch ON ch.id=l.chapter_id JOIN courses c ON c.id=ch.course_id WHERE l.id = ?');
        $stmt->execute([$id]);
        $lesson = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$lesson) { http_response_code(404); echo 'Lesson not found'; return; }

        // Resources
        $resStmt = $this->db->prepare('SELECT * FROM resources WHERE lesson_id = ?');
        $resStmt->execute([$id]);
        $resources = $resStmt->fetchAll();

        // Forum threads for this lesson
        $thrStmt = $this->db->prepare('SELECT ft.*, u.name AS author_name FROM forum_threads ft JOIN users u ON u.id=ft.author_id WHERE ft.lesson_id = ? ORDER BY ft.created_at DESC LIMIT 10');
        $thrStmt->execute([$id]);
        $threads = $thrStmt->fetchAll();

        $completed = false;
        if (\Core\Auth::check()) {
            $cm = $this->db->prepare('SELECT 1 FROM lesson_completions WHERE user_id=? AND lesson_id=?');
            $cm->execute([\Core\Auth::id(), $id]);
            $completed = (bool)$cm->fetchColumn();
        }
        $this->render('lessons/show', compact('lesson','resources','threads','completed'));
    }

    public function complete(): void
    {
        $this->requireLogin();
        if (!\Core\Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(400); echo 'Bad CSRF'; return; }
        $lessonId = (int)($_POST['lesson_id'] ?? 0);
        if ($lessonId <= 0) { http_response_code(400); echo 'Invalid'; return; }
        // insert or update completion
        $st = $this->db->prepare('INSERT INTO lesson_completions(user_id, lesson_id, completed_at) VALUES(?, ?, NOW()) ON DUPLICATE KEY UPDATE completed_at = VALUES(completed_at)');
        $st->execute([\Core\Auth::id(), $lessonId]);

        // find course of the lesson
        $stmt = $this->db->prepare('SELECT ch.course_id FROM lessons l JOIN chapters ch ON ch.id=l.chapter_id WHERE l.id=?');
        $stmt->execute([$lessonId]);
        $courseId = (int)$stmt->fetchColumn();

        if ($courseId) {
            // total lessons in course
            $cntStmt = $this->db->prepare('SELECT COUNT(*) FROM lessons l JOIN chapters ch ON ch.id=l.chapter_id WHERE ch.course_id=?');
            $cntStmt->execute([$courseId]);
            $total = max(1, (int)$cntStmt->fetchColumn());
            // completed by user in course
            $doneStmt = $this->db->prepare('SELECT COUNT(*) FROM lesson_completions lc WHERE lc.user_id=? AND lc.lesson_id IN (SELECT l.id FROM lessons l JOIN chapters ch ON ch.id=l.chapter_id WHERE ch.course_id=?)');
            $doneStmt->execute([\Core\Auth::id(), $courseId]);
            $done = (int)$doneStmt->fetchColumn();
            $percent = (int)floor(($done / $total) * 100);
            // upsert enrollment
            $enr = $this->db->prepare('INSERT INTO enrollments(user_id,course_id,progress_percent,last_view_at) VALUES(?,?,?,NOW()) ON DUPLICATE KEY UPDATE progress_percent=VALUES(progress_percent), last_view_at=VALUES(last_view_at)');
            $enr->execute([\Core\Auth::id(), $courseId, $percent]);
        }

        \Core\Helpers::redirect(\Core\Helpers::baseUrl($this->config) . '/index.php?route=/lesson&id=' . $lessonId);
    }
}
