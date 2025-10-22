<div class="d-flex justify-content-between align-items-center">
  <div>
    <h2><?=Helpers::e($course['title'])?></h2>
    <div class="text-muted">Môn: <?=Helpers::e($course['subject'])?> • GV: <?=Helpers::e($course['teacher_name'])?></div>
  </div>
  <a class="btn btn-secondary" href="index.php?route=/courses">← Danh sách</a>
</div>
<hr>
<?php foreach ($chapters as $ch): ?>
  <h5 class="mt-3">Chương <?=$ch['position']?>: <?=Helpers::e($ch['title'])?></h5>
  <ul>
    <?php foreach ($lessonsByChapter[$ch['id']] ?? [] as $ls): ?>
      <li><a href="index.php?route=/lesson&id=<?=$ls['id']}"><?=Helpers::e($ls['title'])?></a></li>
    <?php endforeach; ?>
  </ul>
<?php endforeach; ?>
