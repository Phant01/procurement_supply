<?php $pageTitle = 'Stock Card'; ?>
<div class="form-header">
  <p><strong><?= htmlspecialchars(APP_AGENCY) ?></strong></p>
  <h4>STOCK CARD</h4>
  <p><strong>Item:</strong> <?= htmlspecialchars($item['item_name']) ?>
     &nbsp;&nbsp; <strong>Code:</strong> <?= htmlspecialchars($item['item_code']) ?>
     &nbsp;&nbsp; <strong>UOM:</strong> <?= htmlspecialchars($item['unit_of_measure']) ?></p>
  <p><strong>Period:</strong> <?= date('F d, Y', strtotime($from)) ?> to <?= date('F d, Y', strtotime($to)) ?></p>
</div>
<table>
  <thead><tr><th>Date</th><th>Reference</th><th>Type</th><th>Office</th>
    <th>Qty In</th><th>Qty Out</th><th>Unit Cost</th><th>Balance</th></tr></thead>
  <tbody>
  <?php foreach ($ledger as $e): ?>
    <tr>
      <td><?= date('m/d/Y', strtotime($e['txn_date'])) ?></td>
      <td><?= htmlspecialchars($e['ref_number']) ?></td>
      <td><?= $e['ref_type'] ?></td>
      <td><?= htmlspecialchars($e['office_name'] ?? '') ?></td>
      <td style="text-align:right"><?= $e['qty_in']>0?number_format((float)$e['qty_in'],2):'' ?></td>
      <td style="text-align:right"><?= $e['qty_out']>0?number_format((float)$e['qty_out'],2):'' ?></td>
      <td style="text-align:right">&#8369; <?= number_format((float)$e['unit_cost'],2) ?></td>
      <td style="text-align:right"><strong><?= number_format((float)$e['balance'],2) ?></strong></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
