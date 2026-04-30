<?php
class Router {
    private array $moduleMap = [
        'auth'            => ['path' => 'auth',            'class' => 'AuthController'],
        'dashboard'       => ['path' => 'dashboard',       'class' => 'DashboardController'],
        'suppliers'       => ['path' => 'suppliers',       'class' => 'SupplierController'],
        'items'           => ['path' => 'items',           'class' => 'ItemController'],
        'purchase_orders' => ['path' => 'purchase_orders', 'class' => 'PurchaseOrderController'],
        'receiving'       => ['path' => 'receiving',       'class' => 'ReceivingController'],
        'stock_cards'     => ['path' => 'stock_cards',     'class' => 'StockCardController'],
        'ris'             => ['path' => 'ris',             'class' => 'RisController'],
        'ics'             => ['path' => 'ics',             'class' => 'IcsController'],
        'par'             => ['path' => 'par',             'class' => 'ParController'],
        'reports'         => ['path' => 'reports',         'class' => 'ReportController'],
        'offices'         => ['path' => 'offices',         'class' => 'OfficeController'],
        'personnel'       => ['path' => 'personnel',       'class' => 'PersonnelController'],
        'users'           => ['path' => 'users',           'class' => 'UserController'],
    ];

    public function dispatch(): void {
        $mod = $_GET['mod'] ?? 'dashboard';
        $act = $_GET['act'] ?? 'index';

        // Sanitize
        $mod = preg_replace('/[^a-z_]/', '', strtolower($mod));
        $act = preg_replace('/[^a-zA-Z_]/', '', $act);

        if (!isset($this->moduleMap[$mod])) {
            $mod = 'dashboard';
        }

        $info  = $this->moduleMap[$mod];
        $file  = ROOT_PATH . '/modules/' . $info['path'] . '/' . $info['class'] . '.php';
        $class = $info['class'];

        if (!file_exists($file)) {
            die("Controller not found: $file");
        }

        require_once $file;
        $controller = new $class();

        if (!method_exists($controller, $act)) {
            $act = 'index';
        }

        $controller->$act();
    }
}
