<?php $pageTitle = 'PO #' . htmlspecialchars($po['po_number'] ?? ''); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <a href="<?= BASE_URL ?>/index.php?mod=purchase_orders&act=index" class="text-decoration-none me-3">
      <i class="bi bi-arrow-left me-1"></i>Back</a>
    <strong>Purchase Order: <?= htmlspecialchars($po['po_number']) ?></strong>
  </div>
  <div class="d-flex gap-2">
    <?php if (in_array($po['status'], ['approved','partially_received'])): ?>
    <a href="<?= BASE_URL ?>/index.php?mod=receiving&act=create&po_id=<?= $po['po_id'] ?>"
       class="btn btn-success btn-sm"><i class="bi bi-box-arrow-in-down me-1"></i>Receive Delivery</a>
    <?php endif; ?>
    <a href="<?= BASE_URL ?>/index.php?mod=purchase_orders&act=print&id=<?= $po['po_id'] ?>"
       target="_blank" class="btn btn-outline-dark btn-sm"><i class="bi bi-printer me-1"></i>Print</a>
  </div>
</div>
<div class="row g-3 mb-3">
  <div class="col-md-6">
    <div class="card h-100"><div class="card-body">
      <dl class="row mb-0">
        <dt class="col-5">PO Number</dt><dd class="col-7"><?= htmlspecialchars($po['po_number']) ?></dd>
        <dt class="col-5">PO Date</dt><dd class="col-7"><?= date('F d, Y', strtotime($po['po_date'])) ?></dd>
        <dt class="col-5">Status</dt><dd class="col-7">
          <span class="badge status-<?= $po['status'] ?>"><?= str_replace('_',' ',ucfirst($po['status'])) ?></span></dd>
        <dt class="col-5">Fund Source</dt><dd class="col-7"><?= htmlspecialchars($po['fund_source']) ?></dd>
        <dt class="col-5">Mode</dt><dd class="col-7"><?= htmlspecialchars($po['mode_of_procurement']) ?></dd>
      </dl>
    </div></div>
  </div>
  <div class="col-md-6">
    <div class="card h-100"><div class="card-body">
      <dl class="row mb-0">
        <dt class="col-5">Supplier</dt><dd class="col-7 fw-semibold"><?= htmlspecialchars($po['supplier_name']) ?></dd>
        <dt class="col-5">Office</dt><dd class="col-7"><?= htmlspecialchars($po['office_name']) ?></dd>
        <dt class="col-5">Delivery Date</dt><dd class="col-7"><?= $po['delivery_date'] ? date('m/d/Y',strtotime($po['delivery_date'])) : '—' ?></dd>
        <dt class="col-5">Approved By</dt><dd class="col-7"><?= htmlspecialchars($po['approved_by']) ?></dd>
        <dt class="col-5">Total Amount</dt><dd class="col-7 fw-bold text-success fs-5">&#8369; <?= number_format((float)$po['total_amount'], 2) ?></dd>
      </dl>
    </div></div>
  </div>
</div>
<div class="card">
  <div class="card-header"><strong>Line Items</strong></div>
  <div class="card-body p-0">
    <table class="table table-sm mb-0">
      <thead><tr><th>#</th><th>Item</th><th>Type</th><th>UOM</th>
        <th class="text-end">Qty Ordered</th><th class="text-end">Unit Price</th>
        <th class="text-end">Total</th><th class="text-end">Received</th></tr></thead>
      <tbody>
      <?php $n=1; foreach ($lines as $l): ?>
        <tr>
          <td><?= $n++ ?></td>
          <td><?= htmlspecialchars($l['item_name']) ?></td>
          <td><span class="badge badge-<?= $l['item_type'] ?>"><?= $l['item_type'] ?></span></td>
          <td><?= $l['unit_of_measure'] ?></td>
          <td class="text-end"><?= number_format((float)$l['qty_ordered'],2) ?></td>
          <td class="text-end">&#8369; <?= number_format((float)$l['unit_price'],2) ?></td>
          <td class="text-end fw-semibold">&#8369; <?= number_format($l['qty_ordered']*$l['unit_price'],2) ?></td>
          <td class="text-end <?= $l['qty_received']>=$l['qty_ordered']?'text-success':'' ?>">
            <?= number_format((float)$l['qty_received'],2) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
