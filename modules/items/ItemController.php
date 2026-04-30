<?php
require_once ROOT_PATH . '/models/ItemModel.php';
require_once ROOT_PATH . '/models/ItemCategoryModel.php';
require_once ROOT_PATH . '/models/StockCardModel.php';
class ItemController extends Controller {
    private ItemModel $m;
    public function __construct() { $this->m = new ItemModel(); }
    public function index(): void {
        Auth::requireLogin();
        $items = $this->m->getWithCategory();
        $flash = $this->getFlash();
        $this->render('items/index', compact('items', 'flash'));
    }
    public function create(): void {
        Auth::requireRole('admin', 'supply_officer');
        $cats = (new ItemCategoryModel())->findAll('category_name ASC');
        if ($this->isPost()) {
            $data = [
                'category_id'     => (int)$_POST['category_id'],
                'item_code'       => trim($_POST['item_code']),
                'item_name'       => trim($_POST['item_name']),
                'description'     => trim($_POST['description']),
                'unit_of_measure' => trim($_POST['unit_of_measure']),
                'uacs_code'       => trim($_POST['uacs_code']),
                'unit_cost'       => (float)$_POST['unit_cost'],
                'reorder_point'   => (float)$_POST['reorder_point'],
            ];
            $itemId = $this->m->create($data);
            // Auto-create stock card
            (new StockCardModel())->create(['item_id' => $itemId, 'balance_qty' => 0]);
            $this->setFlash('success', 'Item created with stock card.');
            $this->redirect(BASE_URL . '/index.php?mod=items&act=index');
        }
        $this->render('items/create', compact('cats'));
    }
    public function edit(): void {
        Auth::requireRole('admin', 'supply_officer');
        $id  = (int)$this->get('id');
        $row = $this->m->findById($id);
        $cats = (new ItemCategoryModel())->findAll('category_name ASC');
        if ($this->isPost()) {
            $this->m->update($id, [
                'category_id'     => (int)$_POST['category_id'],
                'item_code'       => trim($_POST['item_code']),
                'item_name'       => trim($_POST['item_name']),
                'description'     => trim($_POST['description']),
                'unit_of_measure' => trim($_POST['unit_of_measure']),
                'uacs_code'       => trim($_POST['uacs_code']),
                'unit_cost'       => (float)$_POST['unit_cost'],
                'reorder_point'   => (float)$_POST['reorder_point'],
            ]);
            $this->setFlash('success', 'Item updated.');
            $this->redirect(BASE_URL . '/index.php?mod=items&act=index');
        }
        $this->render('items/edit', compact('row', 'cats'));
    }
    public function search(): void {
        Auth::requireLogin();
        $q    = $this->get('q');
        $data = $this->m->search($q);
        $this->json($data);
    }
}
