<?php $pageTitle = 'Diễn đàn'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Diễn đàn thảo luận</h1>
        <?php if (isset($course)): ?>
            <p>Khóa học: <?php echo Helpers::escape($course['title']); ?></p>
            <a href="/elearning/public/index.php?route=forum/create&course_id=<?php echo $course['id']; ?>" class="btn btn-primary">+ Đặt câu hỏi mới</a>
        <?php endif; ?>
    </div>
    
    <?php if (empty($threads)): ?>
        <div class="empty-state">
            <p>Chưa có thảo luận nào</p>
        </div>
    <?php else: ?>
        <div class="threads-list">
            <?php foreach ($threads as $thread): ?>
                <div class="thread-card">
                    <div class="thread-header">
                        <h3><a href="/elearning/public/index.php?route=forum/thread&id=<?php echo $thread['id']; ?>"><?php echo Helpers::escape($thread['title']); ?></a></h3>
                        <span class="thread-course"><?php echo Helpers::escape($thread['course_title'] ?? ''); ?></span>
                    </div>
                    <div class="thread-meta">
                        <span>👤 <?php echo Helpers::escape($thread['author_name']); ?></span>
                        <span>💬 <?php echo $thread['post_count'] ?? 0; ?> phản hồi</span>
                        <span>🕐 <?php echo Helpers::timeAgo($thread['updated_at']); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
