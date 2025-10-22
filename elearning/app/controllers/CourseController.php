<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/DB.php';
require_once __DIR__ . '/../core/Helpers.php';

class CourseController extends BaseController {
    public function index(): string {
        $pdo = DB::getConnection();
        $q = trim($_GET['q'] ?? '');
        $subject = trim($_GET['subject'] ?? '');
        $teacher = trim($_GET['teacher'] ?? '');
        $sql = 'SELECT c.*, u.name AS teacher_name FROM courses c JOIN users u ON u.id=c.teacher_id WHERE 1=1';
        $params = [];
        if ($q !== '') { $sql .= ' AND (c.title LIKE ? OR c.description LIKE ?)'; $params[] = "%$q%"; $params[] = "%$q%"; }
        if ($subject !== '') { $sql .= ' AND c.subject = ?'; $params[] = $subject; }
        if ($teacher !== '') { $sql .= ' AND u.name LIKE ?'; $params[] = "%$teacher%"; }
        $sql .= ' ORDER BY c.created_at DESC LIMIT 20';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $courses = $stmt->fetchAll();
        return $this->render('courses/index', compact('courses','q','subject','teacher'));
    }

    public function show(): string {
        $id = (int)($_GET['id'] ?? 0);
        $pdo = DB::getConnection();
        $stmt = $pdo->prepare('SELECT c.*, u.name AS teacher_name FROM courses c JOIN users u ON u.id=c.teacher_id WHERE c.id=?');
        $stmt->execute([$id]);
        $course = $stmt->fetch();
        if (!$course) { http_response_code(404); return 'Course not found'; }
        $chapters = $pdo->prepare('SELECT * FROM chapters WHERE course_id=? ORDER BY position');
        $chapters->execute([$id]);
        $chapters = $chapters->fetchAll();
        $lessonsByChapter = [];
        foreach ($chapters as $ch) {
            $ls = $pdo->prepare('SELECT * FROM lessons WHERE chapter_id=? ORDER BY position');
            $ls->execute([$ch['id']]);
            $lessonsByChapter[$ch['id']] = $ls->fetchAll();
        }
        return $this->render('courses/show', compact('course','chapters','lessonsByChapter'));
    }
}
