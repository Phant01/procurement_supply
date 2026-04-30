<?php $pageTitle = 'New Purchase Order';
$itemsJson = json_encode(array_map(fn($i) => [
    'item_id'=>$i['item_id'], 'item_name'=>$i['item_name'],
    'unit_cost'=>$i['unit_cost'], 'unit_of_measure'=>$i['unit_of_measure']
], $items));
?>
<div class="mb-3"><a href="<?= BASE_URL ?>/index.php?mod=purchase_orders&act=index"
  class="text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Back to POs</a></div>
<form method="post">
<div class="card mb-3">
  <div class="card-header"><strong>Purchase Order Header</strong></div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label fw-semibold">PO Number <span class="text-danger">*</span></label>
        <input type="text" name="po_number" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold">PO Date <span class="text-danger">*</span></label>
        <input type="date" name="po_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold">Expected Delivery</label>
        <input type="date" name="delivery_date" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
        <select name="supplier_id" class="form-select" required>
          <option value="">-- select supplier --</option>
          <?php foreach ($suppliers as $s): ?>
            <option value="<?= $s['supplier_id'] ?>"><?= htmlspecialchars($s['supplier_name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
          <label class="form-label fw-semibold">Requesting Office <span class="text-danger">*</span>
          </label>
          <select name="office_id" class="form-select" required>
          <option value="">-- select office --</option>
          <?php foreach ($offices as $o): ?>
          <option value="<?= $o['office_id'] ?>">
          <?= htmlspecialchars($o['display_name'] ?? $o['office_name'] ?? '') ?>
          </option>
          <?php endforeach; ?>
          </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Mode of Procurement</label>
        <select name="mode_of_procurement" class="form-select">
          <option>Small Value Procurement</option>
          <option>Shopping</option>
          <option>Negotiated Procurement</option>
          <option>Competitive Bidding</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Fund Source</label>
        <select name="fund_source" class="form-select">
          <option>MOOE</option><option>Capital Outlay</option>
          <option>GAA</option><option>SEF</option><option>Trust Fund</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Place of Delivery</label>
        <input type="text" name="place_of_delivery" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">Confirmed by</label>
        <input type="text" name="approved_by" class="form-control">
      </div>
      <div class="col-md-3">
        <label class="form-label">Confirmed Date</label>
        <input type="date" name="approved_date" class="form-control">
      </div>
    </div>
  </div>
</div>
<div class="card mb-3">
  <div class="card-header d-flex justify-content-between align-items-center">
    <strong>Items</strong>
    <button type="button" id="btn-add-row" class="btn btn-sm btn-outline-primary">
      <i class="bi bi-plus-lg me-1"></i>Add Item Row</button>
  </div>
  <div class="card-body p-0">
    <table class="table table-sm mb-0" id="po-items-table">
      <thead><tr><th>Item</th><th>Qty</th><th>UOM</th><th>Unit Price (&#8369;)</th>
        <th class="text-end">Total</th><th></th></tr></thead>
      <tbody></tbody>
      <tfoot><tr><td colspan="4" class="text-end fw-bold">Grand Total:</td>
        <td class="text-end fw-bold" id="grand-total">0.00</td><td></td></tr></tfoot>
    </table>
    <input type="hidden" id="grand-total-input" name="total_amount" value="0">
  </div>
</div>
<button class="btn btn-primary btn-lg"><i class="bi bi-save me-1"></i>Save Purchase Order</button>
</form>

<?php $extraJs = '
<script>
window.ITEMS_DATA = ' . $itemsJson . ';
</script>
<script src="' . BASE_URL . '/assets/js/po_items.js"></script>
'; ?>
