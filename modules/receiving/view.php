<?php $pageTitle = 'IAR ' . htmlspecialchars($receipt['iar_number']); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <a href="<?= BASE_URL ?>/index.php?mod=receiving&act=index" class="text-decoration-none me-3">
      <i class="bi bi-arrow-left me-1"></i>Back</a>
    <strong>IAR: <?= htmlspecialchars($receipt['iar_number']) ?></strong>
  </div>
  <a href="<?= BASE_URL ?>/index.php?mod=receiving&act=printIar&id=<?= $receipt['receipt_id'] ?>"
     target="_blank" class="btn btn-outline-dark btn-sm"><i class="bi bi-printer me-1"></i>Print IAR</a>
</div>
<div class="card mb-3"><div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <dl class="row mb-0">
        <dt class="col-5">IAR No.</dt><dd class="col-7"><?= htmlspecialchars($receipt['iar_number']) ?></dd>
        <dt class="col-5">Date</dt><dd class="col-7"><?= date('F d, Y', strtotime($receipt['receipt_date'])) ?></dd>
        <dt class="col-5">PO No.</dt><dd class="col-7"><?= htmlspecialchars($receipt['po_number']) ?></dd>
      </dl>
    </div>
    <div class="col-md-6">
      <dl class="row mb-0">
        <dt class="col-5">Supplier</dt><dd class="col-7"><?= htmlspecialchars($receipt['supplier_name']) ?></dd>
        <dt class="col-5">Received By</dt><dd class="col-7"><?= htmlspecialchars($receipt['received_by']) ?></dd>
        <dt class="col-5">Inspected By</dt><dd class="col-7"><?= htmlspecialchars($receipt['inspected_by']) ?></dd>
      </dl>
    </div>
  </div>
</div></div>
<div class="card"><div class="card-body p-0">
  <table class="table table-sm mb-0">
    <thead><tr><th>Item</th><th>UOM</th><th class="text-end">Received</th><th class="text-end">Unit Cost</th><th class="text-end">Amount</th></tr></thead>
    <tbody>
    <?php foreach ($lines as $l): ?>
      <tr>
        <td><?= htmlspecialchars($l['item_name']) ?></td>
        <td><?= $l['unit_of_measure'] ?></td>
        <td class="text-end"><?= number_format((float)$l['qty_received'], 2) ?></td>
        <td class="text-end">&#8369; <?= number_format((float)$l['unit_cost'], 2) ?></td>
        <td class="text-end">&#8369; <?= number_format($l['qty_received']*$l['unit_cost'], 2) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div></div>
