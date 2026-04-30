<?php $pageTitle = 'Add Item'; ?>
<div class="mb-3"><a href="<?= BASE_URL ?>/index.php?mod=items&act=index"
  class="text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Back to Items</a></div>
<div class="card" style="max-width:640px"><div class="card-header"><strong>Add New Item</strong></div>
<div class="card-body"><form method="post">
  <?php include ROOT_PATH . '/views/partials/_item_form.php'; ?>
  <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Save Item</button>
</form></div></div>
