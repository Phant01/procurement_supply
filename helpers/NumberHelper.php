<?php
class NumberHelper {
    private static array $ones = [
        '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
        'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen',
        'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen',
    ];
    private static array $tens = [
        '', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety',
    ];

    public static function toWords(float $amount): string {
        $amount   = round($amount, 2);
        $pesos    = (int) $amount;
        $centavos = (int) round(($amount - $pesos) * 100);
        $words    = self::convert($pesos) . ' Pesos';
        if ($centavos > 0) {
            $words .= ' and ' . self::convert($centavos) . ' Centavos';
        }
        return $words . ' Only';
    }

    private static function convert(int $n): string {
        if ($n < 20)  return self::$ones[$n];
        if ($n < 100) return self::$tens[(int)($n/10)] . (($n%10) ? ' ' . self::$ones[$n%10] : '');
        if ($n < 1000) {
            return self::$ones[(int)($n/100)] . ' Hundred'
                . (($n%100) ? ' ' . self::convert($n%100) : '');
        }
        if ($n < 1000000) {
            return self::convert((int)($n/1000)) . ' Thousand'
                . (($n%1000) ? ' ' . self::convert($n%1000) : '');
        }
        if ($n < 1000000000) {
            return self::convert((int)($n/1000000)) . ' Million'
                . (($n%1000000) ? ' ' . self::convert($n%1000000) : '');
        }
        return self::convert((int)($n/1000000000)) . ' Billion'
            . (($n%1000000000) ? ' ' . self::convert($n%1000000000) : '');
    }

    public static function money(float $amount, bool $withSymbol = true): string {
        $formatted = number_format($amount, 2);
        return $withSymbol ? '₱' . $formatted : $formatted;
    }
}
