<?php

class Forum {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    // Thread methods
    public function getThread($id) {
        $sql = "SELECT ft.*, u.name as author_name, u.role as author_role,
                       c.title as course_title, l.title as lesson_title
                FROM forum_threads ft
                JOIN users u ON ft.author_id = u.id
                JOIN courses c ON ft.course_id = c.id
                LEFT JOIN lessons l ON ft.lesson_id = l.id
                WHERE ft.id = ?";
        return $this->db->fetch($sql, [$id]);
    }

    public function getThreads($filters = []) {
        $where = "1=1";
        $params = [];

        if (!empty($filters['course_id'])) {
            $where .= " AND ft.course_id = ?";
            $params[] = $filters['course_id'];
        }

        if (!empty($filters['lesson_id'])) {
            $where .= " AND ft.lesson_id = ?";
            $params[] = $filters['lesson_id'];
        }

        if (!empty($filters['author_id'])) {
            $where .= " AND ft.author_id = ?";
            $params[] = $filters['author_id'];
        }

        if (!empty($filters['search'])) {
            $where .= " AND (ft.title LIKE ? OR EXISTS (SELECT 1 FROM forum_posts fp WHERE fp.thread_id = ft.id AND fp.content LIKE ?))";
            $params[] = "%{$filters['search']}%";
            $params[] = "%{$filters['search']}%";
        }

        $sql = "SELECT ft.*, u.name as author_name, u.role as author_role,
                       c.title as course_title, l.title as lesson_title,
                       (SELECT COUNT(*) FROM forum_posts fp WHERE fp.thread_id = ft.id) as post_count,
                       (SELECT MAX(fp.created_at) FROM forum_posts fp WHERE fp.thread_id = ft.id) as last_post_at,
                       (SELECT u2.name FROM forum_posts fp2 JOIN users u2 ON fp2.author_id = u2.id WHERE fp2.thread_id = ft.id ORDER BY fp2.created_at DESC LIMIT 1) as last_post_author
                FROM forum_threads ft
                JOIN users u ON ft.author_id = u.id
                JOIN courses c ON ft.course_id = c.id
                LEFT JOIN lessons l ON ft.lesson_id = l.id
                WHERE {$where}
                ORDER BY ft.is_pinned DESC, ft.updated_at DESC";

        if (!empty($filters['limit'])) {
            $sql .= " LIMIT " . (int)$filters['limit'];
            if (!empty($filters['offset'])) {
                $sql .= " OFFSET " . (int)$filters['offset'];
            }
        }

        return $this->db->fetchAll($sql, $params);
    }

    public function createThread($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $threadId = $this->db->insert('forum_threads', $data);

        // Create the first post with the thread content if provided
        if (!empty($data['content'])) {
            $this->createPost([
                'thread_id' => $threadId,
                'author_id' => $data['author_id'],
                'content' => $data['content']
            ]);
        }

        return $threadId;
    }

    public function updateThread($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->update('forum_threads', $data, 'id = ?', [$id]);
    }

    public function deleteThread($id) {
        // Delete all posts in the thread first
        $this->db->delete('forum_posts', 'thread_id = ?', [$id]);
        return $this->db->delete('forum_threads', 'id = ?', [$id]);
    }

    public function pinThread($id) {
        return $this->updateThread($id, ['is_pinned' => 1]);
    }

    public function unpinThread($id) {
        return $this->updateThread($id, ['is_pinned' => 0]);
    }

    public function lockThread($id) {
        return $this->updateThread($id, ['is_locked' => 1]);
    }

    public function unlockThread($id) {
        return $this->updateThread($id, ['is_locked' => 0]);
    }

    public function incrementViews($id) {
        $this->db->query("UPDATE forum_threads SET views = views + 1 WHERE id = ?", [$id]);
    }

    // Post methods
    public function getPost($id) {
        $sql = "SELECT fp.*, u.name as author_name, u.role as author_role
                FROM forum_posts fp
                JOIN users u ON fp.author_id = u.id
                WHERE fp.id = ?";
        return $this->db->fetch($sql, [$id]);
    }

