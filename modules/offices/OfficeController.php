<?php
require_once ROOT_PATH . '/models/OfficeModel.php';
class OfficeController extends Controller {
    private OfficeModel $m;
    public function __construct() { $this->m = new OfficeModel(); }
    public function index(): void {
        Auth::requireLogin();
        $offices = $this->m->getActive();
        $flash = $this->getFlash();
        $this->render('offices/index', compact('offices', 'flash'));
    }
    public function create(): void {
        Auth::requireRole('admin', 'supply_officer');
        if ($this->isPost()) {
            $this->m->create([
                'office_code'   => trim($_POST['office_code']),
                'office_name'   => trim($_POST['office_name']),
                'department'    => trim($_POST['department']),
                'head_of_office'=> trim($_POST['head_of_office']),
            ]);
            $this->setFlash('success', 'Office added.');
            $this->redirect(BASE_URL . '/index.php?mod=offices&act=index');
        }
        $this->render('offices/create', []);
    }
    public function edit(): void {
        Auth::requireRole('admin', 'supply_officer');
        $id = (int)$this->get('id');
        $row = $this->m->findById($id);
        if ($this->isPost()) {
            $this->m->update($id, [
                'office_code'   => trim($_POST['office_code']),
                'office_name'   => trim($_POST['office_name']),
                'department'    => trim($_POST['department']),
                'head_of_office'=> trim($_POST['head_of_office']),
            ]);
            $this->setFlash('success', 'Office updated.');
            $this->redirect(BASE_URL . '/index.php?mod=offices&act=index');
        }
        $this->render('offices/edit', compact('row'));
    }
}
