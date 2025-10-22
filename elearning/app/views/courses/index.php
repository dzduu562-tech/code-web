<h2>Khóa học</h2>
<form class="row gy-2 gx-2 align-items-end mb-3" method="get" action="index.php">
  <input type="hidden" name="route" value="/courses">
  <div class="col-sm-4">
    <label class="form-label" for="q">Từ khóa</label>
    <input class="form-control" type="text" id="q" name="q" value="<?=Helpers::e($q ?? '')?>" placeholder="Tìm kiếm...">
  </div>
  <div class="col-sm-3">
    <label class="form-label" for="subject">Môn học</label>
    <input class="form-control" type="text" id="subject" name="subject" value="<?=Helpers::e($subject ?? '')?>" placeholder="Toán, Lý, ...">
  </div>
  <div class="col-sm-3">
    <label class="form-label" for="teacher">Giáo viên</label>
    <input class="form-control" type="text" id="teacher" name="teacher" value="<?=Helpers::e($teacher ?? '')?>" placeholder="Tên giáo viên">
  </div>
  <div class="col-sm-2">
    <button class="btn btn-secondary w-100">Lọc</button>
  </div>
</form>
<div class="row g-3">
<?php foreach ($courses as $c): ?>
  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-body">
        <h5 class="card-title"><?=Helpers::e($c['title'])?></h5>
        <p class="card-text small text-muted">Môn: <?=Helpers::e($c['subject'])?> • GV: <?=Helpers::e($c['teacher_name'])?></p>
        <p class="card-text"><?=Helpers::e(mb_strimwidth($c['description'],0,120,'...'))?></p>
        <a class="btn btn-primary btn-sm" href="index.php?route=/course&id=<?=$c['id']}">Vào khóa</a>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>
