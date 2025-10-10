<nav class="navbar navbar-expand-lg navbar-light sticky-top shadow-sm">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand fw-bold text-primary" href="<?= BASE_URL ?>">
            <i class="bi bi-mortarboard-fill"></i> <?= SITE_NAME ?>
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav Items -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/home">
                        <i class="bi bi-house"></i> Trang chủ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/courses">
                        <i class="bi bi-book"></i> Khóa học
                    </a>
                </li>
                <?php if (isLoggedIn()): ?>
                    <?php $user = currentUser(); ?>
                    <?php if ($user['role'] === 'student'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/student/my-courses">
                                <i class="bi bi-collection"></i> Khóa học của tôi
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">
                <!-- Theme Toggle -->
                <li class="nav-item me-2">
                    <button class="btn btn-link nav-link" id="theme-toggle" title="Chuyển đổi chế độ sáng/tối">
                        <i class="bi bi-moon-fill"></i>
                    </button>
                </li>

                <?php if (isLoggedIn()): ?>
                    <?php $user = currentUser(); ?>
                    
                    <!-- Notifications -->
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link position-relative" href="#" id="notificationDropdown" data-bs-toggle="dropdown">
                            <i class="bi bi-bell fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notification-count">
                                0
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" style="min-width: 300px;">
                            <li><h6 class="dropdown-header">Thông báo</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="px-3 py-2 text-muted text-center">
                                Không có thông báo mới
                            </li>
                        </ul>
                    </li>

                    <!-- User Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" data-bs-toggle="dropdown">
                            <img src="<?= $user['avatar'] ?: ASSETS_URL . '/images/default-avatar.png' ?>" 
                                 alt="Avatar" 
                                 class="rounded-circle me-2" 
                                 width="32" 
                                 height="32"
                                 style="object-fit: cover;">
                            <span><?= e($user['name']) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?= BASE_URL ?>/<?= $user['role'] ?>/dashboard">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= BASE_URL ?>/profile">
                                    <i class="bi bi-person"></i> Hồ sơ cá nhân
                                </a>
                            </li>
                            <?php if ($user['role'] === 'student'): ?>
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL ?>/student/progress">
                                        <i class="bi bi-graph-up"></i> Tiến độ học tập
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL ?>/student/badges">
                                        <i class="bi bi-award"></i> Huy hiệu
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <?php if ($user['role'] === 'admin'): ?>
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL ?>/admin/settings">
                                        <i class="bi bi-gear"></i> Cài đặt hệ thống
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li>
                                <a class="dropdown-item text-danger" href="<?= BASE_URL ?>/auth/logout">
                                    <i class="bi bi-box-arrow-right"></i> Đăng xuất
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/auth/login">
                            <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm ms-2" href="<?= BASE_URL ?>/auth/register">
                            Đăng ký
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Flash Messages -->
<?php
$flash = flash('login_success') ?: flash('logout_success') ?: flash('register_success') ?: flash('success') ?: flash('error');
if ($flash):
?>
<div class="container mt-3">
    <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
        <?= $flash['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>
