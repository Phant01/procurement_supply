<nav class="navbar navbar-expand-lg navbar-dark  top-navbar"
  style="background-color: #7ed957;">
  
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/index.php?mod=dashboard&act=index">
      
      <img src="<?= BASE_URL ?>/assets/img/MBLISTTDA_MainLogo.png"
     alt="Logo"
     style="height:45px; width:auto; object-fit:contain;">
    </a>
    <div class="ms-auto d-flex align-items-center gap-3">
      <span class="text-white small"><i class="bi bi-building me-1"></i><?= APP_AGENCY ?></span>
      <div class="dropdown">
        <a href="#" class="dropdown-toggle text-white text-decoration-none" data-bs-toggle="dropdown">
          <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($user['full_name'] ?? '') ?>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="<?= BASE_URL ?>/index.php?mod=users&act=changePassword">
            <i class="bi bi-key me-2"></i>Change Password</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/index.php?mod=auth&act=logout">
            <i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>
