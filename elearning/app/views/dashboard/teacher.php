<?php $pageTitle = 'Dashboard - Giáo viên'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Dashboard</h1>
        <p>Xin chào, Giáo viên <?php echo Helpers::escape(Auth::user()['name']); ?>! 👨‍🏫</p>
        <a href="/elearning/public/index.php?route=courses/create" class="btn btn-primary">+ Tạo khóa học mới</a>
    </div>
    
    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-value"><?php echo count($courses); ?></div>
            <div class="stat-label">Khóa học</div>
        </div>
        <div class="stat-box">
            <div class="stat-value"><?php 
                $totalStudents = 0;
                foreach ($courses as $c) {
                    $totalStudents += $c['student_count'] ?? 0;
                }
                echo $totalStudents;
            ?></div>
            <div class="stat-label">Học sinh</div>
        </div>
    </div>
    
    <div class="section">
        <h2>Khóa học của tôi</h2>
        
        <?php if (empty($courses)): ?>
            <div class="empty-state">
                <p>Bạn chưa tạo khóa học nào</p>
                <a href="/elearning/public/index.php?route=courses/create" class="btn btn-primary">Tạo khóa học đầu tiên</a>
            </div>
        <?php else: ?>
            <div class="courses-grid">
                <?php foreach ($courses as $course): ?>
                    <div class="course-card">
                        <div class="course-header">
                            <span class="course-subject"><?php echo Helpers::escape($course['subject'] ?? 'Khóa học'); ?></span>
                        </div>
                        <h3 class="course-title"><?php echo Helpers::escape($course['title']); ?></h3>
                        <p class="course-description"><?php echo Helpers::truncate(Helpers::escape($course['description'] ?? ''), 100); ?></p>
                        <div class="course-footer">
                            <span class="course-meta">👥 <?php echo $course['student_count'] ?? 0; ?> học sinh</span>
                            <div class="course-actions">
                                <a href="/elearning/public/index.php?route=course&id=<?php echo $course['id']; ?>" class="btn btn-sm">Xem</a>
                                <a href="/elearning/public/index.php?route=courses/edit&id=<?php echo $course['id']; ?>" class="btn btn-sm btn-outline">Sửa</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
