<?php $base = Core\Helpers::baseUrl($this->config); ?>
<h2>Người dùng</h2>
<form class="row g-2 mb-3" method="get" action="<?= $base ?>/index.php">
  <input type="hidden" name="route" value="/admin/users">
  <div class="col-sm-4"><input class="form-control" type="text" name="q" placeholder="Từ khóa" value="<?= Core\Helpers::e($q ?? '') ?>"></div>
  <div class="col-sm-2 d-grid"><button class="btn btn-primary">Tìm</button></div>
</form>
<table class="table table-sm align-middle">
  <thead><tr><th>ID</th><th>Tên</th><th>Email</th><th>Vai trò</th><th></th></tr></thead>
  <tbody>
    <?php foreach ($users as $u): ?>
      <tr>
        <td><?= (int)$u['id'] ?></td>
        <td><?= Core\Helpers::e($u['name']) ?></td>
        <td><?= Core\Helpers::e($u['email']) ?></td>
        <td>
          <form method="post" action="<?= $base ?>/index.php?route=/admin/user/update" class="d-flex gap-2">
            <input type="hidden" name="csrf" value="<?= Core\Helpers::csrfToken() ?>">
            <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
            <select class="form-select form-select-sm" name="role">
              <option value="student" <?= $u['role']==='student'?'selected':'' ?>>student</option>
              <option value="teacher" <?= $u['role']==='teacher'?'selected':'' ?>>teacher</option>
              <option value="admin" <?= $u['role']==='admin'?'selected':'' ?>>admin</option>
            </select>
            <button class="btn btn-sm btn-outline-primary">Lưu</button>
          </form>
        </td>
        <td></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
