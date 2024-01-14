<?php

namespace App\Http\Controllers\Marketing;

use App\Enums\Order\StatusEnum as OrderStatusEnum;
use App\Enums\Preorder\StatusEnum;
use App\Enums\Preorder\StatusPaymentEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Preorder\PreorderResource;
use App\Models\Order;
use App\Models\Preorder;
use App\Services\PreorderService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        private PreorderService $preorderService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $preorderPaid = $this->preorderService->getSummaryByStatusPayment(StatusPaymentEnum::PAID);
        $preorderNotPaid = $this->preorderService->getSummaryByStatusPayment(StatusPaymentEnum::NOT_PAID);
        $preorderDp = $this->preorderService->getSummaryByStatusPayment(StatusPaymentEnum::DP);
        $preorderProcess = $this->preorderService->getSummaryByStatusOrder(StatusEnum::PROCESS);

        $query = Preorder::with([
            'collector',
            'collector.village',
            'collector.district',
            'collector.regency',
            'collector.province',
        ])
            ->withCount([
                'orders' => function ($qOrder) {
                    $qOrder->whereNotIn('status', [
                        OrderStatusEnum::DONE,
                        OrderStatusEnum::CANCEL,
                    ]);
                },
            ])
            ->orderBy('orders_count', 'DESC');

        $preorders = $query->paginate(15)->appends($request->except(['page']));

        return view('marketing.transaction.index', [
            'total' => [
                'paid' => $preorderPaid['total'],
                'not_paid' => $preorderNotPaid['total'],
                'dp' => $preorderDp['total'],
                'process' => $preorderProcess['total'],
            ],
            'count' => [
                'paid' => $preorderPaid['count'],
                'not_paid' => $preorderNotPaid['count'],
                'dp' => $preorderDp['count'],
                'process' => $preorderProcess['count'],
            ],
            'preorders' => $preorders,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function detail(Request $request, $id)
    {
        if ($request->ajax()) {
            $query = Order::wherePreorderId($id);

            if ($q = $request->input('search.value')) {
                $query->where(function ($qPreorder) use ($q) {
                    $qPreorder->whereHas('collector', function ($qUser) use ($q) {
                        $qUser->whereLike('name', $q);
                    });
                });
            }

            if ($month = $request->input('search.month')) {
                $query->whereMonth('date', $month);
            }

            if (is_numeric($request->input('order.0.column'))) {
                $column = $request->input('order.0.column');
                $columnData = $request->input("columns.$column.data");
                $sorting = $request->input('order.0.dir');

                if ($sorting == 'desc') {
                    $query->orderBy($columnData, 'DESC');
                } else {
                    $query->orderBy($columnData, 'ASC');
                }
            }

            $total = (clone $query)->count();
            $preorders = $query->offset($request->get('start', 0))
                ->limit($request->get('length', 10))
                ->get();

            return PreorderResource::collection($preorders)->additional([
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
            ]);
        }

        $preorder = Preorder::with([
            'collector',
            'collector.village',
            'collector.district',
            'collector.regency',
            'collector.province',
        ])
            ->withCount([
                'orders as count_order_not_done' => function ($qOrder) {
                    $qOrder->whereNotIn('status', [
                        OrderStatusEnum::DONE,
                        OrderStatusEnum::CANCEL,
                    ]);
                },
                'orders as count_order_done' => function ($qOrder) {
                    $qOrder->whereIn('status', [
                        OrderStatusEnum::DONE,
                    ]);
                },
                'orders as count_order_paid' => function ($qOrder) {
                    $qOrder->whereIn('status_payment', [
                        StatusPaymentEnum::PAID,
                    ]);
                },
                'orders as count_order_not_paid' => function ($qOrder) {
                    $qOrder->whereIn('status_payment', [
                        StatusPaymentEnum::NOT_PAID,
                    ]);
                },
                'orders as count_order_dp' => function ($qOrder) {
                    $qOrder->whereIn('status_payment', [
                        StatusPaymentEnum::DP,
                    ]);
                },
            ])
            ->withSum([
                'orders as total_order_not_done' => function ($qOrder) {
                    $qOrder->whereNotIn('status', [
                        OrderStatusEnum::DONE,
                        OrderStatusEnum::CANCEL,
                    ]);
                },
            ], 'total_amount')
            ->withSum([
                'orders as total_order_paid' => function ($qOrder) {
                    $qOrder->whereIn('status_payment', [
                        StatusPaymentEnum::PAID,
                    ]);
                },
            ], 'total_amount')
            ->withSum([
                'orders as total_order_not_paid' => function ($qOrder) {
                    $qOrder->whereIn('status_payment', [
                        StatusPaymentEnum::NOT_PAID,
                    ]);
                },
            ], 'total_amount')
            ->withSum([
                'orders as total_order_dp' => function ($qOrder) {
                    $qOrder->whereIn('status_payment', [
                        StatusPaymentEnum::DP,
                    ]);
                },
            ], 'total_amount')
            ->where('id', $id)
            ->first();

        return view('marketing.transaction.detail', [
            'preorder' => $preorder,
        ]);
    }
}
