<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container py-5">
    <div class="row">
        <!-- Course Info -->
        <div class="col-lg-8 mb-4">
            <?php if ($course['thumbnail']): ?>
                <img src="<?= BASE_URL . $course['thumbnail'] ?>" class="img-fluid rounded mb-4" alt="<?= e($course['title']) ?>">
            <?php else: ?>
                <div class="bg-gradient-primary text-white rounded p-5 text-center mb-4">
                    <i class="bi bi-book" style="font-size: 5rem;"></i>
                </div>
            <?php endif; ?>
            
            <h1 class="fw-bold mb-3"><?= e($course['title']) ?></h1>
            
            <div class="d-flex align-items-center mb-4">
                <div class="me-4">
                    <i class="bi bi-person-circle"></i>
                    <strong>Giáo viên:</strong> <?= e($course['teacher_name']) ?>
                </div>
                <div class="me-4">
                    <i class="bi bi-clock"></i>
                    <?= $course['duration_hours'] ?> giờ
                </div>
                <div class="me-4">
                    <i class="bi bi-people"></i>
                    <?= $course['enrollment_count'] ?> học sinh
                </div>
                <div>
                    <span class="badge bg-primary"><?= ucfirst($course['level']) ?></span>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Mô tả khóa học</h5>
                    <div><?= nl2br(e($course['description'])) ?></div>
                </div>
            </div>
            
            <!-- Lessons -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">Nội dung khóa học</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($lessons)): ?>
                        <p class="text-muted">Chưa có bài giảng nào</p>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($lessons as $index => $lesson): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi bi-play-circle me-2"></i>
                                        <?= $index + 1 ?>. <?= e($lesson['title']) ?>
                                    </div>
                                    <div>
                                        <?php if ($lesson['is_preview']): ?>
                                            <span class="badge bg-success">Preview</span>
                                        <?php endif; ?>
                                        <?php if ($lesson['duration_minutes']): ?>
                                            <small class="text-muted"><?= $lesson['duration_minutes'] ?> phút</small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 80px;">
                <div class="card-body text-center">
                    <?php if ($is_enrolled): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> Bạn đã đăng ký khóa học này
                        </div>
                        <a href="<?= BASE_URL ?>/student/course/<?= $course['slug'] ?>" class="btn btn-primary btn-lg w-100 mb-2">
                            <i class="bi bi-play-circle"></i> Vào học
                        </a>
                    <?php else: ?>
                        <?php if (isLoggedIn() && hasRole('student')): ?>
                            <a href="<?= BASE_URL ?>/courses/enroll/<?= $course['id'] ?>" class="btn btn-primary btn-lg w-100 mb-3">
                                <i class="bi bi-plus-circle"></i> Tham gia khóa học
                            </a>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/auth/login" class="btn btn-primary btn-lg w-100 mb-3">
                                Đăng nhập để tham gia
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <hr>
                    
                    <div class="text-start">
                        <h6 class="fw-bold mb-3">Khóa học bao gồm:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success"></i>
                                <?= count($lessons) ?> bài giảng
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success"></i>
                                <?= $course['duration_hours'] ?> giờ nội dung video
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success"></i>
                                Truy cập trọn đời
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success"></i>
                                Chứng chỉ hoàn thành
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
