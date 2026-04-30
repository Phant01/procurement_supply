<?php $pageTitle = 'Users'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0"><i class="bi bi-shield-lock me-2"></i>System Users</h4>
  <a href="<?= BASE_URL ?>/index.php?mod=users&act=create" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg me-1"></i>Add User</a>
</div>
<div class="card"><div class="card-body p-0">
  <table class="table datatable mb-0">
    <thead><tr><th>Username</th><th>Full Name</th><th>Role</th><th>Last Login</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($users as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['username']) ?></td>
        <td><?= htmlspecialchars($r['full_name']) ?></td>
        <td><span class="badge bg-secondary"><?= $r['role'] ?></span></td>
        <td><small><?= $r['last_login'] ? date('m/d/Y H:i', strtotime($r['last_login'])) : '—' ?></small></td>
        <td><span class="badge <?= $r['is_active']?'bg-success':'bg-secondary' ?>">
          <?= $r['is_active']?'Active':'Inactive' ?></span></td>
        <td>
          <a href="<?= BASE_URL ?>/index.php?mod=users&act=toggle&id=<?= $r['user_id'] ?>"
             class="btn btn-xs btn-outline-secondary"><?= $r['is_active']?'Disable':'Enable' ?></a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div></div>
