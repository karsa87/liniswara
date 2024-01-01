<?php

declare(strict_types=1);

namespace App\Enums\Order;

use BenSampo\Enum\Enum;

/**
 * @method static static VALIDATION_ADMIN()
 * @method static static PROCESS()
 * @method static static SENT()
 * @method static static DONE()
 */
final class StatusEnum extends Enum
{
    const VALIDATION_ADMIN = 1;

    const PROCESS = 2;

    const SENT = 3;

    const DONE = 4;

    const PACKING = 5;

    const CANCEL = 9;

    const MAP_LABEL = [
        self::VALIDATION_ADMIN => 'Validasi Admin',
        self::PROCESS => 'Proses',
        self::SENT => 'Kirim',
        self::DONE => 'Selesai',
        self::PACKING => 'Packing',
        self::CANCEL => 'Dibatalkan',
    ];

    public function getLabel()
    {
        return match ($this->value) {
            self::VALIDATION_ADMIN => 'Validasi Admin',
            self::PROCESS => 'Proses',
            self::SENT => 'Kirim',
            self::DONE => 'Selesai',
            self::PACKING => 'Packing',
            self::CANCEL => 'Dibatalkan',
            default => ''
        };
    }
}
