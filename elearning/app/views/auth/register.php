<div class="row justify-content-center">
  <div class="col-md-5">
    <h2>Đăng ký</h2>
    <form method="post" action="index.php?route=/register">
      <input type="hidden" name="csrf" value="<?=Helpers::csrfToken()?>">
      <div class="mb-3">
        <label class="form-label" for="name">Họ tên</label>
        <input required type="text" class="form-control" name="name" id="name">
      </div>
      <div class="mb-3">
        <label class="form-label" for="email">Email</label>
        <input required type="email" class="form-control" name="email" id="email">
      </div>
      <div class="mb-3">
        <label class="form-label" for="password">Mật khẩu (≥ 6 ký tự)</label>
        <input required minlength="6" type="password" class="form-control" name="password" id="password">
      </div>
      <button class="btn btn-primary w-100">Tạo tài khoản</button>
    </form>
  </div>
</div>
