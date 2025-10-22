<?php $pageTitle = 'Quản lý khóa học'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>Quản lý khóa học</h1>
        <a href="/elearning/public/index.php?route=courses/create" class="btn btn-primary">+ Tạo khóa học mới</a>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên khóa học</th>
                    <th>Môn học</th>
                    <th>Giáo viên</th>
                    <th>Học sinh</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?php echo $course['id']; ?></td>
                        <td><a href="/elearning/public/index.php?route=course&id=<?php echo $course['id']; ?>"><?php echo Helpers::escape($course['title']); ?></a></td>
                        <td><?php echo Helpers::escape($course['subject'] ?? '-'); ?></td>
                        <td><?php echo Helpers::escape($course['teacher_name']); ?></td>
                        <td><?php echo $course['student_count'] ?? 0; ?></td>
                        <td><?php echo Helpers::formatDate($course['created_at'], 'd/m/Y'); ?></td>
                        <td>
                            <a href="/elearning/public/index.php?route=courses/edit&id=<?php echo $course['id']; ?>" class="btn btn-sm">Sửa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <?php if ($pagination['total_pages'] > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                <a href="/elearning/public/index.php?route=admin/courses&page=<?php echo $i; ?>" 
                   class="page-link <?php echo $i === $pagination['current_page'] ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
