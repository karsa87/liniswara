<?php

declare(strict_types=1);

namespace App\Enums\Preorder;

use BenSampo\Enum\Enum;

/**
 * @method static static NO_TAX()
 * @method static static PPN_10()
 * @method static static GST_6()
 * @method static static VAT_20()
 */
final class TaxEnum extends Enum
{
    const NO_TAX = 0;

    const PPN_10 = 1;

    const GST_6 = 2;

    const VAT_20 = 3;

    const MAP_LABEL = [
        self::NO_TAX => 'No Tax',
        self::PPN_10 => 'PPN @10%',
        self::GST_6 => 'GST @6%',
        self::VAT_20 => 'VAT @20%',
    ];

    public function getLabel()
    {
        return match ($this->value) {
            self::NO_TAX => 'No Tax',
            self::PPN_10 => 'PPN @10%',
            self::GST_6 => 'GST @6%',
            self::VAT_20 => 'VAT @20%',
            default => ''
        };
    }
}
