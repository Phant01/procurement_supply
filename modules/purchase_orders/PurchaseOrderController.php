<?php
require_once ROOT_PATH . '/models/PurchaseOrderModel.php';
require_once ROOT_PATH . '/models/SupplierModel.php';
require_once ROOT_PATH . '/models/OfficeModel.php';
require_once ROOT_PATH . '/models/ItemModel.php';
class PurchaseOrderController extends Controller {
    private PurchaseOrderModel $m;
    public function __construct() { $this->m = new PurchaseOrderModel(); }

    public function index(): void {
        Auth::requireLogin();
        $status = $this->get('status', '');
        $pos    = $this->m->getList(1, $status);
        $flash  = $this->getFlash();
        $this->render('purchase_orders/index', compact('pos', 'flash', 'status'));
    }

    public function create(): void {
    Auth::requireRole('admin', 'supply_officer');

    $suppliers = (new SupplierModel())->getActive();
    $offices   = (new OfficeModel())->getActiveWithDept();
    $items     = (new ItemModel())->getWithCategory();

    if ($this->isPost()) {
        try {
            $db = Database::getInstance();
            $db->beginTransaction();

            $poData = [
                'supplier_id'         => (int)$_POST['supplier_id'],
                'office_id'           => (int)$_POST['office_id'],
                'po_number'           => trim($_POST['po_number']),
                'po_date'             => $_POST['po_date'],
                'delivery_date'       => $_POST['delivery_date'] ?: null,
                'place_of_delivery'   => trim($_POST['place_of_delivery'] ?? ''),
                'fund_source'         => trim($_POST['fund_source'] ?? ''),
                'mode_of_procurement' => trim($_POST['mode_of_procurement'] ?? ''),
                'approved_by'         => trim($_POST['approved_by'] ?? ''),
                'approved_date'       => $_POST['approved_date'] ?: null,
                'status'              => 'approved',
                'created_by'          => $this->currentUser()['full_name'],
            ];

            $cols = implode(', ', array_keys($poData));
            $phs  = implode(', ', array_fill(0, count($poData), '?'));
            $db->execute(
                "INSERT INTO purchase_orders ($cols) VALUES ($phs)",
                array_values($poData)
            );
            $poId = (int) $db->lastInsertId();

            $total = 0;
            foreach ($_POST['items'] as $line) {
                if (empty($line['item_id'])) continue;
                $qty   = (float)$line['qty'];
                $price = (float)$line['price'];
                $db->execute(
                    "INSERT INTO po_items
                     (po_id, item_id, unit_of_measure, qty_ordered, unit_price)
                     VALUES (?, ?, ?, ?, ?)",
                    [$poId, (int)$line['item_id'], $line['uom'], $qty, $price]
                );
                $total += $qty * $price;
            }

            $db->execute(
                "UPDATE purchase_orders SET total_amount = ? WHERE po_id = ?",
                [$total, $poId]
            );

            $db->commit();
            $this->setFlash('success', "PO #{$_POST['po_number']} created successfully.");
            $this->redirect(BASE_URL . "/index.php?mod=purchase_orders&act=view&id=$poId");

        } catch (\Exception $e) {
            Database::getInstance()->rollBack();
            $this->setFlash('danger', 'Error: ' . $e->getMessage());
            $this->redirect(BASE_URL . '/index.php?mod=purchase_orders&act=create');
        }
    }

    $flash = $this->getFlash();
    $this->render('purchase_orders/create', compact('suppliers', 'offices', 'items', 'flash'));
}

    public function view(): void {
        Auth::requireLogin();
        $id    = (int)$this->get('id');
        $po    = $this->m->getWithDetails($id);
        $lines = $this->m->getItems($id);
        $flash = $this->getFlash();
        $this->render('purchase_orders/view', compact('po', 'lines', 'flash'));
    }

    public function print(): void {
        Auth::requireLogin();
        $id    = (int)$this->get('id');
        $po    = $this->m->getWithDetails($id);
        $lines = $this->m->getItems($id);
        $this->renderPrint('purchase_orders/print', compact('po', 'lines'));
    }

    public function cancel(): void {
        Auth::requireRole('admin', 'supply_officer');
        $id = (int)$this->get('id');
        $this->m->update($id, ['status' => 'cancelled', 'cancelled_reason' => $this->post('reason')]);
        $this->setFlash('success', 'PO cancelled.');
        $this->redirect(BASE_URL . "/index.php?mod=purchase_orders&act=view&id=$id");
    }
}
