<?php

namespace App\Http\Controllers\Marketing;

use App\Enums\Preorder\MarketingEnum;
use App\Enums\Preorder\StatusPaymentEnum;
use App\Enums\Preorder\ZoneEnum;
use App\Enums\SettingKeyEnum;
use App\Http\Controllers\Controller;
use App\Models\Preorder;
use App\Models\Setting;
use App\Services\CustomerService;
use App\Services\OrderService;
use App\Services\PreorderService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private PreorderService $preorderService,
        private OrderService $orderService,
        private CustomerService $customerService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $marketingTeam = session(config('session.app.selected_marketing_tim'));
        $settingKey = [];
        switch (optional($marketingTeam)->value) {
            case MarketingEnum::TEAM_B:
                $settingKey = [SettingKeyEnum::MARKETING_TARGET_TIM_B];
                break;

            case MarketingEnum::TEAM_A:
                $settingKey = [SettingKeyEnum::MARKETING_TARGET_TIM_A];
                break;

            default:
                $marketingTeam = null;
                $settingKey = [
                    SettingKeyEnum::MARKETING_TARGET_TIM_B,
                    SettingKeyEnum::MARKETING_TARGET_TIM_A,
                ];
                break;
        }

        $targetTeam = Setting::whereIn('key', $settingKey)->sum('value');
        $summary = $this->preorderService->getSummaryAll([
            'marketing' => optional($marketingTeam)->value,
        ]);

        $rankingRegency = $this->preorderService->rankingByRegency(5, $request->get('regency_month_id'), optional($marketingTeam)->value);
        $rankingAgent = $this->customerService->rankingAgents(5, optional($marketingTeam)->value);

        $rangkingByMarketingTeamA = $this->preorderService->rankingByMarketingTeam(MarketingEnum::TEAM_A, false);
        $rangkingByMarketingTeamB = $this->preorderService->rankingByMarketingTeam(MarketingEnum::TEAM_B, false);

        return view('marketing.dashboard.index', [
            'targetTeam' => $targetTeam,
            'targetAchieved' => $summary['total'],
            'rankingRegency' => $rankingRegency,
            'rankingAgent' => $rankingAgent,
            'rangkingByMarketingTeamA' => $rangkingByMarketingTeamA,
            'rangkingByMarketingTeamB' => $rangkingByMarketingTeamB,
        ]);
    }

    public function switch_marketing($marketing = null)
    {
        try {
            \Session::forget(config('session.app.selected_marketing_tim'));
            if ($marketing) {
                switch ($marketing) {
                    case MarketingEnum::TEAM_A:
                        $marketingValue = MarketingEnum::TEAM_A();
                        break;
                    case MarketingEnum::TEAM_B:
                        $marketingValue = MarketingEnum::TEAM_B();
                        break;
                    case MarketingEnum::RETAIL:
                        $marketingValue = MarketingEnum::RETAIL();
                        break;
                    case MarketingEnum::WRITING:
                        $marketingValue = MarketingEnum::WRITING();
                        break;

                    default:
                        // code...
                        break;
                }
                \Session::put(config('session.app.selected_marketing_tim'), $marketingValue);
            }

        } catch (\Throwable $th) {
        }

        return redirect()->route('marketing.dashboard');
    }

    public function widget_preorder()
    {
        $marketingTeam = session(config('session.app.selected_marketing_tim'));
        $preorderPaid = $this->preorderService->getSummary([
            'status_payment' => StatusPaymentEnum::PAID,
            'marketing' => optional($marketingTeam)->value,
        ]);
        $preorderNotPaid = $this->preorderService->getSummary([
            'status_payment' => StatusPaymentEnum::NOT_PAID,
            'marketing' => optional($marketingTeam)->value,
        ]);
        $preorderDp = $this->preorderService->getSummary([
            'status_payment' => StatusPaymentEnum::DP,
            'marketing' => optional($marketingTeam)->value,
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
            'marketing' => optional($marketingTeam)->value,
        ]);
        $orderNotPaid = $this->orderService->getSummary([
            'status_payment' => StatusPaymentEnum::NOT_PAID,
            'marketing' => optional($marketingTeam)->value,
        ]);
        $orderDp = $this->orderService->getSummary([
            'status_payment' => StatusPaymentEnum::DP,
            'marketing' => optional($marketingTeam)->value,
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
                ->where('is_exclude_target', false)
                ->whereMonth('date', $i);
            if (optional($marketingTeam)->value) {
                $zone1->where('marketing', $marketingTeam->value);
            }
            $zone1 = $zone1->sum('total_amount');

            $zone2 = Preorder::where('zone', ZoneEnum::ZONE_2)
                ->where('is_exclude_target', false)
                ->whereMonth('date', $i);
            if (optional($marketingTeam)->value) {
                $zone2->where('marketing', $marketingTeam->value);
            }
            $zone2 = $zone2->sum('total_amount');

            $zones['zone_1'][] = $zone1;
            $zones['zone_2'][] = $zone2;
        }

        return response()->json($zones);
    }

    public function widget_transaction_all()
    {
        $marketingTeam = session(config('session.app.selected_marketing_tim'));

        $result = [
            'transactions' => [],
        ];
        for ($i = 1; $i <= 12; $i++) {
            $transaction = Preorder::where('is_exclude_target', false)
                ->whereMonth('date', $i);
            if (optional($marketingTeam)->value) {
                $transaction->where('marketing', $marketingTeam->value);
            }

            $result['transactions'][] = $transaction->sum('total_amount');
        }

        return response()->json($result);
    }
}
