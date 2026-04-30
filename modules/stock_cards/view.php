<?php $pageTitle = 'Stock Card: ' . htmlspecialchars($item['item_name']); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <a href="<?= BASE_URL ?>/index.php?mod=stock_cards&act=index" class="text-decoration-none me-3">
      <i class="bi bi-arrow-left me-1"></i>Back</a>
    <strong>Stock Card: <?= htmlspecialchars($item['item_name']) ?></strong>
  </div>
  <a href="<?= BASE_URL ?>/index.php?mod=stock_cards&act=print&item_id=<?= $item['item_id'] ?>&from=<?= $from ?>&to=<?= $to ?>"
     target="_blank" class="btn btn-outline-dark btn-sm"><i class="bi bi-printer me-1"></i>Print</a>
</div>
<form class="card p-3 mb-3 d-flex flex-row gap-3 align-items-end flex-wrap">
  <input type="hidden" name="mod" value="stock_cards"><input type="hidden" name="act" value="view">
  <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">
  <div>
    <label class="form-label small mb-1">From</label>
    <input type="date" name="from" class="form-control form-control-sm" value="<?= $from ?>">
  </div>
  <div>
    <label class="form-label small mb-1">To</label>
    <input type="date" name="to" class="form-control form-control-sm" value="<?= $to ?>">
  </div>
  <button class="btn btn-sm btn-primary">Filter</button>
</form>
<div class="card"><div class="card-body p-0">
  <table class="table table-sm datatable mb-0">
    <thead><tr><th>Date</th><th>Reference</th><th>Type</th><th>Office</th>
      <th class="text-end">Qty In</th><th class="text-end">Qty Out</th>
      <th class="text-end">Unit Cost</th><th class="text-end">Balance</th></tr></thead>
    <tbody>
    <?php foreach ($ledger as $e): ?>
      <tr>
        <td><?= date('m/d/Y', strtotime($e['txn_date'])) ?></td>
        <td><small><?= htmlspecialchars($e['ref_number']) ?></small></td>
        <td><span class="badge <?= $e['ref_type']==='RECEIPT'?'bg-success':($e['ref_type']==='ISSUANCE'?'bg-primary':'bg-secondary') ?>">
          <?= $e['ref_type'] ?></span></td>
        <td><small><?= htmlspecialchars($e['office_name'] ?? '') ?></small></td>
        <td class="text-end text-success"><?= $e['qty_in']>0 ? number_format((float)$e['qty_in'],2) : '' ?></td>
        <td class="text-end text-danger"><?= $e['qty_out']>0 ? number_format((float)$e['qty_out'],2) : '' ?></td>
        <td class="text-end">&#8369; <?= number_format((float)$e['unit_cost'],2) ?></td>
        <td class="text-end fw-semibold"><?= number_format((float)$e['balance'],2) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div></div>
