<?php $pageTitle = 'Items / Supplies'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0"><i class="bi bi-boxes me-2"></i>Items &amp; Supplies</h4>
  <a href="<?= BASE_URL ?>/index.php?mod=items&act=create" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg me-1"></i>Add Item</a>
</div>
<div class="card"><div class="card-body p-0">
  <table class="table datatable mb-0">
    <thead><tr><th>Code</th><th>Item Name</th><th>Category</th><th>Type</th><th>UOM</th>
      <th class="text-end">Unit Cost</th><th class="text-end">Balance</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($items as $r): ?>
      <tr>
        <td><small><?= htmlspecialchars($r['item_code']) ?></small></td>
        <td><?= htmlspecialchars($r['item_name']) ?></td>
        <td><small><?= htmlspecialchars($r['category_name']) ?></small></td>
        <td><span class="badge badge-<?= $r['item_type'] ?>"><?= $r['item_type'] ?></span></td>
        <td><?= htmlspecialchars($r['unit_of_measure']) ?></td>
        <td class="text-end">&#8369; <?= number_format((float)$r['unit_cost'], 2) ?></td>
        <td class="text-end">—</td>
        <td>
          <a href="<?= BASE_URL ?>/index.php?mod=items&act=edit&id=<?= $r['item_id'] ?>"
             class="btn btn-xs btn-outline-primary"><i class="bi bi-pencil"></i></a>
          <a href="<?= BASE_URL ?>/index.php?mod=stock_cards&act=view&item_id=<?= $r['item_id'] ?>"
             class="btn btn-xs btn-outline-secondary"><i class="bi bi-card-list"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div></div>
