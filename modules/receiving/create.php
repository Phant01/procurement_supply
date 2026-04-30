<?php $pageTitle = 'Receive Delivery'; ?>
<div class="mb-3"><a href="<?= BASE_URL ?>/index.php?mod=purchase_orders&act=view&id=<?= $po['po_id'] ?>"
  class="text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Back to PO <?= htmlspecialchars($po['po_number']) ?></a></div>
<form method="post">
<div class="card mb-3">
  <div class="card-header"><strong>IAR Header &mdash; PO <?= htmlspecialchars($po['po_number']) ?></strong></div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label fw-semibold">IAR Number <span class="text-danger">*</span></label>
        <input type="text" name="iar_number" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold">Receipt Date <span class="text-danger">*</span></label>
        <input type="date" name="receipt_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Supplier DR / Invoice No.</label>
        <input type="text" name="delivery_ref" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">Received By</label>
        <input type="text" name="received_by" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">Inspected By</label>
        <input type="text" name="inspected_by" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">Approved By</label>
        <input type="text" name="approved_by" class="form-control">
      </div>
      <div class="col-12">
        <label class="form-label">Remarks</label>
        <input type="text" name="remarks" class="form-control">
      </div>
    </div>
  </div>
</div>
<div class="card mb-3">
  <div class="card-header"><strong>Items Received</strong></div>
  <div class="card-body p-0">
    <table class="table table-sm mb-0">
      <thead><tr><th>Item</th><th>UOM</th><th class="text-end">Ordered</th>
        <th class="text-end">Previously Received</th><th>Qty Received Now</th><th>Unit Cost (&#8369;)</th></tr></thead>
      <tbody>
      <?php foreach ($lines as $l): ?>
        <tr>
          <td><?= htmlspecialchars($l['item_name']) ?></td>
          <td><?= htmlspecialchars($l['unit_of_measure']) ?></td>
          <td class="text-end"><?= number_format((float)$l['qty_ordered'], 2) ?></td>
          <td class="text-end"><?= number_format((float)$l['qty_received'], 2) ?></td>
          <td><input type="number" name="lines[<?= $l['po_item_id'] ?>][qty_received]"
              class="form-control form-control-sm" style="width:100px"
              max="<?= $l['qty_ordered'] - $l['qty_received'] ?>"
              min="0" step="any" value="0"></td>
          <td><input type="number" name="lines[<?= $l['po_item_id'] ?>][unit_cost]"
              class="form-control form-control-sm" style="width:110px"
              value="<?= number_format((float)$l['unit_price'], 2, '.', '') ?>" step="0.01" min="0"></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<button class="btn btn-success btn-lg"><i class="bi bi-check2-circle me-1"></i>Confirm Receipt &amp; Update Stock</button>
</form>
