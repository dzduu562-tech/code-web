<?php
namespace Core;

class Auth
{
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function id(): ?int
    {
        return $_SESSION['user']['id'] ?? null;
    }

    public static function role(): ?string
    {
        return $_SESSION['user']['role'] ?? null;
    }

    public static function requireRole(array $roles): void
    {
        if (!self::check() || !in_array(self::role(), $roles, true)) {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }
    }
}
