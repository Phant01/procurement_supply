<?php
require_once ROOT_PATH . '/models/ReceivingModel.php';
require_once ROOT_PATH . '/models/PurchaseOrderModel.php';
class ReceivingController extends Controller {
    private ReceivingModel $m;
    public function __construct() { $this->m = new ReceivingModel(); }

    public function index(): void {
        Auth::requireLogin();
        $receipts = $this->m->getList();
        $flash    = $this->getFlash();
        $this->render('receiving/index', compact('receipts', 'flash'));
    }

    public function create(): void {
        Auth::requireRole('admin', 'supply_officer');
        $poId = (int)$this->get('po_id');
        $pm   = new PurchaseOrderModel();
        $po   = $pm->getWithDetails($poId);
        $lines = $pm->getItems($poId);
        if ($this->isPost()) {
            try {
                $header = [
                    'po_id'        => $poId,
                    'iar_number'   => trim($_POST['iar_number']),
                    'receipt_date' => $_POST['receipt_date'],
                    'delivery_ref' => trim($_POST['delivery_ref']),
                    'received_by'  => trim($_POST['received_by']),
                    'inspected_by' => trim($_POST['inspected_by']),
                    'approved_by'  => trim($_POST['approved_by']),
                    'remarks'      => trim($_POST['remarks']),
                ];
                $receivedLines = [];
                foreach ($_POST['lines'] as $poItemId => $line) {
                    $qty = (float)$line['qty_received'];
                    if ($qty <= 0) continue;
                    $receivedLines[] = [
                        'po_item_id'   => (int)$poItemId,
                        'qty_received' => $qty,
                        'unit_cost'    => (float)$line['unit_cost'],
                    ];
                }
                $receiptId = $this->m->createReceipt($header, $receivedLines);
                $this->setFlash('success', 'Delivery received and stock card updated.');
                $this->redirect(BASE_URL . "/index.php?mod=receiving&act=view&id=$receiptId");
            } catch (\Exception $e) {
                $this->setFlash('danger', 'Error: ' . $e->getMessage());
                $this->redirect(BASE_URL . "/index.php?mod=receiving&act=create&po_id=$poId");
            }
        }
        $flash = $this->getFlash();
        $this->render('receiving/create', compact('po', 'lines', 'flash'));
    }

    public function view(): void {
        Auth::requireLogin();
        $id      = (int)$this->get('id');
        $receipt = $this->m->getWithDetails($id);
        $lines   = $this->m->getItems($id);
        $this->render('receiving/view', compact('receipt', 'lines'));
    }

    public function printIar(): void {
        Auth::requireLogin();
        $id      = (int)$this->get('id');
        $receipt = $this->m->getWithDetails($id);
        $lines   = $this->m->getItems($id);
        $this->renderPrint('receiving/print_iar', compact('receipt', 'lines'));
    }
}
