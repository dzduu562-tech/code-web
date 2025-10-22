<?php
require_once __DIR__ . '/../core/DB.php';

class Lesson {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    public function find($id) {
        return $this->db->fetch(
            "SELECT l.*, c.title as chapter_title, c.course_id, co.title as course_title, co.teacher_id, u.name as teacher_name
             FROM lessons l
             LEFT JOIN chapters c ON l.chapter_id = c.id
             LEFT JOIN courses co ON c.course_id = co.id
             LEFT JOIN users u ON co.teacher_id = u.id
             WHERE l.id = ?",
            [$id]
        );
    }
    
    public function getByChapter($chapterId) {
        return $this->db->fetchAll(
            "SELECT * FROM lessons WHERE chapter_id = ? ORDER BY position ASC",
            [$chapterId]
        );
    }
    
    public function getByCourse($courseId) {
        return $this->db->fetchAll(
            "SELECT l.*, c.title as chapter_title, c.position as chapter_position
             FROM lessons l
             LEFT JOIN chapters c ON l.chapter_id = c.id
             WHERE c.course_id = ? AND l.is_published = 1
             ORDER BY c.position ASC, l.position ASC",
            [$courseId]
        );
    }
    
    public function create($data) {
        return $this->db->insert('lessons', $data);
    }
    
    public function update($id, $data) {
        return $this->db->update('lessons', $data, 'id = ?', [$id]);
    }
    
    public function delete($id) {
        return $this->db->delete('lessons', 'id = ?', [$id]);
    }
    
    public function publish($id) {
        return $this->db->update('lessons', ['is_published' => 1], 'id = ?', [$id]);
    }
    
    public function unpublish($id) {
        return $this->db->update('lessons', ['is_published' => 0], 'id = ?', [$id]);
    }
    
    public function getResources($lessonId) {
        return $this->db->fetchAll(
            "SELECT * FROM resources WHERE lesson_id = ? ORDER BY created_at ASC",
            [$lessonId]
        );
    }
    
    public function addResource($lessonId, $filePath, $fileName, $fileSize, $mimeType) {
        return $this->db->insert('resources', [
            'lesson_id' => $lessonId,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'mime_type' => $mimeType
        ]);
    }
    
    public function removeResource($resourceId) {
        $resource = $this->db->fetch("SELECT * FROM resources WHERE id = ?", [$resourceId]);
        if ($resource) {
            // Delete file from filesystem
            if (file_exists($resource['file_path'])) {
                unlink($resource['file_path']);
            }
            // Delete from database
            return $this->db->delete('resources', 'id = ?', [$resourceId]);
        }
        return false;
    }
    
    public function isCompleted($userId, $lessonId) {
        $result = $this->db->fetch(
            "SELECT is_completed FROM lesson_progress WHERE user_id = ? AND lesson_id = ?",
            [$userId, $lessonId]
        );
        return $result ? $result['is_completed'] : false;
    }
    
    public function markCompleted($userId, $lessonId) {
        // Check if already exists
        $existing = $this->db->fetch(
            "SELECT id FROM lesson_progress WHERE user_id = ? AND lesson_id = ?",
            [$userId, $lessonId]
        );
        
        if ($existing) {
            return $this->db->update('lesson_progress', [
                'is_completed' => 1,
                'completed_at' => date('Y-m-d H:i:s')
            ], 'user_id = ? AND lesson_id = ?', [$userId, $lessonId]);
        } else {
            return $this->db->insert('lesson_progress', [
                'user_id' => $userId,
                'lesson_id' => $lessonId,
                'is_completed' => 1,
                'completed_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
    
    public function markIncomplete($userId, $lessonId) {
        return $this->db->update('lesson_progress', [
            'is_completed' => 0,
            'completed_at' => null
        ], 'user_id = ? AND lesson_id = ?', [$userId, $lessonId]);
    }
    
    public function getProgress($userId, $courseId) {
        $totalLessons = $this->db->fetch(
            "SELECT COUNT(*) as total 
             FROM lessons l
             LEFT JOIN chapters c ON l.chapter_id = c.id
             WHERE c.course_id = ? AND l.is_published = 1",
            [$courseId]
        )['total'];
        
        $completedLessons = $this->db->fetch(
            "SELECT COUNT(*) as completed
             FROM lesson_progress lp
             LEFT JOIN lessons l ON lp.lesson_id = l.id
             LEFT JOIN chapters c ON l.chapter_id = c.id
             WHERE lp.user_id = ? AND c.course_id = ? AND lp.is_completed = 1",
            [$userId, $courseId]
        )['completed'];
        
        if ($totalLessons == 0) {
            return 0;
        }
        
        return round(($completedLessons / $totalLessons) * 100, 2);
    }
    
    public function getNextLesson($userId, $courseId) {
        return $this->db->fetch(
            "SELECT l.*, c.title as chapter_title
             FROM lessons l
             LEFT JOIN chapters c ON l.chapter_id = c.id
             LEFT JOIN lesson_progress lp ON l.id = lp.lesson_id AND lp.user_id = ?
             WHERE c.course_id = ? AND l.is_published = 1 AND (lp.is_completed IS NULL OR lp.is_completed = 0)
             ORDER BY c.position ASC, l.position ASC
             LIMIT 1",
            [$userId, $courseId]
        );
    }
    
    public function getPreviousLesson($userId, $courseId, $currentLessonId) {
        $currentLesson = $this->find($currentLessonId);
        if (!$currentLesson) {
            return null;
        }
        
        return $this->db->fetch(
            "SELECT l.*, c.title as chapter_title
             FROM lessons l
             LEFT JOIN chapters c ON l.chapter_id = c.id
             WHERE c.course_id = ? AND l.is_published = 1 
             AND (c.position < ? OR (c.position = ? AND l.position < ?))
             ORDER BY c.position DESC, l.position DESC
             LIMIT 1",
            [$courseId, $currentLesson['chapter_position'], $currentLesson['chapter_position'], $currentLesson['position']]
        );
    }
    
    public function getQuiz($lessonId) {
        return $this->db->fetch(
            "SELECT * FROM quizzes WHERE lesson_id = ?",
            [$lessonId]
        );
    }
    
    public function search($query, $courseId = null) {
        $sql = "SELECT l.*, c.title as chapter_title, co.title as course_title
                FROM lessons l
                LEFT JOIN chapters c ON l.chapter_id = c.id
                LEFT JOIN courses co ON c.course_id = co.id
                WHERE (l.title LIKE ? OR l.content_html LIKE ?) AND l.is_published = 1";
        $params = ["%{$query}%", "%{$query}%"];
        
        if ($courseId) {
            $sql .= " AND c.course_id = ?";
            $params[] = $courseId;
        }
        
        $sql .= " ORDER BY c.position ASC, l.position ASC";
        
        return $this->db->fetchAll($sql, $params);
    }
}