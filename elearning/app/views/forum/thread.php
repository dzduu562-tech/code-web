<?php $base = Core\Helpers::baseUrl($this->config); ?>
<h3><?= Core\Helpers::e($thread['title']) ?></h3>
<div class="mb-3 small text-muted">bởi <?= Core\Helpers::e($thread['author_name']) ?> • <?= Core\Helpers::e($thread['created_at']) ?></div>
<ul class="list-group mb-3">
<?php foreach ($posts as $p): ?>
  <li class="list-group-item">
    <div class="small text-muted mb-1"><?= Core\Helpers::e($p['author_name']) ?> • <?= Core\Helpers::e($p['created_at']) ?></div>
    <div><?= nl2br(Core\Helpers::e($p['content'])) ?></div>
  </li>
<?php endforeach; ?>
</ul>
<?php if (Core\Auth::check()): ?>
<form method="post" action="<?= $base ?>/index.php?route=/thread/reply" class="card card-body">
  <input type="hidden" name="csrf" value="<?= Core\Helpers::csrfToken() ?>">
  <input type="hidden" name="thread_id" value="<?= (int)$thread['id'] ?>">
  <div class="mb-2"><textarea class="form-control" name="content" rows="3" required></textarea></div>
  <div><button class="btn btn-primary">Gửi</button></div>
</form>
<?php endif; ?>
