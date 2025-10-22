<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'E-Learning Platform' ?> - <?= APP_NAME ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/assets/css/main.css" rel="stylesheet">
    
    <meta name="description" content="Nền tảng học tập trực tuyến hiện đại, dễ sử dụng">
    <meta name="keywords" content="elearning, học trực tuyến, giáo dục, khóa học">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fas fa-graduation-cap me-2"></i>
                <?= APP_NAME ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/courses">Khóa học</a>
                    </li>
                    <?php if ($this->auth->check()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/dashboard">Bảng điều khiển</a>
                        </li>
                        <?php if ($this->auth->isTeacher()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/course/create">Tạo khóa học</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/assignments">Bài tập</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/forum">Diễn đàn</a>
                        </li>
                    <?php endif; ?>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if ($this->auth->check()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-bell"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notification-badge" style="display: none;">
                                    0
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" id="notifications-menu">
                                <li><h6 class="dropdown-header">Thông báo</h6></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/notifications">Xem tất cả thông báo</a></li>
                            </ul>
                        </li>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>
                                <?= htmlspecialchars($this->auth->user()['name']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/profile">Thông tin cá nhân</a></li>
                                <li><a class="dropdown-item" href="/change-password">Đổi mật khẩu</a></li>
                                <?php if ($this->auth->isAdmin()): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="/admin/dashboard">Quản trị</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/logout">Đăng xuất</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/login">Đăng nhập</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/register">Đăng ký</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <?php if (Helpers::hasFlash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?= Helpers::getFlash('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (Helpers::hasFlash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= Helpers::getFlash('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (Helpers::hasFlash('warning')): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?= Helpers::getFlash('warning') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (Helpers::hasFlash('info')): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                <?= Helpers::getFlash('info') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-graduation-cap me-2"></i><?= APP_NAME ?></h5>
                    <p class="mb-0">Nền tảng học tập trực tuyến hiện đại, dễ sử dụng</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="/assets/js/main.js"></script>
    
    <?php if ($this->auth->check()): ?>
    <script>
        // Load notifications
        function loadNotifications() {
            fetch('/api/notifications')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateNotificationBadge(data.unread_count);
                        updateNotificationMenu(data.notifications);
                    }
                })
                .catch(error => console.error('Error loading notifications:', error));
        }
        
        function updateNotificationBadge(count) {
            const badge = document.getElementById('notification-badge');
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'block';
            } else {
                badge.style.display = 'none';
            }
        }
        
        function updateNotificationMenu(notifications) {
            const menu = document.getElementById('notifications-menu');
            const items = menu.querySelectorAll('.notification-item');
            items.forEach(item => item.remove());
            
            if (notifications.length === 0) {
                const item = document.createElement('li');
                item.innerHTML = '<span class="dropdown-item-text text-muted">Không có thông báo mới</span>';
                menu.insertBefore(item, menu.querySelector('.dropdown-divider'));
            } else {
                notifications.forEach(notification => {
                    const item = document.createElement('li');
                    item.className = 'notification-item';
                    item.innerHTML = `
                        <a class="dropdown-item ${notification.is_read ? '' : 'fw-bold'}" href="/notifications">
                            <div class="d-flex justify-content-between">
                                <span>${notification.title}</span>
                                <small class="text-muted">${new Date(notification.created_at).toLocaleDateString('vi-VN')}</small>
                            </div>
                        </a>
                    `;
                    menu.insertBefore(item, menu.querySelector('.dropdown-divider'));
                });
            }
        }
        
        // Load notifications on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications();
            
            // Refresh notifications every 30 seconds
            setInterval(loadNotifications, 30000);
        });
    </script>
    <?php endif; ?>
</body>
</html>