<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<!-- Hero Section -->
<section class="py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-6 text-white">
                <h1 class="display-4 fw-bold mb-4">
                    Học tập trực tuyến <br>hiện đại & hiệu quả
                </h1>
                <p class="lead mb-4">
                    Nền tảng E-Learning toàn diện dành cho giáo viên và học sinh. 
                    Tham gia ngay để trải nghiệm phương pháp học tập mới.
                </p>
                <div class="d-flex gap-3">
                    <?php if (!isLoggedIn()): ?>
                        <a href="<?= BASE_URL ?>/auth/register" class="btn btn-light btn-lg px-4">
                            <i class="bi bi-rocket-takeoff"></i> Bắt đầu ngay
                        </a>
                        <a href="<?= BASE_URL ?>/courses" class="btn btn-outline-light btn-lg px-4">
                            <i class="bi bi-book"></i> Khám phá khóa học
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/<?= $_SESSION['role'] ?>/dashboard" class="btn btn-light btn-lg px-4">
                            <i class="bi bi-speedometer2"></i> Vào Dashboard
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <div class="text-center">
                    <i class="bi bi-laptop text-white" style="font-size: 15rem; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="bi bi-people-fill text-primary" style="font-size: 3rem;"></i>
                        <h3 class="mt-3 mb-0 fw-bold"><?= number_format($stats['students']) ?></h3>
                        <p class="text-muted mb-0">Học sinh</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="bi bi-person-badge-fill text-success" style="font-size: 3rem;"></i>
                        <h3 class="mt-3 mb-0 fw-bold"><?= number_format($stats['teachers']) ?></h3>
                        <p class="text-muted mb-0">Giáo viên</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="bi bi-book-fill text-warning" style="font-size: 3rem;"></i>
                        <h3 class="mt-3 mb-0 fw-bold"><?= number_format($stats['courses']) ?></h3>
                        <p class="text-muted mb-0">Khóa học</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="bi bi-journal-text text-info" style="font-size: 3rem;"></i>
                        <h3 class="mt-3 mb-0 fw-bold"><?= number_format($stats['lessons']) ?></h3>
                        <p class="text-muted mb-0">Bài giảng</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Courses -->
<?php if (!empty($featured_courses)): ?>
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Khóa học nổi bật</h2>
            <p class="text-muted">Khám phá các khóa học được nhiều học sinh quan tâm</p>
        </div>
        
        <div class="row g-4">
            <?php foreach ($featured_courses as $course): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 hover-shadow">
                        <?php if ($course['thumbnail']): ?>
                            <img src="<?= BASE_URL . $course['thumbnail'] ?>" class="card-img-top" alt="<?= e($course['title']) ?>" style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-gradient text-white d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
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
                                <a href="<?= BASE_URL ?>/courses/detail/<?= $course['slug'] ?>" class="text-decoration-none text-dark">
                                    <?= e($course['title']) ?>
                                </a>
                            </h5>
                            
                            <p class="card-text text-muted small">
                                <?= mb_substr(strip_tags($course['description']), 0, 100) ?>...
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-clock"></i> <?= $course['duration_hours'] ?> giờ
                                </small>
                                <a href="<?= BASE_URL ?>/courses/<?= $course['slug'] ?>" class="btn btn-sm btn-outline-primary">
                                    Xem chi tiết <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="<?= BASE_URL ?>/courses" class="btn btn-primary btn-lg">
                Xem tất cả khóa học <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Tính năng nổi bật</h2>
            <p class="text-muted">Những gì chúng tôi cung cấp</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-film text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="fw-bold">Bài giảng đa dạng</h5>
                    <p class="text-muted">Video, PDF, slides và nhiều định dạng khác</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="text-center">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-patch-check text-success" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="fw-bold">Quiz & Kiểm tra</h5>
                    <p class="text-muted">Đánh giá năng lực trực tuyến với chấm điểm tự động</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="text-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-trophy text-warning" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="fw-bold">Gamification</h5>
                    <p class="text-muted">Huy hiệu, XP và bảng xếp hạng động lực</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="text-center">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-graph-up text-info" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="fw-bold">Theo dõi tiến độ</h5>
                    <p class="text-muted">Báo cáo chi tiết về quá trình học tập</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="text-center">
                    <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-chat-dots text-danger" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="fw-bold">Tương tác & Q&A</h5>
                    <p class="text-muted">Diễn đàn thảo luận và hỏi đáp</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="text-center">
                    <div class="bg-secondary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-phone text-secondary" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="fw-bold">Responsive</h5>
                    <p class="text-muted">Học mọi lúc mọi nơi trên mọi thiết bị</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.hover-shadow {
    transition: transform 0.3s, box-shadow 0.3s;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}
</style>

<?php require_once APP . '/views/layouts/footer.php'; ?>
