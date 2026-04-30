<?php $pageTitle = 'COA Reports'; ?>
<h4 class="fw-bold mb-4"><i class="bi bi-printer me-2"></i>COA Reports</h4>
<div class="row g-3">
<?php
$reports = [
  ['title'=>'Stock Card',      'desc'=>'Per-item movement ledger (receipts, issuances, adjustments)',
   'icon'=>'card-list',    'url'=>'stock_cards&act=index',   'color'=>'primary'],
  ['title'=>'RSMI',            'desc'=>'Report of Supplies and Materials Issued — monthly summary',
   'icon'=>'receipt',      'url'=>'reports&act=rsmi',         'color'=>'success'],
  ['title'=>'RPCI',            'desc'=>'Report on Physical Count of Inventories — all stock balances',
   'icon'=>'clipboard-data','url'=>'reports&act=rpci',        'color'=>'info'],
  ['title'=>'ICS Registry',    'desc'=>'Inventory Custodian Slip accountability list',
   'icon'=>'person-badge', 'url'=>'reports&act=icsRegistry',  'color'=>'warning'],
  ['title'=>'PAR Registry',    'desc'=>'Property Acknowledgement Receipt accountability list',
   'icon'=>'pc-display',   'url'=>'reports&act=parRegistry',  'color'=>'danger'],
  ['title'=>'Low Stock Alert', 'desc'=>'Items at or below their reorder point',
   'icon'=>'exclamation-triangle','url'=>'reports&act=lowStock','color'=>'secondary'],
];
foreach ($reports as $r): ?>
  <div class="col-md-4">
    <div class="card h-100 border-<?= $r['color'] ?>">
      <div class="card-body">
        <div class="d-flex align-items-center gap-3 mb-2">
          <i class="bi bi-<?= $r['icon'] ?> fs-2 text-<?= $r['color'] ?>"></i>
          <h6 class="fw-bold mb-0"><?= $r['title'] ?></h6>
        </div>
        <p class="text-muted small mb-3"><?= $r['desc'] ?></p>
        <a href="<?= BASE_URL ?>/index.php?mod=<?= $r['url'] ?>"
           class="btn btn-sm btn-<?= $r['color'] ?>">Open Report</a>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>
