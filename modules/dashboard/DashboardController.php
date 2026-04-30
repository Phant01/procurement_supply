<?php
require_once ROOT_PATH . '/models/ReportModel.php';
require_once ROOT_PATH . '/models/ItemModel.php';
class DashboardController extends Controller {
    public function index(): void {
        Auth::requireLogin();
        $rm    = new ReportModel();
        $im    = new ItemModel();
        $stats = $rm->getDashboardStats();
        $lowStock = $im->getLowStock();
        $this->render('dashboard/index', compact('stats', 'lowStock'));
    }
}
