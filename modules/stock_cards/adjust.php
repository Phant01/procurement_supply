<?php $pageTitle = 'Stock Adjustment'; ?>
<div class="mb-3"><a href="<?= BASE_URL ?>/index.php?mod=stock_cards&act=view&item_id=<?= $item['item_id'] ?>"
  class="text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Back to Stock Card</a></div>
<div class="card" style="max-width:480px"><div class="card-header"><strong>Stock Adjustment: <?= htmlspecialchars($item['item_name']) ?></strong></div>
<div class="card-body"><form method="post">
  <div class="row g-3 mb-3">
    <div class="col-md-6">
      <label class="form-label fw-semibold">Date</label>
      <input type="date" name="adj_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
    </div>
    <div class="col-md-6">
      <label class="form-label fw-semibold">Type</label>
      <select name="adj_type" class="form-select" required>
        <option value="add">Add (Receipt)</option>
        <option value="deduct">Deduct (Loss/Waste)</option>
      </select>
    </div>
    <div class="col-12">
      <label class="form-label fw-semibold">Quantity (<?= htmlspecialchars($item['unit_of_measure']) ?>)</label>
      <input type="number" name="quantity" class="form-control" step="any" min="0.0001" required>
    </div>
    <div class="col-12">
      <label class="form-label fw-semibold">Remarks / Reason <span class="text-danger">*</span></label>
      <input type="text" name="remarks" class="form-control" required>
    </div>
  </div>
  <button class="btn btn-warning"><i class="bi bi-save me-1"></i>Record Adjustment</button>
</form></div></div>
