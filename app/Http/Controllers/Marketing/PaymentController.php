<?php

namespace App\Http\Controllers\Marketing;

use App\Enums\Preorder\StatusEnum;
use App\Enums\Preorder\StatusPaymentEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Preorder\PreorderResource;
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
        $query = Customer::with([
            'user:id,name,email,phone_number,profile_photo_id',
            'user.profile_photo',
            'address.village',
            'address.district',
            'address.regency',
            'address.province',
        ])
            ->withCount('preorders')
            ->withSum('preorders', 'total_amount')
            ->orderBy('preorders_sum_total_amount', 'DESC');

        $agents = $query->paginate(15)->appends($request->except(['page']));

        return view('marketing.payment.list_agent', [
            'agents' => $agents,
        ]);
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
}
