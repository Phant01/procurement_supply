<?php $pageTitle = 'Dashboard'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="fw-bold mb-0"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h4>
  <small class="text-muted"><?= date(DATE_DISPLAY) ?></small>
</div>

<div class="row g-3 mb-4">
  <?php
  $cards = [
    ['label'=>'Total Items',       'val'=>$stats['total_items'],    'icon'=>'boxes',       'cls'=>'blue'],
    ['label'=>'Pending POs',       'val'=>$stats['pending_po'],     'icon'=>'file-text',   'cls'=>'orange'],
    ['label'=>'Pending RIS',       'val'=>$stats['pending_ris'],    'icon'=>'clipboard',   'cls'=>'orange'],
    ['label'=>'Low Stock Items',   'val'=>$stats['low_stock'],      'icon'=>'exclamation-triangle','cls'=>'red'],
    ['label'=>'Active ICS',        'val'=>$stats['active_ics'],     'icon'=>'person-badge','cls'=>'green'],
    ['label'=>'Active PAR',        'val'=>$stats['active_par'],     'icon'=>'pc-display',  'cls'=>'green'],
  ];
  foreach ($cards as $c):
  ?>
  <div class="col-6 col-md-4 col-xl-2">
    <div class="card card-stat <?= $c['cls'] ?> h-100">
      <div class="card-body py-3">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="fs-4 fw-bold"><?= number_format($c['val']) ?></div>
            <div class="text-muted small"><?= $c['label'] ?></div>
          </div>
          <i class="bi bi-<?= $c['icon'] ?> fs-3 text-muted opacity-50"></i>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<div class="card mb-4">
  <div class="card-header bg-warning bg-opacity-10 border-warning">
    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
    <strong>Low Stock Alert</strong>
    <a href="<?= BASE_URL ?>/index.php?mod=reports&act=lowStock" class="btn btn-sm btn-outline-secondary float-end">View All</a>
  </div>
  <div class="card-body p-0">
    <?php if (empty($lowStock)): ?>
      <p class="text-muted text-center py-3 mb-0">All items are sufficiently stocked.</p>
    <?php else: ?>
    <table class="table table-sm table-hover mb-0">
      <thead><tr><th>Item</th><th>Category</th><th class="text-end">Balance</th><th class="text-end">Reorder Point</th></tr></thead>
      <tbody>
      <?php foreach (array_slice($lowStock, 0, 10) as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['item_name']) ?></td>
          <td><small><?= htmlspecialchars($r['category_name']) ?></small></td>
          <td class="text-end text-danger fw-bold"><?= number_format((float)$r['balance_qty'], 2) ?> <?= $r['unit_of_measure'] ?></td>
          <td class="text-end"><?= number_format((float)$r['reorder_point'], 2) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>

<div class="card">
  <div class="card-header"><strong>Inventory Value Summary</strong></div>
  <div class="card-body">
    <h4 class="text-success">&#8369; <?= number_format((float)$stats['total_inv_value'], 2) ?></h4>
    <small class="text-muted">Total value of all items in stock (based on unit cost × balance quantity)</small>
  </div>
</div>
