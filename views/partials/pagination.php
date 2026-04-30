<?php
/** @var array $paginate  ['page','totalPages','total'] */
/** @var string $baseUrl  URL without page param */
if (!isset($paginate) || $paginate['totalPages'] <= 1) return;
$p  = (int)$paginate['page'];
$tp = (int)$paginate['totalPages'];
?>
<nav>
<ul class="pagination pagination-sm justify-content-center mb-0">
  <li class="page-item <?= $p <= 1 ? 'disabled' : '' ?>">
    <a class="page-link" href="<?= $baseUrl ?>&page=<?= $p-1 ?>">Previous</a></li>
  <?php for ($i = max(1,$p-2); $i <= min($tp,$p+2); $i++): ?>
  <li class="page-item <?= $i==$p ? 'active' : '' ?>">
    <a class="page-link" href="<?= $baseUrl ?>&page=<?= $i ?>"><?= $i ?></a></li>
  <?php endfor; ?>
  <li class="page-item <?= $p >= $tp ? 'disabled' : '' ?>">
    <a class="page-link" href="<?= $baseUrl ?>&page=<?= $p+1 ?>">Next</a></li>
</ul>
<p class="text-muted text-center small mt-1">Page <?= $p ?> of <?= $tp ?> (<?= $paginate['total'] ?> records)</p>
</nav>
