<?php
$mod = $_GET['mod'] ?? 'dashboard';
function navItem(string $label, string $icon, string $m, string $current): string {
    $active = $m === $current ? 'active' : '';
    return "<li class=\"nav-item\">
      <a class=\"nav-link $active\" href=\"" . BASE_URL . "/index.php?mod=$m&act=index\">
        <i class=\"bi bi-$icon me-2\"></i>$label</a></li>";
}
?>
<aside class="sidebar bg-white border-end" style="min-width:220px">
  <div class="py-3 px-3 border-bottom">
    <small class="text-muted text-uppercase fw-bold">Navigation</small>
  </div>
  <ul class="nav flex-column p-2">
    <?= navItem('Dashboard', 'speedometer2', 'dashboard', $mod) ?>
    <li class="nav-item mt-2">
      <small class="px-3 text-muted text-uppercase" style="font-size:.7rem">Procurement</small>
    </li>
    <?= navItem('Purchase Orders', 'file-earmark-text', 'purchase_orders', $mod) ?>
    <?= navItem('Receiving / IAR', 'box-arrow-in-down', 'receiving', $mod) ?>
    <li class="nav-item mt-2">
      <small class="px-3 text-muted text-uppercase" style="font-size:.7rem">Inventory</small>
    </li>
    <?= navItem('Stock Cards', 'card-list', 'stock_cards', $mod) ?>
    <?= navItem('Requisition (RIS)', 'clipboard-check', 'ris', $mod) ?>
    <?= navItem('Inv. Custodian (ICS)', 'person-badge', 'ics', $mod) ?>
    <?= navItem('Property (PAR)', 'pc-display', 'par', $mod) ?>
    <li class="nav-item mt-2">
      <small class="px-3 text-muted text-uppercase" style="font-size:.7rem">COA Reports</small>
    </li>
    <?= navItem('Reports', 'printer', 'reports', $mod) ?>
    <li class="nav-item mt-2">
      <small class="px-3 text-muted text-uppercase" style="font-size:.7rem">Masterfiles</small>
    </li>
    <?= navItem('Suppliers', 'truck', 'suppliers', $mod) ?>
    <?= navItem('Items / Supplies', 'boxes', 'items', $mod) ?>
    <?= navItem('Offices', 'building', 'offices', $mod) ?>
    <?= navItem('Personnel', 'people', 'personnel', $mod) ?>
    <?php if (($user['role'] ?? '') === 'admin'): ?>
    <?= navItem('Users', 'shield-lock', 'users', $mod) ?>
    <?php endif; ?>
  </ul>
</aside>
