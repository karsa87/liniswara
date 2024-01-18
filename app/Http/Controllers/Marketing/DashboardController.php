<?php

namespace App\Http\Controllers\Marketing;

use App\Enums\Preorder\MarketingEnum;
use App\Enums\Preorder\StatusPaymentEnum;
use App\Enums\Preorder\ZoneEnum;
use App\Enums\SettingKeyEnum;
use App\Http\Controllers\Controller;
use App\Models\Preorder;
use App\Models\Setting;
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
        $marketingTeam = session(config('session.app.selected_marketing_tim'));
        $settingKey = SettingKeyEnum::MARKETING_TARGET_TIM_A;
        switch ($marketingTeam->value) {
            case MarketingEnum::TEAM_B:
                $settingKey = SettingKeyEnum::MARKETING_TARGET_TIM_B;
                break;

            default:
                // code...
                break;
        }

        $settingTargetTeam = Setting::where('key', $settingKey)->first();
        $summary = $this->preorderService->getSummary([
            'marketing' => $marketingTeam->value,
        ]);

        $rankingRegency = $this->preorderService->rankingByRegency(5, $request->get('regency_month_id'));

        return view('marketing.dashboard.index', [
            'targetTeam' => $settingTargetTeam->value,
            'targetAchieved' => $summary['total'],
            'rankingRegency' => $rankingRegency,
        ]);
    }

    public function switch_marketing(int $marketing)
    {
        try {
            $marketingValue = MarketingEnum::fromValue($marketing);
            \Session::put(config('session.app.selected_marketing_tim'), $marketingValue);
        } catch (\Throwable $th) {
        }

        return redirect()->route('marketing.dashboard');
    }

    public function widget_preorder()
    {
        $marketingTeam = session(config('session.app.selected_marketing_tim'));
        $preorderPaid = $this->preorderService->getSummary([
            'status_payment' => StatusPaymentEnum::PAID,
            'marketing' => $marketingTeam->value,
        ]);
        $preorderNotPaid = $this->preorderService->getSummary([
            'status_payment' => StatusPaymentEnum::NOT_PAID,
            'marketing' => $marketingTeam->value,
        ]);
        $preorderDp = $this->preorderService->getSummary([
            'status_payment' => StatusPaymentEnum::DP,
            'marketing' => $marketingTeam->value,
        ]);

        return response()->json([
            'total' => [
                'paid' => $preorderPaid['total'],
                'not_paid' => $preorderNotPaid['total'],
                'dp' => $preorderDp['total'],
            ],
        ]);
    }

    public function widget_order()
    {
        $marketingTeam = session(config('session.app.selected_marketing_tim'));
        $orderPaid = $this->orderService->getSummary([
            'status_payment' => StatusPaymentEnum::PAID,
            'marketing' => $marketingTeam->value,
        ]);
        $orderNotPaid = $this->orderService->getSummary([
            'status_payment' => StatusPaymentEnum::NOT_PAID,
            'marketing' => $marketingTeam->value,
        ]);
        $orderDp = $this->orderService->getSummary([
            'status_payment' => StatusPaymentEnum::DP,
            'marketing' => $marketingTeam->value,
        ]);

        return response()->json([
            'total' => [
                'paid' => $orderPaid['total'],
                'not_paid' => $orderNotPaid['total'],
                'dp' => $orderDp['total'],
            ],
        ]);
    }

    public function widget_zone()
    {
        $marketingTeam = session(config('session.app.selected_marketing_tim'));

        $zones = [
            'zone_1' => [],
            'zone_2' => [],
        ];
        for ($i = 1; $i <= 12; $i++) {
            $zone1 = Preorder::where('zone', ZoneEnum::ZONE_1)
                ->where('marketing', $marketingTeam->value)
                ->whereMonth('date', $i)
                ->sum('total_amount');
            $zone2 = Preorder::where('zone', ZoneEnum::ZONE_2)
                ->where('marketing', $marketingTeam->value)
                ->whereMonth('date', $i)
                ->sum('total_amount');

            $zones['zone_1'][] = $zone1;
            $zones['zone_2'][] = $zone2;
        }

        return response()->json($zones);
    }
}
