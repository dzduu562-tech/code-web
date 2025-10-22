<div class="row justify-content-center">
  <div class="col-md-4">
    <h2>Đăng nhập</h2>
    <form method="post" action="index.php?route=/login">
      <input type="hidden" name="csrf" value="<?=Helpers::csrfToken()?>">
      <div class="mb-3">
        <label class="form-label" for="email">Email</label>
        <input required type="email" class="form-control" name="email" id="email">
      </div>
      <div class="mb-3">
        <label class="form-label" for="password">Mật khẩu</label>
        <input required type="password" class="form-control" name="password" id="password">
      </div>
      <button class="btn btn-primary w-100">Đăng nhập</button>
      <p class="mt-3">Chưa có tài khoản? <a href="index.php?route=/register">Đăng ký</a></p>
    </form>
  </div>
</div>
