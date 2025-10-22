<?php $pageTitle = 'Tạo bài tập mới'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Tạo bài tập mới</h1>
    </div>
    
    <div class="form-container">
        <form action="/elearning/public/index.php?route=assignments/create&course_id=<?php echo $_GET['course_id']; ?>" method="POST" class="form">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
            
            <div class="form-group">
                <label for="title">Tiêu đề bài tập *</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea id="description" name="description" class="form-control" rows="5"></textarea>
            </div>
            
            <div class="form-group">
                <label for="due_at">Hạn nộp</label>
                <input type="datetime-local" id="due_at" name="due_at" class="form-control">
            </div>
            
            <div class="form-actions">
                <a href="/elearning/public/index.php?route=course&id=<?php echo $_GET['course_id']; ?>" class="btn btn-outline">Hủy</a>
                <button type="submit" class="btn btn-primary">Tạo bài tập</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
