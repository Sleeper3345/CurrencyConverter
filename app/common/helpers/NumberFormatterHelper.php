<?php

declare(strict_types=1);

namespace common\helpers;

class NumberFormatterHelper
{
    /**
     * @param float $number
     * @return string
     */
    public static function getFormattedNumber(float $number): string
    {
        $numberFormatted = number_format($number, 10, '.', '');

        return rtrim(rtrim($numberFormatted, '0'), '.');
    }
}
