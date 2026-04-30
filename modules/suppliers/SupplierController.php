<?php
require_once ROOT_PATH . '/models/SupplierModel.php';
class SupplierController extends Controller {
    private SupplierModel $m;
    public function __construct() { $this->m = new SupplierModel(); }

    public function index(): void {
        Auth::requireLogin();
        $suppliers = $this->m->getActive();
        $flash = $this->getFlash();
        $this->render('suppliers/index', compact('suppliers', 'flash'));
    }
    public function create(): void {
        Auth::requireRole('admin', 'supply_officer');
        if ($this->isPost()) {
            $this->m->create($this->sanitize($_POST));
            $this->setFlash('success', 'Supplier added successfully.');
            $this->redirect(BASE_URL . '/index.php?mod=suppliers&act=index');
        }
        $this->render('suppliers/create', []);
    }
    public function edit(): void {
        Auth::requireRole('admin', 'supply_officer');
        $id  = (int)$this->get('id');
        $row = $this->m->findById($id);
        if (!$row) { $this->setFlash('danger','Supplier not found.'); $this->redirect(BASE_URL.'/index.php?mod=suppliers&act=index'); }
        if ($this->isPost()) {
            $this->m->update($id, $this->sanitize($_POST));
            $this->setFlash('success', 'Supplier updated.');
            $this->redirect(BASE_URL . '/index.php?mod=suppliers&act=index');
        }
        $this->render('suppliers/edit', compact('row'));
    }
    public function delete(): void {
        Auth::requireRole('admin');
        $id = (int)$this->get('id');
        $this->m->update($id, ['is_active' => 0]);
        $this->setFlash('success', 'Supplier deactivated.');
        $this->redirect(BASE_URL . '/index.php?mod=suppliers&act=index');
    }
    private function sanitize(array $p): array {
        return [
            'supplier_name'   => trim($p['supplier_name'] ?? ''),
            'contact_person'  => trim($p['contact_person'] ?? ''),
            'telephone'       => trim($p['telephone'] ?? ''),
            'mobile'          => trim($p['mobile'] ?? ''),
            'email'           => trim($p['email'] ?? ''),
            'address'         => trim($p['address'] ?? ''),
            'tin_no'          => trim($p['tin_no'] ?? ''),
            'philgeps_reg_no' => trim($p['philgeps_reg_no'] ?? ''),
        ];
    }
}
