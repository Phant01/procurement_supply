<?php
require_once ROOT_PATH . '/models/UserModel.php';
class AuthController extends Controller {
    protected string $layout = 'auth';
    public function login(): void {
        if (Auth::isLoggedIn()) {
            $this->redirect(BASE_URL . '/index.php?mod=dashboard&act=index');
        }
        $error = '';
        if ($this->isPost()) {
            $u = trim($this->post('username'));
            $p = $this->post('password');
            if (Auth::login($u, $p)) {
                $this->redirect(BASE_URL . '/index.php?mod=dashboard&act=index');
            }
            $error = 'Invalid username or password.';
        }
        $timeout = $this->get('msg') === 'timeout';
        $this->render('auth/login', compact('error', 'timeout'));
    }
    public function logout(): void {
        Auth::logout();
        $this->redirect(BASE_URL . '/index.php?mod=auth&act=login');
    }
}
