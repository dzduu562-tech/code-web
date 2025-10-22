<?php
require_once __DIR__ . '/../core/DB.php';

class Notification {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    public function create($userId, $type, $title, $message, $payload = null) {
        return $this->db->insert('notifications', [
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'payload_json' => $payload ? json_encode($payload) : null
        ]);
    }
    
    public function getByUser($userId, $page = 1, $limit = ITEMS_PER_PAGE, $unreadOnly = false) {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM notifications WHERE user_id = ?";
        $params = [$userId];
        
        if ($unreadOnly) {
            $sql .= " AND is_read = 0";
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getUnreadCount($userId) {
        $result = $this->db->fetch(
            "SELECT COUNT(*) as total FROM notifications WHERE user_id = ? AND is_read = 0",
            [$userId]
        );
        return $result['total'];
    }
    
    public function markAsRead($id, $userId = null) {
        $sql = "UPDATE notifications SET is_read = 1 WHERE id = ?";
        $params = [$id];
        
        if ($userId) {
            $sql .= " AND user_id = ?";
            $params[] = $userId;
        }
        
        return $this->db->query($sql, $params);
    }
    
    public function markAllAsRead($userId) {
        return $this->db->update('notifications', ['is_read' => 1], 'user_id = ?', [$userId]);
    }
    
    public function delete($id, $userId = null) {
        $sql = "DELETE FROM notifications WHERE id = ?";
        $params = [$id];
        
        if ($userId) {
            $sql .= " AND user_id = ?";
            $params[] = $userId;
        }
        
        return $this->db->query($sql, $params);
    }
    
    public function deleteOld($days = 30) {
        $date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        return $this->db->delete('notifications', 'created_at < ?', [$date]);
    }
    
    // Notification types
    public function notifyNewAssignment($courseId, $assignmentId, $assignmentTitle) {
        // Get all enrolled students
        $students = $this->db->fetchAll(
            "SELECT u.id FROM users u
             INNER JOIN enrollments e ON u.id = e.user_id
             WHERE e.course_id = ? AND u.role = 'student'",
            [$courseId]
        );
        
        foreach ($students as $student) {
            $this->create(
                $student['id'],
                'assignment',
                'Bài tập mới',
                "Có bài tập mới: {$assignmentTitle}",
                ['course_id' => $courseId, 'assignment_id' => $assignmentId]
            );
        }
    }
    
    public function notifyNewGrade($studentId, $assignmentTitle, $score) {
        $this->create(
            $studentId,
            'grade',
            'Điểm mới',
            "Bạn đã nhận được điểm {$score} cho bài tập: {$assignmentTitle}",
            ['assignment_title' => $assignmentTitle, 'score' => $score]
        );
    }
    
    public function notifyNewForumPost($threadId, $postId, $threadTitle, $authorName) {
        // Get thread info
        $thread = $this->db->fetch(
            "SELECT ft.course_id, ft.author_id, c.title as course_title
             FROM forum_threads ft
             LEFT JOIN courses c ON ft.course_id = c.id
             WHERE ft.id = ?",
            [$threadId]
        );
        
        if (!$thread) return;
        
        // Get all users enrolled in the course except the author
        $users = $this->db->fetchAll(
            "SELECT DISTINCT u.id FROM users u
             INNER JOIN enrollments e ON u.id = e.user_id
             WHERE e.course_id = ? AND u.id != ?",
            [$thread['course_id'], $thread['author_id']]
        );
        
        foreach ($users as $user) {
            $this->create(
                $user['id'],
                'forum',
                'Trả lời trong diễn đàn',
                "{$authorName} đã trả lời trong chủ đề: {$threadTitle}",
                ['thread_id' => $threadId, 'post_id' => $postId, 'course_id' => $thread['course_id']]
            );
        }
    }
    
    public function notifyNewCourse($courseId, $courseTitle, $teacherName) {
        // Get all students (for now, in a real app you might want to target specific students)
        $students = $this->db->fetchAll(
            "SELECT id FROM users WHERE role = 'student'"
        );
        
        foreach ($students as $student) {
            $this->create(
                $student['id'],
                'course',
                'Khóa học mới',
                "Khóa học mới: {$courseTitle} bởi {$teacherName}",
                ['course_id' => $courseId]
            );
        }
    }
    
    public function notifyNewLesson($courseId, $lessonTitle, $teacherName) {
        // Get all enrolled students
        $students = $this->db->fetchAll(
            "SELECT u.id FROM users u
             INNER JOIN enrollments e ON u.id = e.user_id
             WHERE e.course_id = ? AND u.role = 'student'",
            [$courseId]
        );
        
        foreach ($students as $student) {
            $this->create(
                $student['id'],
                'lesson',
                'Bài học mới',
                "Bài học mới: {$lessonTitle}",
                ['course_id' => $courseId, 'lesson_title' => $lessonTitle]
            );
        }
    }
    
    public function getRecentNotifications($userId, $limit = 5) {
        return $this->db->fetchAll(
            "SELECT * FROM notifications 
             WHERE user_id = ? 
             ORDER BY created_at DESC 
             LIMIT ?",
            [$userId, $limit]
        );
    }
    
    public function getNotificationStats($userId) {
        $total = $this->db->fetch(
            "SELECT COUNT(*) as total FROM notifications WHERE user_id = ?",
            [$userId]
        )['total'];
        
        $unread = $this->db->fetch(
            "SELECT COUNT(*) as unread FROM notifications WHERE user_id = ? AND is_read = 0",
            [$userId]
        )['unread'];
        
        return [
            'total' => $total,
            'unread' => $unread,
            'read' => $total - $unread
        ];
    }
}