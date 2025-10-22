<?php $pageTitle = Helpers::escape($course['title']); ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="course-detail">
        <div class="course-detail-header">
            <div class="breadcrumb">
                <a href="/elearning/public/index.php?route=courses">Khóa học</a> / 
                <span><?php echo Helpers::escape($course['title']); ?></span>
            </div>
            
            <h1><?php echo Helpers::escape($course['title']); ?></h1>
            
            <div class="course-meta-info">
                <span class="badge"><?php echo Helpers::escape($course['subject'] ?? 'Khóa học'); ?></span>
                <span>👨‍🏫 <?php echo Helpers::escape($course['teacher_name']); ?></span>
                <span>📅 <?php echo Helpers::formatDate($course['created_at'], 'd/m/Y'); ?></span>
            </div>
            
            <p class="course-description-full"><?php echo nl2br(Helpers::escape($course['description'] ?? '')); ?></p>
            
            <?php if (Auth::isStudent()): ?>
                <?php if (!$isEnrolled): ?>
                    <form action="/elearning/public/index.php?route=courses/enroll" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
                        <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                        <button type="submit" class="btn btn-primary btn-lg">Đăng ký học</button>
                    </form>
                <?php else: ?>
                    <span class="badge badge-success">✅ Đã đăng ký</span>
                <?php endif; ?>
            <?php endif; ?>
            
            <?php if ((Auth::isTeacher() && $course['teacher_id'] == Auth::id()) || Auth::isAdmin()): ?>
                <div class="course-actions">
                    <a href="/elearning/public/index.php?route=courses/edit&id=<?php echo $course['id']; ?>" class="btn btn-outline">✏️ Chỉnh sửa</a>
                    <a href="/elearning/public/index.php?route=assignments/create&course_id=<?php echo $course['id']; ?>" class="btn btn-outline">+ Tạo bài tập</a>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="course-content">
            <h2>Nội dung khóa học</h2>
            
            <?php if (empty($chapters)): ?>
                <div class="empty-state">
                    <p>Chưa có nội dung nào</p>
                    <?php if ((Auth::isTeacher() && $course['teacher_id'] == Auth::id()) || Auth::isAdmin()): ?>
                        <p class="text-muted">Hãy thêm chương và bài học cho khóa học này</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="chapters-list">
                    <?php foreach ($chapters as $chapter): ?>
                        <div class="chapter-item">
                            <h3 class="chapter-title">📂 <?php echo Helpers::escape($chapter['title']); ?></h3>
                            
                            <?php if (!empty($chapter['lessons'])): ?>
                                <ul class="lessons-list">
                                    <?php foreach ($chapter['lessons'] as $lesson): ?>
                                        <li class="lesson-item">
                                            <a href="/elearning/public/index.php?route=lesson&id=<?php echo $lesson['id']; ?>">
                                                📄 <?php echo Helpers::escape($lesson['title']); ?>
                                            </a>
                                            <?php if ($lesson['video_url']): ?>
                                                <span class="lesson-badge">🎥 Video</span>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-muted">Chưa có bài học</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
