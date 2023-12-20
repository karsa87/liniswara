<?php

declare(strict_types=1);

namespace App\Enums\Preorder;

use BenSampo\Enum\Enum;

/**
 * @method static static CASH()
 * @method static static DEBIT()
 * @method static static FREELANCE()
 */
final class MethodPaymentEnum extends Enum
{
    const CASH = 1;

    const DEBIT = 2;

    const FREELANCE = 3;

    const MAP_LABEL = [
        self::CASH => 'Cash',
        // self::DEBIT => 'Debit',
        self::FREELANCE => 'Freelance',
    ];

    public function getLabel()
    {
        return match ($this->value) {
            self::CASH => 'Cash',
            self::DEBIT => 'Debit',
            self::FREELANCE => 'Freelance',
            default => ''
        };
    }
}
