<?php $pageTitle = 'Deliveries / IAR'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0"><i class="bi bi-box-arrow-in-down me-2"></i>Deliveries / IAR</h4>
</div>
<div class="card"><div class="card-body p-0">
  <table class="table datatable mb-0">
    <thead><tr><th>IAR No.</th><th>Date</th><th>PO No.</th><th>Supplier</th><th>Received By</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($receipts as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['iar_number']) ?></td>
        <td><?= date('m/d/Y', strtotime($r['receipt_date'])) ?></td>
        <td><?= htmlspecialchars($r['po_number']) ?></td>
        <td><?= htmlspecialchars($r['supplier_name']) ?></td>
        <td><?= htmlspecialchars($r['received_by']) ?></td>
        <td>
          <a href="<?= BASE_URL ?>/index.php?mod=receiving&act=view&id=<?= $r['receipt_id'] ?>"
             class="btn btn-xs btn-outline-secondary"><i class="bi bi-eye"></i></a>
          <a href="<?= BASE_URL ?>/index.php?mod=receiving&act=printIar&id=<?= $r['receipt_id'] ?>"
             target="_blank" class="btn btn-xs btn-outline-dark"><i class="bi bi-printer"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div></div>
