<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/DB.php';
require_once __DIR__ . '/../core/Helpers.php';

class NotificationController extends BaseController {
    public function index(): string {
        Auth::startSecureSession();
        if (!Auth::check()) { header('Location: index.php?route=/login'); exit; }
        $pdo = DB::getConnection();
        $stmt = $pdo->prepare('SELECT id, type, payload_json, is_read, created_at FROM notifications WHERE user_id=? ORDER BY id DESC LIMIT 50');
        $stmt->execute([Auth::id()]);
        $items = $stmt->fetchAll();
        return $this->render('notifications/list', compact('items'));
    }
    public function poll(): void {
        Auth::startSecureSession();
        if (!Auth::check()) { http_response_code(401); exit; }
        $pdo = DB::getConnection();
        $stmt = $pdo->prepare('SELECT id, type, payload_json, is_read, created_at FROM notifications WHERE user_id=? AND is_read=0 ORDER BY id DESC LIMIT 10');
        $stmt->execute([Auth::id()]);
        $rows = $stmt->fetchAll();
        Helpers::json(['count' => count($rows), 'items' => $rows]);
    }

    public function markRead(): void {
        Auth::startSecureSession();
        if (!Auth::check() || !Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(400); exit; }
        $pdo = DB::getConnection();
        $pdo->prepare('UPDATE notifications SET is_read=1 WHERE user_id=?')->execute([Auth::id()]);
        Helpers::json(['ok'=>true]);
    }
}
