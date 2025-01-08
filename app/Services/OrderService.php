<?php

namespace App\Services;

use App\Enums\Order\StatusEnum;
use App\Enums\Preorder\StatusPaymentEnum;
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
            'updatedBy',
            'customer.user',
            'branch',
            'shipping',
            'school',
        ]);

        if ($statusOrder) {
            if (
                in_array(StatusEnum::PACKING, $statusOrder)
                && in_array(StatusEnum::SENT, $statusOrder)
            ) {
                $query->where(function ($qOrder) use ($statusOrder) {
                    $qOrder->whereIn('status', $statusOrder)
                        ->orWhere(function ($qOrder2) {
                            $qOrder2->where('status', StatusEnum::DONE)->where('status_payment', '!=', StatusPaymentEnum::PAID);
                        });
                });
            } else {
                if (is_array($statusOrder)) {
                    $query->whereIn('status', $statusOrder);
                } else {
                    $query->where('status', $statusOrder);
                }
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

        $filterColumn = $params['order'][0]['column'] ?? '';
        if (is_numeric($filterColumn)) {
            $columnData = $params['columns'][$filterColumn]['data'];
            $sorting = $params['order'][0]['dir'];

            if ($sorting == 'desc') {
                $query->orderBy($columnData, 'DESC');
            } else {
                $query->orderBy($columnData, 'ASC');
            }
        } else {
            $query->orderBy('created_at', 'DESC');
        }

        $totalAll = (clone $query)->count();
        $orders = $query->offset(($params['start'] ?? 0))
            ->limit($params['length'] ?? 10)
            ->get();

        return OrderResource::collection($orders)->additional([
            'recordsTotal' => $totalAll,
            'recordsFiltered' => $totalAll,
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
        $query = Order::where('is_exclude_target', false);

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

        $orders = $query->get();

        return [
            'total' => optional($orders)->sum('total_amount') ?? 0,
            'count' => optional($orders)->count() ?? 0,
        ];
    }
}
