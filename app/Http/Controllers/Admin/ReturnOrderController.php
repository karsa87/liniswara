<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Preorder\DiscountTypeEnum;
use App\Enums\Preorder\TaxEnum;
use App\Enums\ReturnOrder\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReturnOrder\ReturnOrderStoreUpdateRequest;
use App\Http\Resources\Admin\ReturnOrder\ReturnOrderResource;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ReturnOrder;
use App\Models\ReturnOrderDetail;
use App\Services\StockHistoryLogService;
use App\Services\TrackExpeditionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ReturnOrderController extends Controller
{
    public function __construct(
        private TrackExpeditionService $trackExpeditionService,
        private StockHistoryLogService $stockLogService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $params = $request->all();
            $query = ReturnOrder::with([
                'branch',
            ]);

            if ($q = ($params['search']['value'] ?? '')) {
                $query->where(function ($q2) use ($q) {
                    $q2->whereLike('invoice_number', $q);
                });
            }

            $branchId = $params['search']['branch_id'] ?? '';
            if ($branchId) {
                $query->where('branch_id', $branchId);
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

            return ReturnOrderResource::collection($orders)->additional([
                'recordsTotal' => $totalAll,
                'recordsFiltered' => $totalAll,
            ]);
        }

        return view(
            'admin.return_order.index'
        );
    }

    public function create($orderId)
    {
        $order = Order::with([
            'details' => function ($q) {
                $q->whereRaw('qty_return <= qty');
            },
            'details.product',
            'shipping',
        ])->find($orderId);

        if (is_null($order)) {
            return redirect()->route('order.index')->with('error', 'Pesananan tidak ditemukan');
        }

        $returnOrder = new ReturnOrder();
        $returnOrder->order_id = $orderId;
        $returnOrder->branch_id = $order->branch_id;
        $returnOrder->date = Carbon::now()->format('Y-m-d H:i');
        $returnOrder->no_return = ReturnOrder::nextNoReturn($orderId);
        $returnOrder->notes = '';
        $returnOrder->status = StatusEnum::NEW;
        $returnOrder->tax = $order->tax;
        $returnOrder->shipping_price = $order->shipping_price;
        $returnOrder->discount_type = $order->discount_type;
        $returnOrder->discount_percentage = $order->discount_percentage;
        $returnOrder->discount_price = $order->discount_price;

        $details = collect();
        foreach ($order->details as $detail) {
            $returnOrderDetail = new ReturnOrderDetail();
            $returnOrderDetail->order_detail_id = $detail->id;
            $returnOrderDetail->product_id = $detail->product_id;
            $returnOrderDetail->qty = $detail->qty - $detail->qty_return;
            $returnOrderDetail->price = $detail->price;
            $returnOrderDetail->discount_description = $detail->discount_description;
            $returnOrderDetail->discount = $detail->discount ?: 0;
            $returnOrderDetail->total_price = $returnOrderDetail->qty * $returnOrderDetail->price;
            $returnOrderDetail->total_discount = $returnOrderDetail->qty * $returnOrderDetail->discount;
            $returnOrderDetail->total = $returnOrderDetail->total_price - $returnOrderDetail->total_discount;

            $returnOrderDetail->setRelation('product', $detail->product);
            $returnOrderDetail->setRelation('order_detail', $detail);
            $details->push($returnOrderDetail);
        }

        if ($details->count() > 0) {
            $returnOrder->setRelation('details', $details);
        }

        return view('admin.return_order._form', [
            'returnOrder' => $returnOrder,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReturnOrderStoreUpdateRequest $request, $orderId)
    {
        DB::beginTransaction();
        try {
            $input = [
                'date' => Carbon::parse($request->input('return_date'))->toDateString(),
                'branch_id' => $request->input('return_branch_id'),
                'status' => StatusEnum::NEW,
                'tax' => $request->input('return_tax'),
                'notes' => $request->input('return_notes') ? htmlentities($request->input('return_notes')) : null,
                'shipping_price' => $request->input('return_shipping_price', 0) ?: 0,
                'discount_type' => $request->input('return_discount_type', DiscountTypeEnum::DISCOUNT_NO),
                'discount_percentage' => $request->input('return_discount_percentage') ?: 0,
                'discount_price' => $request->input('return_discount_price') ?: 0,
                'order_id' => $orderId,
            ];

            $returnOrder = new ReturnOrder();
            $returnOrder->fill($input);
            $returnOrder->no_return = ReturnOrder::nextNoReturn($orderId);

            $totalAmountDetails = 0;
            $totalDiscountDetails = 0;
            if ($returnOrder->save()) {
                $returnOrderDetails = collect($request->input('return_details'));
                $orderDetails = OrderDetail::whereIn('id', $returnOrderDetails->pluck('return_detail_id'))->get()->keyBy('id');
                foreach ($returnOrderDetails as $detail) {
                    $returnOrderDetail = new ReturnOrderDetail();
                    $totalPrice = $detail['qty'] * $detail['price'];
                    $totalDiscount = $detail['qty'] * ($detail['discount'] ?: 0);
                    $returnOrderDetail->fill([
                        'return_order_id' => $returnOrder->id,
                        'order_detail_id' => $detail['order_detail_id'],
                        'product_id' => $detail['product_id'],
                        'price' => $detail['price'],
                        'qty' => $detail['qty'],
                        'discount_description' => $detail['discount_description'],
                        'discount' => $detail['discount'],
                        'total_price' => $totalPrice,
                        'total_discount' => $totalDiscount,
                        'total' => $totalPrice - $totalDiscount,
                    ]);

                    $returnOrderDetail->save();
                    $totalAmountDetails += $totalPrice;
                    $totalDiscountDetails += $totalDiscount;

                    if ($orderDetails->has($returnOrderDetail->order_detail_id)) {
                        $orderDetail = $orderDetails[$returnOrderDetail->order_detail_id];
                        $orderDetail->qty_return = ($orderDetail->qty_return ?: 0) + $returnOrderDetail->qty;
                        $orderDetail->save();
                    }
                }
            }

            $returnOrder->total_amount_details = $totalAmountDetails;
            $returnOrder->total_discount_details = $totalDiscountDetails;
            $returnOrder->subtotal = $totalAmountDetails - $totalDiscountDetails;

            $discountPrice = $returnOrder->discount_price;
            if ($returnOrder->discount_type == DiscountTypeEnum::DISCOUNT_PERCENTAGE) {
                $discountPrice = $returnOrder->subtotal * ($returnOrder->discount_percentage / 100);
            }

            $returnOrder->total_amount = $returnOrder->subtotal - $discountPrice;

            if ($returnOrder->tax == TaxEnum::PPN_10) {
                $returnOrder->tax_amount = $returnOrder->total_amount * 0.1;
            } elseif ($returnOrder->tax == TaxEnum::GST_6) {
                $returnOrder->tax_amount = $returnOrder->total_amount * 0.06;
            } elseif ($returnOrder->tax == TaxEnum::VAT_20) {
                $returnOrder->tax_amount = $returnOrder->total_amount * 0.2;
            }
            $returnOrder->skipLog()->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);

            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $returnOrder = ReturnOrder::with([
            'order.customer',
            'createdBy',
            'branch',
            'details.product',
        ])->find($id);

        if (is_null($returnOrder)) {
            return abort(404);
        }

        return view('admin.return_order.show', [
            'returnOrder' => $returnOrder,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function detail(string $id)
    {
        $returnOrder = ReturnOrder::with([
            'order.customer.user',
            'order.customer_address',
            'order.area',
            'createdBy',
            'branch',
            'details.product',
        ])->find($id);

        if (is_null($returnOrder)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        return new ReturnOrderResource($returnOrder);
    }

    /**
     * Display the specified resource.
     */
    public function edit(string $id)
    {
        $returnOrder = ReturnOrder::with([
            'order',
            'createdBy',
            'branch',
            'details.product',
        ])->find($id);

        if (is_null($returnOrder)) {
            return abort(404);
        }

        return view('admin.return_order._form_update', [
            'returnOrder' => $returnOrder,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReturnOrderStoreUpdateRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $sentNotifResi = false;
            $input = [
                'date' => Carbon::parse($request->input('return_date'))->toDateString(),
                'branch_id' => $request->input('return_branch_id'),
                'status' => $request->input('return_status'),
                'tax' => $request->input('return_tax'),
                'notes' => $request->input('return_notes') ? htmlentities($request->input('return_notes')) : null,
                'shipping_price' => $request->input('return_shipping_price', 0) ?: 0,
                'discount_type' => $request->input('return_discount_type', DiscountTypeEnum::DISCOUNT_NO),
                'discount_percentage' => $request->input('return_discount_percentage') ?: 0,
                'discount_price' => $request->input('return_discount_price') ?: 0,
            ];

            $returnOrder = ReturnOrder::with('details.product')->find($id);
            if (empty($returnOrder)) {
                return response()->json([
                    'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                ]);
            }

            $returnOrder->fill($input);

            $totalAmountDetails = 0;
            $totalDiscountDetails = 0;
            if ($returnOrder->save()) {
                $returnOrderDetails = collect($request->input('return_details'));
                $orderDetails = OrderDetail::whereIn('id', $returnOrderDetails->pluck('return_detail_id'))->get()->keyBy('id');
                $detailIds = [];
                foreach ($returnOrderDetails as $detail) {
                    $returnOrderDetail = $returnOrder->details->where('product_id', $detail['product_id'])->first();
                    if (empty($returnOrderDetail)) {
                        $returnOrderDetail = new ReturnOrderDetail();
                    }
                    $totalPrice = $detail['qty'] * $detail['price'];
                    $totalDiscount = $detail['qty'] * ($detail['discount'] ?: 0);
                    $oldQty = $returnOrderDetail->qty;

                    $returnOrderDetail->fill([
                        'return_order_id' => $returnOrder->id,
                        'order_detail_id' => $detail['order_detail_id'],
                        'product_id' => $detail['product_id'],
                        'price' => $detail['price'],
                        'qty' => $detail['qty'],
                        'discount_description' => $detail['discount_description'],
                        'discount' => $detail['discount'],
                        'total_price' => $totalPrice,
                        'total_discount' => $totalDiscount,
                        'total' => $totalPrice - $totalDiscount,
                    ]);

                    $returnOrderDetail->save();
                    $detailIds[] = $returnOrderDetail->id;
                    $totalAmountDetails += $totalPrice;
                    $totalDiscountDetails += $totalDiscount;

                    if ($orderDetails->has($returnOrderDetail->order_detail_id)) {
                        $orderDetail = $orderDetails[$returnOrderDetail->order_detail_id];
                        $orderDetail->qty_return = ($orderDetail->qty_return ?: 0) - $oldQty + $returnOrderDetail->qty;
                        $orderDetail->save();
                    }
                }

                if ($detailIds) {
                    $removeDetails = $returnOrder->details()->with('product', 'order_detail')->whereNotIn('id', $detailIds)->get();
                    foreach ($removeDetails as $removeDetail) {
                        $removeDetail->order_detail->qty_return = ($removeDetail->order_detail->qty_return ?: 0) - $removeDetail->qty;
                        $removeDetail->order_detail->save();

                        $removeDetail->forceDelete();
                    }
                }
            }

            $returnOrder->total_amount_details = $totalAmountDetails;
            $returnOrder->total_discount_details = $totalDiscountDetails;
            $returnOrder->subtotal = $totalAmountDetails - $totalDiscountDetails;

            $discountPrice = $returnOrder->discount_price;
            if ($returnOrder->discount_type == DiscountTypeEnum::DISCOUNT_PERCENTAGE) {
                $discountPrice = $returnOrder->subtotal * ($returnOrder->discount_percentage / 100);
            }

            $returnOrder->total_amount = $returnOrder->subtotal - $discountPrice;

            if ($returnOrder->tax == TaxEnum::PPN_10) {
                $returnOrder->tax_amount = $returnOrder->total_amount * 0.1;
            } elseif ($returnOrder->tax == TaxEnum::GST_6) {
                $returnOrder->tax_amount = $returnOrder->total_amount * 0.06;
            } elseif ($returnOrder->tax == TaxEnum::VAT_20) {
                $returnOrder->tax_amount = $returnOrder->total_amount * 0.2;
            }
            $returnOrder->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $returnOrder = ReturnOrder::find($id);

        if (is_null($returnOrder)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            $removeDetails = $returnOrder->details()->with('product', 'order_detail')->get();
            foreach ($removeDetails as $removeDetail) {
                $product = $removeDetail->product;
                $oldStock = $product->stock;
                $product->stock += $removeDetail->qty;
                $product->save();

                if ($returnOrder->status == StatusEnum::CONFIRMATION) {
                    $this->stockLogService->logStockIn(
                        $returnOrder,
                        $product->id,
                        $oldStock,
                        $removeDetail->qty
                    );
                }

                if ($removeDetail->order_detail) {
                    $removeDetail->order_detail->qty_return = ($removeDetail->order_detail->qty_return ?: 0) - $removeDetail->qty;
                    $removeDetail->order_detail->save();
                }

                $removeDetail->delete();
            }

            $returnOrder->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }

    public function confirmation($id)
    {
        $returnOrder = ReturnOrder::with([
            'details.product',
        ])->find($id);

        if (is_null($returnOrder)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            $returnOrder->status = StatusEnum::CONFIRMATION;
            DB::beginTransaction();
            if ($returnOrder->save()) {
                foreach ($returnOrder->details as $detail) {
                    if (! $detail->product) {
                        continue;
                    }

                    $product = $detail->product;
                    $oldStock = $product->stock;
                    $product->stock = $oldStock + $detail->qty;
                    $this->stockLogService->logStockIn(
                        $returnOrder,
                        $product->id,
                        $oldStock,
                        $detail->qty
                    );
                    $product->save();
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }
}
