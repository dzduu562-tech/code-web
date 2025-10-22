<h2>Thông báo</h2>
<form class="mb-3" method="post" action="index.php?route=/notifications/read">
  <input type="hidden" name="csrf" value="<?=Helpers::csrfToken()?>">
  <button class="btn btn-sm btn-secondary">Đánh dấu đã đọc</button>
</form>
<ul class="list-group">
<?php foreach ($items as $n): $payload=json_decode($n['payload_json'],true) ?: []; ?>
  <li class="list-group-item d-flex justify-content-between align-items-center <?= $n['is_read']? 'opacity-75':'' ?>">
    <span>
      <strong><?=Helpers::e($n['type'])?></strong>
      <span class="text-muted small"><?=Helpers::e($n['created_at'])?></span>
      <div class="small"><?=Helpers::e(json_encode($payload, JSON_UNESCAPED_UNICODE))?></div>
    </span>
    <?php if (!$n['is_read']): ?><span class="badge text-bg-danger">Mới</span><?php endif; ?>
  </li>
<?php endforeach; ?>
</ul>
