<?php

declare(strict_types=1);

namespace App\Enums\Import;

use BenSampo\Enum\Enum;

/**
 * @method static static VALIDATION_ADMIN()
 * @method static static PROCESS()
 * @method static static SENT()
 * @method static static DONE()
 */
final class StatusEnum extends Enum
{
    const WAITING = 0;

    const PROCESS = 1;

    const DONE = 2;

    const MAP_LABEL = [
        self::WAITING => 'Menunggu',
        self::PROCESS => 'Proses',
        self::DONE => 'Selesai',
    ];

    public function getLabel()
    {
        return match ($this->value) {
            self::WAITING => 'Menunggu',
            self::PROCESS => 'Proses',
            self::DONE => 'Selesai',
            default => ''
        };
    }
}
