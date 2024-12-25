<?php

namespace App\Http\Controllers\Marketing;

use App\Enums\Preorder\StatusEnum;
use App\Enums\Preorder\StatusPaymentEnum;
use App\Exports\Marketing\AgentOrderExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Customer\MarketingCustomerListResource;
use App\Http\Resources\Admin\Preorder\PreorderResource;
use App\Http\Resources\Marketing\RegionListResource;
use App\Models\Area;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Preorder;
use App\Services\CustomerService;
use App\Services\OrderService;
use App\Services\PreorderService;
use App\Services\SchoolService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PaymentController extends Controller
{
    public function __construct(
        private PreorderService $preorderService,
        private CustomerService $customerService,
        private SchoolService $schoolService,
        private OrderService $orderService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function transaction(Request $request)
    {
        $preorderPaid = $this->preorderService->getSummaryByStatusPayment(StatusPaymentEnum::PAID);
        $preorderNotPaid = $this->preorderService->getSummaryByStatusPayment(StatusPaymentEnum::NOT_PAID);
        $preorderDp = $this->preorderService->getSummaryByStatusPayment(StatusPaymentEnum::DP);
        $preorderProcess = $this->preorderService->getSummaryByStatusOrder(StatusEnum::PROCESS);
        $rankingRegency = $this->preorderService->rankingByRegency(10, $request->get('regency_month_id'));
        $rankingAgents = $this->customerService->rankingAgents(10);

        return view('marketing.payment.transaction', [
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
            'rankingAgents' => $rankingAgents,
            'rankingRegency' => $rankingRegency,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function agent(Request $request)
    {
        if ($request->ajax()) {
            $marketingTeam = session(config('session.app.selected_marketing_tim'));
            $query = Customer::with([
                'user:id,name,email,phone_number,profile_photo_id',
                'user.profile_photo',
                'address.village',
                'address.district',
                'address.regency',
                'address.province',
            ])
                ->where('target', '>', 0)
                ->withCount('preorders')
                ->withSum('preorders as total_achieved', 'total_amount');

            if ($marketingTeam && $marketingTeam->value) {
                $query->where('marketing', $marketingTeam->value);
            }

            if ($q = $request->input('search.value')) {
                $query->whereHas('user', function ($qUser) use ($q) {
                    $qUser->where(function ($qUser1) use ($q) {
                        $qUser1->whereLike('name', $q)
                            ->orWhereLike('company', $q)
                            ->orWhereLike('phone_number', $q)
                            ->orWhereLike('email', $q);
                    });
                });
            }

            if (is_numeric($request->input('order.0.column'))) {
                $column = $request->input('order.0.column');

                $columnData = match ($column) {
                    '3' => 'total_achieved',
                    '4' => '(total_achieved / target)',
                    default => $request->input("columns.$column.data")
                };

                $sorting = $request->input('order.0.dir');

                if ($sorting == 'desc') {
                    $query->orderBy(DB::raw($columnData), 'DESC');
                } else {
                    $query->orderBy(DB::raw($columnData), 'ASC');
                }
            }

            $totalAll = (clone $query)->count();

            $customers = $query->offset($request->get('start', 0))
                ->limit($request->get('length', 10))
                ->get();

            return MarketingCustomerListResource::collection($customers)->additional([
                'recordsTotal' => $totalAll,
                'recordsFiltered' => $totalAll,
            ]);
        }

        return view('marketing.payment.list_agent');
    }

    /**
     * Display a listing of the resource.
     */
    public function detail_agent(Request $request, $id)
    {
        if ($request->ajax()) {
            $query = Preorder::whereCustomerId($id);

            if ($q = $request->input('search.value')) {
                $query->where(function ($qPreorder) use ($q) {
                    $qPreorder->whereHas('customer.user', function ($qUser) use ($q) {
                        $qUser->whereLike('name', $q);
                    });
                });
            }

            if ($month = $request->input('search.month')) {
                $query->whereMonth('date', $month);
            }

            if ($areaId = $request->input('search.area_id')) {
                $query->where('area_id', $areaId);
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

        $customer = Customer::with([
            'user:id,name,email,phone_number,profile_photo_id',
            'user.profile_photo',
            'address.village',
            'address.district',
            'address.regency',
            'address.province',
        ])
            ->withCount('preorders')
            ->withSum('preorders as total_preorder', 'total_amount')
            ->withSum('paid_preorders as total_paid_preorder', 'total_amount')
            ->where('id', $id)
            ->first();

        $preorderAll = $this->preorderService->getSummary([
            'customer_id' => $id,
        ]);
        $preorderPaid = $this->preorderService->getSummaryByStatusPayment(StatusPaymentEnum::PAID, $id);
        $preorderNotPaid = $this->preorderService->getSummaryByStatusPayment(StatusPaymentEnum::NOT_PAID, $id);
        $preorderDp = $this->preorderService->getSummaryByStatusPayment(StatusPaymentEnum::DP, $id);

        $orderPaid = $this->orderService->getSummary([
            'status_payment' => StatusPaymentEnum::PAID,
            'customer_id' => $id,
        ]);
        $preorderProcess = $this->preorderService->getSummaryByStatusOrder(StatusEnum::PROCESS, $id);
        $rankingRegency = $this->preorderService->rankingByRegencySpecificAgent($id);

        return view('marketing.payment.detail_agent', [
            'agent' => $customer,
            'rankingRegency' => $rankingRegency,
            'total' => [
                'preorder_all' => $preorderAll['total'],
                'paid' => $orderPaid['total'],
                'not_paid' => $preorderNotPaid['total'],
                'dp' => $preorderDp['total'],
                'process' => $preorderProcess['total'],
            ],
            'count' => [
                'preorder_all' => $preorderAll['count'],
                'paid' => $orderPaid['count'],
                'not_paid' => $preorderNotPaid['count'],
                'dp' => $preorderDp['count'],
                'process' => $preorderProcess['count'],
            ],
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function transaction_order_agent($id)
    {
        $orders = Order::with('customer_address')->where('preorder_id', $id)->orderBy('id', 'desc')->get();

        $listOrders = collect();
        foreach ($orders as $order) {
            $listOrders->push([
                'id' => $order->id,
                'date' => $order->date,
                'receiver_name' => optional($order->customer_address)->name,
                'invoice_number' => $order->invoice_number,
                'status' => $order->status,
                'status_payment' => $order->status_payment,
                'total_amount' => $order->total_amount,
            ]);
        }

        return response()->json([
            'orders' => $listOrders,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function download_transaction_order_agent($id)
    {
        $preorders = Preorder::with([
            'orders' => function ($qOrder) {
                return $qOrder->with('customer_address')->orderBy('id', 'desc');
            },
        ])
            ->where('customer_id', $id)
            ->get();

        $listOrders = collect();
        foreach ($preorders as $preorder) {
            $listOrders->push([
                'preorder_id' => $preorder->id,
                'preorder_date' => $preorder->date,
                'preorder_receiver_name' => optional($preorder->customer_address)->name,
                'preorder_invoice_number' => $preorder->invoice_number,
                'preorder_total_amount' => $preorder->total_amount,
            ]);
            if ($preorder->orders->count() > 0) {
                foreach ($preorder->orders as $order) {
                    $listOrders->push([
                        'id' => $order->id,
                        'date' => $order->date,
                        'receiver_name' => optional($order->customer_address)->name,
                        'invoice_number' => $order->invoice_number,
                        'status' => $order->status,
                        'status_payment' => $order->status_payment,
                        'total_amount' => $order->total_amount,

                        'preorder_id' => $preorder->id,
                        'preorder_date' => $preorder->date,
                        'preorder_receiver_name' => optional($preorder->customer_address)->name,
                        'preorder_invoice_number' => $preorder->invoice_number,
                        'preorder_total_amount' => $preorder->total_amount,
                    ]);
                }
            }

        }

        return Excel::download(new AgentOrderExport($listOrders), 'Agent Detail Order.xlsx');
    }

    /**
     * Display a listing of the resource.
     */
    public function transaction_rank_regency(Request $request, $id)
    {
        $marketingTeam = session(config('session.app.selected_marketing_tim'));
        $rankingRegency = $this->preorderService->rankingByRegencySpecificAgent($id, optional($marketingTeam)->value);

        return response()->json($rankingRegency);
    }

    /**
     * Display a listing of the resource.
     */
    public function transaction_rank_school_agent(Request $request, $id)
    {
        $marketingTeam = session(config('session.app.selected_marketing_tim'));
        $rankingSchool = $this->schoolService->rankingByCustomer($id, optional($marketingTeam)->value);

        return response()->json($rankingSchool);
    }

    public function transaction_per_month_agent($id)
    {
        $result = [
            'transactions' => [],
        ];
        for ($i = 1; $i <= 12; $i++) {
            $transaction = Preorder::where('is_exclude_target', false)
                ->whereMonth('date', $i)
                ->where('customer_id', $id);

            $result['transactions'][] = $transaction->sum('total_amount');
        }

        return response()->json($result);
    }

    /**
     * Display a listing of the resource.
     */
    public function region(Request $request)
    {
        if ($request->ajax()) {
            $marketingTeam = session(config('session.app.selected_marketing_tim'));

            $query = Area::with([
                'preorders',
            ])
                ->where('target', '>', 0)
                ->withCount([
                    'preorders' => function ($qPreorder) use ($marketingTeam) {
                        if (optional($marketingTeam)->value) {
                            $qPreorder->where('marketing', $marketingTeam->value);
                        }
                    },
                ])
                ->withSum([
                    'preorders as total_achieved' => function ($qPreorder) use ($marketingTeam) {
                        if (optional($marketingTeam)->value) {
                            $qPreorder->where('marketing', $marketingTeam->value);
                        }
                    },
                ], 'total_amount');

            if ($q = $request->input('search.value')) {
                $query->whereLike('name', $q);
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

            $totalAll = (clone $query)->count();

            $customers = $query->offset($request->get('start', 0))
                ->limit($request->get('length', 10))
                ->get();

            return RegionListResource::collection($customers)->additional([
                'recordsTotal' => $totalAll,
                'recordsFiltered' => $totalAll,
            ]);
        }

        return view('marketing.payment.list_region');
    }

    /**
     * Display a listing of the resource.
     */
    public function transaction_rank_agent(Request $request, $id)
    {
        $marketingTeam = session(config('session.app.selected_marketing_tim'));
        $rankingAgents = $this->customerService->rankingSpecificAgent($id, $marketingTeam);

        return response()->json($rankingAgents);
    }

    /**
     * Display a listing of the resource.
     */
    public function transaction_rank_school(Request $request, $id)
    {
        $marketingTeam = session(config('session.app.selected_marketing_tim'));
        $rankingSchoolss = $this->schoolService->rankingByArea($id, $marketingTeam);

        return response()->json($rankingSchoolss);
    }
}
