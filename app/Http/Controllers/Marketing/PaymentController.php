<?php

namespace App\Http\Controllers\Marketing;

use App\Enums\Preorder\StatusEnum;
use App\Enums\Preorder\StatusPaymentEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Customer\MarketingCustomerListResource;
use App\Http\Resources\Admin\Preorder\PreorderResource;
use App\Http\Resources\Marketing\RegionListResource;
use App\Models\Area;
use App\Models\Customer;
use App\Models\Preorder;
use App\Services\CustomerService;
use App\Services\PreorderService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private PreorderService $preorderService,
        private CustomerService $customerService,
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
        // if ($request->ajax()) {
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

        return MarketingCustomerListResource::collection($customers)->additional([
            'recordsTotal' => $totalAll,
            'recordsFiltered' => $totalAll,
        ]);
        // }

        // return view('marketing.payment.list_agent');
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

        $preorderPaid = $this->preorderService->getSummaryByStatusPayment(StatusPaymentEnum::PAID, $id);
        $preorderNotPaid = $this->preorderService->getSummaryByStatusPayment(StatusPaymentEnum::NOT_PAID, $id);
        $preorderDp = $this->preorderService->getSummaryByStatusPayment(StatusPaymentEnum::DP, $id);
        $preorderProcess = $this->preorderService->getSummaryByStatusOrder(StatusEnum::PROCESS, $id);

        return view('marketing.payment.detail_agent', [
            'agent' => $customer,
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
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function region(Request $request)
    {
        if ($request->ajax()) {
            $query = Area::with([
                'preorders',
            ])
                ->where('target', '>', 0)
                ->withCount('preorders')
                ->withSum('preorders as total_achieved', 'total_amount');

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
}
