<?php $pageTitle = 'Quản lý người dùng'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Quản lý người dùng</h1>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo Helpers::escape($user['name']); ?></td>
                        <td><?php echo Helpers::escape($user['email']); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $user['role']; ?>">
                                <?php 
                                $roles = ['admin' => 'Admin', 'teacher' => 'Giáo viên', 'student' => 'Học sinh'];
                                echo $roles[$user['role']] ?? $user['role'];
                                ?>
                            </span>
                        </td>
                        <td><?php echo Helpers::formatDate($user['created_at']); ?></td>
                        <td>
                            <a href="/elearning/public/index.php?route=admin/users/edit&id=<?php echo $user['id']; ?>" class="btn btn-sm">Sửa</a>
                            <?php if ($user['id'] != Auth::id()): ?>
                                <form action="/elearning/public/index.php?route=admin/users/delete" method="POST" style="display:inline;" onsubmit="return confirm('Xác nhận xóa?');">
                                    <input type="hidden" name="csrf_token" value="<?php echo Auth::generateCSRFToken(); ?>">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <?php if ($pagination['total_pages'] > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                <a href="/elearning/public/index.php?route=admin/users&page=<?php echo $i; ?>" 
                   class="page-link <?php echo $i === $pagination['current_page'] ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
