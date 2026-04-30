<?php $pageTitle = 'Add User'; ?>
<div class="mb-3"><a href="<?= BASE_URL ?>/index.php?mod=users&act=index"
  class="text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Back</a></div>
<div class="card" style="max-width:540px"><div class="card-header"><strong>Add User</strong></div>
<div class="card-body"><form method="post">
  <div class="row g-3 mb-3">
    <div class="col-12">
      <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
      <input type="text" name="full_name" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
      <input type="text" name="username" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label fw-semibold">Role</label>
      <select name="role" class="form-select">
        <option value="viewer">Viewer</option>
        <option value="supply_officer">Supply Officer</option>
        <option value="admin">Admin</option>
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label fw-semibold">Confirm Password</label>
      <input type="password" name="password_confirm" class="form-control" required>
    </div>
    <div class="col-12">
      <label class="form-label">Link to Personnel (optional)</label>
      <select name="personnel_id" class="form-select">
        <option value="">-- none --</option>
        <?php foreach ($personnel as $p): ?>
          <option value="<?= $p['personnel_id'] ?>"><?= htmlspecialchars($p['full_name']) ?> — <?= htmlspecialchars($p['office_name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Create User</button>
</form></div></div>
