<?php

namespace App\Services;

use App\Models\Preorder;

class PreorderService
{
    /**
     * Get info total preorder by status payment
     *
     * @param  array  $statusPayment status payment
     * **/
    public function getSummaryByStatusPayment(
        $statusPayment = null
    ): array {
        $query = Preorder::selectRaw('SUM(total_amount) as total, count(id) as count');

        if ($statusPayment) {
            if (is_array($statusPayment)) {
                $query->whereIn('status_payment', $statusPayment);
            } else {
                $query->where('status_payment', $statusPayment);
            }
        }

        $summary = $query->get()->first();

        return [
            'total' => optional($summary)->total ?? 0,
            'count' => optional($summary)->count ?? 0,
        ];
    }

    /**
     * Get info total preorder by status order
     *
     * @param  array  $statusPayment status payment
     * **/
    public function getSummaryByStatusOrder(
        $statusOrder = null
    ): array {
        $query = Preorder::selectRaw('SUM(total_amount) as total, count(id) as count');

        if ($statusOrder) {
            if (is_array($statusOrder)) {
                $query->whereIn('status', $statusOrder);
            } else {
                $query->where('status', $statusOrder);
            }
        }

        $summary = $query->get()->first();

        return [
            'total' => optional($summary)->total ?? 0,
            'count' => optional($summary)->count ?? 0,
        ];
    }
}
