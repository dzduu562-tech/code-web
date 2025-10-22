<?php

class Lesson {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    public function findById($id) {
        $sql = "SELECT l.*, ch.title as chapter_title, ch.course_id
                FROM lessons l
                LEFT JOIN chapters ch ON l.chapter_id = ch.id
                WHERE l.id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    public function create($data) {
        return $this->db->insert('lessons', $data);
    }
    
    public function update($id, $data) {
        return $this->db->update('lessons', $data, 'id = :id', ['id' => $id]);
    }
    
    public function delete($id) {
        return $this->db->delete('lessons', 'id = :id', ['id' => $id]);
    }
    
    public function getByChapter($chapterId) {
        $sql = "SELECT * FROM lessons WHERE chapter_id = :chapter_id ORDER BY position ASC";
        return $this->db->fetchAll($sql, ['chapter_id' => $chapterId]);
    }
    
    public function getResources($lessonId) {
        $sql = "SELECT * FROM resources WHERE lesson_id = :lesson_id ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, ['lesson_id' => $lessonId]);
    }
    
    public function addResource($data) {
        return $this->db->insert('resources', $data);
    }
    
    public function deleteResource($id) {
        return $this->db->delete('resources', 'id = :id', ['id' => $id]);
    }
    
    public function markAsCompleted($userId, $lessonId) {
        // Check if already exists
        $sql = "SELECT * FROM lesson_progress WHERE user_id = :user_id AND lesson_id = :lesson_id";
        $existing = $this->db->fetchOne($sql, ['user_id' => $userId, 'lesson_id' => $lessonId]);
        
        if ($existing) {
            return $this->db->update('lesson_progress',
                ['completed' => 1, 'completed_at' => date('Y-m-d H:i:s')],
                'user_id = :user_id AND lesson_id = :lesson_id',
                ['user_id' => $userId, 'lesson_id' => $lessonId]
            );
        } else {
            return $this->db->insert('lesson_progress', [
                'user_id' => $userId,
                'lesson_id' => $lessonId,
                'completed' => 1,
                'completed_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
    
    public function isCompleted($userId, $lessonId) {
        $sql = "SELECT completed FROM lesson_progress 
                WHERE user_id = :user_id AND lesson_id = :lesson_id";
        $result = $this->db->fetchOne($sql, ['user_id' => $userId, 'lesson_id' => $lessonId]);
        return ($result['completed'] ?? 0) == 1;
    }
}
