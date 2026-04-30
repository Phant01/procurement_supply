<?php
class StringHelper {
    public static function clean(string $str): string {
        return htmlspecialchars(strip_tags(trim($str)), ENT_QUOTES, 'UTF-8');
    }

    public static function truncate(string $str, int $len = 60): string {
        return mb_strlen($str) > $len ? mb_substr($str, 0, $len) . '…' : $str;
    }

    public static function slug(string $str): string {
        return preg_replace('/[^a-z0-9_-]/', '', strtolower(str_replace(' ', '_', $str)));
    }

    public static function statusBadge(string $status): string {
        return '<span class="badge status-' . htmlspecialchars($status) . '">'
            . ucfirst(str_replace('_', ' ', $status)) . '</span>';
    }
}
