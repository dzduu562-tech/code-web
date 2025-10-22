<?php $pageTitle = 'Tạo khóa học mới'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Tạo khóa học mới</h1>
    </div>
    
    <div class="form-container">
        <form action="/elearning/public/index.php?route=courses/create" method="POST" class="form">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
            
            <div class="form-group">
                <label for="title">Tên khóa học *</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="subject">Môn học</label>
                <input type="text" id="subject" name="subject" class="form-control" placeholder="VD: Công nghệ thông tin">
            </div>
            
            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea id="description" name="description" class="form-control" rows="5"></textarea>
            </div>
            
            <div class="form-actions">
                <a href="/elearning/public/index.php?route=courses" class="btn btn-outline">Hủy</a>
                <button type="submit" class="btn btn-primary">Tạo khóa học</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
