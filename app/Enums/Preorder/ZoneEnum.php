<?php

declare(strict_types=1);

namespace App\Enums\Preorder;

use BenSampo\Enum\Enum;

/**
 * @method static static ZONE_1()
 * @method static static ZONE_2()
 */
final class ZoneEnum extends Enum
{
    const ZONE_1 = 1;

    const ZONE_2 = 2;

    const MAP_LABEL = [
        self::ZONE_1 => 'Zona 1',
        self::ZONE_2 => 'Zona 2',
    ];

    public function getLabel()
    {
        return match ($this->value) {
            self::ZONE_1 => 'Zona 1',
            self::ZONE_2 => 'Zona 2',
            default => ''
        };
    }
}
