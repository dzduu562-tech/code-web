<?php $base = Core\Helpers::baseUrl($this->config); ?>
<h2 class="mb-1"><?= Core\Helpers::e($lesson['title']) ?></h2>
<div class="text-muted mb-3">Khóa học: <a href="<?= $base ?>/index.php?route=/course&id=<?= (int)$lesson['course_id'] ?>"><?= Core\Helpers::e($lesson['course_title']) ?></a> · Chương: <?= Core\Helpers::e($lesson['chapter_title']) ?></div>
<?php if (!empty($lesson['video_url'])): ?>
  <div class="ratio ratio-16x9 mb-3">
    <iframe src="<?= Core\Helpers::e($lesson['video_url']) ?>" allowfullscreen title="Video bài học"></iframe>
  </div>
<?php endif; ?>
<div class="mb-3">
  <?= $lesson['content_html'] ?>
</div>
<h5>Tài liệu</h5>
<ul>
  <?php foreach ($resources as $r): ?>
    <li><a href="<?= $base ?>/uploads/resources/<?= rawurlencode($r['file_path']) ?>" download><?= Core\Helpers::e($r['file_name']) ?></a></li>
  <?php endforeach; ?>
</ul>
<?php if (Core\Auth::check()): ?>
  <form method="post" action="<?= $base ?>/index.php?route=/lesson/complete" class="mt-3">
    <input type="hidden" name="csrf" value="<?= Core\Helpers::csrfToken() ?>">
    <input type="hidden" name="lesson_id" value="<?= (int)$lesson['id'] ?>">
    <button type="submit" class="btn btn-success" <?= !empty($completed)?'disabled':'' ?>><?= !empty($completed)?'Đã hoàn thành':'Đánh dấu đã học' ?></button>
  </form>
<?php endif; ?>
<h5 class="mt-4">Thảo luận</h5>
<ul class="list-group">
  <?php foreach ($threads as $t): ?>
    <li class="list-group-item">
      <strong><?= Core\Helpers::e($t['title']) ?></strong>
      <div class="small text-muted">bởi <?= Core\Helpers::e($t['author_name']) ?> • <?= Core\Helpers::e($t['created_at']) ?></div>
    </li>
  <?php endforeach; ?>
</ul>