    public function getPosts($threadId, $filters = []) {
        $where = "fp.thread_id = ?";
        $params = [$threadId];

        if (!empty($filters['author_id'])) {
            $where .= " AND fp.author_id = ?";
            $params[] = $filters['author_id'];
        }

        $sql = "SELECT fp.*, u.name as author_name, u.role as author_role, u.avatar
                FROM forum_posts fp
                JOIN users u ON fp.author_id = u.id
                WHERE {$where}
                ORDER BY fp.created_at ASC";

        if (!empty($filters['limit'])) {
            $sql .= " LIMIT " . (int)$filters['limit'];
            if (!empty($filters['offset'])) {
                $sql .= " OFFSET " . (int)$filters['offset'];
            }
        }

        return $this->db->fetchAll($sql, $params);
    }

    public function createPost($data) {
        // Check if thread is locked
        $thread = $this->getThread($data['thread_id']);
        if ($thread['is_locked']) {
            return ['success' => false, 'message' => 'Chủ đề đã bị khóa'];
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $postId = $this->db->insert('forum_posts', $data);

        // Update thread's updated_at timestamp
        $this->updateThread($data['thread_id'], []);

        // Notify thread participants (except the post author)
        $this->notifyThreadParticipants($data['thread_id'], $data['author_id']);

        return ['success' => true, 'post_id' => $postId];
    }

    public function updatePost($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->update('forum_posts', $data, 'id = ?', [$id]);
    }

    public function deletePost($id) {
        return $this->db->delete('forum_posts', 'id = ?', [$id]);
    }

    public function markAsSolution($postId) {
        $post = $this->getPost($postId);
        if (!$post) return false;

        // Remove solution mark from other posts in the same thread
        $this->db->update('forum_posts', 
            ['is_solution' => 0], 
            'thread_id = ?', 
            [$post['thread_id']]
        );

        // Mark this post as solution
        return $this->updatePost($postId, ['is_solution' => 1]);
    }

    public function unmarkAsSolution($postId) {
        return $this->updatePost($postId, ['is_solution' => 0]);
    }

    // Permission and access control
    public function canUserAccessThread($threadId, $userId) {
        $thread = $this->getThread($threadId);
        if (!$thread) return false;

        // Check if user is enrolled in the course or is the teacher
        $courseModel = new Course();
        $course = $courseModel->find($thread['course_id']);
        
        return $courseModel->isEnrolled($thread['course_id'], $userId) || 
               $course['teacher_id'] == $userId;
    }

    public function canUserModerateThread($threadId, $userId) {
        $thread = $this->getThread($threadId);
        if (!$thread) return false;

        $auth = Auth::getInstance();
        
        // Admin can moderate all threads
        if ($auth->isAdmin()) return true;

        // Course teacher can moderate threads in their course
        $courseModel = new Course();
        $course = $courseModel->find($thread['course_id']);
        
        return $course['teacher_id'] == $userId;
    }

    public function canUserEditPost($postId, $userId) {
        $post = $this->getPost($postId);
        if (!$post) return false;

        $auth = Auth::getInstance();
        
        // Admin can edit all posts
        if ($auth->isAdmin()) return true;

        // Post author can edit their own post
        if ($post['author_id'] == $userId) return true;

        // Thread moderator can edit posts
        return $this->canUserModerateThread($post['thread_id'], $userId);
    }

    // Statistics and analytics
    public function getForumStats($courseId = null) {
        if ($courseId) {
            // Course-specific stats
            return [
                'thread_count' => $this->db->count('forum_threads', 'course_id = ?', [$courseId]),
                'post_count' => $this->db->fetch(
                    "SELECT COUNT(*) as count FROM forum_posts fp 
                     JOIN forum_threads ft ON fp.thread_id = ft.id 
                     WHERE ft.course_id = ?", 
                    [$courseId]
                )['count'],
                'active_users' => $this->db->fetch(
                    "SELECT COUNT(DISTINCT fp.author_id) as count FROM forum_posts fp 
                     JOIN forum_threads ft ON fp.thread_id = ft.id 
                     WHERE ft.course_id = ? AND fp.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)", 
                    [$courseId]
                )['count'],
                'recent_activity' => $this->db->count(
                    'forum_posts fp JOIN forum_threads ft ON fp.thread_id = ft.id', 
                    'ft.course_id = ? AND fp.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)', 
                    [$courseId]
                )
            ];
        } else {
            // System-wide stats
            return [
                'total_threads' => $this->db->count('forum_threads'),
                'total_posts' => $this->db->count('forum_posts'),
                'active_users' => $this->db->count(
                    'forum_posts', 
                    'created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY author_id'
                ),
                'new_threads_today' => $this->db->count('forum_threads', 'DATE(created_at) = CURDATE()'),
                'new_posts_today' => $this->db->count('forum_posts', 'DATE(created_at) = CURDATE()')
            ];
        }
    }

