<?php $pageTitle = 'Change Password'; ?>
<div class="card" style="max-width:420px; margin: 0 auto">
  <div class="card-header"><strong><i class="bi bi-key me-2"></i>Change Password</strong></div>
  <div class="card-body">
    <?php if (!empty($flash)): ?>
      <div class="alert alert-<?= $flash['type'] ?> small"><?= htmlspecialchars($flash['msg']) ?></div>
    <?php endif; ?>
    <form method="post">
      <div class="mb-3">
        <label class="form-label fw-semibold">Current Password</label>
        <input type="password" name="current_password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">New Password</label>
        <input type="password" name="new_password" class="form-control" required>
      </div>
      <div class="mb-4">
        <label class="form-label fw-semibold">Confirm New Password</label>
        <input type="password" name="confirm_password" class="form-control" required>
      </div>
      <button class="btn btn-primary w-100"><i class="bi bi-save me-1"></i>Update Password</button>
    </form>
  </div>
</div>
