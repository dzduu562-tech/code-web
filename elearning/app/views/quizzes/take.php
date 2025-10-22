<h3><?=Helpers::e($quiz['title'])?></h3>
<form method="post" action="index.php?route=/quiz/submit">
  <input type="hidden" name="csrf" value="<?=Helpers::csrfToken()?>">
  <input type="hidden" name="quiz_id" value="<?=$quiz['id']?>">
  <?php foreach ($questions as $q): ?>
    <div class="mb-3">
      <div class="fw-semibold"><?=Helpers::e($q['text'])?></div>
      <?php foreach (($options[$q['id']] ?? []) as $op): ?>
        <div class="form-check">
          <input class="form-check-input" required type="radio" name="answers[<?=$q['id']?>]" value="<?=$op['id']?>" id="op<?=$op['id']?>">
          <label class="form-check-label" for="op<?=$op['id']?>"><?=Helpers::e($op['text'])?></label>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endforeach; ?>
  <button class="btn btn-primary">Nộp bài</button>
</form>
