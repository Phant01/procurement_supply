<?php $pageTitle = 'PAR ' . htmlspecialchars($row['par_number']); ?>
<div class="d-flex justify-content-between mb-3">
  <a href="<?= BASE_URL ?>/index.php?mod=par&act=index" class="text-decoration-none">
    <i class="bi bi-arrow-left me-1"></i>Back to PAR List</a>
  <a href="<?= BASE_URL ?>/index.php?mod=par&act=print&id=<?= $row['par_id'] ?>"
     target="_blank" class="btn btn-outline-dark btn-sm"><i class="bi bi-printer me-1"></i>Print PAR</a>
</div>
<div class="card" style="max-width:700px"><div class="card-body">
  <h5 class="fw-bold border-bottom pb-2 mb-3">PROPERTY ACKNOWLEDGEMENT RECEIPT</h5>
  <dl class="row">
    <dt class="col-4">PAR No.</dt><dd class="col-8"><?= htmlspecialchars($row['par_number']) ?></dd>
    <dt class="col-4">Date</dt><dd class="col-8"><?= date('F d, Y', strtotime($row['par_date'])) ?></dd>
    <dt class="col-4">Item</dt><dd class="col-8 fw-semibold"><?= htmlspecialchars($row['item_name']) ?></dd>
    <dt class="col-4">Brand / Model</dt><dd class="col-8"><?= htmlspecialchars($row['brand_model']) ?></dd>
    <dt class="col-4">Serial No.</dt><dd class="col-8"><?= htmlspecialchars($row['serial_no']) ?></dd>
    <dt class="col-4">Property No.</dt><dd class="col-8"><?= htmlspecialchars($row['property_no']) ?></dd>
    <dt class="col-4">Quantity</dt><dd class="col-8"><?= number_format((float)$row['quantity'],2) ?> <?= $row['unit_of_measure'] ?></dd>
    <dt class="col-4">Unit Cost</dt><dd class="col-8">&#8369; <?= number_format((float)$row['unit_cost'],2) ?></dd>
    <dt class="col-4">Total Cost</dt><dd class="col-8 fw-bold text-success">&#8369; <?= number_format((float)$row['total_cost'],2) ?></dd>
    <dt class="col-4">Location</dt><dd class="col-8"><?= htmlspecialchars($row['location']) ?></dd>
    <dt class="col-4">Assigned To</dt><dd class="col-8 fw-semibold"><?= htmlspecialchars($row['assigned_to']) ?></dd>
    <dt class="col-4">Position</dt><dd class="col-8"><?= htmlspecialchars($row['position']) ?></dd>
    <dt class="col-4">Office</dt><dd class="col-8"><?= htmlspecialchars($row['office_name']) ?></dd>
    <dt class="col-4">Status</dt><dd class="col-8"><span class="badge status-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></dd>
  </dl>
</div></div>
