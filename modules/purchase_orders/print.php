<?php $pageTitle = 'Purchase Order ' . $po['po_number']; ?>
<div class="form-header">
  <p><strong><?= htmlspecialchars(APP_AGENCY) ?></strong></p>
  <h4>PURCHASE ORDER</h4>
  <table style="width:100%; border:none; margin-bottom:8pt;">
    <tr>
      <td style="border:none"><strong>PO No.:</strong> <?= htmlspecialchars($po['po_number']) ?></td>
      <td style="border:none; text-align:right"><strong>Date:</strong> <?= date('F d, Y',strtotime($po['po_date'])) ?></td>
    </tr>
    <tr>
      <td style="border:none"><strong>Supplier:</strong> <?= htmlspecialchars($po['supplier_name']) ?></td>
      <td style="border:none; text-align:right"><strong>Mode:</strong> <?= htmlspecialchars($po['mode_of_procurement']) ?></td>
    </tr>
    <tr>
      <td style="border:none" colspan="2"><strong>Address:</strong> <?= htmlspecialchars($po['supplier_address']) ?></td>
    </tr>
    <tr>
      <td style="border:none"><strong>TIN:</strong> <?= htmlspecialchars($po['tin_no']) ?></td>
      <td style="border:none; text-align:right"><strong>Fund:</strong> <?= htmlspecialchars($po['fund_source']) ?></td>
    </tr>
  </table>
</div>
<p style="margin-bottom:8pt">Gentlemen: Please furnish this office the following articles subject to the terms and conditions:</p>
<p><strong>Delivery Date:</strong> <?= $po['delivery_date']?date('F d, Y',strtotime($po['delivery_date'])):'____________' ?>
&nbsp;&nbsp;&nbsp; <strong>Delivery Place:</strong> <?= htmlspecialchars($po['place_of_delivery']) ?></p>
<table>
  <thead><tr><th>Item No.</th><th>Unit</th><th>Description</th>
    <th>Qty</th><th>Unit Price</th><th>Amount</th></tr></thead>
  <tbody>
  <?php $n=1; $total=0; foreach ($lines as $l):
    $amt = $l['qty_ordered'] * $l['unit_price']; $total += $amt; ?>
    <tr>
      <td style="text-align:center"><?= $n++ ?></td>
      <td style="text-align:center"><?= htmlspecialchars($l['unit_of_measure']) ?></td>
      <td><?= htmlspecialchars($l['item_name']) ?></td>
      <td style="text-align:right"><?= number_format((float)$l['qty_ordered'],2) ?></td>
      <td style="text-align:right">&#8369; <?= number_format((float)$l['unit_price'],2) ?></td>
      <td style="text-align:right">&#8369; <?= number_format($amt,2) ?></td>
    </tr>
  <?php endforeach; ?>
    <tr><td colspan="5" style="text-align:right"><strong>TOTAL AMOUNT</strong></td>
      <td style="text-align:right"><strong>&#8369; <?= number_format($total,2) ?></strong></td></tr>
  </tbody>
</table>
<div class="signature-block">
  <table style="width:100%; border:none; margin-top:24pt">
    <tr>
      <td style="border:none; width:50%">
        <p><strong>Requested by:</strong></p>
        <br><br>
        <span class="signature-line"></span><br>
        <small><?= htmlspecialchars($po['office_name']) ?></small>
      </td>
      <td style="border:none; width:50%; text-align:right">
        <p><strong>Approved by:</strong></p>
        <br><br>
        <span class="signature-line"></span><br>
        <small><?= htmlspecialchars($po['approved_by']) ?></small>
      </td>
    </tr>
  </table>
</div>
