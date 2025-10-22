<?php
$content = '
<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Học tập trực tuyến hiện đại</h1>
                <p class="lead mb-4">Nền tảng E-Learning được thiết kế để mang đến trải nghiệm học tập tốt nhất với giao diện thân thiện và tính năng đầy đủ.</p>
                <div class="d-flex gap-3">
                    <a href="/register" class="btn btn-light btn-lg">
                        <i class="fas fa-rocket me-2"></i>Bắt đầu ngay
                    </a>
                    <a href="/courses" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-book me-2"></i>Xem khóa học
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-graduation-cap fa-10x opacity-75"></i>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-5 fw-bold">Tại sao chọn chúng tôi?</h2>
                <p class="lead text-muted">Những tính năng nổi bật giúp bạn học tập hiệu quả</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                            <i class="fas fa-book fa-2x"></i>
                        </div>
                        <h5 class="card-title">Khóa học đa dạng</h5>
                        <p class="card-text text-muted">Nhiều môn học và chủ đề khác nhau, phù hợp với mọi nhu cầu học tập của bạn.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                            <i class="fas fa-chalkboard-teacher fa-2x"></i>
                        </div>
                        <h5 class="card-title">Giáo viên chuyên nghiệp</h5>
                        <p class="card-text text-muted">Đội ngũ giáo viên giàu kinh nghiệm và tận tâm trong việc giảng dạy.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                            <i class="fas fa-mobile-alt fa-2x"></i>
                        </div>
                        <h5 class="card-title">Học mọi lúc mọi nơi</h5>
                        <p class="card-text text-muted">Truy cập khóa học trên mọi thiết bị, học tập linh hoạt theo thời gian của bạn.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                            <i class="fas fa-tasks fa-2x"></i>
                        </div>
                        <h5 class="card-title">Bài tập & Kiểm tra</h5>
                        <p class="card-text text-muted">Hệ thống bài tập và kiểm tra trực tuyến giúp đánh giá tiến độ học tập.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                            <i class="fas fa-comments fa-2x"></i>
                        </div>
                        <h5 class="card-title">Diễn đàn thảo luận</h5>
                        <p class="card-text text-muted">Tương tác với giáo viên và bạn học thông qua diễn đàn thảo luận.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                        <h5 class="card-title">Theo dõi tiến độ</h5>
                        <p class="card-text text-muted">Theo dõi tiến độ học tập và nhận chứng chỉ hoàn thành khóa học.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="stat-item">
                    <h3 class="display-4 fw-bold text-primary">' . $stats['total_courses'] . '</h3>
                    <p class="text-muted mb-0">Khóa học</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="stat-item">
                    <h3 class="display-4 fw-bold text-success">' . $stats['total_teachers'] . '</h3>
                    <p class="text-muted mb-0">Giáo viên</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="stat-item">
                    <h3 class="display-4 fw-bold text-info">' . $stats['total_students'] . '</h3>
                    <p class="text-muted mb-0">Học sinh</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="stat-item">
                    <h3 class="display-4 fw-bold text-warning">100%</h3>
                    <p class="text-muted mb-0">Hài lòng</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Recent Courses Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-5 fw-bold">Khóa học nổi bật</h2>
                <p class="lead text-muted">Khám phá những khóa học mới nhất và phổ biến nhất</p>
            </div>
        </div>
        
        <div class="row g-4">
            ' . (empty($recent_courses) ? 
                '<div class="col-12 text-center"><p class="text-muted">Chưa có khóa học nào</p></div>' :
                implode('', array_map(function($course) {
                    return '
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            ' . ($course['thumbnail'] ? 
                                '<img src="' . htmlspecialchars($course['thumbnail']) . '" class="card-img-top" alt="' . htmlspecialchars($course['title']) . '" style="height: 200px; object-fit: cover;">' :
                                '<div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-book fa-3x text-muted"></i>
                                </div>'
                            ) . '
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">' . htmlspecialchars($course['title']) . '</h5>
                                <p class="card-text text-muted">' . Helpers::truncate(strip_tags($course['description']), 100) . '</p>
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>' . htmlspecialchars($course['teacher_name']) . '
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-tag me-1"></i>' . htmlspecialchars($course['subject']) . '
                                        </small>
                                    </div>
                                    <a href="/course?id=' . $course['id'] . '" class="btn btn-primary w-100">
                                        <i class="fas fa-eye me-2"></i>Xem chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>';
                }, $recent_courses))
            ) . '
        </div>
        
        <div class="text-center mt-5">
            <a href="/courses" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-book me-2"></i>Xem tất cả khóa học
            </a>
        </div>
    </div>
</section>

<style>
.feature-icon {
    width: 80px;
    height: 80px;
}

.stat-item {
    padding: 2rem 0;
}

.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card {
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}
</style>
';

include __DIR__ . '/../layouts/main.php';
?>