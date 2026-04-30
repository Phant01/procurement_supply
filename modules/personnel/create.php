<?php $pageTitle = 'Add Personnel'; ?>
<div class="mb-3"><a href="<?= BASE_URL ?>/index.php?mod=personnel&act=index"
  class="text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Back</a></div>
<div class="card" style="max-width:540px"><div class="card-header"><strong>Add Personnel</strong></div>
<div class="card-body"><form method="post">
  <div class="row g-3 mb-3">
    <div class="col-md-5">
      <label class="form-label fw-semibold">Employee No.</label>
      <input type="text" name="employee_no" class="form-control">
    </div>
    <div class="col-md-7">
      <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
      <input type="text" name="full_name" class="form-control" required>
    </div>
    <div class="col-12">
      <label class="form-label fw-semibold">Position</label>
      <input type="text" name="position" class="form-control">
    </div>
    <div class="col-12">
      <label class="form-label fw-semibold">Office <span class="text-danger">*</span></label>
      <select name="office_id" class="form-select" required>
        <option value="">-- select office --</option>
        <?php foreach ($offices as $o): ?>
          <option value="<?= $o['office_id'] ?>"><?= htmlspecialchars($o['office_name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Save</button>
</form></div></div>
