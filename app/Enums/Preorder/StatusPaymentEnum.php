<?php

declare(strict_types=1);

namespace App\Enums\Preorder;

use BenSampo\Enum\Enum;

/**
 * @method static static NOT_PAID()
 * @method static static PAID()
 * @method static static DP()
 */
final class StatusPaymentEnum extends Enum
{
    const NOT_PAID = 1;

    const PAID = 2;

    const DP = 3;

    const MAP_LABEL = [
        self::NOT_PAID => 'Belum Terbayar',
        self::PAID => 'Terbayar',
        self::DP => 'Terbayar Sebagian',
    ];

    public function getLabel()
    {
        return match ($this->value) {
            self::NOT_PAID => 'Belum Terbayar',
            self::PAID => 'Terbayar',
            self::DP => 'Terbayar Sebagian',
            default => ''
        };
    }
}
