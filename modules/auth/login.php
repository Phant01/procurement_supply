<?php
$pageTitle = 'Login';
?>
<div class="auth-card card shadow-sm mx-auto p-4">
  <div class="text-center mb-4">
    <i class="bi bi-boxes fs-1 text-primary"></i>
    <h5 class="fw-bold mt-2"><?= APP_NAME ?></h5>
    <small class="text-muted"><?= APP_AGENCY ?></small>
  </div>
  <?php if ($timeout): ?>
    <div class="alert alert-warning small">Your session has expired. Please log in again.</div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="alert alert-danger small"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="post">
    <div class="mb-3">
      <label class="form-label fw-semibold">Username</label>
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-person"></i></span>
        <input type="text" name="username" class="form-control" required autofocus>
      </div>
    </div>
    <div class="mb-4">
      <label class="form-label fw-semibold">Password</label>
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-lock"></i></span>
        <input type="password" name="password" class="form-control" required>
      </div>
    </div>
    <button class="btn btn-primary w-100 fw-semibold">
      <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
    </button>
  </form>
</div>
