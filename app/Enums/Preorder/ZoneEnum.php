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

    const ZONE_3 = 3;

    const ZONE_4 = 4;

    const ZONE_5 = 5;

    const ZONE_6 = 6;

    const MAP_LABEL = [
        self::ZONE_1 => 'Zona 1',
        self::ZONE_2 => 'Zona 2',
        self::ZONE_3 => 'Zona 3',
        self::ZONE_4 => 'Zona 4',
        self::ZONE_5 => 'Zona 5',
        self::ZONE_6 => 'Zona 6',
    ];

    public function getLabel()
    {
        return match ($this->value) {
            self::ZONE_1 => 'Zona 1',
            self::ZONE_2 => 'Zona 2',
            self::ZONE_3 => 'Zona 3',
            self::ZONE_4 => 'Zona 4',
            self::ZONE_5 => 'Zona 5',
            self::ZONE_6 => 'Zona 6',
            default => ''
        };
    }
}
