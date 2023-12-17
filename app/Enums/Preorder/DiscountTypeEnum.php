<?php

declare(strict_types=1);

namespace App\Enums\Preorder;

use BenSampo\Enum\Enum;

/**
 * @method static static ZONE_1()
 * @method static static ZONE_2()
 */
final class DiscountTypeEnum extends Enum
{
    const DISCOUNT_NO = 0;

    const DISCOUNT_PERCENTAGE = 1;

    const DISCOUNT_PRICE = 2;

    const MAP_LABEL = [
        self::DISCOUNT_NO => 'Tidak',
        self::DISCOUNT_PERCENTAGE => 'Persen',
        self::DISCOUNT_PRICE => 'Harga',
    ];

    public function getLabel()
    {
        return match ($this->value) {
            self::DISCOUNT_NO => 'Tidak',
            self::DISCOUNT_PERCENTAGE => 'Persen',
            self::DISCOUNT_PRICE => 'Harga',
            default => ''
        };
    }
}