    public function getPopularThreads($courseId = null, $limit = 10) {
        $where = "1=1";
        $params = [];

        if ($courseId) {
            $where .= " AND ft.course_id = ?";
            $params[] = $courseId;
        }

        $sql = "SELECT ft.*, u.name as author_name,
                       (SELECT COUNT(*) FROM forum_posts fp WHERE fp.thread_id = ft.id) as post_count
                FROM forum_threads ft
                JOIN users u ON ft.author_id = u.id
                WHERE {$where}
                ORDER BY ft.views DESC, post_count DESC
                LIMIT ?";

        $params[] = $limit;
        return $this->db->fetchAll($sql, $params);
    }

    public function getRecentActivity($userId = null, $limit = 10) {
        $where = "1=1";
        $params = [];

        if ($userId) {
            $where .= " AND fp.author_id = ?";
            $params[] = $userId;
        }

        $sql = "SELECT fp.*, ft.title as thread_title, ft.course_id,
                       u.name as author_name, c.title as course_title
                FROM forum_posts fp
                JOIN forum_threads ft ON fp.thread_id = ft.id
                JOIN users u ON fp.author_id = u.id
                JOIN courses c ON ft.course_id = c.id
                WHERE {$where}
                ORDER BY fp.created_at DESC
                LIMIT ?";

        $params[] = $limit;
        return $this->db->fetchAll($sql, $params);
    }

    public function getUserPostCount($userId, $courseId = null) {
        $where = "fp.author_id = ?";
        $params = [$userId];

        if ($courseId) {
            $where .= " AND ft.course_id = ?";
            $params[] = $courseId;
        }

        return $this->db->fetch(
            "SELECT COUNT(*) as count FROM forum_posts fp 
             JOIN forum_threads ft ON fp.thread_id = ft.id 
             WHERE {$where}",
            $params
        )['count'];
    }

    // Search functionality
    public function search($query, $filters = []) {
        $where = "(ft.title LIKE ? OR fp.content LIKE ?)";
        $params = ["%{$query}%", "%{$query}%"];

        if (!empty($filters['course_id'])) {
            $where .= " AND ft.course_id = ?";
            $params[] = $filters['course_id'];
        }

        if (!empty($filters['author_id'])) {
            $where .= " AND (ft.author_id = ? OR fp.author_id = ?)";
            $params[] = $filters['author_id'];
            $params[] = $filters['author_id'];
        }

        $sql = "SELECT DISTINCT ft.*, u.name as author_name,
                       (SELECT COUNT(*) FROM forum_posts fp2 WHERE fp2.thread_id = ft.id) as post_count
                FROM forum_threads ft
                JOIN users u ON ft.author_id = u.id
                LEFT JOIN forum_posts fp ON fp.thread_id = ft.id
                WHERE {$where}
                ORDER BY ft.updated_at DESC";

        if (!empty($filters['limit'])) {
            $sql .= " LIMIT " . (int)$filters['limit'];
        }

        return $this->db->fetchAll($sql, $params);
    }

    private function notifyThreadParticipants($threadId, $authorId) {
        // Get all users who have posted in this thread (except the current author)
        $participants = $this->db->fetchAll(
            "SELECT DISTINCT fp.author_id, ft.title as thread_title, ft.course_id
             FROM forum_posts fp
             JOIN forum_threads ft ON fp.thread_id = ft.id
             WHERE fp.thread_id = ? AND fp.author_id != ?",
            [$threadId, $authorId]
        );

        $thread = $this->getThread($threadId);
        $author = (new User())->find($authorId);

        foreach ($participants as $participant) {
            Helpers::sendNotification(
                $participant['author_id'],
                'forum_reply',
                'Có phản hồi mới trong diễn đàn',
                "{$author['name']} đã trả lời trong chủ đề '{$thread['title']}'",
                [
                    'thread_id' => $threadId,
                    'course_id' => $thread['course_id']
                ]
            );
        }
    }
}