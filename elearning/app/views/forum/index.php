<div class="d-flex justify-content-between align-items-center">
  <h2>Diễn đàn</h2>
  <form class="d-flex gap-2" method="post" action="index.php?route=/forum/create">
    <input type="hidden" name="csrf" value="<?=Helpers::csrfToken()?>">
    <input class="form-control" name="title" placeholder="Tiêu đề...">
    <button class="btn btn-secondary">Tạo chủ đề</button>
  </form>
</div>
<hr>
<div class="list-group">
<?php foreach ($threads as $t): ?>
  <a href="index.php?route=/thread&id=<?=$t['id']?>" class="list-group-item list-group-item-action">
    <div class="d-flex w-100 justify-content-between">
      <h5 class="mb-1"><?=Helpers::e($t['title'])?></h5>
      <small class="text-muted"><?=Helpers::e($t['created_at'])?></small>
    </div>
    <p class="mb-1 small">Khóa học: <?=Helpers::e($t['course_title'])?> • Tác giả: <?=Helpers::e($t['author_name'])?></p>
  </a>
<?php endforeach; ?>
</div>
