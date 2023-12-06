<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static GENERAL()
 * @method static static DISTRIBUTOR_CASH()
 */
final class ProductDiscountTypeEnum extends Enum
{
    const DISCOUNT_NO = 1;

    const DISCOUNT_PERCENTAGE = 2;

    const DISCOUNT_PRICE = 3;
}
