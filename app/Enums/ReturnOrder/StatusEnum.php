<?php

declare(strict_types=1);

namespace App\Enums\ReturnOrder;

use BenSampo\Enum\Enum;

/**
 * @method static static VALIDATION_ADMIN()
 * @method static static PROCESS()
 * @method static static SENT()
 * @method static static DONE()
 */
final class StatusEnum extends Enum
{
    const NEW = 1;

    const CONFIRMATION = 2;

    const CANCELLED = 3;

    public function getLabel()
    {
        return match ($this->value) {
            self::NEW => 'Menunggu Konfirmasi',
            self::CONFIRMATION => 'Konfirmasi',
            self::CANCELLED => 'Dibatalkan',
            default => ''
        };
    }

    public static function getLabels()
    {
        return [
            self::NEW => 'Menunggu Konfirmasi',
            self::CONFIRMATION => 'Konfirmasi',
            self::CANCELLED => 'Dibatalkan',
        ];
    }
}
