<?php $pageTitle = 'RIS ' . $ris['ris_number']; ?>
<div class="form-header">
  <p><strong><?= htmlspecialchars(APP_AGENCY) ?></strong></p>
  <h4>REQUISITION AND ISSUE SLIP</h4>
  <table style="width:100%;border:none;margin-bottom:6pt">
    <tr>
      <td style="border:none"><strong>RIS No.:</strong> <?= htmlspecialchars($ris['ris_number']) ?></td>
      <td style="border:none;text-align:right"><strong>Date:</strong> <?= date('F d, Y',strtotime($ris['ris_date'])) ?></td>
    </tr>
    <tr>
      <td style="border:none"><strong>Office:</strong> <?= htmlspecialchars($ris['office_name']) ?></td>
      <td style="border:none;text-align:right"><strong>Purpose:</strong> <?= htmlspecialchars($ris['purpose']) ?></td>
    </tr>
  </table>
</div>
<table>
  <thead><tr><th>Item</th><th>UOM</th><th>Qty Requested</th><th>Qty Issued</th><th>Unit Cost</th><th>Amount</th></tr></thead>
  <tbody>
  <?php foreach ($items as $it): ?>
    <tr>
      <td><?= htmlspecialchars($it['item_name']) ?></td>
      <td><?= $it['unit_of_measure'] ?></td>
      <td style="text-align:right"><?= number_format((float)$it['qty_requested'],2) ?></td>
      <td style="text-align:right"><?= number_format((float)$it['qty_issued'],2) ?></td>
      <td style="text-align:right">&#8369; <?= number_format((float)$it['unit_cost'],2) ?></td>
      <td style="text-align:right">&#8369; <?= number_format($it['qty_issued']*$it['unit_cost'],2) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<div class="signature-block">
  <table style="width:100%;border:none;margin-top:20pt">
    <tr>
      <td style="border:none;width:25%"><strong>Requested by:</strong><br><br>
        <span class="signature-line"></span><br><small><?= htmlspecialchars($ris['requested_by']) ?></small></td>
      <td style="border:none;width:25%;text-align:center"><strong>Approved by:</strong><br><br>
        <span class="signature-line"></span><br><small><?= htmlspecialchars($ris['approved_by']) ?></small></td>
      <td style="border:none;width:25%;text-align:center"><strong>Issued by:</strong><br><br>
        <span class="signature-line"></span><br><small><?= htmlspecialchars($ris['issued_by']) ?></small></td>
      <td style="border:none;width:25%;text-align:right"><strong>Received by:</strong><br><br>
        <span class="signature-line"></span><br><small><?= htmlspecialchars($ris['received_by']) ?></small></td>
    </tr>
  </table>
</div>
