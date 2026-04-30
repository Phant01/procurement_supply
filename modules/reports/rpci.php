<?php $pageTitle = 'RPCI'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0"><i class="bi bi-clipboard-data me-2"></i>Report on Physical Count of Inventories (RPCI)</h4>
  <div class="d-flex gap-2">
    <small class="text-muted align-self-center">As of <?= date(DATE_DISPLAY) ?></small>
    <a href="?mod=reports&act=rpci&print=1" target="_blank" class="btn btn-outline-dark btn-sm">
      <i class="bi bi-printer me-1"></i>Print</a>
  </div>
</div>
<div class="card"><div class="card-body p-0">
  <table class="table table-sm datatable mb-0">
    <thead><tr><th>Item Code</th><th>Item Name</th><th>Type</th><th>UACS Code</th>
      <th>UOM</th><th class="text-end">Balance Qty</th>
      <th class="text-end">Unit Cost</th><th class="text-end">Total Value</th></tr></thead>
    <tbody>
    <?php $total = 0; foreach ($rows as $r): $total += $r['total_value']; ?>
      <tr>
        <td><small><?= htmlspecialchars($r['item_code']) ?></small></td>
        <td><?= htmlspecialchars($r['item_name']) ?></td>
        <td><span class="badge badge-<?= $r['item_type'] ?>"><?= $r['item_type'] ?></span></td>
        <td><small><?= htmlspecialchars($r['uacs_code']) ?></small></td>
        <td><?= $r['unit_of_measure'] ?></td>
        <td class="text-end <?= $r['balance_qty']<=0?'text-danger':'' ?>">
          <?= number_format((float)$r['balance_qty'],2) ?></td>
        <td class="text-end">&#8369; <?= number_format((float)$r['unit_cost'],2) ?></td>
        <td class="text-end fw-semibold">&#8369; <?= number_format((float)$r['total_value'],2) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr class="table-secondary fw-bold">
        <td colspan="7" class="text-end">Total Inventory Value:</td>
        <td class="text-end">&#8369; <?= number_format($total,2) ?></td>
      </tr>
    </tfoot>
  </table>
</div></div>
