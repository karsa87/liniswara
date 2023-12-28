<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static TRANSACTION_CREATE()
 * @method static static TRANSACTION_UPDATE()
 * @method static static TRANSACTION_DELETE()
 */
final class LogHistoryEnum extends Enum
{
    const TRANSACTION_CREATE = 1;

    const TRANSACTION_UPDATE = 2;

    const TRANSACTION_DELETE = 3;

    const MAP_LABEL = [
        self::TRANSACTION_CREATE => 'Buat',
        self::TRANSACTION_UPDATE => 'Edit',
        self::TRANSACTION_DELETE => 'Hapus',
    ];
}
