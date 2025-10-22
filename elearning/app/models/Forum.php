<?php

class Forum {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    // Threads
    public function getThreadById($id) {
        $sql = "SELECT t.*, u.name as author_name, u.role as author_role,
                       c.title as course_title, l.title as lesson_title
                FROM forum_threads t
                LEFT JOIN users u ON t.author_id = u.id
                LEFT JOIN courses c ON t.course_id = c.id
                LEFT JOIN lessons l ON t.lesson_id = l.id
                WHERE t.id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    public function getThreadsByCourse($courseId, $lessonId = null, $limit = 20, $offset = 0) {
        $params = ['course_id' => $courseId, 'limit' => $limit, 'offset' => $offset];
        $lessonCondition = '';
        
        if ($lessonId !== null) {
            $lessonCondition = ' AND t.lesson_id = :lesson_id';
            $params['lesson_id'] = $lessonId;
        }
        
        $sql = "SELECT t.*, u.name as author_name,
                       (SELECT COUNT(*) FROM forum_posts WHERE thread_id = t.id) as post_count,
                       (SELECT MAX(created_at) FROM forum_posts WHERE thread_id = t.id) as last_post_at
                FROM forum_threads t
                LEFT JOIN users u ON t.author_id = u.id
                WHERE t.course_id = :course_id $lessonCondition
                ORDER BY t.updated_at DESC
                LIMIT :limit OFFSET :offset";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getAllThreads($limit = 20, $offset = 0) {
        $sql = "SELECT t.*, u.name as author_name, c.title as course_title,
                       (SELECT COUNT(*) FROM forum_posts WHERE thread_id = t.id) as post_count
                FROM forum_threads t
                LEFT JOIN users u ON t.author_id = u.id
                LEFT JOIN courses c ON t.course_id = c.id
                ORDER BY t.updated_at DESC
                LIMIT :limit OFFSET :offset";
        return $this->db->fetchAll($sql, ['limit' => $limit, 'offset' => $offset]);
    }
    
    public function createThread($data) {
        return $this->db->insert('forum_threads', $data);
    }
    
    public function updateThread($id, $data) {
        return $this->db->update('forum_threads', $data, 'id = :id', ['id' => $id]);
    }
    
    public function deleteThread($id) {
        return $this->db->delete('forum_threads', 'id = :id', ['id' => $id]);
    }
    
    // Posts
    public function getPostsByThread($threadId) {
        $sql = "SELECT p.*, u.name as author_name, u.role as author_role
                FROM forum_posts p
                LEFT JOIN users u ON p.author_id = u.id
                WHERE p.thread_id = :thread_id
                ORDER BY p.created_at ASC";
        return $this->db->fetchAll($sql, ['thread_id' => $threadId]);
    }
    
    public function createPost($data) {
        $postId = $this->db->insert('forum_posts', $data);
        
        // Update thread updated_at
        $this->db->update('forum_threads',
            ['updated_at' => date('Y-m-d H:i:s')],
            'id = :id',
            ['id' => $data['thread_id']]
        );
        
        return $postId;
    }
    
    public function updatePost($id, $data) {
        return $this->db->update('forum_posts', $data, 'id = :id', ['id' => $id]);
    }
    
    public function deletePost($id) {
        return $this->db->delete('forum_posts', 'id = :id', ['id' => $id]);
    }
    
    public function getPostById($id) {
        $sql = "SELECT * FROM forum_posts WHERE id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
}
