<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Order\StatusEnum;
use App\Enums\Preorder\MarketingEnum;
use App\Enums\Preorder\StatusPaymentEnum;
use App\Enums\Preorder\ZoneEnum;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Preorder;
use App\Models\PreorderDetail;
use App\Models\Product;
use App\Models\School;
use App\Services\OrderService;
use App\Services\PreorderService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private PreorderService $preorderService,
        private OrderService $orderService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $statusOrder = [
            StatusEnum::SENT,
            StatusEnum::PACKING,
        ];

        $orderSents = Order::with([
            'customer.user',
            'shipping.expedition.logo',
        ])
            ->where(function ($qOrder) use ($statusOrder) {
                $qOrder->whereIn('status', $statusOrder)
                    ->orWhere(function ($qOrder2) {
                        $qOrder2->where('status', StatusEnum::DONE)->where('status_payment', '!=', StatusPaymentEnum::PAID);
                    });
            })
            ->limit(10)
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.dashboard.index', [
            'orderSents' => $orderSents,
        ]);
    }

    public function widget_preorder()
    {
        $preorderPaid = $this->preorderService->getSummary([
            'status_payment' => StatusPaymentEnum::PAID,
        ]);
        $preorderNotPaid = $this->preorderService->getSummary([
            'status_payment' => StatusPaymentEnum::NOT_PAID,
        ]);
        $preorderDp = $this->preorderService->getSummary([
            'status_payment' => StatusPaymentEnum::DP,
        ]);

        return response()->json([
            'total' => [
                'paid' => $preorderPaid['total'],
                'not_paid' => $preorderNotPaid['total'],
                'dp' => $preorderDp['total'],
            ],
            'count' => [
                'paid' => $preorderPaid['count'],
                'not_paid' => $preorderNotPaid['count'],
                'dp' => $preorderDp['count'],
            ],
        ]);
    }

    public function widget_order()
    {
        $orderPaid = $this->orderService->getSummary([
            'status_payment' => StatusPaymentEnum::PAID,
        ]);
        $orderNotPaid = $this->orderService->getSummary([
            'status_payment' => StatusPaymentEnum::NOT_PAID,
        ]);
        $orderDp = $this->orderService->getSummary([
            'status_payment' => StatusPaymentEnum::DP,
        ]);

        return response()->json([
            'total' => [
                'paid' => $orderPaid['total'],
                'not_paid' => $orderNotPaid['total'],
                'dp' => $orderDp['total'],
            ],
            'count' => [
                'paid' => $orderPaid['count'],
                'not_paid' => $orderNotPaid['count'],
                'dp' => $orderDp['count'],
            ],
        ]);
    }

    public function widget_zone()
    {
        $zones = [
            'zone_1' => [],
            'zone_2' => [],
            'zone_3' => [],
            'zone_4' => [],
            'zone_5' => [],
            'zone_6' => [],
        ];
        for ($i = 1; $i <= 12; $i++) {
            $zone1 = Preorder::where('zone', ZoneEnum::ZONE_1)
                ->whereMonth('date', $i)
                ->where('is_exclude_target', false)
                ->sum('total_amount');
            $zone2 = Preorder::where('zone', ZoneEnum::ZONE_2)
                ->whereMonth('date', $i)
                ->where('is_exclude_target', false)
                ->sum('total_amount');
            $zone3 = Preorder::where('zone', ZoneEnum::ZONE_3)
                ->whereMonth('date', $i)
                ->where('is_exclude_target', false)
                ->sum('total_amount');
            $zone4 = Preorder::where('zone', ZoneEnum::ZONE_4)
                ->whereMonth('date', $i)
                ->where('is_exclude_target', false)
                ->sum('total_amount');
            $zone5 = Preorder::where('zone', ZoneEnum::ZONE_5)
                ->whereMonth('date', $i)
                ->where('is_exclude_target', false)
                ->sum('total_amount');
            $zone6 = Preorder::where('zone', ZoneEnum::ZONE_6)
                ->whereMonth('date', $i)
                ->where('is_exclude_target', false)
                ->sum('total_amount');

            $zones['zone_1'][] = $zone1;
            $zones['zone_2'][] = $zone2;
            $zones['zone_3'][] = $zone3;
            $zones['zone_4'][] = $zone4;
            $zones['zone_5'][] = $zone5;
            $zones['zone_6'][] = $zone6;
        }

        return response()->json($zones);
    }

    public function widget_sales()
    {
        $salesTeam = [
            'team_a' => [],
            'team_b' => [],
            'retail' => [],
            'writing' => [],
        ];
        for ($i = 1; $i <= 12; $i++) {
            $sales = Preorder::selectRaw('sum(total_amount) as total_amount, marketing')
                ->where('zone', ZoneEnum::ZONE_1)
                ->where('is_exclude_target', false)
                ->whereMonth('date', $i)
                ->groupBy('marketing')
                ->get();

            $sales = $sales->pluck('total_amount', 'marketing');
            if ($sales->has(MarketingEnum::TEAM_A)) {
                $salesTeam['team_a'][] = $sales[MarketingEnum::TEAM_A];
            } else {
                $salesTeam['team_a'][] = 0;
            }
            if ($sales->has(MarketingEnum::TEAM_B)) {
                $salesTeam['team_b'][] = $sales[MarketingEnum::TEAM_B];
            } else {
                $salesTeam['team_b'][] = 0;
            }
            if ($sales->has(MarketingEnum::RETAIL)) {
                $salesTeam['retail'][] = $sales[MarketingEnum::RETAIL];
            } else {
                $salesTeam['retail'][] = 0;
            }
            if ($sales->has(MarketingEnum::WRITING)) {
                $salesTeam['writing'][] = $sales[MarketingEnum::WRITING];
            } else {
                $salesTeam['writing'][] = 0;
            }
        }

        return response()->json($salesTeam);
    }

    public function widget_product()
    {
        $productReadyCount = Product::where('stock', '>', 30)->count();
        $productEmptyCount = Product::where('stock', '<=', 0)->count();
        $productLimitedCount = Product::where('stock', '>', 0)->where('stock', '<=', 30)->count();

        $productPrintedCount = Product::addSelect([
            // Key is the alias, value is the sub-select
            'total_stock_need' => PreorderDetail::query()
                // You can use eloquent methods here
                ->selectRaw('((sum(IFNULL(qty, 0)) - sum(IFNULL(qty_order, 0))) - IFNULL(products.stock, 0))')
                ->whereColumn('product_id', 'products.id')
                ->whereRaw('qty != qty_order'),
        ])
            ->havingRaw('total_stock_need > 0')
            ->has('preorder_details')
            ->count();

        return response()->json([
            'count' => [
                'ready' => $productReadyCount,
                'empty' => $productEmptyCount,
                'limited' => $productLimitedCount,
                'printed' => $productPrintedCount,
            ],
            'total_count' => Product::count(),
        ]);
    }

    public function widget_school(Request $request)
    {
        $schools = School::all();
        $results = [];
        foreach ($schools as $school) {
            $results[$school->name] = [];
        }

        for ($i = 1; $i <= 12; $i++) {
            foreach ($schools as $school) {
                $results[$school->name][] = Preorder::where('school_id', $school->id)
                    ->whereMonth('date', $i)
                    ->where('is_exclude_target', false)
                    ->sum('total_amount');
            }
        }

        return response()->json($results);
    }

    public function maintanance()
    {
        return view('admin.maintanance');
    }
}
