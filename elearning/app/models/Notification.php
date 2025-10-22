<?php

class Notification {
    private $db;
    
    public function __construct() {
        $this->db = DB::getInstance();
    }
    
    public function create($data) {
        return $this->db->insert('notifications', $data);
    }
    
    public function getByUser($userId, $limit = 10, $unreadOnly = false) {
        $condition = $unreadOnly ? ' AND is_read = 0' : '';
        
        $sql = "SELECT * FROM notifications 
                WHERE user_id = :user_id $condition
                ORDER BY created_at DESC 
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, ['user_id' => $userId, 'limit' => $limit]);
    }
    
    public function markAsRead($id) {
        return $this->db->update('notifications', 
            ['is_read' => 1], 
            'id = :id', 
            ['id' => $id]
        );
    }
    
    public function markAllAsRead($userId) {
        return $this->db->update('notifications', 
            ['is_read' => 1], 
            'user_id = :user_id AND is_read = 0', 
            ['user_id' => $userId]
        );
    }
    
    public function getUnreadCount($userId) {
        $sql = "SELECT COUNT(*) as count FROM notifications 
                WHERE user_id = :user_id AND is_read = 0";
        $result = $this->db->fetchOne($sql, ['user_id' => $userId]);
        return $result['count'] ?? 0;
    }
    
    public function delete($id) {
        return $this->db->delete('notifications', 'id = :id', ['id' => $id]);
    }
    
    // Helper methods to create specific notification types
    public function notifyNewLesson($userId, $lessonTitle, $lessonId) {
        return $this->create([
            'user_id' => $userId,
            'type' => 'new_lesson',
            'title' => 'Bài học mới',
            'message' => "Bài học \"$lessonTitle\" vừa được thêm vào khóa học",
            'link' => "lesson&id=$lessonId"
        ]);
    }
    
    public function notifyAssignmentGraded($userId, $assignmentTitle, $score) {
        return $this->create([
            'user_id' => $userId,
            'type' => 'assignment_graded',
            'title' => 'Bài tập đã được chấm điểm',
            'message' => "Bài tập \"$assignmentTitle\" đã được chấm: $score/10",
            'link' => 'submissions'
        ]);
    }
    
    public function notifyForumReply($userId, $threadId) {
        return $this->create([
            'user_id' => $userId,
            'type' => 'forum_reply',
            'title' => 'Có phản hồi mới',
            'message' => 'Có người đã trả lời câu hỏi của bạn',
            'link' => "forum/thread&id=$threadId"
        ]);
    }
}
