<?php $pageTitle = 'Issue RIS ' . htmlspecialchars($ris['ris_number']); ?>
<div class="mb-3"><a href="<?= BASE_URL ?>/index.php?mod=ris&act=view&id=<?= $ris['ris_id'] ?>"
  class="text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Back to RIS</a></div>
<div class="alert alert-info small">
  <i class="bi bi-info-circle me-1"></i>
  Semi-expendable items will auto-generate an <strong>ICS</strong>.
  Equipment items will auto-generate a <strong>PAR</strong>.
  Personnel assignment is required for both.
</div>
<form method="post">
<div class="card mb-3">
  <div class="card-header"><strong>Issue Signatories</strong></div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label fw-semibold">Issued By</label>
        <input type="text" name="issued_by" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Received By</label>
        <input type="text" name="received_by" class="form-control" required>
      </div>
    </div>
  </div>
</div>
<div class="card mb-3">
  <div class="card-header"><strong>Items to Issue</strong></div>
  <div class="card-body p-0">
    <table class="table table-sm mb-0">
      <thead><tr><th>Item</th><th>Type</th><th>Stock</th>
        <th>Qty Req.</th><th>Qty to Issue</th><th>Unit Cost</th><th>Accountable Officer</th></tr></thead>
      <tbody>
      <?php foreach ($items as $it): ?>
        <tr>
          <td><?= htmlspecialchars($it['item_name']) ?></td>
          <td><span class="badge badge-<?= $it['item_type'] ?>"><?= $it['item_type'] ?></span></td>
          <td class="<?= $it['balance_qty']<$it['qty_requested']?'text-danger':'' ?>">
            <?= number_format((float)$it['balance_qty'],2) ?></td>
          <td><?= number_format((float)$it['qty_requested'],2) ?></td>
          <td><input type="number" name="lines[<?= $it['ris_item_id'] ?>][qty_issued]"
              class="form-control form-control-sm" style="width:90px"
              value="<?= number_format((float)$it['qty_requested'],2,'.','') ?>"
              max="<?= min($it['balance_qty'],$it['qty_requested']) ?>" min="0" step="any"></td>
          <td><input type="number" name="lines[<?= $it['ris_item_id'] ?>][unit_cost]"
              class="form-control form-control-sm" style="width:100px"
              value="<?= number_format((float)$it['unit_cost'],2,'.','') ?>" step="0.01" min="0"></td>
          <td>
            <?php if (in_array($it['item_type'],['semi_expendable','equipment'])): ?>
            <select name="lines[<?= $it['ris_item_id'] ?>][personnel_id]" class="form-select form-select-sm">
              <option value="">-- assign to --</option>
              <?php foreach ($personnel as $p): ?>
                <option value="<?= $p['personnel_id'] ?>"><?= htmlspecialchars($p['full_name']) ?></option>
              <?php endforeach; ?>
            </select>
            <?php else: ?>
              <span class="text-muted small">N/A</span>
              <input type="hidden" name="lines[<?= $it['ris_item_id'] ?>][personnel_id]" value="">
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<button class="btn btn-success btn-lg"><i class="bi bi-check2-all me-1"></i>Confirm Issuance</button>
</form>
