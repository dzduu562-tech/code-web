<?php $pageTitle = 'Trang chủ - E-Learning Platform'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Học tập trực tuyến hiện đại</h1>
            <p class="hero-subtitle">Nền tảng E-Learning dành cho nhà trường - Dễ sử dụng, hiệu quả, hoàn toàn miễn phí</p>
            <div class="hero-cta">
                <?php if (Auth::check()): ?>
                    <a href="/elearning/public/index.php?route=dashboard" class="btn btn-primary btn-lg">Vào học ngay</a>
                    <a href="/elearning/public/index.php?route=courses" class="btn btn-outline btn-lg">Khám phá khóa học</a>
                <?php else: ?>
                    <a href="/elearning/public/index.php?route=register" class="btn btn-primary btn-lg">Bắt đầu học</a>
                    <a href="/elearning/public/index.php?route=login" class="btn btn-outline btn-lg">Đăng nhập</a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="hero-stats">
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-number"><?php echo $stats['total_students']; ?></div>
                <div class="stat-label">Học sinh</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">👨‍🏫</div>
                <div class="stat-number"><?php echo $stats['total_teachers']; ?></div>
                <div class="stat-label">Giáo viên</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📖</div>
                <div class="stat-number"><?php echo $stats['total_courses']; ?></div>
                <div class="stat-label">Khóa học</div>
            </div>
        </div>
    </div>
</section>

<section class="features">
    <div class="container">
        <h2 class="section-title">Tính năng nổi bật</h2>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">📚</div>
                <h3>Quản lý khóa học</h3>
                <p>Tạo và quản lý khóa học, chương, bài học với giao diện đơn giản, trực quan</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">✅</div>
                <h3>Bài tập & Chấm điểm</h3>
                <p>Giao bài tập, học sinh nộp bài online, giáo viên chấm điểm và phản hồi nhanh chóng</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">📝</div>
                <h3>Quiz trắc nghiệm</h3>
                <p>Tạo bài kiểm tra trắc nghiệm, tự động chấm điểm và hiển thị kết quả</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">💬</div>
                <h3>Diễn đàn thảo luận</h3>
                <p>Học sinh đặt câu hỏi, giáo viên và bạn bè cùng thảo luận, hỗ trợ học tập</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Theo dõi tiến độ</h3>
                <p>Hệ thống tự động tính toán tiến độ học tập, giúp học sinh nắm rõ quá trình học</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🔔</div>
                <h3>Thông báo realtime</h3>
                <p>Nhận thông báo khi có bài mới, điểm số, phản hồi từ giáo viên</p>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($courses)): ?>
<section class="courses-preview">
    <div class="container">
        <h2 class="section-title">Khóa học nổi bật</h2>
        
        <div class="courses-grid">
            <?php foreach ($courses as $course): ?>
                <div class="course-card">
                    <div class="course-header">
                        <span class="course-subject"><?php echo Helpers::escape($course['subject'] ?? 'Khóa học'); ?></span>
                    </div>
                    <h3 class="course-title"><?php echo Helpers::escape($course['title']); ?></h3>
                    <p class="course-description"><?php echo Helpers::truncate(Helpers::escape($course['description'] ?? ''), 100); ?></p>
                    <div class="course-footer">
                        <div class="course-meta">
                            <span>👨‍🏫 <?php echo Helpers::escape($course['teacher_name']); ?></span>
                            <span>👥 <?php echo $course['student_count'] ?? 0; ?></span>
                        </div>
                        <a href="/elearning/public/index.php?route=course&id=<?php echo $course['id']; ?>" class="btn btn-sm">Xem chi tiết</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center" style="margin-top: 2rem;">
            <a href="/elearning/public/index.php?route=courses" class="btn btn-primary">Xem tất cả khóa học</a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
