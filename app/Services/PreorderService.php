<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Preorder;
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
        return $this->getSummary([
            'status_payment' => $statusPayment,
            'customer_id' => $customerId,
            'collector_id' => $collectorId,
        ]);
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
        return $this->getSummary([
            'status_order' => $statusOrder,
            'customer_id' => $customerId,
            'collector_id' => $collectorId,
        ]);
    }

    /**
     * Get info total preorder by param where clause
     *
     * @param  array  $params parameter where clause
     *
     * **/
    public function getSummary($params): array
    {
        $query = Preorder::selectRaw('SUM(total_amount) as total, count(id) as count')
            ->whereHas('details', function ($qDetail) {
                $qDetail->whereRaw('qty != qty_order');
            })
            ->where('is_exclude_target', false);

        if (isset($params['marketing']) && $params['marketing']) {
            $query->where('marketing', $params['marketing']);
        }

        if (isset($params['status_order']) && $params['status_order']) {
            if (is_array($params['status_order'])) {
                $query->whereIn('status', $params['status_order']);
            } else {
                $query->where('status', $params['status_order']);
            }
        }

        if (isset($params['status_payment']) && $params['status_payment']) {
            $statusPayment = $params['status_payment'];
            if (is_array($statusPayment)) {
                $query->whereIn('status_payment', $statusPayment);
            } else {
                $query->where('status_payment', $statusPayment);
            }
        }

        if (isset($params['customer_id']) && $params['customer_id']) {
            $query->where('customer_id', $params['customer_id']);
        }

        if (isset($params['collector_id']) && $params['collector_id']) {
            $query->where('collector_id', $params['collector_id']);
        }

        $summary = $query->get()->first();

        return [
            'total' => optional($summary)->total ?? 0,
            'count' => optional($summary)->count ?? 0,
        ];
    }

    /**
     * Get info total preorder by param where clause
     *
     * @param  array  $params parameter where clause
     *
     * **/
    public function getSummaryAll($params): array
    {
        $query = Preorder::selectRaw('SUM(total_amount) as total, count(id) as count')
            ->where('is_exclude_target', false);

        if (isset($params['marketing']) && $params['marketing']) {
            $query->where('marketing', $params['marketing']);
        }

        if (isset($params['status_order']) && $params['status_order']) {
            if (is_array($params['status_order'])) {
                $query->whereIn('status', $params['status_order']);
            } else {
                $query->where('status', $params['status_order']);
            }
        }

        if (isset($params['status_payment']) && $params['status_payment']) {
            $statusPayment = $params['status_payment'];
            if (is_array($statusPayment)) {
                $query->whereIn('status_payment', $statusPayment);
            } else {
                $query->where('status_payment', $statusPayment);
            }
        }

        if (isset($params['customer_id']) && $params['customer_id']) {
            $query->where('customer_id', $params['customer_id']);
        }

        if (isset($params['collector_id']) && $params['collector_id']) {
            $query->where('collector_id', $params['collector_id']);
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
        $query = Preorder::selectRaw('SUM(total_amount) as preorders_total, count(id) as preorders_count, area_id')
            ->with('area')
            ->groupBy('area_id')
            ->orderBy('preorders_total', 'desc')
            ->limit(10);

        if ($month) {
            $query->whereMonth('date', $month);
        }

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Get top ranking of agen by total amount transaction
     *
     * @param  sstring  $team marketing team
     * @param  int  $month month preorder
     * @param  int  $limit limit to get ranking
     *
     * **/
    public function rankingByMarketingTeam(
        $marketingTeam,
        $month,
        $limit = 5,
    ): Collection {
        $query = Preorder::with('customer.user')->orderBy('total_amount', 'desc')
            ->where('marketing', $marketingTeam)
            ->limit(10);

        if ($month) {
            $query->whereMonth('date', $month);
        }

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Get top ranking of agen by total amount transaction
     *
     * @param  int  $limit limit to get ranking
     * @param  int  $month month preorder
     *
     * **/
    public function rankingByRegencySpecificAgent(
        $agentId
    ): Collection {
        $agent = Customer::with([
            'areas' => function ($qArea) {
                $qArea->withSum('preorders as preorders_total', 'total_amount')
                    ->withCount('preorders');
            },
        ])->whereId($agentId)->first();

        return $agent->areas;
    }
}
