<?php $pageTitle = 'Tạo bài học mới'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Tạo bài học mới</h1>
        <p>Chương: <?php echo Helpers::escape($chapter['title']); ?></p>
    </div>
    
    <div class="form-container">
        <form action="/elearning/public/index.php?route=lessons/create&chapter_id=<?php echo $chapter['id']; ?>" method="POST" class="form">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
            
            <div class="form-group">
                <label for="title">Tiêu đề bài học *</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="content_html">Nội dung</label>
                <textarea id="content_html" name="content_html" class="form-control" rows="10"></textarea>
            </div>
            
            <div class="form-group">
                <label for="video_url">Video URL (YouTube embed)</label>
                <input type="url" id="video_url" name="video_url" class="form-control" placeholder="https://www.youtube.com/embed/...">
            </div>
            
            <div class="form-group">
                <label for="position">Thứ tự</label>
                <input type="number" id="position" name="position" class="form-control" value="0" min="0">
            </div>
            
            <div class="form-actions">
                <a href="/elearning/public/index.php?route=course&id=<?php echo $chapter['course_id']; ?>" class="btn btn-outline">Hủy</a>
                <button type="submit" class="btn btn-primary">Tạo bài học</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
