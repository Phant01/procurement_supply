// PO-specific item row logic (extends app.js)
$(function() {
  $('#btn-add-row').on('click', function() {
    if (typeof addItemRow === 'function') {
      addItemRow('po-items-table', window.ITEMS_DATA || []);
    }
  });
});
