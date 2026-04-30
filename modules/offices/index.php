<?php $pageTitle = 'Offices'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0"><i class="bi bi-building me-2"></i>Offices</h4>
  <a href="<?= BASE_URL ?>/index.php?mod=offices&act=create" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg me-1"></i>Add Office</a>
</div>
<div class="card">
  <div class="card-body p-0">
    <table class="table datatable mb-0">
      <thead><tr><th>Code</th><th>Office Name</th><th>Department</th><th>Head</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($offices as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['office_code']) ?></td>
          <td><?= htmlspecialchars($r['office_name']) ?></td>
          <td><?= htmlspecialchars($r['department']) ?></td>
          <td><?= htmlspecialchars($r['head_of_office']) ?></td>
          <td><a href="<?= BASE_URL ?>/index.php?mod=offices&act=edit&id=<?= $r['office_id'] ?>"
             class="btn btn-xs btn-outline-primary"><i class="bi bi-pencil"></i></a></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
