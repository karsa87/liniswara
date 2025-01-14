<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static GENERAL()
 * @method static static DISTRIBUTOR_CASH()
 */
final class SourceLogEnum extends Enum
{
    const ORDER = 'orders';

    const ORDER_DETAIL = 'order_details';

    const ORDER_SHIPPING = 'order_shippings';

    const PREORDER = 'preorders';

    const PREORDER_DETAIL = 'preorder_details';

    const PREORDER_SHIPPING = 'preorder_shippings';

    const RESTOCK = 'restocks';

    const RESTOCK_DETAIL = 'restock_details';

    const RETURN_ORDER = 'return_orders';

    const RETURN_ORDER_DETAIL = 'return_order_details';

    const MAP_LABEL = [
        self::ORDER => 'Pesanan',
        self::ORDER_DETAIL => 'Item Pesanan',
        self::ORDER_SHIPPING => 'Shipping Pesanan',
        self::PREORDER => 'Preorder',
        self::PREORDER_DETAIL => 'Item Preorder',
        self::PREORDER_SHIPPING => 'Shipping Preorder',
        self::RESTOCK => 'Re-stock',
        self::RESTOCK_DETAIL => 'Item Re-stock',
        self::RETURN_ORDER => 'Retur Order',
        self::RETURN_ORDER_DETAIL => 'Item Retur Order',
    ];

    public function getLabel()
    {
        return match ($this->value) {
            self::ORDER => 'Pesanan',
            self::ORDER_DETAIL => 'Item Pesanan',
            self::ORDER_SHIPPING => 'Shipping Pesanan',
            self::PREORDER => 'Preorder',
            self::PREORDER_DETAIL => 'Item Preorder',
            self::PREORDER_SHIPPING => 'Shipping Preorder',
            self::RESTOCK => 'Re-stock',
            self::RESTOCK_DETAIL => 'Item Re-stock',
            self::RETURN_ORDER => 'Retur Order',
            self::RETURN_ORDER_DETAIL => 'Item Retur Order',
            default => ''
        };
    }
}
