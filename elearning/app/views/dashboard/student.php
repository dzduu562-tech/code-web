<!-- Student Dashboard -->
<div class="container-fluid py-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card bg-gradient-primary text-white rounded-4 p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-2">Chào mừng trở lại, <?= Helpers::escape(Auth::getInstance()->name()) ?>! 👋</h2>
                        <p class="mb-0 opacity-90">Hãy tiếp tục hành trình học tập của bạn</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="welcome-stats">
                            <div class="stat-item">
                                <div class="stat-number"><?= number_format($stats['avg_progress'], 1) ?>%</div>
                                <div class="stat-label">Tiến độ trung bình</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="dashboard-card bg-gradient-success text-white">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="dashboard-stat-number"><?= $stats['enrolled_courses'] ?></div>
                        <div class="dashboard-stat-label">Khóa học đã đăng ký</div>
                    </div>
                    <div class="dashboard-stat-icon">
                        <i class="bi bi-book-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="dashboard-card bg-gradient-warning text-white">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="dashboard-stat-number"><?= $stats['completed_courses'] ?></div>
                        <div class="dashboard-stat-label">Khóa học hoàn thành</div>
                    </div>
                    <div class="dashboard-stat-icon">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="dashboard-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="dashboard-stat-number"><?= $stats['total_assignments'] ?></div>
                        <div class="dashboard-stat-label">Tổng bài tập</div>
                    </div>
                    <div class="dashboard-stat-icon">
                        <i class="bi bi-file-text-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="dashboard-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="dashboard-stat-number"><?= $stats['completed_assignments'] ?></div>
                        <div class="dashboard-stat-label">Bài tập đã nộp</div>
                    </div>
                    <div class="dashboard-stat-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Enrolled Courses -->
        <div class="col-lg-8 mb-4">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Khóa học của tôi</h5>
                    <a href="<?= Helpers::url('courses') ?>" class="btn btn-outline-primary btn-sm">
                        Xem tất cả
                    </a>
                </div>
                
                <?php if (empty($enrolled_courses)): ?>
                    <div class="text-center py-4">
                        <i class="bi bi-book display-4 text-muted mb-3"></i>
                        <p class="text-muted">Bạn chưa đăng ký khóa học nào</p>
                        <a href="<?= Helpers::url('courses') ?>" class="btn btn-primary">
                            Khám phá khóa học
                        </a>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach (array_slice($enrolled_courses, 0, 4) as $course): ?>
                            <div class="col-md-6 mb-3">
                                <div class="course-card-mini">
                                    <div class="d-flex align-items-center">
                                        <div class="course-thumbnail me-3">
                                            <div class="bg-primary rounded text-white d-flex align-items-center justify-content-center" 
                                                 style="width: 60px; height: 60px;">
                                                <i class="bi bi-book"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <a href="<?= Helpers::url('course', ['id' => $course['id']]) ?>" 
                                                   class="text-decoration-none">
                                                    <?= Helpers::truncate($course['title'], 40) ?>
                                                </a>
                                            </h6>
                                            <small class="text-muted"><?= Helpers::escape($course['teacher_name']) ?></small>
                                            <div class="progress mt-2" style="height: 6px;">
                                                <div class="progress-bar" 
                                                     style="width: <?= $course['progress_percent'] ?>%"
                                                     data-width="<?= $course['progress_percent'] ?>%"></div>
                                            </div>
                                            <small class="text-muted"><?= number_format($course['progress_percent'], 1) ?>% hoàn thành</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Upcoming Assignments -->
        <div class="col-lg-4 mb-4">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Bài tập sắp tới</h5>
                    <a href="<?= Helpers::url('assignments') ?>" class="btn btn-outline-primary btn-sm">
                        Xem tất cả
                    </a>
                </div>
                
                <?php if (empty($upcoming_assignments)): ?>
                    <div class="text-center py-3">
                        <i class="bi bi-check-circle text-success display-6"></i>
                        <p class="text-muted mb-0 mt-2">Không có bài tập nào sắp tới</p>
                    </div>
                <?php else: ?>
                    <div class="assignment-list">
                        <?php foreach ($upcoming_assignments as $assignment): ?>
                            <div class="assignment-item">
                                <div class="d-flex align-items-start">
                                    <div class="assignment-icon me-3">
                                        <i class="bi bi-file-text text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <a href="<?= Helpers::url('assignment', ['id' => $assignment['id']]) ?>" 
                                               class="text-decoration-none">
                                                <?= Helpers::truncate($assignment['title'], 30) ?>
                                            </a>
                                        </h6>
                                        <small class="text-muted d-block"><?= Helpers::escape($assignment['course_title']) ?></small>
                                        <?php if ($assignment['due_at']): ?>
                                            <small class="text-warning">
                                                <i class="bi bi-clock me-1"></i>
                                                <?= Helpers::formatDate($assignment['due_at'], 'd/m H:i') ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Activity -->
        <div class="col-lg-6 mb-4">
            <div class="dashboard-card">
                <h5 class="mb-3">Hoạt động gần đây</h5>
                
                <?php if (empty($recent_activity)): ?>
                    <div class="text-center py-3">
                        <i class="bi bi-clock-history text-muted display-6"></i>
                        <p class="text-muted mb-0 mt-2">Chưa có hoạt động nào</p>
                    </div>
                <?php else: ?>
                    <div class="activity-list">
                        <?php foreach ($recent_activity as $activity): ?>
                            <div class="activity-item">
                                <div class="d-flex align-items-start">
                                    <div class="activity-icon me-3">
                                        <?php
                                        $iconClass = match($activity['type']) {
                                            'enrollment' => 'bi-book text-primary',
                                            'lesson_completed' => 'bi-check-circle text-success',
                                            'quiz_attempt' => 'bi-question-circle text-info',
                                            default => 'bi-circle text-muted'
                                        };
                                        ?>
                                        <i class="bi <?= $iconClass ?>"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="activity-content">
                                            <?php
                                            $message = match($activity['type']) {
                                                'enrollment' => "Đăng ký khóa học: {$activity['title']}",
                                                'lesson_completed' => "Hoàn thành bài học: {$activity['title']}",
                                                'quiz_attempt' => "Làm bài kiểm tra: {$activity['title']}" . 
                                                    (isset($activity['score']) ? " (Điểm: {$activity['score']})" : ""),
                                                default => $activity['title']
                                            };
                                            ?>
                                            <p class="mb-1"><?= Helpers::escape($message) ?></p>
                                            <small class="text-muted"><?= Helpers::timeAgo($activity['created_at']) ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Notifications -->
        <div class="col-lg-6 mb-4">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Thông báo mới</h5>
                    <a href="<?= Helpers::url('notifications') ?>" class="btn btn-outline-primary btn-sm">
                        Xem tất cả
                    </a>
                </div>
                
                <?php if (empty($notifications)): ?>
                    <div class="text-center py-3">
                        <i class="bi bi-bell text-muted display-6"></i>
                        <p class="text-muted mb-0 mt-2">Không có thông báo mới</p>
                    </div>
                <?php else: ?>
                    <div class="notification-list">
                        <?php foreach ($notifications as $notification): ?>
                            <div class="notification-item <?= !$notification['is_read'] ? 'unread' : '' ?>">
                                <div class="d-flex align-items-start">
                                    <div class="notification-icon me-3">
                                        <?php
                                        $iconClass = match($notification['type']) {
                                            'new_lesson' => 'bi-book text-primary',
                                            'assignment_graded' => 'bi-check-circle text-success',
                                            'forum_reply' => 'bi-chat-dots text-info',
                                            'new_assignment' => 'bi-file-text text-warning',
                                            default => 'bi-bell text-muted'
                                        };
                                        ?>
                                        <i class="bi <?= $iconClass ?>"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?= Helpers::escape($notification['title']) ?></h6>
                                        <p class="mb-1 small text-muted"><?= Helpers::escape($notification['message']) ?></p>
                                        <small class="text-muted"><?= Helpers::timeAgo($notification['created_at']) ?></small>
                                        <?php if (!$notification['is_read']): ?>
                                            <span class="badge bg-primary ms-2">Mới</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.welcome-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.welcome-stats .stat-item {
    text-align: center;
}

.welcome-stats .stat-number {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
}

.welcome-stats .stat-label {
    font-size: 0.875rem;
    opacity: 0.9;
}

.dashboard-card {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.dashboard-stat-icon {
    font-size: 2.5rem;
    opacity: 0.8;
}

.course-card-mini {
    background: var(--bg-secondary);
    border-radius: 0.75rem;
    padding: 1rem;
    transition: var(--transition);
}

.course-card-mini:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}

.assignment-list .assignment-item,
.activity-list .activity-item,
.notification-list .notification-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-light);
}

.assignment-list .assignment-item:last-child,
.activity-list .activity-item:last-child,
.notification-list .notification-item:last-child {
    border-bottom: none;
}

.notification-item.unread {
    background-color: rgba(var(--bs-primary-rgb), 0.1);
    border-radius: 0.5rem;
    margin: 0 -0.5rem;
    padding: 0.75rem 0.5rem;
}
</style>