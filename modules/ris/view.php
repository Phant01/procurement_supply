<?php $pageTitle = 'RIS ' . htmlspecialchars($ris['ris_number'] ?? ''); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <a href="<?= BASE_URL ?>/index.php?mod=ris&act=index" class="text-decoration-none me-3">
      <i class="bi bi-arrow-left me-1"></i>Back</a>
    <strong>RIS: <?= htmlspecialchars($ris['ris_number'] ?? '') ?></strong>
    <span class="badge status-<?= $ris['status'] ?> ms-2"><?= ucfirst($ris['status'] ?? '') ?></span>
  </div>
  <div class="d-flex gap-2">
    <?php if ($ris['status'] === 'pending'): ?>
      <a href="<?= BASE_URL ?>/index.php?mod=ris&act=issue&id=<?= $ris['ris_id'] ?>"
         class="btn btn-success btn-sm">
        <i class="bi bi-check2-circle me-1"></i>Issue Items</a>
    <?php endif; ?>
    <a href="<?= BASE_URL ?>/index.php?mod=ris&act=print&id=<?= $ris['ris_id'] ?>"
       target="_blank" class="btn btn-outline-dark btn-sm">
      <i class="bi bi-printer me-1"></i>Print</a>
  </div>
</div>

<div class="card mb-3">
  <div class="card-body">
    <div class="row">
      <div class="col-md-4">
        <dl class="row mb-0">
          <dt class="col-5">RIS No.</dt>
          <dd class="col-7"><?= htmlspecialchars($ris['ris_number']  ?? '') ?></dd>
          <dt class="col-5">Date</dt>
          <dd class="col-7"><?= $ris['ris_date'] ? date('m/d/Y', strtotime($ris['ris_date'])) : '' ?></dd>
        </dl>
      </div>
      <div class="col-md-4">
        <dl class="row mb-0">
          <dt class="col-5">Office</dt>
          <dd class="col-7"><?= htmlspecialchars($ris['office_name']  ?? '') ?></dd>
          <dt class="col-5">Purpose</dt>
          <dd class="col-7"><?= htmlspecialchars($ris['purpose']      ?? '') ?></dd>
        </dl>
      </div>
      <div class="col-md-4">
        <dl class="row mb-0">
          <dt class="col-5">Requested By</dt>
          <dd class="col-7"><?= htmlspecialchars($ris['requested_by'] ?? '') ?></dd>
          <dt class="col-5">Approved By</dt>
          <dd class="col-7"><?= htmlspecialchars($ris['approved_by']  ?? '') ?></dd>
          <dt class="col-5">Issued By</dt>
          <dd class="col-7"><?= htmlspecialchars($ris['issued_by']    ?? '') ?></dd>
          <dt class="col-5">Received By</dt>
          <dd class="col-7"><?= htmlspecialchars($ris['received_by']  ?? '') ?></dd>
        </dl>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-body p-0">
    <table class="table table-sm mb-0">
      <thead>
        <tr>
          <th>Item</th>
          <th>Type</th>
          <th>UOM</th>
          <th class="text-end">Qty Requested</th>
          <th class="text-end">Qty Issued</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($items as $it): ?>
        <tr>
          <td><?= htmlspecialchars($it['item_name']      ?? '') ?></td>
          <td><span class="badge badge-<?= htmlspecialchars($it['item_type'] ?? '') ?>">
            <?= htmlspecialchars($it['item_type'] ?? '') ?></span></td>
          <td><?= htmlspecialchars($it['unit_of_measure'] ?? '') ?></td>
          <td class="text-end"><?= number_format((float)($it['qty_requested'] ?? 0), 2) ?></td>
          <td class="text-end fw-semibold"><?= number_format((float)($it['qty_issued'] ?? 0), 2) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>