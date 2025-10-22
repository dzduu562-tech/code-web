<?php $base = Core\Helpers::baseUrl($this->config); ?>
<h3><?= Core\Helpers::e($quiz['title']) ?></h3>
<form method="post" action="<?= $base ?>/index.php?route=/quiz/submit">
  <input type="hidden" name="csrf" value="<?= Core\Helpers::csrfToken() ?>">
  <input type="hidden" name="quiz_id" value="<?= (int)$quiz['id'] ?>">
  <?php foreach ($questions as $q): ?>
    <div class="mb-3">
      <strong>Q<?= (int)$q['id'] ?>.</strong> <?= Core\Helpers::e($q['text']) ?>
      <?php foreach ($options[$q['id']] ?? [] as $o): ?>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="answers[<?= (int)$q['id'] ?>]" value="<?= (int)$o['id'] ?>" required>
          <label class="form-check-label"><?= Core\Helpers::e($o['text']) ?></label>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endforeach; ?>
  <button class="btn btn-success">Nộp bài</button>
</form>
