<?php
class Auth {
    public static function login(string $username, string $password): array|false {
        $db  = Database::getInstance();
        $row = $db->fetchOne(
            "SELECT * FROM users WHERE username = ? AND is_active = 1",
            [$username]
        );
        if ($row && password_verify($password, $row['password'])) {
            $_SESSION['user_id']   = $row['user_id'];
            $_SESSION['username']  = $row['username'];
            $_SESSION['full_name'] = $row['full_name'];
            $_SESSION['role']      = $row['role'];
            $_SESSION['last_activity'] = time();
            $db->execute(
                "UPDATE users SET last_login = NOW() WHERE user_id = ?",
                [$row['user_id']]
            );
            return $row;
        }
        return false;
    }

    public static function logout(): void {
        session_unset();
        session_destroy();
    }

    public static function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }

    public static function requireLogin(): void {
        //require_once ROOT_PATH . '/config/session.php';
        //if (!self::isLoggedIn()) {
        //    header('Location: ' . BASE_URL . '/index.php?mod=auth&act=login');
        //    exit;
        //}
    }

    public static function getCurrentUser(): array {
    return [
        'user_id'   => 0,
        'username'  => 'admin',
        'full_name' => 'Administrator',
        'role'      => 'admin',
    ];
}

    public static function hasRole(string ...$roles): bool {
        return in_array($_SESSION['role'] ?? '', $roles, true);
    }

    public static function requireRole(string ...$roles): void {
        //self::requireLogin();
        //if (!self::hasRole(...$roles)) {
        //    $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Access denied.'];
         //   header('Location: ' . BASE_URL . '/index.php?mod=dashboard&act=index');
        //    exit;
        //}
    }
}
