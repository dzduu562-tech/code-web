<?php $pageTitle = 'Chỉnh sửa người dùng'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Chỉnh sửa người dùng</h1>
    </div>
    
    <div class="form-container">
        <form action="/elearning/public/index.php?route=admin/users/edit&id=<?php echo $user['id']; ?>" method="POST" class="form">
            <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
            
            <div class="form-group">
                <label for="name">Họ và tên *</label>
                <input type="text" id="name" name="name" class="form-control" required value="<?php echo Helpers::escape($user['name']); ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" class="form-control" required value="<?php echo Helpers::escape($user['email']); ?>">
            </div>
            
            <div class="form-group">
                <label for="role">Vai trò *</label>
                <select id="role" name="role" class="form-control" required>
                    <option value="student" <?php echo $user['role'] === 'student' ? 'selected' : ''; ?>>Học sinh</option>
                    <option value="teacher" <?php echo $user['role'] === 'teacher' ? 'selected' : ''; ?>>Giáo viên</option>
                    <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            
            <div class="form-actions">
                <a href="/elearning/public/index.php?route=admin/users" class="btn btn-outline">Hủy</a>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
