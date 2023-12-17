<?php

declare(strict_types=1);

namespace App\Enums\Preorder;

use BenSampo\Enum\Enum;

/**
 * @method static static TEAM_A()
 * @method static static TEAM_B()
 * @method static static RETAIL()
 * @method static static WRITING()
 */
final class MarketingEnum extends Enum
{
    const TEAM_A = 1;

    const TEAM_B = 2;

    const RETAIL = 3;

    const WRITING = 4;

    const MAP_LABEL = [
        self::TEAM_A => 'Tim A',
        self::TEAM_B => 'Tim B',
        self::RETAIL => 'Retail',
        self::WRITING => 'Penulis',
    ];

    public function getLabel()
    {
        return match ($this->value) {
            self::TEAM_A => 'Tim A',
            self::TEAM_B => 'Tim B',
            self::RETAIL => 'Retail',
            self::WRITING => 'Penulis',
            default => ''
        };
    }
}
