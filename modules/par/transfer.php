<?php $pageTitle = 'Transfer PAR ' . htmlspecialchars($row['par_number']); ?>
<div class="mb-3"><a href="<?= BASE_URL ?>/index.php?mod=par&act=index" class="text-decoration-none">
  <i class="bi bi-arrow-left me-1"></i>Back</a></div>
<div class="card" style="max-width:480px">
  <div class="card-header"><strong>Transfer: <?= htmlspecialchars($row['par_number']) ?></strong></div>
  <div class="card-body">
    <p class="text-muted small">Item: <?= htmlspecialchars($row['item_name']) ?> | Currently: <?= htmlspecialchars($row['assigned_to']) ?></p>
    <form method="post">
      <div class="mb-3">
        <label class="form-label fw-semibold">Reason / Remarks</label>
        <textarea name="remarks" class="form-control" rows="3" required></textarea>
      </div>
      <button class="btn btn-warning"><i class="bi bi-arrow-left-right me-1"></i>Confirm Transfer</button>
    </form>
  </div>
</div>
