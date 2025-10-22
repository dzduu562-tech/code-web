<h2>Quản trị</h2>
<div class="row g-3">
  <div class="col-md-4"><div class="card p-3"><div class="h5">Người dùng</div><div class="display-6"><?= (int)$stats['users'] ?></div></div></div>
  <div class="col-md-4"><div class="card p-3"><div class="h5">Khóa học</div><div class="display-6"><?= (int)$stats['courses'] ?></div></div></div>
  <div class="col-md-4"><div class="card p-3"><div class="h5">Đăng ký</div><div class="display-6"><?= (int)$stats['enrollments'] ?></div></div></div>
</div>
<h5 class="mt-4">Người dùng mới</h5>
<ul class="list-group">
  <?php foreach ($recent as $u): ?>
    <li class="list-group-item d-flex justify-content-between">
      <span><?= Core\Helpers::e($u['name']) ?> (<?= Core\Helpers::e($u['email']) ?>)</span>
      <span class="text-muted small"><?= Core\Helpers::e($u['role']) ?> • <?= Core\Helpers::e($u['created_at']) ?></span>
    </li>
  <?php endforeach; ?>
</ul>
