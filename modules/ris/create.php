<?php
$pageTitle = 'New RIS';
$itemsJson = json_encode(array_map(fn($i) => [
    'item_id'         => $i['item_id'],
    'item_name'       => $i['item_name'],
    'unit_of_measure' => $i['unit_of_measure'],
    'unit_cost'       => $i['unit_cost'],
], $items ?? []));

$extraJs = '
<script>
window.ITEMS_DATA = ' . $itemsJson . ';
</script>
<script src="' . BASE_URL . '/assets/js/ris_items.js"></script>
';
?>

<div class="mb-3">
  <a href="<?= BASE_URL ?>/index.php?mod=ris&act=index"
     class="text-decoration-none">
    <i class="bi bi-arrow-left me-1"></i>Back to RIS List
  </a>
</div>

<?php if (!empty($flash)): ?>
  <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show">
    <?= htmlspecialchars($flash['msg'] ?? '') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<form method="post" id="ris-form">

  <div class="card mb-3">
    <div class="card-header"><strong>RIS Header</strong></div>
    <div class="card-body">
      <div class="row g-3">

        <div class="col-md-4">
          <label class="form-label fw-semibold">
            RIS Number <span class="text-danger">*</span>
          </label>
          <input type="text" name="ris_number" class="form-control" required
                 placeholder="e.g. RIS-2024-001">
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">
            Date <span class="text-danger">*</span>
          </label>
          <input type="date" name="ris_date" class="form-control"
                 value="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">
            Requesting Office <span class="text-danger">*</span>
          </label>
          <select name="office_id" class="form-select" required>
            <option value="">-- select office --</option>
            <?php foreach ($offices ?? [] as $o): ?>
              <option value="<?= $o['office_id'] ?>">
                <?= htmlspecialchars($o['display_name'] ?? $o['office_name'] ?? '') ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-12">
          <label class="form-label fw-semibold">Purpose</label>
          <input type="text" name="purpose" class="form-control"
                 placeholder="e.g. For office use">
        </div>

        <div class="col-md-6">
          <label class="form-label">Requested By</label>
          <input type="text" name="requested_by" class="form-control">
        </div>

        <div class="col-md-6">
          <label class="form-label">Approved By</label>
          <input type="text" name="approved_by" class="form-control">
        </div>

      </div>
    </div>
  </div>

  <div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
      <strong>Items Requested</strong>
      <button type="button" id="btn-add-ris-row" class="btn btn-sm btn-outline-primary">
        <i class="bi bi-plus-lg me-1"></i>Add Item
      </button>
    </div>
    <div class="card-body p-0">
      <table class="table table-sm mb-0" id="ris-items-table">
        <thead>
          <tr>
            <th>Item</th>
            <th style="width:120px">Qty Requested</th>
            <th style="width:100px">UOM</th>
            <th style="width:50px"></th>
          </tr>
        </thead>
        <tbody>
          <!-- rows added by JS -->
        </tbody>
      </table>
      <div id="no-items-msg" class="text-muted text-center py-3 small">
        Click <strong>Add Item</strong> to add items to this RIS.
      </div>
    </div>
  </div>

  <button type="submit" class="btn btn-primary btn-lg"
          onclick="return validateRis()">
    <i class="bi bi-save me-1"></i>Submit RIS
  </button>

</form>