<?php

namespace App\Services;

use App\Models\Preorder;
use App\Models\Regency;
use Illuminate\Database\Eloquent\Collection;

class PreorderService
{
    /**
     * Get info total preorder by status payment
     *
     * @param  string  $statusPayment status payment
     * @param  int  $customerId Customer unique ID
     * @param  int  $collectorId Customer unique ID
     *
     * **/
    public function getSummaryByStatusPayment(
        $statusPayment = null,
        $customerId = null,
        $collectorId = null,
    ): array {
        $query = Preorder::selectRaw('SUM(total_amount) as total, count(id) as count');

        if ($statusPayment) {
            if (is_array($statusPayment)) {
                $query->whereIn('status_payment', $statusPayment);
            } else {
                $query->where('status_payment', $statusPayment);
            }
        }

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        if ($collectorId) {
            $query->where('collector_id', $customerId);
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
     * @param  string  $statusPayment status payment
     * @param  int  $customerId Customer unique ID
     * @param  int  $collectorId Customer unique ID
     *
     * **/
    public function getSummaryByStatusOrder(
        $statusOrder = null,
        $customerId = null,
        $collectorId = null,
    ): array {
        $query = Preorder::selectRaw('SUM(total_amount) as total, count(id) as count');

        if ($statusOrder) {
            if (is_array($statusOrder)) {
                $query->whereIn('status', $statusOrder);
            } else {
                $query->where('status', $statusOrder);
            }
        }

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        if ($collectorId) {
            $query->where('collector_id', $customerId);
        }

        $summary = $query->get()->first();

        return [
            'total' => optional($summary)->total ?? 0,
            'count' => optional($summary)->count ?? 0,
        ];
    }

    /**
     * Get top ranking of agen by total amount transaction
     *
     * @param  int  $limit limit to get ranking
     * @param  int  $month month preorder
     *
     * **/
    public function rankingByRegency(
        $limit = null,
        $month = null
    ): Collection {
        $query = Regency::withCount([
            'preorders' => function ($qPreorder) use ($month) {
                if ($month) {
                    $qPreorder->whereMonth('date', $month);
                }
            },
        ])
            ->withSum([
                'preorders' => function ($qPreorder) use ($month) {
                    if ($month) {
                        $qPreorder->whereMonth('date', $month);
                    }
                },
            ], 'total_amount')
            ->havingRaw('preorders_sum_total_amount > 0')
            ->orderBy('preorders_sum_total_amount', 'DESC');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }
}
