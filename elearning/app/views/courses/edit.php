<?php $pageTitle = 'Chỉnh sửa khóa học'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Chỉnh sửa khóa học</h1>
    </div>
    
    <div class="form-container">
        <form action="/elearning/public/index.php?route=courses/edit&id=<?php echo $course['id']; ?>" method="POST" class="form">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
            
            <div class="form-group">
                <label for="title">Tên khóa học *</label>
                <input type="text" id="title" name="title" class="form-control" required value="<?php echo Helpers::escape($course['title']); ?>">
            </div>
            
            <div class="form-group">
                <label for="subject">Môn học</label>
                <input type="text" id="subject" name="subject" class="form-control" value="<?php echo Helpers::escape($course['subject'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea id="description" name="description" class="form-control" rows="5"><?php echo Helpers::escape($course['description'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-actions">
                <a href="/elearning/public/index.php?route=course&id=<?php echo $course['id']; ?>" class="btn btn-outline">Hủy</a>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
        
        <hr style="margin: 2rem 0;">
        
        <div class="danger-zone">
            <h3>Xóa khóa học</h3>
            <p>Hành động này không thể hoàn tác. Tất cả dữ liệu liên quan sẽ bị xóa.</p>
            <form action="/elearning/public/index.php?route=courses/delete" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khóa học này?');">
                <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
                <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                <button type="submit" class="btn btn-danger">Xóa khóa học</button>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
