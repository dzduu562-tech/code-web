<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/admin/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Quản lý lớp học</h2>
                <a href="<?= BASE_URL ?>/admin/addClass" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Thêm lớp học
                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tên lớp</th>
                                    <th>Mã lớp</th>
                                    <th>Khối</th>
                                    <th>Năm học</th>
                                    <th>GVCN</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($classes)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            Chưa có lớp học nào
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($classes as $class): ?>
                                        <tr>
                                            <td><strong><?= e($class['name']) ?></strong></td>
                                            <td><?= e($class['code']) ?></td>
                                            <td>Lớp <?= $class['grade_level'] ?></td>
                                            <td><?= e($class['academic_year']) ?></td>
                                            <td><?= e($class['teacher_name'] ?? 'Chưa có') ?></td>
                                            <td>
                                                <span class="badge bg-<?= $class['status'] === 'active' ? 'success' : 'secondary' ?>">
                                                    <?= $class['status'] === 'active' ? 'Hoạt động' : 'Không hoạt động' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= BASE_URL ?>/admin/editClass/<?= $class['id'] ?>" 
                                                       class="btn btn-outline-primary" 
                                                       title="Sửa">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="<?= BASE_URL ?>/admin/deleteClass/<?= $class['id'] ?>" 
                                                       class="btn btn-outline-danger" 
                                                       onclick="return confirm('Xóa lớp học này?')"
                                                       title="Xóa">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
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
