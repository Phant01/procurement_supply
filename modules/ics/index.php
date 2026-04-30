<?php $pageTitle = 'Inventory Custodian Slips'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0"><i class="bi bi-person-badge me-2"></i>Inventory Custodian Slips (ICS)</h4>
</div>
<div class="mb-3 d-flex gap-2">
  <?php foreach (['active'=>'Active','returned'=>'Returned','written_off'=>'Written Off',''=>'All'] as $v=>$l): ?>
    <a href="<?= BASE_URL ?>/index.php?mod=ics&act=index&status=<?= $v ?>"
       class="btn btn-sm <?= $status===$v?'btn-primary':'btn-outline-secondary' ?>"><?= $l ?></a>
  <?php endforeach; ?>
</div>
<div class="card"><div class="card-body p-0">
  <table class="table datatable mb-0">
    <thead><tr><th>ICS No.</th><th>Date</th><th>Item</th><th>Type</th><th>Assigned To</th>
      <th>Office</th><th class="text-end">Total Cost</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($registry as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['ics_number']) ?></td>
        <td><?= date('m/d/Y', strtotime($r['ics_date'])) ?></td>
        <td><?= htmlspecialchars($r['item_name']) ?></td>
        <td><span class="badge badge-<?= $r['category_name'] ?>">SE</span></td>
        <td><?= htmlspecialchars($r['assigned_to']) ?></td>
        <td><small><?= htmlspecialchars($r['office_name']) ?></small></td>
        <td class="text-end">&#8369; <?= number_format((float)$r['total_cost'],2) ?></td>
        <td><span class="badge status-<?= $r['status'] ?>"><?= ucfirst($r['status']) ?></span></td>
        <td>
          <a href="<?= BASE_URL ?>/index.php?mod=ics&act=view&id=<?= $r['ics_id'] ?>"
             class="btn btn-xs btn-outline-secondary"><i class="bi bi-eye"></i></a>
          <a href="<?= BASE_URL ?>/index.php?mod=ics&act=print&id=<?= $r['ics_id'] ?>"
             target="_blank" class="btn btn-xs btn-outline-dark"><i class="bi bi-printer"></i></a>
          <?php if ($r['status']==='active'): ?>
          <a href="<?= BASE_URL ?>/index.php?mod=ics&act=return&id=<?= $r['ics_id'] ?>"
             class="btn btn-xs btn-outline-warning"
             onclick="return confirm('Mark as returned?')">Return</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div></div>
