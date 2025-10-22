<?php
require_once __DIR__ . '/../core/DB.php';

class Forum {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    public function getThreads($courseId, $lessonId = null, $page = 1, $limit = ITEMS_PER_PAGE) {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT ft.*, u.name as author_name, u.avatar as author_avatar,
                       COUNT(fp.id) as post_count,
                       MAX(fp.created_at) as last_post_at
                FROM forum_threads ft
                LEFT JOIN users u ON ft.author_id = u.id
                LEFT JOIN forum_posts fp ON ft.id = fp.thread_id
                WHERE ft.course_id = ?";
        $params = [$courseId];
        
        if ($lessonId) {
            $sql .= " AND ft.lesson_id = ?";
            $params[] = $lessonId;
        }
        
        $sql .= " GROUP BY ft.id
                  ORDER BY ft.is_pinned DESC, ft.updated_at DESC
                  LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getThread($id) {
        return $this->db->fetch(
            "SELECT ft.*, u.name as author_name, u.avatar as author_avatar,
                    c.title as course_title, l.title as lesson_title
             FROM forum_threads ft
             LEFT JOIN users u ON ft.author_id = u.id
             LEFT JOIN courses c ON ft.course_id = c.id
             LEFT JOIN lessons l ON ft.lesson_id = l.id
             WHERE ft.id = ?",
            [$id]
        );
    }
    
    public function getPosts($threadId, $page = 1, $limit = ITEMS_PER_PAGE) {
        $offset = ($page - 1) * $limit;
        return $this->db->fetchAll(
            "SELECT fp.*, u.name as author_name, u.avatar as author_avatar
             FROM forum_posts fp
             LEFT JOIN users u ON fp.author_id = u.id
             WHERE fp.thread_id = ?
             ORDER BY fp.created_at ASC
             LIMIT ? OFFSET ?",
            [$threadId, $limit, $offset]
        );
    }
    
    public function createThread($data) {
        return $this->db->insert('forum_threads', $data);
    }
    
    public function updateThread($id, $data) {
        return $this->db->update('forum_threads', $data, 'id = ?', [$id]);
    }
    
    public function deleteThread($id) {
        return $this->db->delete('forum_threads', 'id = ?', [$id]);
    }
    
    public function createPost($data) {
        $postId = $this->db->insert('forum_posts', $data);
        
        if ($postId) {
            // Update thread's updated_at timestamp
            $this->db->update('forum_threads', [
                'updated_at' => date('Y-m-d H:i:s')
            ], 'id = ?', [$data['thread_id']]);
        }
        
        return $postId;
    }
    
    public function updatePost($id, $data) {
        return $this->db->update('forum_posts', $data, 'id = ?', [$id]);
    }
    
    public function deletePost($id) {
        return $this->db->delete('forum_posts', 'id = ?', [$id]);
    }
    
    public function pinThread($id) {
        return $this->db->update('forum_threads', ['is_pinned' => 1], 'id = ?', [$id]);
    }
    
    public function unpinThread($id) {
        return $this->db->update('forum_threads', ['is_pinned' => 0], 'id = ?', [$id]);
    }
    
    public function lockThread($id) {
        return $this->db->update('forum_threads', ['is_locked' => 1], 'id = ?', [$id]);
    }
    
    public function unlockThread($id) {
        return $this->db->update('forum_threads', ['is_locked' => 0], 'id = ?', [$id]);
    }
    
    public function getRecentThreads($courseId, $limit = 10) {
        return $this->db->fetchAll(
            "SELECT ft.*, u.name as author_name, u.avatar as author_avatar,
                    COUNT(fp.id) as post_count
             FROM forum_threads ft
             LEFT JOIN users u ON ft.author_id = u.id
             LEFT JOIN forum_posts fp ON ft.id = fp.thread_id
             WHERE ft.course_id = ?
             GROUP BY ft.id
             ORDER BY ft.updated_at DESC
             LIMIT ?",
            [$courseId, $limit]
        );
    }
    
    public function getPopularThreads($courseId, $limit = 10) {
        return $this->db->fetchAll(
            "SELECT ft.*, u.name as author_name, u.avatar as author_avatar,
                    COUNT(fp.id) as post_count
             FROM forum_threads ft
             LEFT JOIN users u ON ft.author_id = u.id
             LEFT JOIN forum_posts fp ON ft.id = fp.thread_id
             WHERE ft.course_id = ?
             GROUP BY ft.id
             ORDER BY post_count DESC, ft.updated_at DESC
             LIMIT ?",
            [$courseId, $limit]
        );
    }
    
    public function searchThreads($query, $courseId = null) {
        $sql = "SELECT ft.*, u.name as author_name, c.title as course_title
                FROM forum_threads ft
                LEFT JOIN users u ON ft.author_id = u.id
                LEFT JOIN courses c ON ft.course_id = c.id
                WHERE (ft.title LIKE ? OR ft.id IN (
                    SELECT DISTINCT fp.thread_id 
                    FROM forum_posts fp 
                    WHERE fp.content LIKE ?
                ))";
        $params = ["%{$query}%", "%{$query}%"];
        
        if ($courseId) {
            $sql .= " AND ft.course_id = ?";
            $params[] = $courseId;
        }
        
        $sql .= " ORDER BY ft.updated_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getThreadCount($courseId, $lessonId = null) {
        $sql = "SELECT COUNT(*) as total FROM forum_threads WHERE course_id = ?";
        $params = [$courseId];
        
        if ($lessonId) {
            $sql .= " AND lesson_id = ?";
            $params[] = $lessonId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['total'];
    }
    
    public function getPostCount($threadId) {
        $result = $this->db->fetch(
            "SELECT COUNT(*) as total FROM forum_posts WHERE thread_id = ?",
            [$threadId]
        );
        return $result['total'];
    }
    
    public function getUserPostCount($userId) {
        $result = $this->db->fetch(
            "SELECT COUNT(*) as total FROM forum_posts WHERE author_id = ?",
            [$userId]
        );
        return $result['total'];
    }
    
    public function getUserThreadCount($userId) {
        $result = $this->db->fetch(
            "SELECT COUNT(*) as total FROM forum_threads WHERE author_id = ?",
            [$userId]
        );
        return $result['total'];
    }
    
    public function canEditPost($postId, $userId) {
        $post = $this->db->fetch(
            "SELECT author_id FROM forum_posts WHERE id = ?",
            [$postId]
        );
        
        return $post && $post['author_id'] == $userId;
    }
    
    public function canEditThread($threadId, $userId) {
        $thread = $this->db->fetch(
            "SELECT author_id FROM forum_threads WHERE id = ?",
            [$threadId]
        );
        
        return $thread && $thread['author_id'] == $userId;
    }
}