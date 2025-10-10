<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/teacher/sidebar.php'; ?>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Dashboard Giáo viên 👨‍🏫</h2>
                    <p class="text-muted mb-0">Xin chào, <?= e($user['full_name']) ?>!</p>
                </div>
                <a href="<?= BASE_URL ?>/teacher/create-course" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tạo khóa học mới
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 rounded p-3">
                                        <i class="bi bi-book-fill text-primary fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-0 fw-bold"><?= $stats['total_courses'] ?></h3>
                                    <small class="text-muted">Khóa học</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-opacity-10 rounded p-3">
                                        <i class="bi bi-people-fill text-success fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-0 fw-bold"><?= $stats['total_students'] ?></h3>
                                    <small class="text-muted">Học sinh</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning bg-opacity-10 rounded p-3">
                                        <i class="bi bi-journal-text text-warning fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h3 class="mb-0 fw-bold"><?= $stats['total_lessons'] ?></h3>
                                    <small class="text-muted">Bài giảng</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Courses -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Khóa học của tôi</h5>
                        <a href="<?= BASE_URL ?>/teacher/courses" class="btn btn-sm btn-outline-primary">
                            Xem tất cả
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($my_courses)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                            <p class="text-muted mt-3">Chưa có khóa học nào</p>
                            <a href="<?= BASE_URL ?>/teacher/create-course" class="btn btn-primary">
                                Tạo khóa học đầu tiên
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Khóa học</th>
                                        <th>Môn học</th>
                                        <th>Học sinh</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($my_courses as $course): ?>
                                        <tr>
                                            <td>
                                                <strong><?= e($course['title']) ?></strong><br>
                                                <small class="text-muted"><?= formatDate($course['created_at']) ?></small>
                                            </td>
                                            <td><?= e($course['subject_name'] ?? 'N/A') ?></td>
                                            <td><?= $course['total_students'] ?? 0 ?> học sinh</td>
                                            <td>
                                                <?php if ($course['is_published']): ?>
                                                    <span class="badge bg-success">Đã xuất bản</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Nháp</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= BASE_URL ?>/teacher/course/<?= $course['id'] ?>" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-pencil"></i> Quản lý
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
