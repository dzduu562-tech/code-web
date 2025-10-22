<?php
class Helpers {
    public static function e(string $str): string { return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
    public static function redirect(string $url): void { header("Location: {$url}"); exit; }
    public static function csrfToken(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    public static function verifyCsrf(?string $token): bool {
        return is_string($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }
    public static function isPost(): bool { return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST'; }
    public static function json($data): void { header('Content-Type: application/json'); echo json_encode($data); exit; }
}
