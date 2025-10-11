<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/admin/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Quản lý khóa học</h2>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Khóa học</th>
                                    <th>Môn học</th>
                                    <th>Giáo viên</th>
                                    <th>Học sinh</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($courses)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            Chưa có khóa học nào
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($courses as $course): ?>
                                        <tr>
                                            <td>
                                                <strong><?= e($course['title']) ?></strong><br>
                                                <small class="text-muted"><?= formatDate($course['created_at']) ?></small>
                                            </td>
                                            <td><?= e($course['subject_name'] ?? 'N/A') ?></td>
                                            <td><?= e($course['teacher_name']) ?></td>
                                            <td><?= $course['enrollment_count'] ?? 0 ?></td>
                                            <td>
                                                <?php if ($course['is_published']): ?>
                                                    <span class="badge bg-success">Đã xuất bản</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Nháp</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= BASE_URL ?>/courses/detail/<?= $course['slug'] ?>" 
                                                       class="btn btn-outline-primary" 
                                                       title="Xem">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <button class="btn btn-outline-danger" 
                                                            onclick="return confirm('Xóa khóa học này?')"
                                                            title="Xóa">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
