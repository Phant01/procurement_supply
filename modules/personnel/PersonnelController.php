<?php
require_once ROOT_PATH . '/models/PersonnelModel.php';
require_once ROOT_PATH . '/models/OfficeModel.php';
class PersonnelController extends Controller {
    private PersonnelModel $m;
    public function __construct() { $this->m = new PersonnelModel(); }
    public function index(): void {
        Auth::requireLogin();
        $personnel = $this->m->getWithOffice();
        $flash = $this->getFlash();
        $this->render('personnel/index', compact('personnel', 'flash'));
    }
    public function create(): void {
        Auth::requireRole('admin', 'supply_officer');
        $offices = (new OfficeModel())->getActive();
        if ($this->isPost()) {
            $this->m->create([
                'office_id'   => (int)$_POST['office_id'],
                'employee_no' => trim($_POST['employee_no']),
                'full_name'   => trim($_POST['full_name']),
                'position'    => trim($_POST['position']),
            ]);
            $this->setFlash('success', 'Personnel added.');
            $this->redirect(BASE_URL . '/index.php?mod=personnel&act=index');
        }
        $this->render('personnel/create', compact('offices'));
    }
    public function edit(): void {
        Auth::requireRole('admin', 'supply_officer');
        $id  = (int)$this->get('id');
        $row = $this->m->findById($id);
        $offices = (new OfficeModel())->getActive();
        if ($this->isPost()) {
            $this->m->update($id, [
                'office_id'   => (int)$_POST['office_id'],
                'employee_no' => trim($_POST['employee_no']),
                'full_name'   => trim($_POST['full_name']),
                'position'    => trim($_POST['position']),
            ]);
            $this->setFlash('success', 'Personnel updated.');
            $this->redirect(BASE_URL . '/index.php?mod=personnel&act=index');
        }
        $this->render('personnel/edit', compact('row', 'offices'));
    }
}
