<?php $base = Core\Helpers::baseUrl($this->config); ?>
<h2 class="mb-3">Danh sách khóa học</h2>
<form class="row g-2 mb-3" method="get" action="<?= $base ?>/index.php">
  <input type="hidden" name="route" value="/courses">
  <div class="col-sm-4"><input class="form-control" type="text" name="q" placeholder="Từ khóa" value="<?= Core\Helpers::e($q ?? '') ?>"></div>
  <div class="col-sm-3"><input class="form-control" type="text" name="subject" placeholder="Môn học"></div>
  <div class="col-sm-3"><input class="form-control" type="text" name="teacher" placeholder="Giáo viên"></div>
  <div class="col-sm-2 d-grid"><button class="btn btn-primary" type="submit">Tìm kiếm</button></div>
</form>
<div class="row g-3">
<?php foreach ($courses as $c): ?>
  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-body">
        <h5 class="card-title"><?= Core\Helpers::e($c['title']) ?></h5>
        <div class="small text-muted">Môn: <?= Core\Helpers::e($c['subject']) ?> · GV: <?= Core\Helpers::e($c['teacher_name']) ?></div>
        <p class="card-text mt-2"><?= Core\Helpers::e(mb_strimwidth($c['description'] ?? '', 0, 120, '...')) ?></p>
        <a class="btn btn-outline-primary" href="<?= $base ?>/index.php?route=/course&id=<?= (int)$c['id'] ?>">Xem khóa học</a>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>
