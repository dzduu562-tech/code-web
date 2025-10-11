<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/teacher/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Khóa học của tôi</h2>
                <a href="<?= BASE_URL ?>/teacher/createCourse" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tạo khóa học mới
                </a>
            </div>

            <?php if (empty($courses)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 5rem;"></i>
                    <h4 class="mt-3 text-muted">Chưa có khóa học nào</h4>
                    <p class="text-muted">Tạo khóa học đầu tiên của bạn!</p>
                    <a href="<?= BASE_URL ?>/teacher/createCourse" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle"></i> Tạo khóa học
                    </a>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($courses as $course): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <?php if ($course['thumbnail']): ?>
                                    <img src="<?= BASE_URL . $course['thumbnail'] ?>" 
                                         class="card-img-top" 
                                         style="height: 180px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-gradient-primary text-white d-flex align-items-center justify-content-center" 
                                         style="height: 180px;">
                                        <i class="bi bi-book" style="font-size: 3rem;"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <?php if ($course['is_published']): ?>
                                            <span class="badge bg-success">Đã xuất bản</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Nháp</span>
                                        <?php endif; ?>
                                        <small class="text-muted">
                                            <i class="bi bi-people"></i> <?= $course['total_students'] ?? 0 ?>
                                        </small>
                                    </div>
                                    
                                    <h5 class="card-title"><?= e($course['title']) ?></h5>
                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-tag"></i> <?= e($course['subject_name'] ?? 'Chưa phân loại') ?>
                                    </p>
                                    
                                    <div class="d-flex justify-content-between text-muted small">
                                        <span><i class="bi bi-clock"></i> <?= $course['duration_hours'] ?>h</span>
                                        <span><?= formatDate($course['created_at']) ?></span>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-white border-0">
                                    <div class="d-grid gap-2">
                                        <a href="<?= BASE_URL ?>/teacher/manageCourse/<?= $course['id'] ?>" 
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-gear"></i> Quản lý
                                        </a>
                                    </div>
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
