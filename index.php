<?php
session_start();
define('ROOT_PATH', __DIR__);

require_once ROOT_PATH . '/config/app.php';
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/core/Model.php';
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Auth.php';
require_once ROOT_PATH . '/core/Router.php';

// Auto-load models and helpers
spl_autoload_register(function ($class) {
    $paths = [
        ROOT_PATH . '/models/' . $class . '.php',
        ROOT_PATH . '/helpers/' . $class . '.php',
    ];
    foreach ($paths as $p) {
        if (file_exists($p)) { require_once $p; return; }
    }
});

$router = new Router();
$router->dispatch();
