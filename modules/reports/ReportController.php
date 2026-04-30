<?php
require_once ROOT_PATH . '/models/ReportModel.php';
class ReportController extends Controller {

    public function index(): void {
        Auth::requireLogin();
        $this->render('reports/index', []);
    }

    public function rsmi(): void {
        Auth::requireLogin();
        $from  = $this->get('from', date('Y-m-01'));
        $to    = $this->get('to',   date('Y-m-t'));
        $rows  = [];
        $print = (bool)$this->get('print');
        if ($this->get('from')) {
            $rows = (new ReportModel())->getRsmi($from, $to);
        }
        if ($print) {
            $this->renderPrint('reports/rsmi', compact('rows', 'from', 'to'));
        } else {
            $this->render('reports/rsmi', compact('rows', 'from', 'to'));
        }
    }

    public function stockCard(): void {
        Auth::requireLogin();
        // Redirect to stock cards module for single-item view
        $this->redirect(BASE_URL . '/index.php?mod=stock_cards&act=index');
    }

    public function rpci(): void {
        Auth::requireLogin();
        $rows  = (new ReportModel())->getRpci();
        $print = (bool)$this->get('print');
        if ($print) {
            $this->renderPrint('reports/rpci', compact('rows'));
        } else {
            $this->render('reports/rpci', compact('rows'));
        }
    }

    public function icsRegistry(): void {
        Auth::requireLogin();
        $status = $this->get('status', 'active');
        $rows   = (new ReportModel())->getIcsRegistry($status);
        $print  = (bool)$this->get('print');
        if ($print) {
            $this->renderPrint('reports/ics_registry', compact('rows', 'status'));
        } else {
            $this->render('reports/ics_registry', compact('rows', 'status'));
        }
    }

    public function parRegistry(): void {
        Auth::requireLogin();
        $status = $this->get('status', 'active');
        $rows   = (new ReportModel())->getParRegistry($status);
        $print  = (bool)$this->get('print');
        if ($print) {
            $this->renderPrint('reports/par_registry', compact('rows', 'status'));
        } else {
            $this->render('reports/par_registry', compact('rows', 'status'));
        }
    }

    public function lowStock(): void {
        Auth::requireLogin();
        require_once ROOT_PATH . '/models/ItemModel.php';
        $rows  = (new ItemModel())->getLowStock();
        $print = (bool)$this->get('print');
        if ($print) {
            $this->renderPrint('reports/low_stock', compact('rows'));
        } else {
            $this->render('reports/low_stock', compact('rows'));
        }
    }
}
