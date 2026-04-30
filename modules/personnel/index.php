<?php $pageTitle = 'Personnel'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0"><i class="bi bi-people me-2"></i>Personnel</h4>
  <a href="<?= BASE_URL ?>/index.php?mod=personnel&act=create" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg me-1"></i>Add Personnel</a>
</div>
<div class="card"><div class="card-body p-0">
  <table class="table datatable mb-0">
    <thead><tr><th>Employee No.</th><th>Name</th><th>Position</th><th>Office</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($personnel as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['employee_no']) ?></td>
        <td><?= htmlspecialchars($r['full_name']) ?></td>
        <td><?= htmlspecialchars($r['position']) ?></td>
        <td><?= htmlspecialchars($r['office_name']) ?></td>
        <td><a href="<?= BASE_URL ?>/index.php?mod=personnel&act=edit&id=<?= $r['personnel_id'] ?>"
           class="btn btn-xs btn-outline-primary"><i class="bi bi-pencil"></i></a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div></div>
