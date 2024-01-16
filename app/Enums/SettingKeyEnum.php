<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static GENERAL()
 * @method static static DISTRIBUTOR_CASH()
 */
final class SettingKeyEnum extends Enum
{
    const EXPEDITION_API_KEY = 'EXPEDITION_API_KEY';

    const WHATSAPP_API_KEY = 'WHATSAPP_API_KEY';

    const MARKETING_TARGET_TIM_A = 'MARKETING_TARGET_TIM_A';

    const MARKETING_TARGET_TIM_B = 'MARKETING_TARGET_TIM_B';
}
