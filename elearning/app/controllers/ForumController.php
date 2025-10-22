<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/DB.php';
require_once __DIR__ . '/../core/Helpers.php';

class ForumController extends BaseController {
    public function index(): string {
        $pdo = DB::getConnection();
        $threads = $pdo->query('SELECT t.*, u.name AS author_name, c.title AS course_title FROM forum_threads t LEFT JOIN users u ON u.id=t.author_id LEFT JOIN courses c ON c.id=t.course_id ORDER BY t.created_at DESC LIMIT 20')->fetchAll();
        return $this->render('forum/index', compact('threads'));
    }

    public function thread(): string {
        $id = (int)($_GET['id'] ?? 0);
        $pdo = DB::getConnection();
        $threadStmt = $pdo->prepare('SELECT t.*, u.name AS author_name FROM forum_threads t LEFT JOIN users u ON u.id=t.author_id WHERE t.id=?');
        $threadStmt->execute([$id]);
        $thread = $threadStmt->fetch();
        if (!$thread) { http_response_code(404); return 'Thread not found'; }
        $postsStmt = $pdo->prepare('SELECT p.*, u.name AS author_name FROM forum_posts p LEFT JOIN users u ON u.id=p.author_id WHERE p.thread_id=? ORDER BY p.created_at ASC');
        $postsStmt->execute([$id]);
        $posts = $postsStmt->fetchAll();
        return $this->render('forum/thread', compact('thread','posts'));
    }

    public function createThread(): void {
        Auth::startSecureSession();
        if (!Auth::check() || !Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(400); exit; }
        $title = trim($_POST['title'] ?? '');
        $courseId = (int)($_POST['course_id'] ?? 0);
        $lessonId = (int)($_POST['lesson_id'] ?? 0);
        if ($title === '') { $_SESSION['flash_error'] = 'Tiêu đề trống'; Helpers::redirect('index.php?route=/forum'); }
        $pdo = DB::getConnection();
        $pdo->prepare('INSERT INTO forum_threads(course_id, lesson_id, author_id, title, created_at) VALUES(?,?,?,?,NOW())')
            ->execute([$courseId ?: null, $lessonId ?: null, Auth::id(), $title]);
        $_SESSION['flash_success'] = 'Đã tạo chủ đề';
        Helpers::redirect('index.php?route=/forum');
    }

    public function postReply(): void {
        Auth::startSecureSession();
        if (!Auth::check() || !Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(400); exit; }
        $threadId = (int)($_POST['thread_id'] ?? 0);
        $content = trim($_POST['content'] ?? '');
        if ($content === '') { $_SESSION['flash_error'] = 'Nội dung trống'; Helpers::redirect('index.php?route=/thread&id='.$threadId); }
        $pdo = DB::getConnection();
        $pdo->prepare('INSERT INTO forum_posts(thread_id, author_id, content, created_at) VALUES(?,?,?,NOW())')->execute([$threadId, Auth::id(), $content]);
        $_SESSION['flash_success'] = 'Đã gửi phản hồi';
        Helpers::redirect('index.php?route=/thread&id='.$threadId);
    }
}
