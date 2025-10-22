<a class="btn btn-secondary btn-sm" href="index.php?route=/forum">← Diễn đàn</a>
<h3 class="mt-2"><?=Helpers::e($thread['title'])?></h3>
<div class="mt-3">
  <?php foreach ($posts as $p): ?>
    <div class="border rounded p-2 mb-2">
      <div class="small text-muted"><?=Helpers::e($p['author_name'])?> • <?=Helpers::e($p['created_at'])?></div>
      <div><?=nl2br(Helpers::e($p['content']))?></div>
    </div>
  <?php endforeach; ?>
</div>
<form class="mt-3" method="post" action="index.php?route=/forum/reply">
  <input type="hidden" name="csrf" value="<?=Helpers::csrfToken()?>">
  <input type="hidden" name="thread_id" value="<?=$thread['id']?>">
  <div class="mb-2">
    <label class="form-label" for="content">Nội dung</label>
    <textarea class="form-control" id="content" name="content" rows="3"></textarea>
  </div>
  <button class="btn btn-primary">Gửi</button>
</form>
