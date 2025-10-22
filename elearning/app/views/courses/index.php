<?php $pageTitle = 'Khóa học'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Khóa học</h1>
        
        <?php if (Auth::isTeacher() || Auth::isAdmin()): ?>
            <a href="/elearning/public/index.php?route=courses/create" class="btn btn-primary">+ Tạo khóa học</a>
        <?php endif; ?>
    </div>
    
    <div class="search-bar">
        <form action="/elearning/public/index.php" method="GET" class="search-form">
            <input type="hidden" name="route" value="courses">
            <input 
                type="text" 
                name="search" 
                class="search-input" 
                placeholder="Tìm kiếm khóa học..." 
                value="<?php echo Helpers::escape($_GET['search'] ?? ''); ?>"
            >
            <select name="subject" class="search-select">
                <option value="">Tất cả môn học</option>
                <option value="Công nghệ thông tin" <?php echo (($_GET['subject'] ?? '') === 'Công nghệ thông tin') ? 'selected' : ''; ?>>Công nghệ thông tin</option>
                <option value="Toán học" <?php echo (($_GET['subject'] ?? '') === 'Toán học') ? 'selected' : ''; ?>>Toán học</option>
                <option value="Ngoại ngữ" <?php echo (($_GET['subject'] ?? '') === 'Ngoại ngữ') ? 'selected' : ''; ?>>Ngoại ngữ</option>
            </select>
            <button type="submit" class="btn btn-primary">🔍 Tìm kiếm</button>
        </form>
    </div>
    
    <?php if (empty($courses)): ?>
        <div class="empty-state">
            <p>Không tìm thấy khóa học nào</p>
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
                        <div class="course-meta">
                            <span>👨‍🏫 <?php echo Helpers::escape($course['teacher_name']); ?></span>
                            <span>👥 <?php echo $course['student_count'] ?? 0; ?></span>
                        </div>
                        <a href="/elearning/public/index.php?route=course&id=<?php echo $course['id']; ?>" class="btn btn-sm">Xem chi tiết</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ($pagination['total_pages'] > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                    <a href="/elearning/public/index.php?route=courses&page=<?php echo $i; ?>" 
                       class="page-link <?php echo $i === $pagination['current_page'] ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
