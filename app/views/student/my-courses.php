<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/student/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <h2 class="fw-bold mb-4">Khóa học của tôi</h2>

            <?php if (empty($courses)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 5rem;"></i>
                    <h4 class="mt-3 text-muted">Bạn chưa đăng ký khóa học nào</h4>
                    <a href="<?= BASE_URL ?>/courses" class="btn btn-primary mt-3">
                        Khám phá khóa học
                    </a>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($courses as $course): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <?php if ($course['thumbnail']): ?>
                                    <img src="<?= BASE_URL . $course['thumbnail'] ?>" class="card-img-top" style="height: 180px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-gradient-primary text-white d-flex align-items-center justify-content-center" style="height: 180px;">
                                        <i class="bi bi-book fs-1"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="card-body">
                                    <h5 class="card-title"><?= e($course['title']) ?></h5>
                                    <p class="text-muted small mb-3">
                                        <i class="bi bi-person"></i> <?= e($course['teacher_name']) ?>
                                    </p>
                                    
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small>Tiến độ</small>
                                            <small><?= round($course['progress']) ?>%</small>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar" style="width: <?= $course['progress'] ?>%"></div>
                                        </div>
                                    </div>
                                    
                                    <a href="<?= BASE_URL ?>/student/course/<?= $course['slug'] ?>" class="btn btn-primary w-100 mt-3">
                                        Tiếp tục học
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
