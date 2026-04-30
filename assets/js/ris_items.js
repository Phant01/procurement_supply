$(function () {

  // Add row button
  $('#btn-add-ris-row').on('click', function () {
    addRisItemRow('ris-items-table', window.ITEMS_DATA || []);
    $('#no-items-msg').hide();
  });

});

// ── Validate before submit ───────────────────────────────────────────────
window.validateRis = function () {
  const rows = document.querySelectorAll('#ris-items-table tbody tr');
  if (rows.length === 0) {
    alert('Please add at least one item before submitting.');
    return false;
  }
  let hasItem = false;
  rows.forEach(function (row) {
    const sel = row.querySelector('select');
    const qty = row.querySelector('input[type="number"]');
    if (sel && sel.value && qty && parseFloat(qty.value) > 0) {
      hasItem = true;
    }
  });
  if (!hasItem) {
    alert('Please select an item and enter a quantity greater than zero.');
    return false;
  }
  return true;
};

// ── Add a new RIS item row ───────────────────────────────────────────────
window.addRisItemRow = function (tableId, items) {
  const tbody = document.querySelector('#' + tableId + ' tbody');
  const idx   = tbody.querySelectorAll('tr').length;

  const opts = items.map(function (i) {
    return '<option value="' + i.item_id + '" '
      + 'data-uom="' + i.unit_of_measure + '">'
      + i.item_name
      + '</option>';
  }).join('');

  const row = document.createElement('tr');
  row.innerHTML =
    '<td>'
      + '<select class="form-select form-select-sm" '
      + 'name="items[' + idx + '][item_id]" '
      + 'onchange="fillRisUom(this)" required>'
      + '<option value="">-- select item --</option>'
      + opts
      + '</select>'
    + '</td>'
    + '<td>'
      + '<input type="number" '
      + 'class="form-control form-control-sm" '
      + 'name="items[' + idx + '][qty]" '
      + 'value="1" min="0.0001" step="any" required>'
    + '</td>'
    + '<td>'
      + '<input type="text" '
      + 'class="form-control form-control-sm ris-uom-field" '
      + 'name="items[' + idx + '][uom]" '
      + 'readonly>'
    + '</td>'
    + '<td>'
      + '<button type="button" class="btn btn-sm btn-outline-danger" '
      + 'onclick="removeRisRow(this)">'
      + '<i class="bi bi-trash"></i>'
      + '</button>'
    + '</td>';

  tbody.appendChild(row);
};

// ── Fill UOM when item is selected ───────────────────────────────────────
window.fillRisUom = function (sel) {
  const opt      = sel.options[sel.selectedIndex];
  const row      = sel.closest('tr');
  const uomField = row.querySelector('.ris-uom-field');
  if (uomField) {
    uomField.value = opt.dataset.uom || '';
  }
};

// ── Remove a row ─────────────────────────────────────────────────────────
window.removeRisRow = function (btn) {
  btn.closest('tr').remove();
  const remaining = document.querySelectorAll('#ris-items-table tbody tr').length;
  if (remaining === 0) {
    document.getElementById('no-items-msg').style.display = 'block';
  }
};