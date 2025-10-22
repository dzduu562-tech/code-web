<?php $pageTitle = Helpers::escape($lesson['title']); ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="lesson-detail">
        <div class="breadcrumb">
            <a href="/elearning/public/index.php?route=courses">Khóa học</a> / 
            <a href="/elearning/public/index.php?route=course&id=<?php echo $lesson['course_id']; ?>"><?php echo Helpers::escape($course['title']); ?></a> /
            <span><?php echo Helpers::escape($lesson['title']); ?></span>
        </div>
        
        <div class="lesson-header">
            <h1><?php echo Helpers::escape($lesson['title']); ?></h1>
            <p class="text-muted">Chương: <?php echo Helpers::escape($lesson['chapter_title']); ?></p>
            
            <?php if (Auth::isStudent()): ?>
                <?php if ($isCompleted): ?>
                    <span class="badge badge-success">✅ Đã hoàn thành</span>
                <?php else: ?>
                    <button class="btn btn-primary" id="markCompleteBtn" data-lesson-id="<?php echo $lesson['id']; ?>">
                        Đánh dấu đã hoàn thành
                    </button>
                <?php endif; ?>
            <?php endif; ?>
            
            <?php if ((Auth::isTeacher() && $course['teacher_id'] == Auth::id()) || Auth::isAdmin()): ?>
                <a href="/elearning/public/index.php?route=lessons/edit&id=<?php echo $lesson['id']; ?>" class="btn btn-outline">✏️ Chỉnh sửa</a>
            <?php endif; ?>
        </div>
        
        <?php if ($lesson['video_url']): ?>
            <div class="lesson-video">
                <iframe 
                    width="100%" 
                    height="500" 
                    src="<?php echo Helpers::escape($lesson['video_url']); ?>" 
                    frameborder="0" 
                    allowfullscreen>
                </iframe>
            </div>
        <?php endif; ?>
        
        <div class="lesson-content">
            <?php echo $lesson['content_html']; ?>
        </div>
        
        <?php if (!empty($resources)): ?>
            <div class="lesson-resources">
                <h3>Tài liệu đính kèm</h3>
                <ul class="resources-list">
                    <?php foreach ($resources as $resource): ?>
                        <li>
                            <a href="/elearning/public/<?php echo Helpers::escape($resource['file_path']); ?>" download>
                                📎 <?php echo Helpers::escape($resource['file_name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($quizzes)): ?>
            <div class="lesson-quizzes">
                <h3>Bài kiểm tra</h3>
                <?php foreach ($quizzes as $quiz): ?>
                    <div class="quiz-card">
                        <h4><?php echo Helpers::escape($quiz['title']); ?></h4>
                        <p><?php echo Helpers::escape($quiz['description'] ?? ''); ?></p>
                        <a href="/elearning/public/index.php?route=quiz&id=<?php echo $quiz['id']; ?>" class="btn btn-primary">Làm bài</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div class="lesson-discussion">
            <h3>Thảo luận</h3>
            
            <?php if (empty($threads)): ?>
                <p class="text-muted">Chưa có thảo luận nào</p>
            <?php else: ?>
                <div class="threads-list">
                    <?php foreach ($threads as $thread): ?>
                        <div class="thread-item">
                            <a href="/elearning/public/index.php?route=forum/thread&id=<?php echo $thread['id']; ?>">
                                <?php echo Helpers::escape($thread['title']); ?>
                            </a>
                            <span class="thread-meta">
                                <?php echo Helpers::escape($thread['author_name']); ?> - 
                                <?php echo $thread['post_count'] ?? 0; ?> phản hồi
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <a href="/elearning/public/index.php?route=forum/create&course_id=<?php echo $lesson['course_id']; ?>&lesson_id=<?php echo $lesson['id']; ?>" class="btn btn-outline">
                + Đặt câu hỏi
            </a>
        </div>
    </div>
</div>

<script>
document.getElementById('markCompleteBtn')?.addEventListener('click', function() {
    const lessonId = this.getAttribute('data-lesson-id');
    const formData = new FormData();
    formData.append('lesson_id', lessonId);
    
    fetch('/elearning/public/index.php?route=lessons/complete', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
