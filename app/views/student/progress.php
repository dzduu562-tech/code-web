<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/student/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <h2 class="fw-bold mb-4">Tiến độ học tập</h2>

            <!-- Overall Stats -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-journal-check text-success" style="font-size: 3rem;"></i>
                            <h3 class="mt-3 fw-bold"><?= $stats['completed_lessons'] ?? 0 ?></h3>
                            <p class="text-muted mb-0">Bài đã hoàn thành</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-clipboard-check text-primary" style="font-size: 3rem;"></i>
                            <h3 class="mt-3 fw-bold"><?= $stats['total_quizzes'] ?? 0 ?></h3>
                            <p class="text-muted mb-0">Quiz đã làm</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-graph-up text-warning" style="font-size: 3rem;"></i>
                            <h3 class="mt-3 fw-bold"><?= $stats['avg_score'] ?? 0 ?>%</h3>
                            <p class="text-muted mb-0">Điểm trung bình</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Progress -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">Tiến độ từng khóa học</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($courses)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                            <p class="text-muted mt-3">Chưa có dữ liệu tiến độ</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($courses as $course): ?>
                            <div class="mb-4 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0"><?= e($course['title']) ?></h6>
                                    <span class="badge bg-primary"><?= round($course['progress']) ?>%</span>
                                </div>
                                <div class="progress mb-2" style="height: 10px;">
                                    <div class="progress-bar" 
                                         role="progressbar" 
                                         style="width: <?= $course['progress'] ?>%"
                                         aria-valuenow="<?= $course['progress'] ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i> Đăng ký: <?= formatDate($course['enrolled_at'] ?? date('Y-m-d')) ?>
                                </small>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
