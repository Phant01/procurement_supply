<?php $pageTitle = 'RSMI Report'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0"><i class="bi bi-receipt me-2"></i>Report of Supplies and Materials Issued (RSMI)</h4>
  <?php if (!empty($rows)): ?>
  <a href="?mod=reports&act=rsmi&from=<?= $from ?>&to=<?= $to ?>&print=1"
     target="_blank" class="btn btn-outline-dark btn-sm"><i class="bi bi-printer me-1"></i>Print</a>
  <?php endif; ?>
</div>
<form class="card p-3 mb-3 d-flex flex-row gap-3 align-items-end flex-wrap">
  <input type="hidden" name="mod" value="reports"><input type="hidden" name="act" value="rsmi">
  <div>
    <label class="form-label small mb-1 fw-semibold">From</label>
    <input type="date" name="from" class="form-control form-control-sm" value="<?= $from ?>">
  </div>
  <div>
    <label class="form-label small mb-1 fw-semibold">To</label>
    <input type="date" name="to" class="form-control form-control-sm" value="<?= $to ?>">
  </div>
  <button class="btn btn-primary btn-sm">Generate</button>
</form>
<?php if (!empty($rows)): ?>
<div class="card"><div class="card-body p-0">
  <table class="table table-sm datatable mb-0">
    <thead><tr><th>Date</th><th>RIS No.</th><th>Office</th><th>Item</th>
      <th>UACS Code</th><th>UOM</th><th class="text-end">Qty</th>
      <th class="text-end">Unit Cost</th><th class="text-end">Total</th></tr></thead>
    <tbody>
    <?php $grandTotal = 0; foreach ($rows as $r): $grandTotal += $r['total_cost']; ?>
      <tr>
        <td><?= date('m/d/Y', strtotime($r['txn_date'])) ?></td>
        <td><?= htmlspecialchars($r['ris_number']) ?></td>
        <td><small><?= htmlspecialchars($r['office_name']) ?></small></td>
        <td><?= htmlspecialchars($r['item_name']) ?></td>
        <td><small><?= htmlspecialchars($r['uacs_code']) ?></small></td>
        <td><?= $r['unit_of_measure'] ?></td>
        <td class="text-end"><?= number_format((float)$r['qty_issued'],2) ?></td>
        <td class="text-end">&#8369; <?= number_format((float)$r['unit_cost'],2) ?></td>
        <td class="text-end fw-semibold">&#8369; <?= number_format((float)$r['total_cost'],2) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr class="table-secondary fw-bold">
        <td colspan="8" class="text-end">Grand Total:</td>
        <td class="text-end">&#8369; <?= number_format($grandTotal, 2) ?></td>
      </tr>
    </tfoot>
  </table>
</div></div>
<?php else: ?>
  <div class="alert alert-info">Select a date range and click Generate to view the RSMI.</div>
<?php endif; ?>
