<?php $r = $row ?? []; ?>
<div class="row g-3 mb-3">
  <div class="col-md-4">
    <label class="form-label fw-semibold">Item Code</label>
    <input type="text" name="item_code" class="form-control" value="<?= htmlspecialchars($r['item_code'] ?? '') ?>">
  </div>
  <div class="col-md-8">
    <label class="form-label fw-semibold">Item Name <span class="text-danger">*</span></label>
    <input type="text" name="item_name" class="form-control" value="<?= htmlspecialchars($r['item_name'] ?? '') ?>" required>
  </div>
  <div class="col-md-6">
    <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
    <select name="category_id" class="form-select" required>
      <option value="">-- select --</option>
      <?php foreach ($cats as $c): ?>
        <option value="<?= $c['category_id'] ?>" <?= ($r['category_id']??'')==$c['category_id']?'selected':'' ?>>
          <?= htmlspecialchars($c['category_name']) ?> (<?= $c['item_type'] ?>)</option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label fw-semibold">Unit of Measure <span class="text-danger">*</span></label>
    <input type="text" name="unit_of_measure" class="form-control" placeholder="piece, ream, box…"
           value="<?= htmlspecialchars($r['unit_of_measure'] ?? '') ?>" required>
  </div>
  <div class="col-md-3">
    <label class="form-label fw-semibold">UACS Code</label>
    <input type="text" name="uacs_code" class="form-control" value="<?= htmlspecialchars($r['uacs_code'] ?? '') ?>">
  </div>
  <div class="col-md-4">
    <label class="form-label fw-semibold">Unit Cost (&#8369;)</label>
    <input type="number" name="unit_cost" class="form-control" step="0.01" min="0"
           value="<?= htmlspecialchars($r['unit_cost'] ?? '0') ?>">
  </div>
  <div class="col-md-4">
    <label class="form-label fw-semibold">Reorder Point</label>
    <input type="number" name="reorder_point" class="form-control" step="0.01" min="0"
           value="<?= htmlspecialchars($r['reorder_point'] ?? '0') ?>">
  </div>
  <div class="col-12">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="2"><?= htmlspecialchars($r['description'] ?? '') ?></textarea>
  </div>
</div>
