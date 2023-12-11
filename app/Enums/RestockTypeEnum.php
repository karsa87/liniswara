<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static GENERAL()
 * @method static static DISTRIBUTOR_CASH()
 */
final class RestockTypeEnum extends Enum
{
    const STOCK_ADD = 1;

    const STOCK_MINUS = 2;

    public function getLabel()
    {
        return match ($this->value) {
            self::STOCK_ADD => 'Umum',
            self::STOCK_MINUS => 'Distributor Cash',
            default => ''
        };
    }

    public static function getLabels()
    {
        return [
            self::STOCK_ADD => 'Umum',
            self::STOCK_MINUS => 'Distributor Cash',
        ];
    }
}
