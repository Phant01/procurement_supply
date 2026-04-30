<?php
require_once ROOT_PATH . '/models/RisModel.php';
require_once ROOT_PATH . '/models/OfficeModel.php';
require_once ROOT_PATH . '/models/ItemModel.php';
require_once ROOT_PATH . '/models/IcsModel.php';
require_once ROOT_PATH . '/models/ParModel.php';
require_once ROOT_PATH . '/models/PersonnelModel.php';
class RisController extends Controller {
    private RisModel $m;
    public function __construct() { $this->m = new RisModel(); }

    public function index(): void {
        Auth::requireLogin();
        $risList = $this->m->getList();
        $flash   = $this->getFlash();
        $this->render('ris/index', compact('risList', 'flash'));
    }

    public function create(): void {
    Auth::requireRole('admin', 'supply_officer');

    $offices = (new OfficeModel())->getActiveWithDept();
    $items   = (new ItemModel())->getWithCategory();

    if ($this->isPost()) {

        // ── Validate required fields ──────────────────────────────────
        $risNumber = trim($_POST['ris_number'] ?? '');
        $risDate   = trim($_POST['ris_date']   ?? '');
        $officeId  = (int)($_POST['office_id'] ?? 0);
        $lines     = $_POST['items'] ?? [];

        if (!$risNumber) {
            $this->setFlash('danger', 'RIS Number is required.');
            $this->render('ris/create', compact('offices', 'items'));
            return;
        }
        if (!$officeId) {
            $this->setFlash('danger', 'Please select a requesting office.');
            $this->render('ris/create', compact('offices', 'items'));
            return;
        }
        if (empty($lines)) {
            $this->setFlash('danger', 'Please add at least one item.');
            $this->render('ris/create', compact('offices', 'items'));
            return;
        }

        // ── Filter out empty rows ─────────────────────────────────────
        $validLines = [];
        foreach ($lines as $line) {
            $itemId = (int)($line['item_id'] ?? 0);
            $qty    = (float)($line['qty']     ?? 0);
            if ($itemId > 0 && $qty > 0) {
                $validLines[] = ['item_id' => $itemId, 'qty' => $qty];
            }
        }

        if (empty($validLines)) {
            $this->setFlash('danger', 'No valid items found. Make sure each row has an item selected and quantity greater than zero.');
            $flash = $this->getFlash();
            $this->render('ris/create', compact('offices', 'items', 'flash'));
            return;
        }

        // ── Save ──────────────────────────────────────────────────────
        $db = Database::getInstance();
        $db->beginTransaction();
        try {
            $risId = $this->m->create([
                'office_id'    => $officeId,
                'ris_number'   => $risNumber,
                'ris_date'     => $risDate,
                'purpose'      => trim($_POST['purpose']      ?? ''),
                'requested_by' => trim($_POST['requested_by'] ?? ''),
                'approved_by'  => trim($_POST['approved_by']  ?? ''),
                'status'       => 'pending',
            ]);

            foreach ($validLines as $line) {
                $db->execute(
                    "INSERT INTO ris_items (ris_id, item_id, qty_requested)
                     VALUES (?, ?, ?)",
                    [$risId, $line['item_id'], $line['qty']]
                );
            }

            $db->commit();
            $this->setFlash('success', 'RIS ' . $risNumber . ' created successfully.');
            $this->redirect(BASE_URL . "/index.php?mod=ris&act=view&id=$risId");
            return;

        } catch (\Exception $e) {
            $db->rollBack();
            $this->setFlash('danger', 'Database error: ' . $e->getMessage());
            $flash = $this->getFlash();
            $this->render('ris/create', compact('offices', 'items', 'flash'));
            return;
        }
    }

    $flash = $this->getFlash();
    $this->render('ris/create', compact('offices', 'items', 'flash'));
}

    public function view(): void {
        Auth::requireLogin();
        $id    = (int)$this->get('id');
        $ris   = $this->m->getWithDetails($id);
        $items = $this->m->getItems($id);
        $flash = $this->getFlash();
        $this->render('ris/view', compact('ris', 'items', 'flash'));
    }

    public function issue(): void {
        Auth::requireRole('admin', 'supply_officer');
        $id    = (int)$this->get('id');
        $ris   = $this->m->getWithDetails($id);
        $items = $this->m->getItems($id);
        $personnel = (new PersonnelModel())->getWithOffice();
        if ($this->isPost()) {
            try {
                $lines = [];
                foreach ($_POST['lines'] as $risItemId => $line) {
                    $lines[] = [
                        'ris_item_id' => (int)$risItemId,
                        'qty_issued'  => (float)$line['qty_issued'],
                        'unit_cost'   => (float)$line['unit_cost'],
                    ];
                }
                $this->m->issueRis($id, $lines, [
                    'issued_by'   => trim($_POST['issued_by']),
                    'received_by' => trim($_POST['received_by']),
                ]);

                // Auto-generate ICS or PAR per item type
                foreach ($items as $it) {
                    $qty = (float)($_POST['lines'][$it['ris_item_id']]['qty_issued'] ?? 0);
                    if ($qty <= 0) continue;
                    $pId = (int)($_POST['lines'][$it['ris_item_id']]['personnel_id'] ?? 0);
                    if (!$pId) continue;
                    $cost = (float)($_POST['lines'][$it['ris_item_id']]['unit_cost'] ?? $it['unit_cost']);
                    if ($it['item_type'] === 'semi_expendable') {
                        (new IcsModel())->generateFromRisItem(
                            $it['ris_item_id'], $pId, $qty, $cost, $ris['ris_date'],
                            'ICS-PROP-' . $it['ris_item_id']
                        );
                    } elseif ($it['item_type'] === 'equipment') {
                        (new ParModel())->generateFromRisItem(
                            $it['ris_item_id'], $pId, $qty, $cost, $ris['ris_date'],
                            'PAR-PROP-' . $it['ris_item_id']
                        );
                    }
                }
                $this->setFlash('success', 'RIS issued. Stock card updated. ICS/PAR generated where applicable.');
                $this->redirect(BASE_URL . "/index.php?mod=ris&act=view&id=$id");
            } catch (\Exception $e) {
                $this->setFlash('danger', $e->getMessage());
                $this->redirect(BASE_URL . "/index.php?mod=ris&act=issue&id=$id");
            }
        }
        $flash = $this->getFlash();
        $this->render('ris/issue', compact('ris', 'items', 'personnel', 'flash'));
    }

    public function print(): void {
        Auth::requireLogin();
        $id    = (int)$this->get('id');
        $ris   = $this->m->getWithDetails($id);
        $items = $this->m->getItems($id);
        $this->renderPrint('ris/print', compact('ris', 'items'));
    }
}
