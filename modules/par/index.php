<?php $pageTitle = 'Property Acknowledgement Receipts'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0"><i class="bi bi-pc-display me-2"></i>Property Acknowledgement Receipts (PAR)</h4>
</div>
<div class="mb-3 d-flex gap-2">
  <?php foreach (['active'=>'Active','transferred'=>'Transferred','returned'=>'Returned','disposed'=>'Disposed',''=>'All'] as $v=>$l): ?>
    <a href="<?= BASE_URL ?>/index.php?mod=par&act=index&status=<?= $v ?>"
       class="btn btn-sm <?= $status===$v?'btn-primary':'btn-outline-secondary' ?>"><?= $l ?></a>
  <?php endforeach; ?>
</div>
<div class="card"><div class="card-body p-0">
  <table class="table datatable mb-0">
    <thead><tr><th>PAR No.</th><th>Date</th><th>Item</th><th>Brand/Model</th>
      <th>Serial No.</th><th>Assigned To</th><th>Office</th>
      <th class="text-end">Total Cost</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($registry as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['par_number']) ?></td>
        <td><?= date('m/d/Y', strtotime($r['par_date'])) ?></td>
        <td><?= htmlspecialchars($r['item_name']) ?></td>
        <td><small><?= htmlspecialchars($r['brand_model']) ?></small></td>
        <td><small><?= htmlspecialchars($r['serial_no']) ?></small></td>
        <td><?= htmlspecialchars($r['assigned_to']) ?></td>
        <td><small><?= htmlspecialchars($r['office_name']) ?></small></td>
        <td class="text-end">&#8369; <?= number_format((float)$r['total_cost'],2) ?></td>
        <td><span class="badge status-<?= $r['status'] ?>"><?= ucfirst($r['status']) ?></span></td>
        <td>
          <a href="<?= BASE_URL ?>/index.php?mod=par&act=view&id=<?= $r['par_id'] ?>"
             class="btn btn-xs btn-outline-secondary"><i class="bi bi-eye"></i></a>
          <a href="<?= BASE_URL ?>/index.php?mod=par&act=print&id=<?= $r['par_id'] ?>"
             target="_blank" class="btn btn-xs btn-outline-dark"><i class="bi bi-printer"></i></a>
          <?php if ($r['status']==='active'): ?>
          <a href="<?= BASE_URL ?>/index.php?mod=par&act=transfer&id=<?= $r['par_id'] ?>"
             class="btn btn-xs btn-outline-info">Transfer</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div></div>
