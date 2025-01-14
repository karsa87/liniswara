<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Order\StatusEnum;
use App\Http\Controllers\Controller;
use App\Services\OrderService;
use App\Services\TrackExpeditionService;
use Illuminate\Http\Request;

class OrderSentController extends Controller
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
            return $this->orderService->getListByStatus(
                [
                    StatusEnum::SENT,
                    StatusEnum::PACKING,
                ],
                $request->all()
            );
        }

        return view(
            'admin.order_sent.index'
        );
    }
}
