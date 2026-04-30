<?php $pageTitle = 'ICS ' . $row['ics_number']; ?>
<div class="form-header">
  <p><strong><?= htmlspecialchars(APP_AGENCY) ?></strong></p>
  <h4>INVENTORY CUSTODIAN SLIP</h4>
  <p><strong>ICS No.:</strong> <?= htmlspecialchars($row['ics_number']) ?>
     &nbsp;&nbsp;&nbsp; <strong>Date:</strong> <?= date('F d, Y', strtotime($row['ics_date'])) ?></p>
</div>
<table>
  <thead><tr><th>Qty</th><th>Unit</th><th>Description</th><th>Property No.</th><th>Est. Life</th><th>Unit Cost</th><th>Total Cost</th></tr></thead>
  <tbody>
    <tr>
      <td style="text-align:center"><?= number_format((float)$row['quantity'],2) ?></td>
      <td><?= htmlspecialchars($row['unit_of_measure']) ?></td>
      <td><?= htmlspecialchars($row['item_name']) ?></td>
      <td><?= htmlspecialchars($row['property_no']) ?></td>
      <td><?= htmlspecialchars($row['estimated_life']) ?></td>
      <td style="text-align:right">&#8369; <?= number_format((float)$row['unit_cost'],2) ?></td>
      <td style="text-align:right">&#8369; <?= number_format((float)$row['total_cost'],2) ?></td>
    </tr>
    <tr><td colspan="6" style="text-align:right"><strong>Total</strong></td>
        <td style="text-align:right"><strong>&#8369; <?= number_format((float)$row['total_cost'],2) ?></strong></td></tr>
  </tbody>
</table>
<div class="signature-block">
  <table style="width:100%;border:none;margin-top:20pt">
    <tr>
      <td style="border:none;width:50%">
        <strong>Issued by:</strong><br><br>
        <span class="signature-line"></span><br>
        <small>Supply Officer</small>
      </td>
      <td style="border:none;width:50%;text-align:right">
        <strong>Received by:</strong><br><br>
        <span class="signature-line"></span><br>
        <small><?= htmlspecialchars($row['assigned_to']) ?><br><?= htmlspecialchars($row['position']) ?><br><?= htmlspecialchars($row['office_name']) ?></small>
      </td>
    </tr>
  </table>
</div>
