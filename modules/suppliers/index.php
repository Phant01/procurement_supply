<?php $pageTitle = 'Suppliers'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0"><i class="bi bi-truck me-2"></i>Suppliers</h4>
  <a href="<?= BASE_URL ?>/index.php?mod=suppliers&act=create" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg me-1"></i>Add Supplier</a>
</div>
<div class="card">
  <div class="card-body p-0">
    <table class="table table-hover datatable mb-0">
      <thead><tr><th>Name</th><th>Contact</th><th>Mobile</th><th>TIN</th><th>PhilGEPS</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($suppliers as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['supplier_name']) ?></td>
          <td><?= htmlspecialchars($r['contact_person']) ?></td>
          <td><?= htmlspecialchars($r['mobile']) ?></td>
          <td><?= htmlspecialchars($r['tin_no']) ?></td>
          <td><?= htmlspecialchars($r['philgeps_reg_no']) ?></td>
          <td>
            <a href="<?= BASE_URL ?>/index.php?mod=suppliers&act=edit&id=<?= $r['supplier_id'] ?>"
               class="btn btn-xs btn-outline-primary"><i class="bi bi-pencil"></i></a>
            <a href="<?= BASE_URL ?>/index.php?mod=suppliers&act=delete&id=<?= $r['supplier_id'] ?>"
               class="btn btn-xs btn-outline-danger btn-delete"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
