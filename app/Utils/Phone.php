<?php

namespace App\Utils;

class Phone
{
    /**
     * Normalize phone number.
     */
    public static function normalize(string $value = null): ?string
    {
        if (is_null($value)) {
            return null;
        }

        $phoneNo = ltrim($value, '0');

        if (substr($phoneNo, 0, 2) == '62') {
            $phoneNo = "+$phoneNo";
        } elseif (substr($phoneNo, 0, 3) == '+62') {
            $phoneNo = $phoneNo;
        } else {
            $phoneNo = "+62$phoneNo";
        }

        return $phoneNo;
    }

    /**
     * Normalize phone number.
     */
    public static function normalizePrefixZero(string $value): string
    {
        $phoneNo = preg_replace('/^[0\D62]++|\D++/', '', $value);

        return "0$phoneNo";
    }
}
