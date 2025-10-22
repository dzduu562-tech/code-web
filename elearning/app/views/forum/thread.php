<?php $pageTitle = Helpers::escape($thread['title']); ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="thread-detail">
        <div class="breadcrumb">
            <a href="/elearning/public/index.php?route=forum">Diễn đàn</a> / 
            <?php if ($thread['course_title']): ?>
                <a href="/elearning/public/index.php?route=forum&course_id=<?php echo $thread['course_id']; ?>"><?php echo Helpers::escape($thread['course_title']); ?></a> /
            <?php endif; ?>
            <span><?php echo Helpers::escape($thread['title']); ?></span>
        </div>
        
        <div class="thread-header">
            <h1><?php echo Helpers::escape($thread['title']); ?></h1>
            <div class="thread-info">
                <span>👤 <?php echo Helpers::escape($thread['author_name']); ?></span>
                <span class="badge badge-<?php echo $thread['author_role']; ?>"><?php 
                    $roles = ['admin' => 'Admin', 'teacher' => 'Giáo viên', 'student' => 'Học sinh'];
                    echo $roles[$thread['author_role']] ?? '';
                ?></span>
                <span>🕐 <?php echo Helpers::timeAgo($thread['created_at']); ?></span>
            </div>
        </div>
        
        <div class="posts-list">
            <?php foreach ($posts as $post): ?>
                <div class="post-item">
                    <div class="post-author">
                        <strong><?php echo Helpers::escape($post['author_name']); ?></strong>
                        <span class="badge badge-<?php echo $post['author_role']; ?>"><?php 
                            $roles = ['admin' => 'Admin', 'teacher' => 'Giáo viên', 'student' => 'Học sinh'];
                            echo $roles[$post['author_role']] ?? '';
                        ?></span>
                        <span class="post-time"><?php echo Helpers::timeAgo($post['created_at']); ?></span>
                    </div>
                    <div class="post-content">
                        <?php echo nl2br(Helpers::escape($post['content'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="reply-form">
            <h3>Trả lời</h3>
            <form action="/elearning/public/index.php?route=forum/reply" method="POST" class="form">
                <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
                <input type="hidden" name="thread_id" value="<?php echo $thread['id']; ?>">
                
                <div class="form-group">
                    <textarea name="content" class="form-control" rows="4" placeholder="Nhập câu trả lời..." required></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Gửi trả lời</button>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
