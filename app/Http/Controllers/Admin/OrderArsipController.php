<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Preorder\StatusEnum;
use App\Enums\Preorder\StatusPaymentEnum;
use App\Http\Controllers\Controller;
use App\Services\OrderService;
use App\Services\TrackExpeditionService;
use Illuminate\Http\Request;

class OrderArsipController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private TrackExpeditionService $trackExpeditionService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $params = $request->all();
            if (! isset($params['search'])) {
                if (! isset($params['status_payment'])) {
                    $params['search']['status_payment'] = '';
                }
            }
            $params['search']['status_payment'] = StatusPaymentEnum::PAID;

            return $this->orderService->getListByStatus(
                [
                    StatusEnum::DONE,
                ],
                $params
            );
        }

        return view(
            'admin.order_arsip.index'
        );
    }
}
