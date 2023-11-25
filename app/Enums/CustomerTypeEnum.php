<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static GENERAL()
 * @method static static DISTRIBUTOR_CASH()
 */
final class CustomerTypeEnum extends Enum
{
    const GENERAL = 1;

    const DISTRIBUTOR_CASH = 2;

    public function getLabel()
    {
        return match ($this->value) {
            self::GENERAL => 'Umum',
            self::DISTRIBUTOR_CASH => 'Distributor Cash',
            default => ''
        };
    }
}
