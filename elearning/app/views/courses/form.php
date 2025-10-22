<h2><?=$course['id'] ? 'Sửa' : 'Tạo'?> khóa học</h2>
<form method="post" action="index.php?route=/teacher/course/save">
  <input type="hidden" name="csrf" value="<?=Helpers::csrfToken()?>">
  <input type="hidden" name="id" value="<?=$course['id'] ?? 0?>">
  <div class="mb-3">
    <label class="form-label" for="title">Tiêu đề</label>
    <input class="form-control" id="title" name="title" value="<?=Helpers::e($course['title'] ?? '')?>">
  </div>
  <div class="mb-3">
    <label class="form-label" for="subject">Môn học</label>
    <input class="form-control" id="subject" name="subject" value="<?=Helpers::e($course['subject'] ?? '')?>">
  </div>
  <div class="mb-3">
    <label class="form-label" for="description">Mô tả</label>
    <textarea class="form-control" id="description" name="description" rows="4"><?=Helpers::e($course['description'] ?? '')?></textarea>
  </div>
  <button class="btn btn-primary">Lưu</button>
</form>
