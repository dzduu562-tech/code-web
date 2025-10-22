<?php

class NotificationController {
    
    public function getUnread() {
        Auth::requireLogin();
        
        $notificationModel = new Notification();
        $notifications = $notificationModel->getByUser(Auth::id(), 10, true);
        $unreadCount = $notificationModel->getUnreadCount(Auth::id());
        
        Helpers::json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }
    
    public function markRead() {
        Auth::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::json(['success' => false], 400);
            return;
        }
        
        $notificationId = $_POST['id'] ?? 0;
        $notificationModel = new Notification();
        
        $notificationModel->markAsRead($notificationId);
        
        Helpers::json(['success' => true]);
    }
    
    public function markAllRead() {
        Auth::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::json(['success' => false], 400);
            return;
        }
        
        $notificationModel = new Notification();
        $notificationModel->markAllAsRead(Auth::id());
        
        Helpers::json(['success' => true]);
    }
}
