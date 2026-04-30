<?php
require_once ROOT_PATH . '/models/UserModel.php';
require_once ROOT_PATH . '/models/PersonnelModel.php';
class UserController extends Controller {
    private UserModel $m;
    public function __construct() { $this->m = new UserModel(); }
    public function index(): void {
        Auth::requireRole('admin');
        $users = $this->m->listUsers();
        $flash = $this->getFlash();
        $this->render('users/index', compact('users', 'flash'));
    }
    public function create(): void {
        Auth::requireRole('admin');
        $personnel = (new PersonnelModel())->getWithOffice();
        if ($this->isPost()) {
            if ($_POST['password'] !== $_POST['password_confirm']) {
                $this->setFlash('danger', 'Passwords do not match.');
                $this->redirect(BASE_URL . '/index.php?mod=users&act=create');
            }
            $this->m->createUser([
                'personnel_id' => $_POST['personnel_id'] ?: null,
                'username'     => trim($_POST['username']),
                'password'     => $_POST['password'],
                'full_name'    => trim($_POST['full_name']),
                'role'         => $_POST['role'],
            ]);
            $this->setFlash('success', 'User created.');
            $this->redirect(BASE_URL . '/index.php?mod=users&act=index');
        }
        $this->render('users/create', compact('personnel'));
    }
    public function changePassword(): void {
        Auth::requireLogin();
        $id = (int)($this->currentUser()['user_id']);
        if ($this->isPost()) {
            $current = $_POST['current_password'];
            $new     = $_POST['new_password'];
            $confirm = $_POST['confirm_password'];
            $row = $this->m->findById($id);
            if (!password_verify($current, $row['password'])) {
                $this->setFlash('danger', 'Current password is incorrect.');
            } elseif ($new !== $confirm) {
                $this->setFlash('danger', 'New passwords do not match.');
            } else {
                $this->m->changePassword($id, $new);
                $this->setFlash('success', 'Password changed successfully.');
            }
            $this->redirect(BASE_URL . '/index.php?mod=users&act=changePassword');
        }
        $flash = $this->getFlash();
        $this->render('users/change_password', compact('flash'));
    }
    public function toggle(): void {
        Auth::requireRole('admin');
        $id  = (int)$this->get('id');
        $row = $this->m->findById($id);
        $this->m->update($id, ['is_active' => $row['is_active'] ? 0 : 1]);
        $this->setFlash('success', 'User status updated.');
        $this->redirect(BASE_URL . '/index.php?mod=users&act=index');
    }
}
