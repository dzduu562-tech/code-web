<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/admin/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <h2 class="fw-bold mb-4">Nhật ký hoạt động</h2>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="150">Thời gian</th>
                                    <th>Người dùng</th>
                                    <th>Hành động</th>
                                    <th>IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($logs)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Chưa có nhật ký nào
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($logs as $log): ?>
                                        <tr>
                                            <td>
                                                <small><?= formatDate($log['created_at']) ?></small>
                                            </td>
                                            <td><?= e($log['user_name'] ?? 'System') ?></td>
                                            <td><?= e($log['action']) ?></td>
                                            <td><code><?= e($log['ip_address'] ?? 'N/A') ?></code></td>
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
