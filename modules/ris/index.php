<?php $pageTitle = 'Requisition & Issue Slips'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0"><i class="bi bi-clipboard-check me-2"></i>Requisition &amp; Issue Slips (RIS)</h4>
  <a href="<?= BASE_URL ?>/index.php?mod=ris&act=create" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg me-1"></i>New RIS</a>
</div>
<div class="card"><div class="card-body p-0">
  <table class="table datatable mb-0">
    <thead><tr><th>RIS No.</th><th>Date</th><th>Office</th><th>Purpose</th>
      <th>Status</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($risList as $r): ?>
      <tr>
        <td><a href="<?= BASE_URL ?>/index.php?mod=ris&act=view&id=<?= $r['ris_id'] ?>"
               class="fw-semibold text-decoration-none"><?= htmlspecialchars($r['ris_number']) ?></a></td>
        <td><?= date('m/d/Y',strtotime($r['ris_date'])) ?></td>
        <td><?= htmlspecialchars($r['office_name']) ?></td>
        <td><small><?= htmlspecialchars($r['purpose']) ?></small></td>
        <td><span class="badge status-<?= $r['status'] ?>"><?= ucfirst($r['status']) ?></span></td>
        <td>
          <a href="<?= BASE_URL ?>/index.php?mod=ris&act=view&id=<?= $r['ris_id'] ?>"
             class="btn btn-xs btn-outline-secondary"><i class="bi bi-eye"></i></a>
          <?php if ($r['status']==='pending'): ?>
          <a href="<?= BASE_URL ?>/index.php?mod=ris&act=issue&id=<?= $r['ris_id'] ?>"
             class="btn btn-xs btn-success"><i class="bi bi-check2"></i> Issue</a>
          <?php endif; ?>
          <a href="<?= BASE_URL ?>/index.php?mod=ris&act=print&id=<?= $r['ris_id'] ?>"
             target="_blank" class="btn btn-xs btn-outline-dark"><i class="bi bi-printer"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div></div>
