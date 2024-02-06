<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Preorder;
use App\Services\TrackExpeditionService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class InvoiceController extends Controller
{
    public function __construct(
        private TrackExpeditionService $trackExpeditionService
    ) {
    }

    /**
     * Display the specified resource.
     */
    public function po_invoice(string $key)
    {
        $encrypt = Cache::get('user-invoice:'.$key);
        if (empty($encrypt)) {
            abort(404);
        }

        $id = Crypt::decrypt($encrypt);
        $preorder = Preorder::with([
            'customer_address',
            'collector',
            'createdBy',
            'updatedBy',
            'customer.user',
            'customer.addresses',
            'branch',
            'shipping',
            'details.product',
        ])->find($id);

        if (is_null($preorder)) {
            return abort(404);
        }

        return view('invoice.po_invoice', [
            'preorder' => $preorder,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function po_order(string $key)
    {
        $encrypt = Cache::get('user-invoice:'.$key);
        if (empty($encrypt)) {
            abort(404);
        }

        $id = Crypt::decrypt($encrypt);
        $order = Order::with([
            'customer_address',
            'collector.district',
            'collector.regency',
            'collector.province',
            'createdBy',
            'updatedBy',
            'customer.user',
            'customer.addresses',
            'branch',
            'shipping',
            'details.product',
        ])->find($id);

        if (is_null($order)) {
            return abort(404);
        }

        return view('invoice.po_order', [
            'order' => $order,
        ]);
    }
}
