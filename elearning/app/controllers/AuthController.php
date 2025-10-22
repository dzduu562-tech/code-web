<?php
namespace Controllers;

use Core\Helpers;
use PDO;

class AuthController extends BaseController
{
    public function showLogin(): void
    {
        $this->render('auth/login');
    }

    public function login(): void
    {
        if (!\Core\Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(400); echo 'Bad CSRF'; return; }
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if ($email === '' || $password === '') { $this->render('auth/login', ['error' => 'Vui lòng nhập email và mật khẩu']); return; }
        $stmt = $this->db->prepare('SELECT id, name, email, password_hash, role FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            $this->render('auth/login', ['error' => 'Thông tin đăng nhập không đúng']);
            return;
        }
        unset($user['password_hash']);
        $_SESSION['user'] = $user;
        Helpers::redirect(Helpers::baseUrl($this->config) . '/index.php?route=/dashboard');
    }

    public function logout(): void
    {
        session_destroy();
        Helpers::redirect(Helpers::baseUrl($this->config) . '/index.php?route=/');
    }

    public function showRegister(): void
    {
        $this->render('auth/register');
    }

    public function register(): void
    {
        if (!\Core\Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(400); echo 'Bad CSRF'; return; }
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = in_array($_POST['role'] ?? 'student', ['student','teacher'], true) ? $_POST['role'] : 'student';
        if ($name === '' || $email === '' || $password === '') { $this->render('auth/register', ['error' => 'Vui lòng nhập đầy đủ thông tin']); return; }
        $stmt = $this->db->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) { $this->render('auth/register', ['error' => 'Email đã tồn tại']); return; }
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare('INSERT INTO users(name, email, password_hash, role, created_at) VALUES(?,?,?,?,NOW())');
        $stmt->execute([$name, $email, $hash, $role]);
        $id = (int)$this->db->lastInsertId();
        $_SESSION['user'] = ['id' => $id, 'name' => $name, 'email' => $email, 'role' => $role];
        Helpers::redirect(Helpers::baseUrl($this->config) . '/index.php?route=/dashboard');
    }
}
