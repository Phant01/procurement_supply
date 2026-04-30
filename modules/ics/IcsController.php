<?php
require_once ROOT_PATH . '/models/IcsModel.php';
class IcsController extends Controller {
    private IcsModel $m;
    public function __construct() { $this->m = new IcsModel(); }

    public function index(): void {
        Auth::requireLogin();
        $flash    = $this->getFlash();
        $status   = $this->get('status', 'active');
        $registry = $this->m->query(
            "SELECT * FROM v_ics_registry" . ($status ? " WHERE status = ?" : "") . " ORDER BY assigned_to",
            $status ? [$status] : []
        );
        $this->render('ics/index', compact('registry', 'flash', 'status'));
    }

    public function view(): void {
        Auth::requireLogin();
        $id  = (int)$this->get('id');
        $row = $this->m->getWithDetails($id);
        $this->render('ics/view', compact('row'));
    }

    public function print(): void {
        Auth::requireLogin();
        $id  = (int)$this->get('id');
        $row = $this->m->getWithDetails($id);
        $this->renderPrint('ics/print', compact('row'));
    }

    public function return(): void {
    Auth::requireRole('admin', 'supply_officer');
    $id  = (int)$this->get('id');
    $row = $this->m->getWithDetails($id);

    if (!$row) {
        $this->setFlash('danger', 'ICS record not found.');
        $this->redirect(BASE_URL . '/index.php?mod=ics&act=index');
    }

    if ($row['status'] !== 'active') {
        $this->setFlash('danger', 'Only active ICS can be returned.');
        $this->redirect(BASE_URL . '/index.php?mod=ics&act=index');
    }

    $db = Database::getInstance();
    $db->beginTransaction();
    try {
        // 1. Mark ICS as returned
        $this->m->update($id, [
            'status'        => 'returned',
            'returned_date' => date('Y-m-d'),
            'remarks'       => $this->post('remarks'),
        ]);

        // 2. Get the stock card for this item
        $sc = $db->fetchOne(
            "SELECT sc.stock_card_id, sc.balance_qty
             FROM stock_cards sc
             JOIN items i ON i.item_id = sc.item_id
             JOIN ris_items ri ON ri.item_id = i.item_id
             WHERE ri.ris_item_id = ?",
            [$row['ris_item_id'] ?? 0]
        );

        // Fallback: find stock card directly via item_name match
        if (!$sc) {
            $sc = $db->fetchOne(
                "SELECT sc.stock_card_id, sc.balance_qty
                 FROM stock_cards sc
                 JOIN items i ON i.item_id = sc.item_id
                 WHERE i.item_name = ?",
                [$row['item_name']]
            );
        }

        if ($sc) {
            $returnQty  = (float)$row['quantity'];
            $newBalance = (float)$sc['balance_qty'] + $returnQty;

            // 3. Write RETURN entry to stock card
            $db->execute(
                "INSERT INTO stock_card_entries
                 (stock_card_id, txn_date, ref_type, ref_id,
                  ref_number, qty_in, qty_out, unit_cost, balance, remarks)
                 VALUES (?, ?, 'RETURN', ?, ?, ?, 0, ?, ?, ?)",
                [
                    $sc['stock_card_id'],
                    date('Y-m-d'),
                    $id,
                    $row['ics_number'],
                    $returnQty,
                    (float)$row['unit_cost'],
                    $newBalance,
                    'Returned from ICS by ' . $row['assigned_to'],
                ]
            );

            // 4. Update running balance on stock card
            $db->execute(
                "UPDATE stock_cards SET balance_qty = ? WHERE stock_card_id = ?",
                [$newBalance, $sc['stock_card_id']]
            );
        }

        $db->commit();
        $this->setFlash('success',
            'ICS marked as returned. ' .
            ($sc ? number_format((float)$row['quantity'], 2) . ' unit(s) added back to stock.' : 'Stock card not found — please adjust manually.')
        );

    } catch (\Exception $e) {
        $db->rollBack();
        $this->setFlash('danger', 'Error: ' . $e->getMessage());
    }

    $this->redirect(BASE_URL . '/index.php?mod=ics&act=index');
}
}
