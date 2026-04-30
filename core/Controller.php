<?php
abstract class Controller {
    protected string $layout = 'main';

    protected function render(string $viewFile, array $data = []): void {
        extract($data);
        $user = Auth::getCurrentUser();
        $viewPath = ROOT_PATH . '/modules/' . $viewFile . '.php';
        if (!file_exists($viewPath)) {
            die("View not found: $viewFile");
        }
        $layoutPath = ROOT_PATH . '/views/layouts/' . $this->layout . '.php';
        ob_start();
        include $viewPath;
        $pageContent = ob_get_clean();
        include $layoutPath;
    }

    protected function renderPrint(string $viewFile, array $data = []): void {
        $this->layout = 'print';
        $this->render($viewFile, $data);
    }

    protected function redirect(string $url): void {
        header("Location: $url");
        exit;
    }

    protected function redirectBack(): void {
        $ref = $_SERVER['HTTP_REFERER'] ?? BASE_URL . '/index.php?mod=dashboard&act=index';
        $this->redirect($ref);
    }

    protected function json(mixed $data, int $code = 200): void {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function setFlash(string $type, string $msg): void {
        $_SESSION['flash'] = compact('type', 'msg');
    }

    protected function getFlash(): array {
        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $flash;
    }

    protected function input(string $key, mixed $default = ''): mixed {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function post(string $key, mixed $default = ''): mixed {
        return $_POST[$key] ?? $default;
    }

    protected function get(string $key, mixed $default = ''): mixed {
        return $_GET[$key] ?? $default;
    }

    protected function isPost(): bool {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function currentUser(): array {
    return [
        'user_id'   => 0,
        'username'  => 'admin',
        'full_name' => 'Administrator',
        'role'      => 'admin',
    ];
}
}
