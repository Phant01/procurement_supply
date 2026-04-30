<?php $pageTitle = 'Add Office'; $r = []; ?>
<div class="mb-3"><a href="<?= BASE_URL ?>/index.php?mod=offices&act=index"
  class="text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Back</a></div>
<div class="card" style="max-width:540px">
  <div class="card-header"><strong>Add Office</strong></div>
  <div class="card-body">
    <form method="post">
      <div class="row g-3 mb-3">
        <div class="col-md-4">
          <label class="form-label fw-semibold">Code <span class="text-danger">*</span></label>
          <input type="text" name="office_code" class="form-control" required>
        </div>
        <div class="col-md-8">
          <label class="form-label fw-semibold">Office Name <span class="text-danger">*</span></label>
          <input type="text" name="office_name" class="form-control" required>
        </div>
        <div class="col-12">
          <label class="form-label">Department <small class="text-muted">(e.g. FAO, HRMO, GSO)</small></label>
          <input type="text" name="department" class="form-control"
         placeholder="e.g. Finance and Administrative Office"
         value="<?= htmlspecialchars($row['department'] ?? '') ?>">
        </div>
        <div class="col-12">
          <label class="form-label">Head of Office</label>
          <input type="text" name="head_of_office" class="form-control">
        </div>
      </div>
      <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Save</button>
    </form>
  </div>
</div>
