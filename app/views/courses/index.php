<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold mb-3">Khóa học</h1>
        <p class="text-muted">Khám phá các khóa học phong phú và chất lượng</p>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Tìm kiếm khóa học...">
            </div>
        </div>
        <div class="col-md-4">
            <select class="form-select">
                <option value="">Tất cả môn học</option>
                <option>Toán học</option>
                <option>Tiếng Anh</option>
                <option>Vật lý</option>
                <option>Hóa học</option>
            </select>
        </div>
    </div>

    <!-- Courses Grid -->
    <?php if (empty($courses)): ?>
        <div class="text-center py-5">
            <i class="bi bi-inbox text-muted" style="font-size: 5rem;"></i>
            <h4 class="mt-3 text-muted">Chưa có khóa học nào</h4>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($courses as $course): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <?php if ($course['thumbnail']): ?>
                            <img src="<?= BASE_URL . $course['thumbnail'] ?>" class="card-img-top" alt="<?= e($course['title']) ?>" style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-gradient-primary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="bi bi-book" style="font-size: 4rem;"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-primary"><?= ucfirst($course['level']) ?></span>
                                <small class="text-muted">
                                    <i class="bi bi-people"></i> <?= $course['enrollment_count'] ?? 0 ?>
                                </small>
                            </div>
                            
                            <h5 class="card-title">
                                <a href="<?= BASE_URL ?>/courses/<?= $course['slug'] ?>" class="text-decoration-none text-dark">
                                    <?= e($course['title']) ?>
                                </a>
                            </h5>
                            
                            <p class="card-text text-muted small">
                                <?= mb_substr(strip_tags($course['description']), 0, 100) ?>...
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <small class="text-muted">
                                        <i class="bi bi-person"></i> <?= e($course['teacher_name']) ?>
                                    </small>
                                </div>
                                <div>
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> <?= $course['duration_hours'] ?>h
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-white border-0">
                            <a href="<?= BASE_URL ?>/courses/<?= $course['slug'] ?>" class="btn btn-primary w-100">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
