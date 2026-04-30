<?php
require_once ROOT_PATH . '/models/ParModel.php';
class ParController extends Controller {
    private ParModel $m;
    public function __construct() { $this->m = new ParModel(); }

    public function index(): void {
        Auth::requireLogin();
        $flash    = $this->getFlash();
        $status   = $this->get('status', 'active');
        $registry = $this->m->query(
            "SELECT * FROM v_par_registry" . ($status ? " WHERE status = ?" : "") . " ORDER BY assigned_to",
            $status ? [$status] : []
        );
        $this->render('par/index', compact('registry', 'flash', 'status'));
    }

    public function view(): void {
        Auth::requireLogin();
        $id  = (int)$this->get('id');
        $row = $this->m->getWithDetails($id);
        $this->render('par/view', compact('row'));
    }

    public function print(): void {
        Auth::requireLogin();
        $id  = (int)$this->get('id');
        $row = $this->m->getWithDetails($id);
        $this->renderPrint('par/print', compact('row'));
    }

    public function transfer(): void {
        Auth::requireRole('admin', 'supply_officer');
        $id = (int)$this->get('id');
        if ($this->isPost()) {
            $this->m->update($id, [
                'status'        => 'transferred',
                'transfer_date' => date('Y-m-d'),
                'remarks'       => $this->post('remarks'),
            ]);
            $this->setFlash('success', 'PAR marked as transferred.');
            $this->redirect(BASE_URL . '/index.php?mod=par&act=index');
        }
        $row = $this->m->getWithDetails($id);
        $this->render('par/transfer', compact('row'));
    }
}
