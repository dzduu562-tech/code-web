<?php $pageTitle = 'Chỉnh sửa bài học'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Chỉnh sửa bài học</h1>
    </div>
    
    <div class="form-container">
        <form action="/elearning/public/index.php?route=lessons/edit&id=<?php echo $lesson['id']; ?>" method="POST" class="form">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
            
            <div class="form-group">
                <label for="title">Tiêu đề bài học *</label>
                <input type="text" id="title" name="title" class="form-control" required value="<?php echo Helpers::escape($lesson['title']); ?>">
            </div>
            
            <div class="form-group">
                <label for="content_html">Nội dung</label>
                <textarea id="content_html" name="content_html" class="form-control" rows="10"><?php echo Helpers::escape($lesson['content_html'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="video_url">Video URL (YouTube embed)</label>
                <input type="url" id="video_url" name="video_url" class="form-control" value="<?php echo Helpers::escape($lesson['video_url'] ?? ''); ?>">
            </div>
            
            <div class="form-actions">
                <a href="/elearning/public/index.php?route=lesson&id=<?php echo $lesson['id']; ?>" class="btn btn-outline">Hủy</a>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
