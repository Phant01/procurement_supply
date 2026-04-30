<?php $pageTitle = 'IAR ' . $receipt['iar_number']; ?>
<div class="form-header">
  <p><strong><?= htmlspecialchars(APP_AGENCY) ?></strong></p>
  <h4>INSPECTION AND ACCEPTANCE REPORT</h4>
  <table style="width:100%;border:none;margin-bottom:6pt">
    <tr>
      <td style="border:none"><strong>IAR No.:</strong> <?= htmlspecialchars($receipt['iar_number']) ?></td>
      <td style="border:none;text-align:right"><strong>Date:</strong> <?= date('F d, Y',strtotime($receipt['receipt_date'])) ?></td>
    </tr>
    <tr>
      <td style="border:none"><strong>PO No.:</strong> <?= htmlspecialchars($receipt['po_number']) ?></td>
      <td style="border:none;text-align:right"><strong>Supplier DR:</strong> <?= htmlspecialchars($receipt['delivery_ref']) ?></td>
    </tr>
    <tr><td colspan="2" style="border:none"><strong>Supplier:</strong> <?= htmlspecialchars($receipt['supplier_name']) ?></td></tr>
  </table>
</div>
<table>
  <thead><tr><th>Item</th><th>UOM</th><th>Qty Received</th><th>Unit Cost</th><th>Amount</th><th>Remarks</th></tr></thead>
  <tbody>
  <?php foreach ($lines as $l): ?>
    <tr>
      <td><?= htmlspecialchars($l['item_name']) ?></td>
      <td><?= $l['unit_of_measure'] ?></td>
      <td style="text-align:right"><?= number_format((float)$l['qty_received'],2) ?></td>
      <td style="text-align:right">&#8369; <?= number_format((float)$l['unit_cost'],2) ?></td>
      <td style="text-align:right">&#8369; <?= number_format($l['qty_received']*$l['unit_cost'],2) ?></td>
      <td></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<div class="signature-block">
  <table style="width:100%;border:none;margin-top:20pt">
    <tr>
      <td style="border:none;width:33%"><strong>Received by:</strong><br><br>
        <span class="signature-line"></span><br><small><?= htmlspecialchars($receipt['received_by']) ?></small></td>
      <td style="border:none;width:33%;text-align:center"><strong>Inspected by:</strong><br><br>
        <span class="signature-line"></span><br><small><?= htmlspecialchars($receipt['inspected_by']) ?></small></td>
      <td style="border:none;width:33%;text-align:right"><strong>Approved by:</strong><br><br>
        <span class="signature-line"></span><br><small><?= htmlspecialchars($receipt['approved_by']) ?></small></td>
    </tr>
  </table>
</div>
