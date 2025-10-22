<?php $pageTitle = 'Dashboard - Học sinh'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Dashboard</h1>
        <p>Xin chào, <?php echo Helpers::escape(Auth::user()['name']); ?>! 👋</p>
    </div>
    
    <div class="dashboard-grid">
        <div class="dashboard-section">
            <h2>Khóa học của tôi</h2>
            
            <?php if (empty($enrolledCourses)): ?>
                <div class="empty-state">
                    <p>Bạn chưa đăng ký khóa học nào</p>
                    <a href="/elearning/public/index.php?route=courses" class="btn btn-primary">Khám phá khóa học</a>
                </div>
            <?php else: ?>
                <div class="courses-list">
                    <?php foreach ($enrolledCourses as $course): ?>
                        <div class="course-item">
                            <div class="course-info">
                                <h3><a href="/elearning/public/index.php?route=course&id=<?php echo $course['id']; ?>"><?php echo Helpers::escape($course['title']); ?></a></h3>
                                <p class="text-muted">Giáo viên: <?php echo Helpers::escape($course['teacher_name']); ?></p>
                            </div>
                            <div class="course-progress">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo $course['progress_percent']; ?>%"></div>
                                </div>
                                <span class="progress-text"><?php echo round($course['progress_percent'], 1); ?>%</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="dashboard-sidebar">
            <div class="widget">
                <h3>Thông báo gần đây</h3>
                <?php if (empty($notifications)): ?>
                    <p class="text-muted">Không có thông báo mới</p>
                <?php else: ?>
                    <ul class="notification-items">
                        <?php foreach ($notifications as $notif): ?>
                            <li class="<?php echo $notif['is_read'] ? 'read' : 'unread'; ?>">
                                <strong><?php echo Helpers::escape($notif['title']); ?></strong>
                                <p><?php echo Helpers::escape($notif['message']); ?></p>
                                <small><?php echo Helpers::timeAgo($notif['created_at']); ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
