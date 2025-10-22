<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/DB.php';

class DashboardController extends BaseController {
    public function index(): string {
        Auth::startSecureSession();
        if (!Auth::check()) { header('Location: index.php?route=/login'); exit; }
        $user = Auth::user();
        $pdo = DB::getConnection();
        if ($user['role'] === 'admin') {
            $stats = [
                'users' => (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(),
                'courses' => (int)$pdo->query('SELECT COUNT(*) FROM courses')->fetchColumn(),
                'students' => (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetchColumn(),
            ];
        } elseif ($user['role'] === 'teacher') {
            $stmt = $pdo->prepare('SELECT * FROM courses WHERE teacher_id = ? ORDER BY created_at DESC LIMIT 6');
            $stmt->execute([$user['id']]);
            $courses = $stmt->fetchAll();
            $stats = ['courses'=>count($courses)];
        } else {
            $stmt = $pdo->prepare('SELECT c.* FROM courses c JOIN enrollments e ON e.course_id=c.id WHERE e.user_id=? ORDER BY c.created_at DESC LIMIT 6');
            $stmt->execute([$user['id']]);
            $courses = $stmt->fetchAll();
            $stats = ['enrolled'=>count($courses)];
        }
        return $this->render('courses/dashboard', compact('user','stats','courses'));
    }
}
