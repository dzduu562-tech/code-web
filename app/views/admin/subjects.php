<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/admin/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Quản lý môn học</h2>
                <a href="<?= BASE_URL ?>/admin/addSubject" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Thêm môn học
                </a>
            </div>

            <div class="row g-4">
                <?php if (empty($subjects)): ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            Chưa có môn học nào. Hãy thêm môn học mới!
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($subjects as $subject): ?>
                        <div class="col-md-4 col-lg-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="mb-3" style="font-size: 3rem; color: <?= $subject['color'] ?>;">
                                        <i class="bi bi-<?= $subject['icon'] ?>"></i>
                                    </div>
                                    <h5 class="fw-bold"><?= e($subject['name']) ?></h5>
                                    <p class="text-muted small"><?= e($subject['code']) ?></p>
                                    <p class="text-muted small"><?= e($subject['description']) ?></p>
                                    <span class="badge bg-<?= $subject['status'] === 'active' ? 'success' : 'secondary' ?>">
                                        <?= $subject['status'] === 'active' ? 'Hoạt động' : 'Không hoạt động' ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
