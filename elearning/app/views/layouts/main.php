<?php use Core\Helpers; use Core\Auth; $csrf = Helpers::csrfToken(); $base = Helpers::baseUrl($this->config); ?>
<!doctype html>
<html lang="vi" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>E-Learning</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= $base ?>/../assets/css/main.css?v=1" rel="stylesheet">
</head>
<body class="bg-body text-body">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= $base ?>/index.php?route=/">E-Learning</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="<?= $base ?>/index.php?route=/courses">Khóa học</a></li>
        <?php if (Auth::check()): ?>
          <li class="nav-item"><a class="nav-link" href="<?= $base ?>/index.php?route=/dashboard">Bảng điều khiển</a></li>
        <?php endif; ?>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <a href="<?= $base ?>/index.php?route=/forum" class="btn btn-sm btn-outline-light position-relative">
          Forum
          <span id="notifBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none">0</span>
        </a>
        <button id="themeToggle" class="btn btn-sm btn-outline-light" aria-label="Toggle dark mode">🌓</button>
        <?php if (!Auth::check()): ?>
          <a class="btn btn-sm btn-light" href="<?= $base ?>/index.php?route=/login">Đăng nhập</a>
          <a class="btn btn-sm btn-warning" href="<?= $base ?>/index.php?route=/register">Đăng ký</a>
        <?php else: ?>
          <span class="text-white-50 small">Xin chào, <?= Helpers::e(Auth::user()['name']) ?></span>
          <a class="btn btn-sm btn-outline-light" href="<?= $base ?>/index.php?route=/logout">Thoát</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
<main class="container my-4">
  <?= $content ?>
</main>
<script>const BASE_URL='<?= $base ?>'; const CSRF_TOKEN='<?= $csrf ?>';</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $base ?>/../assets/js/main.js?v=1"></script>
</body>
</html>
