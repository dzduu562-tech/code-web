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
</head>
<body class="auth-body">
    <div class="auth-container">
        <!-- Header -->
        <div class="auth-header text-center mb-4">
            <a href="<?= Helpers::url('home') ?>" class="text-decoration-none">
                <h2 class="text-primary mb-0">
                    <i class="bi bi-mortarboard-fill me-2"></i>
                    E-Learning Platform
                </h2>
            </a>
        </div>

        <!-- Main Auth Content -->
        <div class="auth-content">
            <?php
            // Include the specific view
            $viewFile = __DIR__ . "/../{$view}.php";
            if (file_exists($viewFile)) {
                include $viewFile;
            } else {
                include __DIR__ . '/../errors/404.php';
            }
            ?>
        </div>

        <!-- Footer -->
        <div class="auth-footer text-center mt-4">
            <div class="d-flex justify-content-center gap-3 mb-3">
                <a href="<?= Helpers::url('about') ?>" class="text-muted text-decoration-none small">Giới thiệu</a>
                <a href="<?= Helpers::url('privacy') ?>" class="text-muted text-decoration-none small">Bảo mật</a>
                <a href="<?= Helpers::url('contact') ?>" class="text-muted text-decoration-none small">Liên hệ</a>
            </div>
            <p class="text-muted small mb-0">
                &copy; <?= date('Y') ?> E-Learning Platform. All rights reserved.
            </p>
        </div>
    </div>

    <!-- Theme Toggle -->
    <button class="btn btn-link position-fixed top-0 end-0 m-3 text-muted" 
            id="theme-toggle" title="Chuyển đổi theme">
        <i class="bi bi-sun-fill" id="theme-icon"></i>
    </button>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= Helpers::asset('js/main.js') ?>"></script>
</body>
</html>