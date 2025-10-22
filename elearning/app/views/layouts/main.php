<?php
Auth::startSecureSession();
$config = require __DIR__ . '/../../../config/config.php';
$user = Auth::user();
$base = 'index.php';
?><!doctype html>
<html lang="vi" data-bs-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>E-Learning</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/main.css?v=1">
</head>
<body>
<nav class="navbar navbar-expand-lg border-bottom sticky-top bg-body">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="<?=$base?>">E-Learning</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="<?=$base?>?route=/courses">Khóa học</a></li>
        <li class="nav-item"><a class="nav-link" href="<?=$base?>?route=/forum">Diễn đàn</a></li>
        <?php if ($user && $user['role'] === 'teacher'): ?>
          <li class="nav-item"><a class="nav-link" href="<?=$base?>?route=/teacher/courses">Quản lý khóa</a></li>
          <li class="nav-item"><a class="nav-link" href="<?=$base?>?route=/assignments">Bài tập</a></li>
        <?php endif; ?>
        <?php if ($user && $user['role'] === 'admin'): ?>
          <li class="nav-item"><a class="nav-link" href="<?=$base?>?route=/admin">Quản trị</a></li>
        <?php endif; ?>
      </ul>
      <form class="d-flex me-3" role="search" method="get" action="<?=$base?>">
        <input type="hidden" name="route" value="/courses">
        <input class="form-control form-control-sm" name="q" type="search" placeholder="Tìm khóa học" aria-label="Search">
      </form>
      <div class="d-flex align-items-center gap-3">
        <button id="themeToggle" class="btn btn-sm btn-outline-secondary" aria-label="Toggle theme">🌓</button>
        <a class="position-relative" href="<?=$base?>?route=/notifications">
          <span class="badge text-bg-danger" id="notifBadge" style="display:none">0</span>
          🔔
        </a>
        <?php if ($user): ?>
          <a class="btn btn-outline-primary btn-sm" href="<?=$base?>?route=/dashboard">Bảng tin</a>
          <form class="d-inline" method="post" action="<?=$base?>?route=/logout">
            <input type="hidden" name="csrf" value="<?=Helpers::csrfToken()?>" />
            <button class="btn btn-link">Đăng xuất</button>
          </form>
        <?php else: ?>
          <a class="btn btn-primary btn-sm" href="<?=$base?>?route=/login">Đăng nhập</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<main class="container my-4">
  <?php if (!empty($_SESSION['flash_error'])): ?><div class="alert alert-danger" role="alert"><?=$_SESSION['flash_error']; unset($_SESSION['flash_error']);?></div><?php endif; ?>
  <?php if (!empty($_SESSION['flash_success'])): ?><div class="alert alert-success" role="alert"><?=$_SESSION['flash_success']; unset($_SESSION['flash_success']);?></div><?php endif; ?>
  <?=$content?>
</main>

<footer class="text-center small text-muted py-4 border-top">© <?=date('Y')?> E-Learning</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/main.js?v=1"></script>
</body>
</html>
