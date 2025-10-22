<?php $base = Core\Helpers::baseUrl($this->config); ?>
<h2 class="mb-2"><?= Core\Helpers::e($course['title']) ?></h2>
<div class="text-muted mb-3">Môn: <?= Core\Helpers::e($course['subject']) ?> · GV: <?= Core\Helpers::e($course['teacher_name']) ?></div>
<p><?= nl2br(Core\Helpers::e($course['description'])) ?></p>
<div class="row">
  <div class="col-md-4">
    <h5>Mục lục</h5>
    <ol class="list-group list-group-numbered">
      <?php foreach ($chapters as $ch): ?>
        <li class="list-group-item">
          <strong><?= Core\Helpers::e($ch['title']) ?></strong>
          <ul class="mt-2">
          <?php foreach ($lessons as $ls): if ($ls['chapter_id'] == $ch['id']): ?>
            <li><a href="<?= $base ?>/index.php?route=/lesson&id=<?= (int)$ls['id'] ?>"><?= Core\Helpers::e($ls['title']) ?></a></li>
          <?php endif; endforeach; ?>
          </ul>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</div>
