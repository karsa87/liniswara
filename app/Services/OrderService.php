<?php

namespace App\Services;

use App\Http\Resources\Admin\Order\OrderResource;
use App\Models\Order;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderService
{
    /**
     * Get list order by status order
     *
     * @param  array  $statusOrder status order
     * @param  array  $params param for filter
     * @return array
     * **/
    public function getListByStatus(
        $statusOrder = null,
        $params = []
    ): AnonymousResourceCollection {
        $query = Order::with([
            'customer_address',
            'collector',
            'createdBy',
            'customer.user',
            'branch',
            'shipping',
        ]);

        if ($statusOrder) {
            if (is_array($statusOrder)) {
                $query->whereIn('status', $statusOrder);
            } else {
                $query->where('status', $statusOrder);
            }
        } else {
            $status = $params['search']['status'];
            if ($status) {
                $query->where('status', $status);
            }
        }

        if ($q = ($params['search']['value'] ?? '')) {
            $query->where(function ($q2) use ($q) {
                $q2->whereLike('invoice_number', $q);
            });
        }

        $branchId = $params['search']['branch_id'] ?? '';
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $collectorId = $params['search']['collector_id'] ?? '';
        if ($collectorId) {
            $query->where('collector_id', $collectorId);
        }

        $customerId = $params['search']['customer_id'] ?? '';
        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        $marketingId = $params['search']['marketing_id'] ?? '';
        if ($marketingId) {
            $query->where('marketing', $marketingId);
        }

        $statusPayment = $params['search']['status_payment'] ?? '';
        if ($statusPayment) {
            $query->where('status_payment', $statusPayment);
        }

        $methodPayment = $params['search']['method_payment'] ?? '';
        if ($methodPayment) {
            $query->where('method_payment', $methodPayment);
        }

        $totalAll = (clone $query)->count();
        $orders = $query->offset(($params['start'] ?? 0))
            ->limit($params['length'] ?? 10)
            ->get();

        $a = OrderResource::collection($orders)->additional([
            'recordsTotal' => $totalAll,
            'recordsFiltered' => $orders->count(),
        ]);

        return OrderResource::collection($orders)->additional([
            'recordsTotal' => $totalAll,
            'recordsFiltered' => $orders->count(),
        ]);
    }
}
