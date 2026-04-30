<?php
if (isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/index.php?mod=auth&act=login&msg=timeout');
        exit;
    }
}
$_SESSION['last_activity'] = time();
