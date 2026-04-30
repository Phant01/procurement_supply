<?php $pageTitle = 'PAR Registry'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0"><i class="bi bi-pc-display me-2"></i>PAR Accountability Registry</h4>
  <a href="?mod=reports&act=parRegistry&status=<?= $status ?>&print=1" target="_blank"
     class="btn btn-outline-dark btn-sm"><i class="bi bi-printer me-1"></i>Print</a>
</div>
<div class="mb-3 d-flex gap-2">
  <?php foreach (['active'=>'Active','transferred'=>'Transferred','returned'=>'Returned','disposed'=>'Disposed',''=>'All'] as $v=>$l): ?>
    <a href="?mod=reports&act=parRegistry&status=<?= $v ?>"
       class="btn btn-sm <?= $status===$v?'btn-primary':'btn-outline-secondary' ?>"><?= $l ?></a>
  <?php endforeach; ?>
</div>
<div class="card"><div class="card-body p-0">
  <table class="table table-sm datatable mb-0">
    <thead><tr><th>PAR No.</th><th>Date</th><th>Item</th><th>Brand/Model</th>
      <th>Serial No.</th><th>Prop. No.</th><th class="text-end">Total Cost</th>
      <th>Assigned To</th><th>Office</th><th>Status</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['par_number']) ?></td>
        <td><?= date('m/d/Y', strtotime($r['par_date'])) ?></td>
        <td><?= htmlspecialchars($r['item_name']) ?></td>
        <td><small><?= htmlspecialchars($r['brand_model']) ?></small></td>
        <td><small><?= htmlspecialchars($r['serial_no']) ?></small></td>
        <td><small><?= htmlspecialchars($r['property_no']) ?></small></td>
        <td class="text-end">&#8369; <?= number_format((float)$r['total_cost'],2) ?></td>
        <td><?= htmlspecialchars($r['assigned_to']) ?></td>
        <td><small><?= htmlspecialchars($r['office_name']) ?></small></td>
        <td><span class="badge status-<?= $r['status'] ?>"><?= ucfirst($r['status']) ?></span></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div></div>
