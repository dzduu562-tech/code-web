<?php $base = Core\Helpers::baseUrl($this->config); ?>
<div class="row justify-content-center">
  <div class="col-md-4">
    <h2 class="mb-3">Đăng nhập</h2>
    <?php if (!empty($error)): ?><div class="alert alert-danger"><?= Core\Helpers::e($error) ?></div><?php endif; ?>
    <form method="post" action="<?= $base ?>/index.php?route=/login">
      <input type="hidden" name="csrf" value="<?= Core\Helpers::csrfToken() ?>">
      <div class="mb-3">
        <label class="form-label" for="email">Email</label>
        <input class="form-control" type="email" id="email" name="email" required>
      </div>
      <div class="mb-3">
        <label class="form-label" for="password">Mật khẩu</label>
        <input class="form-control" type="password" id="password" name="password" required>
      </div>
      <button class="btn btn-primary w-100" type="submit">Đăng nhập</button>
    </form>
    <div class="mt-3 small">Chưa có tài khoản? <a href="<?= $base ?>/index.php?route=/register">Đăng ký</a></div>
  </div>
</div>
