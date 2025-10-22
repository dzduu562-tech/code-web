<?php
namespace Controllers;

use Core\Helpers;
use PDO;

class CourseController extends BaseController
{
    public function index(): void
    {
        $q = trim($_GET['q'] ?? '');
        $subject = trim($_GET['subject'] ?? '');
        $teacher = trim($_GET['teacher'] ?? '');
        $sql = 'SELECT c.*, u.name AS teacher_name FROM courses c JOIN users u ON u.id=c.teacher_id WHERE 1=1';
        $params = [];
        if ($q !== '') { $sql .= ' AND (c.title LIKE ? OR c.description LIKE ?)'; $params[] = "%$q%"; $params[] = "%$q%"; }
        if ($subject !== '') { $sql .= ' AND c.subject = ?'; $params[] = $subject; }
        if ($teacher !== '') { $sql .= ' AND u.name LIKE ?'; $params[] = "%$teacher%"; }
        $sql .= ' ORDER BY c.created_at DESC LIMIT 20';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $courses = $stmt->fetchAll();
        $this->render('courses/index', ['courses' => $courses, 'q' => $q]);
    }

    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->db->prepare('SELECT c.*, u.name AS teacher_name FROM courses c JOIN users u ON u.id=c.teacher_id WHERE c.id = ?');
        $stmt->execute([$id]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$course) { http_response_code(404); echo 'Course not found'; return; }
        $chapters = $this->db->prepare('SELECT * FROM chapters WHERE course_id = ? ORDER BY position');
        $chapters->execute([$id]);
        $chapters = $chapters->fetchAll();
        $lessons = $this->db->prepare('SELECT l.*, ch.title AS chapter_title FROM lessons l JOIN chapters ch ON ch.id=l.chapter_id WHERE ch.course_id = ? ORDER BY ch.position, l.position');
        $lessons->execute([$id]);
        $lessons = $lessons->fetchAll();
        $this->render('courses/show', compact('course','chapters','lessons'));
    }
}
