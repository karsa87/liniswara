<?php

namespace App\Http\Controllers\Marketing\Follow;

use App\Enums\Order\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Order\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        private OrderService $orderService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $params = $request->all();
            $query = Order::with([
                'customer_address',
                'collector',
                'createdBy',
                'updatedBy',
                'customer.user',
                'branch',
                'shipping',
            ])->whereIn('status', [
                StatusEnum::PACKING,
                StatusEnum::SENT,
            ])->where(function ($qOrder) {
                $qOrder->has('shipping')
                    ->orWhere('date', '<=', Carbon::now()->subMonth()->format('Y-m-d'));
            });

            if ($request->input('input.marketing_id')) {
                $query->where('marketing', $request->input('input.marketing_id'));
            }

            if ($request->input('search.customer_id')) {
                $query->where('customer_id', $request->input('search.customer_id'));
            }

            if ($request->input('search.status_payment')) {
                $query->where('status_payment', $request->input('search.status_payment'));
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

        return view('marketing.follow.list_transaction');
    }
}
