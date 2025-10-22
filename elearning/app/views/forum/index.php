<?php $base = Core\Helpers::baseUrl($this->config); ?>
<h2>Diễn đàn</h2>
<form class="row g-2 mb-3" method="get" action="<?= $base ?>/index.php">
  <input type="hidden" name="route" value="/forum">
  <div class="col-sm-3"><input class="form-control" type="number" name="course_id" placeholder="Course ID" value="<?= (int)($courseId??0) ?>"></div>
  <div class="col-sm-3"><input class="form-control" type="number" name="lesson_id" placeholder="Lesson ID" value="<?= (int)($lessonId??0) ?>"></div>
  <div class="col-sm-2 d-grid"><button class="btn btn-primary">Lọc</button></div>
</form>
<?php if (Core\Auth::check()): ?>
<form class="card card-body mb-3" method="post" action="<?= $base ?>/index.php?route=/thread/create">
  <input type="hidden" name="csrf" value="<?= Core\Helpers::csrfToken() ?>">
  <div class="row g-2">
    <div class="col-sm-4"><input class="form-control" type="text" name="title" placeholder="Tiêu đề" required></div>
    <div class="col-sm-3"><input class="form-control" type="number" name="course_id" placeholder="Course ID"></div>
    <div class="col-sm-3"><input class="form-control" type="number" name="lesson_id" placeholder="Lesson ID"></div>
    <div class="col-sm-2 d-grid"><button class="btn btn-success" type="submit">Tạo</button></div>
  </div>
</form>
<?php endif; ?>
<ul class="list-group">
<?php foreach ($threads as $t): ?>
  <li class="list-group-item d-flex justify-content-between align-items-center">
    <a href="<?= $base ?>/index.php?route=/thread&id=<?= (int)$t['id'] ?>"><?= Core\Helpers::e($t['title']) ?></a>
    <span class="text-muted small">bởi <?= Core\Helpers::e($t['author_name']) ?> • <?= Core\Helpers::e($t['created_at']) ?></span>
  </li>
<?php endforeach; ?>
</ul>
