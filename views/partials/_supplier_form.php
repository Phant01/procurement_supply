<?php $r = $row ?? []; ?>
<div class="row g-3 mb-3">
  <div class="col-12">
    <label class="form-label fw-semibold">Supplier Name <span class="text-danger">*</span></label>
    <input type="text" name="supplier_name" class="form-control"
           value="<?= htmlspecialchars($r['supplier_name'] ?? '') ?>" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Contact Person</label>
    <input type="text" name="contact_person" class="form-control"
           value="<?= htmlspecialchars($r['contact_person'] ?? '') ?>">
  </div>
  <div class="col-md-3">
    <label class="form-label">Telephone</label>
    <input type="text" name="telephone" class="form-control"
           value="<?= htmlspecialchars($r['telephone'] ?? '') ?>">
  </div>
  <div class="col-md-3">
    <label class="form-label">Mobile</label>
    <input type="text" name="mobile" class="form-control"
           value="<?= htmlspecialchars($r['mobile'] ?? '') ?>">
  </div>
  <div class="col-md-6">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control"
           value="<?= htmlspecialchars($r['email'] ?? '') ?>">
  </div>
  <div class="col-md-3">
    <label class="form-label">TIN No.</label>
    <input type="text" name="tin_no" class="form-control"
           value="<?= htmlspecialchars($r['tin_no'] ?? '') ?>">
  </div>
  <div class="col-md-3">
    <label class="form-label">PhilGEPS Reg. No.</label>
    <input type="text" name="philgeps_reg_no" class="form-control"
           value="<?= htmlspecialchars($r['philgeps_reg_no'] ?? '') ?>">
  </div>
  <div class="col-12">
    <label class="form-label">Address</label>
    <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($r['address'] ?? '') ?></textarea>
  </div>
</div>
