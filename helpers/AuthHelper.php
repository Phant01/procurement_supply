<?php
class AuthHelper {
    public static function currentUserName(): string {
        return $_SESSION['full_name'] ?? 'Unknown';
    }

    public static function isAdmin(): bool {
        return ($_SESSION['role'] ?? '') === 'admin';
    }

    public static function isSupplyOfficer(): bool {
        return in_array($_SESSION['role'] ?? '', ['admin', 'supply_officer'], true);
    }
}
