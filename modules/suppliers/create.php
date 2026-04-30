<?php $pageTitle = 'Add Supplier'; ?>
<div class="mb-3"><a href="<?= BASE_URL ?>/index.php?mod=suppliers&act=index"
  class="text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Back to Suppliers</a></div>
<div class="card" style="max-width:640px">
  <div class="card-header"><strong>Add New Supplier</strong></div>
  <div class="card-body">
    <form method="post">
      <?php include ROOT_PATH . '/views/partials/_supplier_form.php'; ?>
      <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Save Supplier</button>
    </form>
  </div>
</div>
