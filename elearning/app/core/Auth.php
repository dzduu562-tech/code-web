<?php
class Auth {
    public static function startSecureSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            $secure = isset($_SERVER['HTTPS']);
            session_set_cookie_params([
                'httponly' => true,
                'secure' => $secure,
                'samesite' => 'Lax',
            ]);
            session_name('ELSESSID');
            session_start();
        }
    }

    public static function user(): ?array { return $_SESSION['user'] ?? null; }
    public static function id(): ?int { return self::user()['id'] ?? null; }
    public static function check(): bool { return isset($_SESSION['user']); }
    public static function requireRole(array $roles): void {
        $user = self::user();
        if (!$user || !in_array($user['role'], $roles, true)) {
            Helpers::redirect('index.php?route=/login');
        }
    }
    public static function login(array $user): void { $_SESSION['user'] = $user; }
    public static function logout(): void { $_SESSION = []; session_destroy(); }
}
