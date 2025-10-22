<?php
namespace Controllers;

use Core\Helpers;
use Core\Auth;
use PDO;

class ForumController extends BaseController
{
    public function index(): void
    {
        $courseId = (int)($_GET['course_id'] ?? 0);
        $lessonId = (int)($_GET['lesson_id'] ?? 0);
        $sql = 'SELECT ft.*, u.name AS author_name FROM forum_threads ft JOIN users u ON u.id=ft.author_id WHERE 1=1';
        $params = [];
        if ($courseId) { $sql .= ' AND ft.course_id=?'; $params[] = $courseId; }
        if ($lessonId) { $sql .= ' AND ft.lesson_id=?'; $params[] = $lessonId; }
        $sql .= ' ORDER BY ft.created_at DESC LIMIT 50';
        $st = $this->db->prepare($sql);
        $st->execute($params);
        $threads = $st->fetchAll(PDO::FETCH_ASSOC);
        $this->render('forum/index', compact('threads', 'courseId', 'lessonId'));
    }

    public function thread(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $th = $this->db->prepare('SELECT ft.*, u.name AS author_name FROM forum_threads ft JOIN users u ON u.id=ft.author_id WHERE ft.id=?');
        $th->execute([$id]);
        $thread = $th->fetch(PDO::FETCH_ASSOC);
        if (!$thread) { http_response_code(404); echo 'Thread not found'; return; }
        $ps = $this->db->prepare('SELECT fp.*, u.name AS author_name FROM forum_posts fp JOIN users u ON u.id=fp.author_id WHERE fp.thread_id=? ORDER BY fp.created_at');
        $ps->execute([$id]);
        $posts = $ps->fetchAll(PDO::FETCH_ASSOC);
        $this->render('forum/thread', compact('thread','posts'));
    }

    public function create(): void
    {
        $this->requireLogin();
        if (!\Core\Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(400); echo 'Bad CSRF'; return; }
        $title = trim($_POST['title'] ?? '');
        $courseId = (int)($_POST['course_id'] ?? 0);
        $lessonId = (int)($_POST['lesson_id'] ?? 0);
        if ($title === '') { http_response_code(400); echo 'Title required'; return; }
        $st = $this->db->prepare('INSERT INTO forum_threads(course_id,lesson_id,author_id,title,created_at) VALUES(?,?,?,?,NOW())');
        $st->execute([$courseId ?: null, $lessonId ?: null, Auth::id(), $title]);
        Helpers::redirect(Helpers::baseUrl($this->config).'/index.php?route=/forum');
    }

    public function reply(): void
    {
        $this->requireLogin();
        if (!\Core\Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(400); echo 'Bad CSRF'; return; }
        $threadId = (int)($_POST['thread_id'] ?? 0);
        $content = trim($_POST['content'] ?? '');
        if ($threadId<=0 || $content==='') { http_response_code(400); echo 'Invalid'; return; }
        $st = $this->db->prepare('INSERT INTO forum_posts(thread_id,author_id,content,created_at) VALUES(?,?,?,NOW())');
        $st->execute([$threadId, Auth::id(), $content]);
        Helpers::redirect(Helpers::baseUrl($this->config).'/index.php?route=/thread&id='.$threadId);
    }
}
