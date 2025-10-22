<?php $pageTitle = 'Đặt câu hỏi mới'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Đặt câu hỏi mới</h1>
        <?php if (isset($course)): ?>
            <p>Khóa học: <?php echo Helpers::escape($course['title']); ?></p>
        <?php endif; ?>
    </div>
    
    <div class="form-container">
        <form action="/elearning/public/index.php?route=forum/create&course_id=<?php echo $_GET['course_id']; ?>&lesson_id=<?php echo $_GET['lesson_id'] ?? ''; ?>" method="POST" class="form">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
            
            <div class="form-group">
                <label for="title">Tiêu đề *</label>
                <input type="text" id="title" name="title" class="form-control" required placeholder="Tóm tắt câu hỏi của bạn">
            </div>
            
            <div class="form-group">
                <label for="content">Nội dung *</label>
                <textarea id="content" name="content" class="form-control" rows="6" required placeholder="Mô tả chi tiết câu hỏi..."></textarea>
            </div>
            
            <div class="form-actions">
                <a href="/elearning/public/index.php?route=forum&course_id=<?php echo $_GET['course_id']; ?>" class="btn btn-outline">Hủy</a>
                <button type="submit" class="btn btn-primary">Đăng câu hỏi</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
