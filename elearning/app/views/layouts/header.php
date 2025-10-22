<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'E-Learning Platform'; ?></title>
    <link rel="stylesheet" href="/elearning/assets/css/main.css">
</head>
<body>
    <?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    ?>
    
    <nav class="navbar">
        <div class="container">
            <a href="/elearning/public/index.php?route=home" class="logo">
                <span class="logo-icon">📚</span>
                <span class="logo-text">E-Learning</span>
            </a>
            
            <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
                <span></span>
                <span></span>
                <span></span>
            </button>
            
            <div class="nav-menu" id="navMenu">
                <?php if (Auth::check()): ?>
                    <a href="/elearning/public/index.php?route=dashboard" class="nav-link">Dashboard</a>
                    <a href="/elearning/public/index.php?route=courses" class="nav-link">Khóa học</a>
                    
                    <?php if (Auth::isStudent()): ?>
                        <a href="/elearning/public/index.php?route=assignments" class="nav-link">Bài tập</a>
                        <a href="/elearning/public/index.php?route=forum" class="nav-link">Diễn đàn</a>
                    <?php endif; ?>
                    
                    <?php if (Auth::isTeacher()): ?>
                        <a href="/elearning/public/index.php?route=courses/create" class="nav-link">Tạo khóa học</a>
                        <a href="/elearning/public/index.php?route=forum" class="nav-link">Diễn đàn</a>
                    <?php endif; ?>
                    
                    <?php if (Auth::isAdmin()): ?>
                        <a href="/elearning/public/index.php?route=admin/users" class="nav-link">Quản lý</a>
                    <?php endif; ?>
                    
                    <div class="nav-item dropdown">
                        <button class="notification-btn" id="notificationBtn" aria-label="Thông báo">
                            🔔
                            <span class="badge" id="notificationBadge" style="display: none;">0</span>
                        </button>
                        <div class="notification-dropdown" id="notificationDropdown">
                            <div class="dropdown-header">
                                <h4>Thông báo</h4>
                                <button class="btn-text" id="markAllRead">Đánh dấu đã đọc</button>
                            </div>
                            <div id="notificationList" class="notification-list">
                                <p class="text-center text-muted">Không có thông báo mới</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="nav-item dropdown">
                        <button class="user-btn">
                            👤 <?php echo Helpers::escape(Auth::user()['name']); ?>
                        </button>
                        <div class="dropdown-menu">
                            <span class="dropdown-role"><?php 
                                $roles = ['admin' => 'Quản trị viên', 'teacher' => 'Giáo viên', 'student' => 'Học sinh'];
                                echo $roles[Auth::role()] ?? Auth::role();
                            ?></span>
                            <a href="/elearning/public/index.php?route=logout" class="dropdown-item">Đăng xuất</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/elearning/public/index.php?route=login" class="nav-link">Đăng nhập</a>
                    <a href="/elearning/public/index.php?route=register" class="btn btn-primary">Đăng ký</a>
                <?php endif; ?>
                
                <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
                    <span class="theme-icon">🌙</span>
                </button>
            </div>
        </div>
    </nav>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo Helpers::escape($_SESSION['success']); ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?php echo Helpers::escape($_SESSION['error']); ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <main class="main-content">
