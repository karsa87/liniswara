<?php

declare(strict_types=1);

namespace App\Enums\Import;

use BenSampo\Enum\Enum;

/**
 * @method static static WAITING()
 * @method static static PROCESS()
 * @method static static DONE()
 * @method static static FAILED()
 */
final class StatusEnum extends Enum
{
    const WAITING = 0;

    const PROCESS = 1;

    const DONE = 2;

    const FAILED = 3;

    const MAP_LABEL = [
        self::WAITING => 'Menunggu',
        self::PROCESS => 'Proses',
        self::DONE => 'Selesai',
        self::FAILED => 'Gagal',
    ];

    public function getLabel()
    {
        return match ($this->value) {
            self::WAITING => 'Menunggu',
            self::PROCESS => 'Proses',
            self::DONE => 'Selesai',
            self::FAILED => 'Gagal',
            default => ''
        };
    }
}
