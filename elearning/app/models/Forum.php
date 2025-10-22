<?php

class Forum {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    public function getThreads($courseId = null, $lessonId = null, $limit = null, $offset = 0) {
        $sql = "SELECT t.*, u.name as author_name, u.role as author_role,
                       COUNT(p.id) as post_count,
                       MAX(p.created_at) as last_post_at
                FROM forum_threads t
                JOIN users u ON t.author_id = u.id
                LEFT JOIN forum_posts p ON t.id = p.thread_id";
        
        $where = [];
        $params = [];
        
        if ($courseId) {
            $where[] = "t.course_id = ?";
            $params[] = $courseId;
        }
        
        if ($lessonId) {
            $where[] = "t.lesson_id = ?";
            $params[] = $lessonId;
        }
        
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        $sql .= " GROUP BY t.id ORDER BY t.is_pinned DESC, t.updated_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getThread($id) {
        $sql = "SELECT t.*, u.name as author_name, u.role as author_role,
                       c.title as course_title, l.title as lesson_title
                FROM forum_threads t
                JOIN users u ON t.author_id = u.id
                LEFT JOIN courses c ON t.course_id = c.id
                LEFT JOIN lessons l ON t.lesson_id = l.id
                WHERE t.id = ?";
        
        return $this->db->fetch($sql, [$id]);
    }
    
    public function getThreadWithPosts($id) {
        $thread = $this->getThread($id);
        if (!$thread) return null;
        
        $thread['posts'] = $this->db->fetchAll(
            "SELECT p.*, u.name as author_name, u.role as author_role, u.avatar
             FROM forum_posts p
             JOIN users u ON p.author_id = u.id
             WHERE p.thread_id = ?
             ORDER BY p.created_at ASC",
            [$id]
        );
        
        return $thread;
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
    
    public function addPost($data) {
        $postId = $this->db->insert('forum_posts', $data);
        
        if ($postId) {
            // Update thread's updated_at
            $this->db->update('forum_threads', 
                ['updated_at' => date('Y-m-d H:i:s')], 
                'id = ?', 
                [$data['thread_id']]
            );
        }
        
        return $postId;
    }
    
    public function updatePost($id, $data) {
        return $this->db->update('forum_posts', $data, 'id = ?', [$id]);
    }
    
    public function deletePost($id) {
        return $this->db->delete('forum_posts', 'id = ?', [$id]);
    }
    
    public function getPost($id) {
        return $this->db->fetch("SELECT * FROM forum_posts WHERE id = ?", [$id]);
    }
    
    public function searchThreads($query, $courseId = null, $limit = 20) {
        $sql = "SELECT t.*, u.name as author_name, u.role as author_role,
                       c.title as course_title, l.title as lesson_title,
                       COUNT(p.id) as post_count
                FROM forum_threads t
                JOIN users u ON t.author_id = u.id
                LEFT JOIN courses c ON t.course_id = c.id
                LEFT JOIN lessons l ON t.lesson_id = l.id
                LEFT JOIN forum_posts p ON t.id = p.thread_id
                WHERE (t.title LIKE ? OR t.id IN (
                    SELECT p2.thread_id FROM forum_posts p2 WHERE p2.content LIKE ?
                ))";
        
        $params = ["%{$query}%", "%{$query}%"];
        
        if ($courseId) {
            $sql .= " AND t.course_id = ?";
            $params[] = $courseId;
        }
        
        $sql .= " GROUP BY t.id ORDER BY t.updated_at DESC LIMIT ?";
        $params[] = $limit;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getRecentThreads($limit = 10) {
        $sql = "SELECT t.*, u.name as author_name, c.title as course_title,
                       COUNT(p.id) as post_count
                FROM forum_threads t
                JOIN users u ON t.author_id = u.id
                LEFT JOIN courses c ON t.course_id = c.id
                LEFT JOIN forum_posts p ON t.id = p.thread_id
                GROUP BY t.id
                ORDER BY t.updated_at DESC
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$limit]);
    }
    
    public function getPopularThreads($limit = 10) {
        $sql = "SELECT t.*, u.name as author_name, c.title as course_title,
                       COUNT(p.id) as post_count
                FROM forum_threads t
                JOIN users u ON t.author_id = u.id
                LEFT JOIN courses c ON t.course_id = c.id
                LEFT JOIN forum_posts p ON t.id = p.thread_id
                GROUP BY t.id
                ORDER BY post_count DESC, t.updated_at DESC
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$limit]);
    }
    
    public function canEditThread($threadId, $userId) {
        $thread = $this->getThread($threadId);
        if (!$thread) return false;
        
        // Author can edit
        if ($thread['author_id'] == $userId) return true;
        
        // Admin and teachers can edit
        $user = $this->db->fetch("SELECT role FROM users WHERE id = ?", [$userId]);
        return $user && in_array($user['role'], ['admin', 'teacher']);
    }
    
    public function canEditPost($postId, $userId) {
        $post = $this->getPost($postId);
        if (!$post) return false;
        
        // Author can edit
        if ($post['author_id'] == $userId) return true;
        
        // Admin and teachers can edit
        $user = $this->db->fetch("SELECT role FROM users WHERE id = ?", [$userId]);
        return $user && in_array($user['role'], ['admin', 'teacher']);
    }
    
    public function canDeleteThread($threadId, $userId) {
        $thread = $this->getThread($threadId);
        if (!$thread) return false;
        
        // Author can delete
        if ($thread['author_id'] == $userId) return true;
        
        // Admin can delete
        $user = $this->db->fetch("SELECT role FROM users WHERE id = ?", [$userId]);
        return $user && $user['role'] === 'admin';
    }
    
    public function canDeletePost($postId, $userId) {
        $post = $this->getPost($postId);
        if (!$post) return false;
        
        // Author can delete
        if ($post['author_id'] == $userId) return true;
        
        // Admin and teachers can delete
        $user = $this->db->fetch("SELECT role FROM users WHERE id = ?", [$userId]);
        return $user && in_array($user['role'], ['admin', 'teacher']);
    }
    
    public function getThreadCount($courseId = null) {
        $where = $courseId ? 'course_id = ?' : '1=1';
        $params = $courseId ? [$courseId] : [];
        return $this->db->count('forum_threads', $where, $params);
    }
    
    public function getPostCount($threadId = null) {
        $where = $threadId ? 'thread_id = ?' : '1=1';
        $params = $threadId ? [$threadId] : [];
        return $this->db->count('forum_posts', $where, $params);
    }
    
    public function getAuthorAvatar($author) {
        if ($author['avatar']) {
            return APP_URL . '/public/uploads/avatars/' . $author['avatar'];
        }
        return APP_URL . '/assets/img/default-avatar.png';
    }
    
    public function getRoleBadgeClass($role) {
        switch ($role) {
            case 'admin': return 'badge-danger';
            case 'teacher': return 'badge-primary';
            case 'student': return 'badge-success';
            default: return 'badge-secondary';
        }
    }
    
    public function formatContent($content) {
        // Basic formatting
        $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
        $content = nl2br($content);
        
        // Convert URLs to links
        $content = preg_replace(
            '/(https?:\/\/[^\s]+)/',
            '<a href="$1" target="_blank" rel="noopener">$1</a>',
            $content
        );
        
        return $content;
    }
}