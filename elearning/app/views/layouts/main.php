<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'E-Learning Platform' ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?= Helpers::asset('css/main.css') ?>" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= Helpers::asset('img/favicon.ico') ?>">
    
    <meta name="description" content="Hệ thống học tập trực tuyến hiện đại">
    <meta name="keywords" content="e-learning, học tập trực tuyến, khóa học">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="<?= Helpers::url('home') ?>">
                <i class="bi bi-mortarboard-fill me-2"></i>
                E-Learning
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Helpers::url('home') ?>">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Helpers::url('courses') ?>">Khóa học</a>
                    </li>
                    <?php if (Auth::getInstance()->isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= Helpers::url('dashboard') ?>">Bảng điều khiển</a>
                        </li>
                        <?php if (Auth::getInstance()->isStudent()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= Helpers::url('assignments') ?>">Bài tập</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= Helpers::url('forum') ?>">Diễn đàn</a>
                        </li>
                    <?php endif; ?>
                </ul>
                
                <ul class="navbar-nav">
                    <!-- Theme Toggle -->
                    <li class="nav-item">
                        <button class="btn btn-link nav-link border-0" id="theme-toggle" title="Chuyển đổi theme">
                            <i class="bi bi-sun-fill" id="theme-icon"></i>
                        </button>
                    </li>
                    
                    <!-- Search -->
                    <li class="nav-item me-2">
                        <form class="d-flex" action="<?= Helpers::url('search') ?>" method="GET">
                            <div class="input-group">
                                <input class="form-control form-control-sm" type="search" name="q" 
                                       placeholder="Tìm khóa học..." value="<?= $_GET['q'] ?? '' ?>">
                                <button class="btn btn-outline-primary btn-sm" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </li>
                    
                    <?php if (Auth::getInstance()->isLoggedIn()): ?>
                        <!-- Notifications -->
                        <li class="nav-item dropdown me-2">
                            <a class="nav-link position-relative" href="#" id="notificationDropdown" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                                      id="notification-badge" style="display: none;">
                                    0
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end notification-dropdown" style="width: 320px;">
                                <li><h6 class="dropdown-header">Thông báo</h6></li>
                                <div id="notification-list">
                                    <li><span class="dropdown-item-text text-muted">Đang tải...</span></li>
                                </div>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-center" href="<?= Helpers::url('notifications') ?>">
                                        Xem tất cả thông báo
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <!-- User Menu -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i>
                                <?= Helpers::escape(Auth::getInstance()->name()) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <span class="dropdown-item-text">
                                        <small class="text-muted">
                                            <?= ucfirst(Auth::getInstance()->role()) ?>
                                        </small>
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= Helpers::url('profile') ?>">
                                    <i class="bi bi-person me-2"></i>Thông tin cá nhân
                                </a></li>
                                <li><a class="dropdown-item" href="<?= Helpers::url('change-password') ?>">
                                    <i class="bi bi-key me-2"></i>Đổi mật khẩu
                                </a></li>
                                <?php if (Auth::getInstance()->isAdmin()): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?= Helpers::url('admin') ?>">
                                        <i class="bi bi-gear me-2"></i>Quản trị
                                    </a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="<?= Helpers::url('logout') ?>">
                                    <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= Helpers::url('login') ?>">Đăng nhập</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm" href="<?= Helpers::url('register') ?>">Đăng ký</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <?php
        // Include the specific view
        $viewFile = __DIR__ . "/../{$view}.php";
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            include __DIR__ . '/../errors/404.php';
        }
        ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>E-Learning Platform</h5>
                    <p class="mb-0">Hệ thống học tập trực tuyến hiện đại và dễ sử dụng.</p>
                </div>
                <div class="col-md-3">
                    <h6>Liên kết</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?= Helpers::url('about') ?>" class="text-light text-decoration-none">Giới thiệu</a></li>
                        <li><a href="<?= Helpers::url('contact') ?>" class="text-light text-decoration-none">Liên hệ</a></li>
                        <li><a href="<?= Helpers::url('privacy') ?>" class="text-light text-decoration-none">Chính sách bảo mật</a></li>
                        <li><a href="<?= Helpers::url('terms') ?>" class="text-light text-decoration-none">Điều khoản sử dụng</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Hỗ trợ</h6>
                    <p class="mb-1"><i class="bi bi-envelope me-2"></i>support@elearning.com</p>
                    <p class="mb-0"><i class="bi bi-telephone me-2"></i>1900 1234</p>
                </div>
            </div>
            <hr class="my-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; <?= date('Y') ?> E-Learning Platform. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <small class="text-muted">Phiên bản 1.0</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= Helpers::asset('js/main.js') ?>"></script>
    
    <?php if (Auth::getInstance()->isLoggedIn()): ?>
        <!-- Notification polling for logged in users -->
        <script>
            // Poll for notifications every 30 seconds
            setInterval(loadNotifications, 30000);
            
            // Load notifications on page load
            document.addEventListener('DOMContentLoaded', loadNotifications);
        </script>
    <?php endif; ?>
</body>
</html>