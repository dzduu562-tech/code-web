<?php $base = Core\Helpers::baseUrl($this->config); ?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <h2 class="mb-3">Đăng ký</h2>
    <?php if (!empty($error)): ?><div class="alert alert-danger"><?= Core\Helpers::e($error) ?></div><?php endif; ?>
    <form method="post" action="<?= $base ?>/index.php?route=/register">
      <input type="hidden" name="csrf" value="<?= Core\Helpers::csrfToken() ?>">
      <div class="mb-3">
        <label class="form-label" for="name">Họ và tên</label>
        <input class="form-control" type="text" id="name" name="name" required>
      </div>
      <div class="mb-3">
        <label class="form-label" for="email">Email</label>
        <input class="form-control" type="email" id="email" name="email" required>
      </div>
      <div class="mb-3">
        <label class="form-label" for="password">Mật khẩu</label>
        <input class="form-control" type="password" id="password" name="password" required>
      </div>
      <div class="mb-3">
        <label class="form-label" for="role">Vai trò</label>
        <select id="role" name="role" class="form-select">
          <option value="student">Học sinh</option>
          <option value="teacher">Giáo viên</option>
        </select>
      </div>
      <button class="btn btn-success w-100" type="submit">Tạo tài khoản</button>
    </form>
  </div>
</div>
