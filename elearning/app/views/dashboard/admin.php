<?php $pageTitle = 'Dashboard - Admin'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Admin Dashboard</h1>
        <p>Quản trị hệ thống</p>
    </div>
    
    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-icon">👥</div>
            <div class="stat-value"><?php echo $stats['total_users']; ?></div>
            <div class="stat-label">Tổng người dùng</div>
        </div>
        <div class="stat-box">
            <div class="stat-icon">👨‍🎓</div>
            <div class="stat-value"><?php echo $stats['total_students']; ?></div>
            <div class="stat-label">Học sinh</div>
        </div>
        <div class="stat-box">
            <div class="stat-icon">👨‍🏫</div>
            <div class="stat-value"><?php echo $stats['total_teachers']; ?></div>
            <div class="stat-label">Giáo viên</div>
        </div>
        <div class="stat-box">
            <div class="stat-icon">📚</div>
            <div class="stat-value"><?php echo $stats['total_courses']; ?></div>
            <div class="stat-label">Khóa học</div>
        </div>
    </div>
    
    <div class="admin-actions">
        <a href="/elearning/public/index.php?route=admin/users" class="btn btn-primary">Quản lý người dùng</a>
        <a href="/elearning/public/index.php?route=admin/courses" class="btn btn-primary">Quản lý khóa học</a>
        <a href="/elearning/public/index.php?route=courses/create" class="btn btn-outline">Tạo khóa học mới</a>
    </div>
    
    <div class="section">
        <h2>Khóa học gần đây</h2>
        
        <?php if (empty($recentCourses)): ?>
            <p class="text-muted">Chưa có khóa học nào</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tên khóa học</th>
                            <th>Môn học</th>
                            <th>Giáo viên</th>
                            <th>Học sinh</th>
                            <th>Ngày tạo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentCourses as $course): ?>
                            <tr>
                                <td><a href="/elearning/public/index.php?route=course&id=<?php echo $course['id']; ?>"><?php echo Helpers::escape($course['title']); ?></a></td>
                                <td><?php echo Helpers::escape($course['subject'] ?? '-'); ?></td>
                                <td><?php echo Helpers::escape($course['teacher_name']); ?></td>
                                <td><?php echo $course['student_count'] ?? 0; ?></td>
                                <td><?php echo Helpers::formatDate($course['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
