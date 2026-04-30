$(function () {
  // Auto-init DataTables
  $('.datatable').each(function () {
    $(this).DataTable({
      pageLength: 20,
      responsive: true,
      language: { search: 'Filter:' }
    });
  });

  // Confirm delete
  $(document).on('click', '.btn-delete', function (e) {
    e.preventDefault();
    const url = $(this).data('url') || $(this).attr('href');
    Swal.fire({
      title: 'Delete this record?',
      text: 'This action cannot be undone.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc3545',
      confirmButtonText: 'Yes, delete',
    }).then(r => { if (r.isConfirmed) window.location.href = url; });
  });

  // Auto-compute row totals in PO/RIS tables
  $(document).on('input', '.qty-input, .price-input', function () {
    const row   = $(this).closest('tr');
    const qty   = parseFloat(row.find('.qty-input').val())   || 0;
    const price = parseFloat(row.find('.price-input').val()) || 0;
    row.find('.row-total').text(formatMoney(qty * price));
    computeGrandTotal();
  });

  function computeGrandTotal() {
    let total = 0;
    $('.row-total').each(function () {
      total += parseMoney($(this).text());
    });
    $('#grand-total').text(formatMoney(total));
    $('#grand-total-input').val(total.toFixed(2));
  }

  function formatMoney(n) {
    return n.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function parseMoney(s) {
    return parseFloat(s.replace(/,/g, '')) || 0;
  }

  // Add item row (PO and RIS)
  window.addItemRow = function (tableId, items) {
    const tbody = document.querySelector(`#${tableId} tbody`);
    const idx   = tbody.querySelectorAll('tr').length;
    const opts  = items.map(i => `<option value="${i.item_id}" data-price="${i.unit_cost}"
      data-uom="${i.unit_of_measure}">${i.item_name}</option>`).join('');
    const row = document.createElement('tr');
    row.innerHTML = `
      <td><select class="form-select form-select-sm item-select" name="items[${idx}][item_id]"
          onchange="fillUom(this)"><option value="">-- select --</option>${opts}</select></td>
      <td><input type="number" class="form-control form-control-sm qty-input"
          name="items[${idx}][qty]" value="1" min="0.0001" step="any"></td>
      <td><input type="text" class="form-control form-control-sm uom-field"
          name="items[${idx}][uom]" readonly></td>
      <td><input type="number" class="form-control form-control-sm price-input"
          name="items[${idx}][price]" value="0" step="0.01"></td>
      <td class="row-total text-end">0.00</td>
      <td><button type="button" class="btn btn-sm btn-outline-danger"
          onclick="this.closest('tr').remove(); computeGrandTotal()">
          <i class="bi bi-trash"></i></button></td>`;
    tbody.appendChild(row);
  };

  window.fillUom = function (sel) {
    const opt = sel.options[sel.selectedIndex];
    const row = sel.closest('tr');
    row.querySelector('.uom-field').value    = opt.dataset.uom   || '';
    row.querySelector('.price-input').value  = opt.dataset.price || '0';
    row.querySelector('.qty-input').dispatchEvent(new Event('input'));
  };
});
