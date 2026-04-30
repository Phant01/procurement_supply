<?php $pageTitle = 'Purchase Orders'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0"><i class="bi bi-file-earmark-text me-2"></i>Purchase Orders</h4>
  <a href="<?= BASE_URL ?>/index.php?mod=purchase_orders&act=create" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg me-1"></i>New PO</a>
</div>
<div class="mb-3 d-flex gap-2 flex-wrap">
  <?php foreach ([''=>'All','approved'=>'Approved','partially_received'=>'Partial','fully_received'=>'Fully Received','cancelled'=>'Cancelled'] as $v=>$l): ?>
    <a href="<?= BASE_URL ?>/index.php?mod=purchase_orders&act=index&status=<?= $v ?>"
       class="btn btn-sm <?= $status===$v?'btn-primary':'btn-outline-secondary' ?>"><?= $l ?></a>
  <?php endforeach; ?>
</div>
<div class="card"><div class="card-body p-0">
  <table class="table datatable mb-0">
    <thead><tr><th>PO No.</th><th>Date</th><th>Supplier</th><th>Office</th>
      <th class="text-end">Amount</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($pos as $r): ?>
      <tr>
        <td><a href="<?= BASE_URL ?>/index.php?mod=purchase_orders&act=view&id=<?= $r['po_id'] ?>"
               class="fw-semibold text-decoration-none"><?= htmlspecialchars($r['po_number']) ?></a></td>
        <td><?= date('m/d/Y', strtotime($r['po_date'])) ?></td>
        <td><?= htmlspecialchars($r['supplier_name']) ?></td>
        <td><small><?= htmlspecialchars($r['office_name']) ?></small></td>
        <td class="text-end">&#8369; <?= number_format((float)$r['total_amount'], 2) ?></td>
        <td><span class="badge status-<?= $r['status'] ?>"><?= str_replace('_',' ',ucfirst($r['status'])) ?></span></td>
        <td>
          <a href="<?= BASE_URL ?>/index.php?mod=purchase_orders&act=view&id=<?= $r['po_id'] ?>"
             class="btn btn-xs btn-outline-secondary"><i class="bi bi-eye"></i></a>
          <a href="<?= BASE_URL ?>/index.php?mod=purchase_orders&act=print&id=<?= $r['po_id'] ?>"
             target="_blank" class="btn btn-xs btn-outline-dark"><i class="bi bi-printer"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div></div>
