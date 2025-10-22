<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../core/DB.php';
require_once __DIR__ . '/../core/Helpers.php';
require_once __DIR__ . '/../core/Auth.php';

class AuthController extends BaseController {
    public function showLogin(): string {
        return $this->render('auth/login', []);
    }
    public function showRegister(): string {
        return $this->render('auth/register', []);
    }
    public function login(): void {
        if (!Helpers::isPost() || !Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(400); exit('Bad Request'); }
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $stmt = DB::getConnection()->prepare('SELECT id, name, email, password_hash, role FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            unset($user['password_hash']);
            Auth::login($user);
            Helpers::redirect('index.php?route=/dashboard');
        } else {
            $_SESSION['flash_error'] = 'Sai email hoặc mật khẩu';
            Helpers::redirect('index.php?route=/login');
        }
    }
    public function register(): void {
        if (!Helpers::isPost() || !Helpers::verifyCsrf($_POST['csrf'] ?? null)) { http_response_code(400); exit('Bad Request'); }
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
            $_SESSION['flash_error'] = 'Dữ liệu không hợp lệ';
            Helpers::redirect('index.php?route=/register');
        }
        $pdo = DB::getConnection();
        $exists = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $exists->execute([$email]);
        if ($exists->fetch()) { $_SESSION['flash_error'] = 'Email đã tồn tại'; Helpers::redirect('index.php?route=/register'); }
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $ins = $pdo->prepare('INSERT INTO users(name,email,password_hash,role,created_at) VALUES(?,?,?,?,NOW())');
        $ins->execute([$name,$email,$hash,'student']);
        $_SESSION['flash_success'] = 'Đăng ký thành công, vui lòng đăng nhập';
        Helpers::redirect('index.php?route=/login');
    }
    public function logout(): void {
        Auth::logout();
        Helpers::redirect('index.php?route=/');
    }
}
