<?php

class Lesson {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    public function find($id) {
        return $this->db->fetch("SELECT * FROM lessons WHERE id = ?", [$id]);
    }
    
    public function getByChapter($chapterId) {
        $sql = "SELECT * FROM lessons WHERE chapter_id = ? ORDER BY position";
        return $this->db->fetchAll($sql, [$chapterId]);
    }
    
    public function getByCourse($courseId) {
        $sql = "SELECT l.*, c.title as chapter_title, c.position as chapter_position
                FROM lessons l
                JOIN chapters c ON l.chapter_id = c.id
                WHERE c.course_id = ?
                ORDER BY c.position, l.position";
        return $this->db->fetchAll($sql, [$courseId]);
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
    
    public function getLessonWithDetails($id) {
        $lesson = $this->find($id);
        if (!$lesson) return null;
        
        // Get chapter and course info
        $chapter = $this->db->fetch(
            "SELECT c.*, co.title as course_title, co.teacher_id 
             FROM chapters c 
             JOIN courses co ON c.course_id = co.id 
             WHERE c.id = ?",
            [$lesson['chapter_id']]
        );
        $lesson['chapter'] = $chapter;
        
        // Get resources
        $lesson['resources'] = $this->db->fetchAll(
            "SELECT * FROM resources WHERE lesson_id = ? ORDER BY created_at",
            [$id]
        );
        
        // Get quiz if exists
        $quiz = $this->db->fetch("SELECT * FROM quizzes WHERE lesson_id = ?", [$id]);
        if ($quiz) {
            $quiz['questions'] = $this->db->fetchAll(
                "SELECT q.*, GROUP_CONCAT(
                    JSON_OBJECT('id', o.id, 'text', o.text, 'is_correct', o.is_correct, 'position', o.position)
                    ORDER BY o.position
                ) as options
                FROM questions q
                LEFT JOIN options o ON q.id = o.question_id
                WHERE q.quiz_id = ?
                GROUP BY q.id
                ORDER BY q.position",
                [$quiz['id']]
            );
            
            // Parse JSON options
            foreach ($quiz['questions'] as &$question) {
                $question['options'] = json_decode($question['options'], true) ?: [];
            }
        }
        $lesson['quiz'] = $quiz;
        
        return $lesson;
    }
    
    public function getNextLesson($currentLessonId, $courseId) {
        $sql = "SELECT l.* FROM lessons l
                JOIN chapters c ON l.chapter_id = c.id
                WHERE c.course_id = ? AND l.id > ?
                ORDER BY c.position, l.position
                LIMIT 1";
        return $this->db->fetch($sql, [$courseId, $currentLessonId]);
    }
    
    public function getPreviousLesson($currentLessonId, $courseId) {
        $sql = "SELECT l.* FROM lessons l
                JOIN chapters c ON l.chapter_id = c.id
                WHERE c.course_id = ? AND l.id < ?
                ORDER BY c.position DESC, l.position DESC
                LIMIT 1";
        return $this->db->fetch($sql, [$courseId, $currentLessonId]);
    }
    
    public function addResource($lessonId, $filePath, $fileName, $fileSize = 0, $mimeType = null) {
        return $this->db->insert('resources', [
            'lesson_id' => $lessonId,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'mime_type' => $mimeType
        ]);
    }
    
    public function getResources($lessonId) {
        return $this->db->fetchAll(
            "SELECT * FROM resources WHERE lesson_id = ? ORDER BY created_at",
            [$lessonId]
        );
    }
    
    public function deleteResource($resourceId) {
        $resource = $this->db->fetch("SELECT * FROM resources WHERE id = ?", [$resourceId]);
        if ($resource) {
            // Delete file
            Helpers::deleteFile($resource['file_path']);
            // Delete database record
            return $this->db->delete('resources', 'id = ?', [$resourceId]);
        }
        return false;
    }
    
    public function getResourceUrl($resource) {
        return APP_URL . '/public/uploads/' . $resource['file_path'];
    }
    
    public function getVideoEmbedUrl($videoUrl) {
        if (strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !== false) {
            // YouTube
            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $videoUrl, $matches)) {
                return 'https://www.youtube.com/embed/' . $matches[1];
            }
        } elseif (strpos($videoUrl, 'vimeo.com') !== false) {
            // Vimeo
            if (preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $matches)) {
                return 'https://player.vimeo.com/video/' . $matches[1];
            }
        }
        return $videoUrl;
    }
    
    public function markAsViewed($lessonId, $userId) {
        // This would typically update user progress
        // For now, we'll just return true
        return true;
    }
    
    public function getLessonProgress($lessonId, $userId) {
        // This would check if user has completed the lesson
        // For now, we'll return a simple check
        return $this->db->count('enrollments e 
            JOIN chapters c ON e.course_id = c.course_id 
            JOIN lessons l ON c.id = l.chapter_id 
            WHERE l.id = ? AND e.user_id = ?', 
            [$lessonId, $userId]) > 0;
    }
    
    public function search($query, $courseId = null, $limit = 20) {
        $sql = "SELECT l.*, c.title as chapter_title, co.title as course_title
                FROM lessons l
                JOIN chapters c ON l.chapter_id = c.id
                JOIN courses co ON c.course_id = co.id
                WHERE (l.title LIKE ? OR l.content_html LIKE ?)";
        
        $params = ["%{$query}%", "%{$query}%"];
        
        if ($courseId) {
            $sql .= " AND co.id = ?";
            $params[] = $courseId;
        }
        
        $sql .= " ORDER BY co.title, c.position, l.position LIMIT ?";
        $params[] = $limit;
        
        return $this->db->fetchAll($sql, $params);
    }
}