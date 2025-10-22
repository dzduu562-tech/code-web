<div class="d-flex justify-content-between align-items-center">
  <h2>Khóa học của tôi</h2>
  <a class="btn btn-primary" href="index.php?route=/teacher/course/new">+ Tạo khóa học</a>
</div>
<hr>
<div class="row g-3">
<?php foreach ($courses as $c): ?>
  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-body">
        <h5 class="card-title"><?=Helpers::e($c['title'])?></h5>
        <div class="small text-muted">Môn: <?=Helpers::e($c['subject'])?></div>
        <p class="card-text"><?=Helpers::e(mb_strimwidth($c['description'],0,120,'...'))?></p>
        <a class="btn btn-outline-primary btn-sm" href="index.php?route=/teacher/course/manage&id=<?=$c['id']}">Quản lý</a>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>
