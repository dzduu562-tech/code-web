<?php
namespace Models;

use PDO;

class Course
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function list(array $filters = []): array
    {
        $sql = 'SELECT c.*, u.name as teacher_name FROM courses c JOIN users u ON u.id=c.teacher_id WHERE 1=1';
        $params = [];
        if (!empty($filters['q'])) { $sql .= ' AND (c.title LIKE ? OR c.description LIKE ?)'; $params[] = '%'.$filters['q'].'%'; $params[] = '%'.$filters['q'].'%'; }
        if (!empty($filters['subject'])) { $sql .= ' AND c.subject = ?'; $params[] = $filters['subject']; }
        if (!empty($filters['teacher'])) { $sql .= ' AND u.name LIKE ?'; $params[] = '%'.$filters['teacher'].'%'; }
        $sql .= ' ORDER BY c.created_at DESC LIMIT 20';
        $st = $this->db->prepare($sql);
        $st->execute($params);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $st = $this->db->prepare('SELECT c.*, u.name AS teacher_name FROM courses c JOIN users u ON u.id=c.teacher_id WHERE c.id=?');
        $st->execute([$id]);
        $c = $st->fetch(PDO::FETCH_ASSOC);
        return $c ?: null;
    }

    public function chapters(int $courseId): array
    {
        $st = $this->db->prepare('SELECT * FROM chapters WHERE course_id = ? ORDER BY position');
        $st->execute([$courseId]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function lessonsByCourse(int $courseId): array
    {
        $st = $this->db->prepare('SELECT l.*, ch.title AS chapter_title FROM lessons l JOIN chapters ch ON ch.id=l.chapter_id WHERE ch.course_id = ? ORDER BY ch.position, l.position');
        $st->execute([$courseId]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
}
