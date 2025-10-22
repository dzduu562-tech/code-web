<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    Học tập trực tuyến<br>
                    <span class="text-warning">hiện đại & hiệu quả</span>
                </h1>
                <p class="lead mb-4">
                    Khám phá hàng ngàn khóa học chất lượng cao từ các giảng viên hàng đầu. 
                    Học mọi lúc, mọi nơi với E-Learning Platform.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="<?= Helpers::url('courses') ?>" class="btn btn-warning btn-lg">
                        <i class="bi bi-play-circle me-2"></i>
                        Bắt đầu học ngay
                    </a>
                    <a href="<?= Helpers::url('about') ?>" class="btn btn-outline-light btn-lg">
                        Tìm hiểu thêm
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="hero-image">
                    <i class="bi bi-laptop display-1 text-warning opacity-75"></i>
                    <div class="floating-elements">
                        <div class="floating-card">
                            <i class="bi bi-book text-primary"></i>
                            <span>Khóa học đa dạng</span>
                        </div>
                        <div class="floating-card">
                            <i class="bi bi-people text-success"></i>
                            <span>Cộng đồng học tập</span>
                        </div>
                        <div class="floating-card">
                            <i class="bi bi-trophy text-warning"></i>
                            <span>Chứng chỉ uy tín</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-book-fill text-primary"></i>
                    </div>
                    <h3 class="stat-number"><?= number_format($stats['total_courses']) ?></h3>
                    <p class="stat-label">Khóa học</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-people-fill text-success"></i>
                    </div>
                    <h3 class="stat-number"><?= number_format($stats['total_students']) ?></h3>
                    <p class="stat-label">Học sinh</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-person-workspace text-info"></i>
                    </div>
                    <h3 class="stat-number"><?= number_format($stats['total_teachers']) ?></h3>
                    <p class="stat-label">Giảng viên</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Courses Section -->
<?php if (!empty($popular_courses)): ?>
<section class="courses-section py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title">Khóa học phổ biến</h2>
                <p class="section-subtitle text-muted">
                    Những khóa học được yêu thích nhất bởi cộng đồng học viên
                </p>
            </div>
        </div>
        
        <div class="row">
            <?php foreach (array_slice($popular_courses, 0, 6) as $course): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="course-card h-100">
                        <div class="course-card-header">
                            <div class="course-subject-badge">
                                <?= Helpers::escape($course['subject']) ?>
                            </div>
                            <div class="course-students">
                                <i class="bi bi-people"></i>
                                <?= $course['student_count'] ?> học viên
                            </div>
                        </div>
                        
                        <div class="course-card-body">
                            <h5 class="course-title">
                                <a href="<?= Helpers::url('course', ['id' => $course['id']]) ?>" 
                                   class="text-decoration-none">
                                    <?= Helpers::escape($course['title']) ?>
                                </a>
                            </h5>
                            
                            <p class="course-teacher text-muted">
                                <i class="bi bi-person-circle me-1"></i>
                                <?= Helpers::escape($course['teacher_name']) ?>
                            </p>
                            
                            <p class="course-description">
                                <?= Helpers::truncate(strip_tags($course['description']), 100) ?>
                            </p>
                        </div>
                        
                        <div class="course-card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <?= Helpers::timeAgo($course['created_at']) ?>
                                </small>
                                <a href="<?= Helpers::url('course', ['id' => $course['id']]) ?>" 
                                   class="btn btn-outline-primary btn-sm">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="<?= Helpers::url('courses') ?>" class="btn btn-primary btn-lg">
                Xem tất cả khóa học
                <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Features Section -->
<section class="features-section py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="section-title">Tại sao chọn chúng tôi?</h2>
                <p class="section-subtitle text-muted">
                    Những tính năng nổi bật giúp bạn học tập hiệu quả hơn
                </p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="bi bi-laptop text-primary"></i>
                    </div>
                    <h4>Học mọi lúc, mọi nơi</h4>
                    <p class="text-muted">
                        Truy cập khóa học trên mọi thiết bị, học tập linh hoạt theo thời gian của bạn.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="bi bi-people text-success"></i>
                    </div>
                    <h4>Cộng đồng hỗ trợ</h4>
                    <p class="text-muted">
                        Tham gia diễn đàn thảo luận, tương tác với giảng viên và học viên khác.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="bi bi-graph-up text-info"></i>
                    </div>
                    <h4>Theo dõi tiến độ</h4>
                    <p class="text-muted">
                        Theo dõi quá trình học tập, hoàn thành bài tập và kiểm tra kiến thức.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="bi bi-award text-warning"></i>
                    </div>
                    <h4>Chứng chỉ uy tín</h4>
                    <p class="text-muted">
                        Nhận chứng chỉ hoàn thành khóa học được công nhận rộng rãi.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="bi bi-headset text-danger"></i>
                    </div>
                    <h4>Hỗ trợ 24/7</h4>
                    <p class="text-muted">
                        Đội ngũ hỗ trợ kỹ thuật sẵn sàng giúp đỡ bạn mọi lúc, mọi nơi.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="bi bi-shield-check text-primary"></i>
                    </div>
                    <h4>Bảo mật cao</h4>
                    <p class="text-muted">
                        Thông tin cá nhân và tiến độ học tập được bảo vệ an toàn tuyệt đối.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Subjects Section -->
<?php if (!empty($subjects)): ?>
<section class="subjects-section py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title">Danh mục môn học</h2>
                <p class="section-subtitle text-muted">
                    Khám phá các lĩnh vực kiến thức đa dạng
                </p>
            </div>
        </div>
        
        <div class="row">
            <?php foreach ($subjects as $subject): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    <a href="<?= Helpers::url('courses', ['subject' => $subject]) ?>" 
                       class="subject-card text-decoration-none">
                        <div class="subject-card-inner">
                            <i class="bi bi-book subject-icon"></i>
                            <span class="subject-name"><?= Helpers::escape($subject) ?></span>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="cta-section py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="mb-3">Sẵn sàng bắt đầu hành trình học tập?</h2>
                <p class="lead mb-0">
                    Tham gia cùng hàng nghìn học viên đã tin tương và lựa chọn nền tảng của chúng tôi.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="<?= Helpers::url('register') ?>" class="btn btn-warning btn-lg">
                    <i class="bi bi-person-plus me-2"></i>
                    Đăng ký ngay
                </a>
            </div>
        </div>
    </div>
</section>