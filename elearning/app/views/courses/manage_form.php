<?php $base = Core\Helpers::baseUrl($this->config); ?>
<h3>Tạo khóa học</h3>
<?php if (!empty($error)): ?><div class="alert alert-danger"><?= Core\Helpers::e($error) ?></div><?php endif; ?>
<form method="post" action="<?= $base ?>/index.php?route=/course/create">
  <input type="hidden" name="csrf" value="<?= Core\Helpers::csrfToken() ?>">
  <div class="mb-2"><label class="form-label">Tiêu đề</label><input class="form-control" name="title" required></div>
  <div class="mb-2"><label class="form-label">Môn học</label><input class="form-control" name="subject" required></div>
  <div class="mb-2"><label class="form-label">Mô tả</label><textarea class="form-control" name="description" rows="4"></textarea></div>
  <button class="btn btn-success">Lưu</button>
</form>
