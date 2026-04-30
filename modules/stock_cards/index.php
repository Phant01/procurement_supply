<?php $pageTitle = 'Stock Cards'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0"><i class="bi bi-card-list me-2"></i>Stock Cards</h4>
</div>
<div class="card"><div class="card-body p-0">
  <table class="table datatable mb-0">
    <thead><tr><th>Item Code</th><th>Item Name</th><th>Type</th><th>UOM</th>
      <th class="text-end">Balance</th><th class="text-end">Unit Cost</th><th class="text-end">Total Value</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($cards as $r): ?>
      <tr>
        <td><small><?= htmlspecialchars($r['item_code']) ?></small></td>
        <td><?= htmlspecialchars($r['item_name']) ?></td>
        <td><span class="badge badge-<?= $r['item_type'] ?>"><?= $r['item_type'] ?></span></td>
        <td><?= $r['unit_of_measure'] ?></td>
        <td class="text-end fw-semibold <?= $r['balance_qty']<=0?'text-danger':'' ?>">
          <?= number_format((float)$r['balance_qty'], 2) ?></td>
        <td class="text-end">&#8369; <?= number_format((float)$r['unit_cost'], 2) ?></td>
        <td class="text-end">&#8369; <?= number_format((float)$r['total_value'], 2) ?></td>
        <td>
          <a href="<?= BASE_URL ?>/index.php?mod=stock_cards&act=view&item_id=<?= $r['item_id'] ?>"
             class="btn btn-xs btn-outline-secondary"><i class="bi bi-eye"></i></a>
          <a href="<?= BASE_URL ?>/index.php?mod=stock_cards&act=adjust&item_id=<?= $r['item_id'] ?>"
             class="btn btn-xs btn-outline-warning"><i class="bi bi-pencil-square"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div></div>
