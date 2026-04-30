<?php $pageTitle = 'PAR ' . $row['par_number']; ?>
<div class="form-header">
  <p><strong><?= htmlspecialchars(APP_AGENCY) ?></strong></p>
  <h4>PROPERTY ACKNOWLEDGEMENT RECEIPT</h4>
  <p><strong>PAR No.:</strong> <?= htmlspecialchars($row['par_number']) ?>
     &nbsp;&nbsp;&nbsp; <strong>Date:</strong> <?= date('F d, Y', strtotime($row['par_date'])) ?></p>
</div>
<table>
  <thead><tr><th>Qty</th><th>Unit</th><th>Description</th><th>Property No.</th>
    <th>Serial No.</th><th>Brand/Model</th><th>Unit Cost</th><th>Total</th></tr></thead>
  <tbody>
    <tr>
      <td style="text-align:center"><?= number_format((float)$row['quantity'],2) ?></td>
      <td><?= htmlspecialchars($row['unit_of_measure']) ?></td>
      <td><?= htmlspecialchars($row['item_name']) ?></td>
      <td><?= htmlspecialchars($row['property_no']) ?></td>
      <td><?= htmlspecialchars($row['serial_no']) ?></td>
      <td><?= htmlspecialchars($row['brand_model']) ?></td>
      <td style="text-align:right">&#8369; <?= number_format((float)$row['unit_cost'],2) ?></td>
      <td style="text-align:right">&#8369; <?= number_format((float)$row['total_cost'],2) ?></td>
    </tr>
    <tr><td colspan="7" style="text-align:right"><strong>Total</strong></td>
        <td style="text-align:right"><strong>&#8369; <?= number_format((float)$row['total_cost'],2) ?></strong></td></tr>
  </tbody>
</table>
<div class="signature-block">
  <table style="width:100%;border:none;margin-top:20pt">
    <tr>
      <td style="border:none;width:50%">
        <strong>Issued by:</strong><br><br>
        <span class="signature-line"></span><br>
        <small>Property Custodian / Supply Officer</small>
      </td>
      <td style="border:none;width:50%;text-align:right">
        <strong>Received by:</strong><br><br>
        <span class="signature-line"></span><br>
        <small><?= htmlspecialchars($row['assigned_to']) ?><br>
        <?= htmlspecialchars($row['position']) ?><br>
        <?= htmlspecialchars($row['office_name']) ?></small>
      </td>
    </tr>
  </table>
</div>
