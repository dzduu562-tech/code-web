<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/admin/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Quản lý người dùng</h2>
                <a href="<?= BASE_URL ?>/admin/addUser" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i> Thêm người dùng
                </a>
            </div>

            <!-- Filter -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Tìm kiếm..." id="searchUser">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterRole">
                                <option value="">Tất cả vai trò</option>
                                <option value="admin">Admin</option>
                                <option value="teacher">Giáo viên</option>
                                <option value="student">Học sinh</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterStatus">
                                <option value="">Tất cả trạng thái</option>
                                <option value="active">Hoạt động</option>
                                <option value="inactive">Tạm khóa</option>
                                <option value="suspended">Bị khóa</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Vai trò</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($users)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Không có dữ liệu</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?= $user['id'] ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?= $user['avatar'] ?: ASSETS_URL . '/images/default-avatar.png' ?>" 
                                                         class="rounded-circle me-2" 
                                                         width="32" 
                                                         height="32">
                                                    <strong><?= e($user['full_name']) ?></strong>
                                                </div>
                                            </td>
                                            <td><?= e($user['email']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'teacher' ? 'success' : 'primary') ?>">
                                                    <?php
                                                    $roles = ['admin' => 'Admin', 'teacher' => 'Giáo viên', 'student' => 'Học sinh'];
                                                    echo $roles[$user['role']] ?? $user['role'];
                                                    ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'secondary' ?>">
                                                    <?php
                                                    $statuses = ['active' => 'Hoạt động', 'inactive' => 'Tạm khóa', 'suspended' => 'Bị khóa'];
                                                    echo $statuses[$user['status']] ?? $user['status'];
                                                    ?>
                                                </span>
                                            </td>
                                            <td><?= formatDate($user['created_at']) ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= BASE_URL ?>/admin/editUser/<?= $user['id'] ?>" 
                                                       class="btn btn-outline-primary" 
                                                       title="Sửa">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                        <a href="<?= BASE_URL ?>/admin/deleteUser/<?= $user['id'] ?>" 
                                                           class="btn btn-outline-danger" 
                                                           onclick="return confirm('Bạn có chắc muốn xóa?')"
                                                           title="Xóa">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    <?php endif; ?>
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
