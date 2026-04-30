<?php
require_once ROOT_PATH . '/models/StockCardModel.php';
require_once ROOT_PATH . '/models/ItemModel.php';
class StockCardController extends Controller {
    private StockCardModel $m;
    public function __construct() { $this->m = new StockCardModel(); }

    public function index(): void {
        Auth::requireLogin();
        $cards = $this->m->getAllBalances();
        $flash = $this->getFlash();
        $this->render('stock_cards/index', compact('cards', 'flash'));
    }

    public function view(): void {
        Auth::requireLogin();
        $itemId = (int)$this->get('item_id');
        $from   = $this->get('from', date('Y-01-01'));
        $to     = $this->get('to',   date('Y-m-d'));
        $item   = (new ItemModel())->findById($itemId);
        $ledger = $this->m->getLedger($itemId, $from, $to);
        $this->render('stock_cards/view', compact('item', 'ledger', 'from', 'to'));
    }

    public function print(): void {
        Auth::requireLogin();
        $itemId = (int)$this->get('item_id');
        $from   = $this->get('from', date('Y-01-01'));
        $to     = $this->get('to',   date('Y-m-d'));
        $item   = (new ItemModel())->findById($itemId);
        $ledger = $this->m->getLedger($itemId, $from, $to);
        $this->renderPrint('stock_cards/print', compact('item', 'ledger', 'from', 'to'));
    }

    public function adjust(): void {
        Auth::requireRole('admin', 'supply_officer');
        $itemId = (int)$this->get('item_id');
        $item   = (new ItemModel())->findById($itemId);
        if ($this->isPost()) {
            try {
                $this->m->addAdjustment(
                    $itemId,
                    (float)$_POST['quantity'],
                    $_POST['adj_type'],
                    trim($_POST['remarks']),
                    $_POST['adj_date']
                );
                $this->setFlash('success', 'Stock adjustment recorded.');
            } catch (\Exception $e) {
                $this->setFlash('danger', $e->getMessage());
            }
            $this->redirect(BASE_URL . "/index.php?mod=stock_cards&act=view&item_id=$itemId");
        }
        $this->render('stock_cards/adjust', compact('item'));
    }
}
