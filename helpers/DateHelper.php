<?php
class DateHelper {
    public static function display(string $date): string {
        return $date ? date(DATE_DISPLAY, strtotime($date)) : '';
    }

    public static function short(string $date): string {
        return $date ? date('m/d/Y', strtotime($date)) : '';
    }

    public static function monthYear(string $date): string {
        return $date ? date('F Y', strtotime($date)) : '';
    }

    public static function currentFiscalYear(): string {
        $m = (int)date('m');
        $y = (int)date('Y');
        return $m >= 1 ? (string)$y : (string)($y - 1);
    }

    public static function periodLabel(string $from, string $to): string {
        return date('F d, Y', strtotime($from)) . ' to ' . date('F d, Y', strtotime($to));
    }
}
