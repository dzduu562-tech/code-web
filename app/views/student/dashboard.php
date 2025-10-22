<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/student/sidebar.php'; ?>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <!-- Welcome Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Xin chào, <?= e($user['full_name'] ?? 'Học sinh') ?>! 👋</h2>
                    <p class="text-muted mb-0">Chào mừng trở lại với hệ thống học tập</p>
                </div>
                <div class="text-end">
                    <div class="badge bg-primary fs-6 px-3 py-2">
                        <i class="bi bi-star-fill"></i> Level <?= isset($user['profile']['level']) ? $user['profile']['level'] : 1 ?>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 rounded p-3">
                                        <i class="bi bi-book-fill text-primary fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-0 fw-bold"><?= $stats['total_courses'] ?></h3>
                                    <small class="text-muted">Khóa học</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-opacity-10 rounded p-3">
                                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-0 fw-bold"><?= $stats['completed_courses'] ?></h3>
                                    <small class="text-muted">Hoàn thành</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning bg-opacity-10 rounded p-3">
                                        <i class="bi bi-lightning-fill text-warning fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-0 fw-bold"><?= number_format($stats['total_xp']) ?></h3>
                                    <small class="text-muted">Điểm XP</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-info bg-opacity-10 rounded p-3">
                                        <i class="bi bi-award-fill text-info fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-0 fw-bold"><?= $stats['total_badges'] ?></h3>
                                    <small class="text-muted">Huy hiệu</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- My Courses -->
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">Khóa học của tôi</h5>
                                <a href="<?= BASE_URL ?>/student/my-courses" class="btn btn-sm btn-outline-primary">
                                    Xem tất cả <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (empty($my_courses)): ?>
                                <div class="text-center py-5">
                                    <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                                    <p class="text-muted mt-3">Bạn chưa đăng ký khóa học nào</p>
                                    <a href="<?= BASE_URL ?>/courses" class="btn btn-primary">
                                        Khám phá khóa học
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach (array_slice($my_courses, 0, 5) as $course): ?>
                                        <a href="<?= BASE_URL ?>/student/course/<?= $course['slug'] ?>" 
                                           class="list-group-item list-group-item-action border-0 px-0">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <?php if ($course['thumbnail']): ?>
                                                        <img src="<?= BASE_URL . $course['thumbnail'] ?>" 
                                                             alt="<?= e($course['title']) ?>" 
                                                             class="rounded" 
                                                             width="60" 
                                                             height="60"
                                                             style="object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center" 
                                                             style="width: 60px; height: 60px;">
                                                            <i class="bi bi-book text-primary fs-4"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1"><?= e($course['title']) ?></h6>
                                                    <small class="text-muted">
                                                        <i class="bi bi-person"></i> <?= e($course['teacher_name']) ?>
                                                    </small>
                                                    <div class="progress mt-2" style="height: 5px;">
                                                        <div class="progress-bar" 
                                                             role="progressbar" 
                                                             style="width: <?= $course['progress'] ?>%"></div>
                                                    </div>
                                                </div>
                                                <div class="text-end ms-3">
                                                    <div class="fw-bold text-primary"><?= round($course['progress']) ?>%</div>
                                                    <small class="text-muted">Hoàn thành</small>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Notifications & Activities -->
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold">Thông báo</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($notifications)): ?>
                                <div class="text-center py-4">
                                    <i class="bi bi-bell-slash text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-2 mb-0">Không có thông báo mới</p>
                                </div>
                            <?php else: ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($notifications as $notif): ?>
                                        <div class="list-group-item border-0 px-0">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <i class="bi bi-bell-fill text-<?= $notif['type'] ?>"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <h6 class="mb-1 small"><?= e($notif['title']) ?></h6>
                                                    <p class="mb-1 small text-muted"><?= e($notif['message']) ?></p>
                                                    <small class="text-muted"><?= timeAgo($notif['created_at']) ?></small>
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
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
