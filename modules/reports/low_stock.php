<?php $pageTitle = 'Low Stock Alert'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0"><i class="bi bi-exclamation-triangle text-danger me-2"></i>Low Stock Alert</h4>
  <a href="?mod=reports&act=lowStock&print=1" target="_blank" class="btn btn-outline-dark btn-sm">
    <i class="bi bi-printer me-1"></i>Print</a>
</div>
<div class="card"><div class="card-body p-0">
  <table class="table datatable mb-0">
    <thead><tr><th>Item</th><th>Type</th><th>Category</th><th>UOM</th>
      <th class="text-end">Balance</th><th class="text-end">Reorder Point</th>
      <th class="text-end">Shortage</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['item_name']) ?></td>
        <td><span class="badge badge-<?= $r['item_type'] ?>"><?= $r['item_type'] ?></span></td>
        <td><small><?= htmlspecialchars($r['category_name']) ?></small></td>
        <td><?= $r['unit_of_measure'] ?></td>
        <td class="text-end text-danger fw-bold"><?= number_format((float)$r['balance_qty'],2) ?></td>
        <td class="text-end"><?= number_format((float)$r['reorder_point'],2) ?></td>
        <td class="text-end text-warning fw-semibold">
          <?= number_format(max(0, $r['reorder_point'] - $r['balance_qty']),2) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div></div>
