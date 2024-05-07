<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Order\StatusEnum;
use App\Enums\Preorder\DiscountTypeEnum;
use App\Enums\Preorder\StatusPaymentEnum;
use App\Enums\Preorder\TaxEnum;
use App\Exports\PreorderExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\OrderStoreUpdateRequest;
use App\Http\Requests\Admin\Order\OrderUpdateDiscountRequest;
use App\Http\Requests\Admin\Order\OrderUpdateStatusRequest;
use App\Http\Resources\Admin\Order\OrderResource;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderShipping;
use App\Models\Preorder;
use App\Models\PreorderDetail;
use App\Models\Product;
use App\Services\OrderService;
use App\Services\StockHistoryLogService;
use App\Services\TrackExpeditionService;
use App\Services\WhatsappService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function __construct(
        private TrackExpeditionService $trackExpeditionService,
        private StockHistoryLogService $stockLogService,
        private OrderService $orderService,
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
                    StatusEnum::VALIDATION_ADMIN,
                    StatusEnum::PROCESS,
                ],
                $request->all()
            );
        }

        return view(
            'admin.order.index'
        );
    }

    public function create($preorderId)
    {
        $preorder = Preorder::with([
            'details.product',
            'shipping',
        ])->find($preorderId);
        if (is_null($preorder)) {
            return redirect()->route('preorder.index')->with('error', 'Preorder tidak ditemukan');
        }

        $order = new Order();
        $order->preorder_id = $preorder->id;
        $order->invoice_number = Order::nextNoInvoice($preorder->id);
        $order->date = date('Y-m-d');
        $order->paid_at = $preorder->paid_at;
        $order->collector_id = $preorder->collector_id;
        $order->branch_id = $preorder->branch_id;
        $order->customer_id = $preorder->customer_id;
        $order->customer_address_id = $preorder->customer_address_id;
        $order->area_id = $preorder->area_id;
        $order->status = StatusEnum::PROCESS;
        $order->status_payment = $preorder->status_payment;
        $order->method_payment = $preorder->method_payment;
        $order->marketing = $preorder->marketing;
        $order->notes = $preorder->notes;
        $order->notes_staff = $preorder->notes_staff;
        $order->zone = $preorder->zone;
        $order->tax = $preorder->tax;
        $order->shipping_price = $preorder->shipping_price;
        $order->discount_type = $preorder->discount_type;
        $order->discount_percentage = $preorder->discount_percentage;
        $order->discount_price = $preorder->discount_price;
        $order->is_exclude_target = $preorder->is_exclude_target;

        $details = collect();
        foreach ($preorder->details as $detail) {
            $stockReady = $detail->product->stock; // 211
            if ($stockReady == 0) {
                continue;
            }

            $stockSent = $detail->qty_order ?: 0;
            $stockNeedSent = $detail->qty - $stockSent; // 325
            if ($stockNeedSent <= 0) {
                continue;
            }

            $stockCanSent = $stockNeedSent;
            if ($stockReady <= $stockNeedSent) {
                $stockCanSent = $stockReady;
            }

            $orderDetail = new OrderDetail();
            $orderDetail->preorder_detail_id = $detail->id;
            $orderDetail->product_id = $detail->product_id;
            $orderDetail->qty = $stockCanSent;
            $orderDetail->price = $detail->price;
            $orderDetail->discount_description = $detail->discount_description;
            $orderDetail->discount = $detail->discount ?: 0;
            $orderDetail->total_price = $orderDetail->qty * $orderDetail->price;
            $orderDetail->total_discount = $orderDetail->qty * $orderDetail->discount;
            $orderDetail->total = $orderDetail->total_price - $orderDetail->total_discount;

            $orderDetail->setRelation('product', $detail->product);
            $orderDetail->setRelation('preorder_detail', $detail);
            $details->push($orderDetail);
        }
        if ($details->count() > 0) {
            $order->setRelation('details', $details);
        }

        if ($preorder->shipping) {
            $order->setRelation('shipping', $preorder->shipping);
        }

        return view('admin.order._form', [
            'order' => $order,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderStoreUpdateRequest $request, $preorderId)
    {
        DB::beginTransaction();
        try {
            $input = [
                'date' => Carbon::parse($request->input('order_date'))->toDateString(),
                'paid_at' => $request->input('order_paid_at') ? Carbon::parse($request->input('order_paid_at'))->toDateString() : null,
                'branch_id' => $request->input('order_branch_id'),
                'collector_id' => $request->input('order_collector_id'),
                'customer_id' => $request->input('order_customer_id'),
                'customer_address_id' => $request->input('order_customer_address_id'),
                'area_id' => $request->input('order_area_id'),
                'status' => StatusEnum::PROCESS,
                'status_payment' => $request->input('order_status_payment'),
                'method_payment' => $request->input('order_method_payment'),
                'marketing' => $request->input('order_marketing'),
                'tax' => $request->input('order_tax'),
                'zone' => $request->input('order_zone'),
                'notes' => $request->input('order_notes') ? htmlentities($request->input('order_notes')) : null,
                'notes_staff' => $request->input('order_notes_staff') ? htmlentities($request->input('order_notes_staff')) : null,
                'shipping_price' => $request->input('order_shipping_price', 0) ?: 0,
                'discount_type' => $request->input('order_discount_type', DiscountTypeEnum::DISCOUNT_NO),
                'discount_percentage' => $request->input('order_discount_percentage') ?: 0,
                'discount_price' => $request->input('order_discount_price') ?: 0,
                'preorder_id' => $preorderId,
                'is_exclude_target' => $request->input('order_is_exclude_target', false),
            ];

            if ($input['status_payment'] != StatusPaymentEnum::PAID) {
                $input['paid_at'] = null;
            }

            $order = new Order();
            $order->fill($input);
            $order->invoice_number = Order::nextNoInvoice($order->preorder_id);

            $totalAmountDetails = 0;
            $totalDiscountDetails = 0;
            if ($order->save()) {
                $orderDetails = collect($request->input('order_details'));
                $products = Product::whereIn('id', $orderDetails->pluck('product_id'))->get()->keyBy('id');
                $preorderDetails = PreorderDetail::whereIn('id', $orderDetails->pluck('preorder_detail_id'))->get()->keyBy('id');
                foreach ($orderDetails as $detail) {
                    $orderDetail = new OrderDetail();
                    $totalPrice = $detail['qty'] * $detail['price'];
                    $totalDiscount = $detail['qty'] * ($detail['discount'] ?: 0);
                    $orderDetail->fill([
                        'order_id' => $order->id,
                        'preorder_detail_id' => $detail['preorder_detail_id'],
                        'product_id' => $detail['product_id'],
                        'price' => $detail['price'],
                        'qty' => $detail['qty'],
                        'discount_description' => $detail['discount_description'],
                        'discount' => $detail['discount'],
                        'total_price' => $totalPrice,
                        'total_discount' => $totalDiscount,
                        'total' => $totalPrice - $totalDiscount,
                    ]);

                    $orderDetail->save();
                    $totalAmountDetails += $totalPrice;
                    $totalDiscountDetails += $totalDiscount;

                    if ($products->has($orderDetail->product_id)) {
                        $product = $products[$orderDetail->product_id];
                        $oldStock = $product->stock;
                        $product->stock = $oldStock - $orderDetail->qty;
                        $this->stockLogService->logStockOut(
                            $order,
                            $product->id,
                            $oldStock,
                            $orderDetail->qty
                        );
                        $product->save();
                    }

                    if ($preorderDetails->has($orderDetail->preorder_detail_id)) {
                        $preorderDetail = $preorderDetails[$orderDetail->preorder_detail_id];
                        $preorderDetail->qty_order = ($preorderDetail->qty_order ?: 0) + $orderDetail->qty;
                        $preorderDetail->save();
                    }
                }

                if ($request->order_resi) {
                    $order->loadMissing([
                        'customer.user',
                        'customer_address',
                    ]);
                    $shipping = new OrderShipping();
                    $shipping->fill([
                        'order_id' => $order->id,
                        'resi' => str($request->order_resi)->trim()->upper(),
                        'expedition_id' => $request->order_expedition_id,
                        'name' => $order->customer->user->name ?? '',
                        'email' => $order->customer->user->email ?? '',
                        'phone' => $order->customer->user->phone ?? '',
                        'address' => $order->customer->user->address ?? '',
                        'province_id' => $order->customer_address->province_id,
                        'regency_id' => $order->customer_address->regency_id,
                        'district_id' => $order->customer_address->district_id,
                        'village_id' => $order->customer_address->village_id,
                        'shipping_price' => $order->shipping_price,
                    ]);
                    $shipping->save();
                }
            }

            $order->total_amount_details = $totalAmountDetails;
            $order->total_discount_details = $totalDiscountDetails;
            $order->subtotal = $totalAmountDetails - $totalDiscountDetails;

            $discountPrice = $order->discount_price;
            if ($order->discount_type == DiscountTypeEnum::DISCOUNT_PERCENTAGE) {
                $discountPrice = $order->subtotal * ($order->discount_percentage / 100);
            }

            $order->total_amount = $order->subtotal - $discountPrice;

            if ($order->tax == TaxEnum::PPN_10) {
                $order->tax_amount = $order->total_amount * 0.1;
            } elseif ($order->tax == TaxEnum::GST_6) {
                $order->tax_amount = $order->total_amount * 0.06;
            } elseif ($order->tax == TaxEnum::VAT_20) {
                $order->tax_amount = $order->total_amount * 0.2;
            }
            $order->skipLog()->save();

            DB::commit();

            app()->make(WhatsappService::class)->sentMigrationMessage(
                $order->customer->user->phone_number ?? '',
                $order
            );
        } catch (\Throwable $th) {
            DB::rollBack();

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
        $order = Order::with([
            'customer_address',
            'area',
            'collector',
            'createdBy',
            'customer.user',
            'customer.addresses',
            'branch',
            'shipping',
            'details.product',
        ])->find($id);

        if (is_null($order)) {
            return abort(404);
        }

        return view('admin.order.show', [
            'order' => $order,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function detail(string $id)
    {
        $order = Order::with([
            'customer_address',
            'area',
            'collector',
            'createdBy',
            'customer.user',
            'customer.addresses',
            'branch',
            'shipping',
            'details.product',
        ])->find($id);

        if (is_null($order)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        return new OrderResource($order);
    }

    /**
     * Display the specified resource.
     */
    public function edit(string $id)
    {
        $order = Order::with([
            'customer_address',
            'area',
            'collector',
            'createdBy',
            'customer.user',
            'customer.addresses',
            'branch',
            'shipping',
            'details.product',
            'details.preorder_detail',
        ])->find($id);

        if (is_null($order)) {
            return abort(404);
        }

        return view('admin.order._form_update', [
            'order' => $order,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderStoreUpdateRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $sentNotifResi = false;
            $input = [
                'date' => Carbon::parse($request->input('order_date'))->toDateString(),
                'paid_at' => $request->input('order_paid_at') ? Carbon::parse($request->input('order_paid_at'))->toDateString() : null,
                'branch_id' => $request->input('order_branch_id'),
                'collector_id' => $request->input('order_collector_id'),
                'customer_id' => $request->input('order_customer_id'),
                'customer_address_id' => $request->input('order_customer_address_id'),
                'area_id' => $request->input('order_area_id'),
                'status' => $request->input('order_status'),
                'status_payment' => $request->input('order_status_payment'),
                'method_payment' => $request->input('order_method_payment'),
                'marketing' => $request->input('order_marketing'),
                'tax' => $request->input('order_tax'),
                'zone' => $request->input('order_zone'),
                'notes' => $request->input('order_notes') ? htmlentities($request->input('order_notes')) : null,
                'notes_staff' => $request->input('order_notes_staff') ? htmlentities($request->input('order_notes_staff')) : null,
                'shipping_price' => $request->input('order_shipping_price', 0) ?: 0,
                'discount_type' => $request->input('order_discount_type', DiscountTypeEnum::DISCOUNT_NO),
                'discount_percentage' => $request->input('order_discount_percentage') ?: 0,
                'discount_price' => $request->input('order_discount_price') ?: 0,
                'is_exclude_target' => $request->input('order_is_exclude_target', false),
            ];

            if ($input['status_payment'] != StatusPaymentEnum::PAID) {
                $input['paid_at'] = null;
            }

            $order = Order::with('details', 'shipping')->find($id);
            if (empty($order)) {
                return response()->json([
                    'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                ]);
            }

            $order->fill($input);

            $totalAmountDetails = 0;
            $totalDiscountDetails = 0;
            if ($order->save()) {
                $orderDetails = collect($request->input('order_details'));
                $products = Product::whereIn('id', $orderDetails->pluck('product_id'))->get()->keyBy('id');
                $preorderDetails = PreorderDetail::whereIn('id', $orderDetails->pluck('preorder_detail_id'))->get()->keyBy('id');
                $detailIds = [];
                foreach ($orderDetails as $detail) {
                    $orderDetail = $order->details->where('product_id', $detail['product_id'])->first();
                    if (empty($orderDetail)) {
                        $orderDetail = new OrderDetail();
                    }
                    $totalPrice = $detail['qty'] * $detail['price'];
                    $totalDiscount = $detail['qty'] * ($detail['discount'] ?: 0);
                    $oldQty = $orderDetail->qty;
                    $orderDetail->fill([
                        'order_id' => $order->id,
                        'preorder_detail_id' => $detail['preorder_detail_id'],
                        'product_id' => $detail['product_id'],
                        'price' => $detail['price'],
                        'qty' => $detail['qty'],
                        'discount_description' => $detail['discount_description'],
                        'discount' => $detail['discount'],
                        'total_price' => $totalPrice,
                        'total_discount' => $totalDiscount,
                        'total' => $totalPrice - $totalDiscount,
                    ]);

                    $orderDetail->save();
                    $detailIds[] = $orderDetail->id;
                    $totalAmountDetails += $totalPrice;
                    $totalDiscountDetails += $totalDiscount;

                    if ($products->has($orderDetail->product_id)) {
                        $product = $products[$orderDetail->product_id];
                        $oldStock = $product->stock + $oldQty;
                        if ($oldQty > $orderDetail->qty) {
                            $product->stock = $oldStock - $orderDetail->qty;
                            $this->stockLogService->logStockIn(
                                $order,
                                $product->id,
                                $oldStock - $oldQty,
                                ($oldQty - $orderDetail->qty)
                            );
                        }

                        if ($oldQty < $orderDetail->qty) {
                            $product->stock = $oldStock - $orderDetail->qty;
                            $this->stockLogService->logStockOut(
                                $order,
                                $product->id,
                                $oldStock - $oldQty,
                                ($orderDetail->qty - $oldQty)
                            );
                        }

                        $product->save();
                    }

                    if ($preorderDetails->has($orderDetail->preorder_detail_id)) {
                        $preorderDetail = $preorderDetails[$orderDetail->preorder_detail_id];
                        $preorderDetail->qty_order = ($preorderDetail->qty_order ?: 0) - $oldQty + $orderDetail->qty;
                        $preorderDetail->save();
                    }
                }

                if ($detailIds) {
                    $removeDetails = $order->details()->with('product', 'preorder_detail')->whereNotIn('id', $detailIds)->get();
                    foreach ($removeDetails as $removeDetail) {
                        $product = $removeDetail->product;
                        $oldStock = $product->stock;
                        $product->stock += $removeDetail->qty;
                        $product->save();

                        $this->stockLogService->logStockIn(
                            $order,
                            $product->id,
                            $oldStock,
                            $removeDetail->qty
                        );

                        $removeDetail->preorder_detail->qty_order = ($removeDetail->preorder_detail->qty_order ?: 0) - $removeDetail->qty;
                        $removeDetail->preorder_detail->save();

                        $removeDetail->forceDelete();
                    }
                }

                if ($request->order_resi) {
                    $order->loadMissing([
                        'customer.user',
                        'customer_address',
                    ]);
                    $shipping = $order->shipping ?: new OrderShipping();
                    $sentNotifResi = true;
                    $shipping->fill([
                        'order_id' => $order->id,
                        'resi' => str($request->order_resi)->trim()->upper(),
                        'expedition_id' => $request->order_expedition_id,
                        'name' => $order->customer->user->name ?? '',
                        'email' => $order->customer->user->email ?? '',
                        'phone' => $order->customer->user->phone ?? '',
                        'address' => $order->customer->user->address ?? '',
                        'province_id' => optional($order->customer_address)->province_id,
                        'regency_id' => optional($order->customer_address)->regency_id,
                        'district_id' => optional($order->customer_address)->district_id,
                        'village_id' => optional($order->customer_address)->village_id,
                        'shipping_price' => $order->shipping_price,
                    ]);
                    $shipping->save();
                } else {
                    if ($order->shipping) {
                        $order->shipping->delete();
                    }
                }
            }

            $order->total_amount_details = $totalAmountDetails;
            $order->total_discount_details = $totalDiscountDetails;
            $order->subtotal = $totalAmountDetails - $totalDiscountDetails;

            $discountPrice = $order->discount_price;
            if ($order->discount_type == DiscountTypeEnum::DISCOUNT_PERCENTAGE) {
                $discountPrice = $order->subtotal * ($order->discount_percentage / 100);
            }

            $order->total_amount = $order->subtotal - $discountPrice;

            if ($order->tax == TaxEnum::PPN_10) {
                $order->tax_amount = $order->total_amount * 0.1;
            } elseif ($order->tax == TaxEnum::GST_6) {
                $order->tax_amount = $order->total_amount * 0.06;
            } elseif ($order->tax == TaxEnum::VAT_20) {
                $order->tax_amount = $order->total_amount * 0.2;
            }
            $order->save();

            DB::commit();

            if ($sentNotifResi) {
                app()->make(WhatsappService::class)->sentInvoiceResiMessage(
                    $order->customer->user->phone_number ?? '',
                    $order
                );
            }
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
     * Update the specified resource in storage.
     */
    public function update_discount(OrderUpdateDiscountRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $order = Order::with('shipping')->find($id);
            if (empty($order)) {
                return response()->json([
                    'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                ]);
            }

            $order->fill([
                'shipping_price' => $request->input('order_shipping_price') ?: 0,
                'discount_type' => $request->input('order_discount_type', DiscountTypeEnum::DISCOUNT_NO),
                'discount_percentage' => $request->input('order_discount_percentage') ?: 0,
                'discount_price' => $request->input('order_discount_price') ?: 0,
            ]);

            $discountPrice = $order->discount_price;
            if ($order->discount_type == DiscountTypeEnum::DISCOUNT_PERCENTAGE) {
                $discountPrice = $order->subtotal * ($order->discount_percentage / 100);
            }

            $order->total_amount = $order->subtotal - $discountPrice;
            $order->save();

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
     * Update the specified resource in storage.
     */
    public function update_status(OrderUpdateStatusRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $sentNotifResi = false;
            $order = Order::with('shipping', 'customer', 'customer_address')->find($id);
            if (empty($order)) {
                return response()->json([
                    'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                ]);
            }

            $order->fill([
                'status' => $request->input('order_status'),
                'status_payment' => $request->input('order_status_payment'),
                'method_payment' => $request->input('order_method_payment'),
                'marketing' => $request->input('order_marketing'),
                'paid_at' => $request->input('order_paid_at') ? Carbon::parse($request->input('order_paid_at'))->toDateString() : null,
            ]);

            if ($order->status_payment != StatusPaymentEnum::PAID) {
                $order->paid_at = null;
            }

            $order->save();

            if (
                $order->status == StatusEnum::SENT
                && $request->order_resi
            ) {
                $order->loadMissing([
                    'customer.user',
                    'customer_address',
                ]);

                $shipping = $order->shipping ? $order->shipping : new OrderShipping();
                $sentNotifResi = true;
                $shipping->fill([
                    'order_id' => $order->id,
                    'resi' => str($request->order_resi)->trim()->upper(),
                    'expedition_id' => $request->order_expedition_id,
                    'name' => $order->customer->user->name ?? '',
                    'email' => $order->customer->user->email ?? '',
                    'phone' => $order->customer->user->phone ?? '',
                    'address' => $order->customer->user->address ?? '',
                    'province_id' => optional($order->customer_address)->province_id,
                    'regency_id' => optional($order->customer_address)->regency_id,
                    'district_id' => optional($order->customer_address)->district_id,
                    'village_id' => optional($order->customer_address)->village_id,
                    'shipping_price' => $order->shipping_price,
                ]);
                $shipping->save();
            }

            if ($order->status == StatusEnum::CANCEL) {
                $details = $order->details()->with('product', 'preorder_detail')->get();
                foreach ($details as $detail) {
                    $product = $detail->product;
                    $oldStock = $product->stock;
                    $product->stock += $detail->qty;
                    $product->save();

                    $this->stockLogService->logStockIn(
                        $order,
                        $product->id,
                        $oldStock,
                        $detail->qty
                    );

                    $detail->preorder_detail->qty_order = ($detail->preorder_detail->qty_order ?: 0) - $detail->qty;
                    $detail->preorder_detail->save();
                }
            }

            DB::commit();

            if ($sentNotifResi) {
                app()->make(WhatsappService::class)->sentInvoiceResiMessage(
                    $order->customer->user->phone_number ?? '',
                    $order
                );
            }
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
        $order = Order::find($id);

        if (is_null($order)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            $order->shipping()->delete();
            $removeDetails = $order->details()->with('product', 'preorder_detail')->get();
            foreach ($removeDetails as $removeDetail) {
                $product = $removeDetail->product;
                $oldStock = $product->stock;
                $product->stock += $removeDetail->qty;
                $product->save();

                $this->stockLogService->logStockIn(
                    $order,
                    $product->id,
                    $oldStock,
                    $removeDetail->qty
                );

                $removeDetail->preorder_detail->qty_order = ($removeDetail->preorder_detail->qty_order ?: 0) - $removeDetail->qty;
                $removeDetail->preorder_detail->save();

                $removeDetail->delete();
            }

            $order->delete();
        } catch (\Throwable $th) {
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
    public function track(string $id)
    {
        $order = Order::with([
            'shipping.expedition',
        ])->find($id);

        if (is_null($order)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        $detailTrack = null;
        if (optional($order->shipping)->expedition && optional($order->shipping)->expedition->courier) {
            $expedition = $order->shipping->expedition;
            $track = $this->trackExpeditionService->track(
                $expedition->courier,
                $order->shipping->resi
            );

            if ($track) {
                $detailTrack = $track['data'];
            }
        }

        return view('admin.order.track', [
            'order' => $order,
            'detailTrack' => $detailTrack,
        ]);
    }

    public function purchase_order($id)
    {
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

        return view('admin.order.print.purchasing', [
            'order' => $order,
        ]);
    }

    public function faktur($id)
    {
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

        return view('admin.order.print.faktur', [
            'order' => $order,
        ]);
    }

    public function address($id)
    {
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
            'shipping.expedition',
        ])->find($id);

        return view('admin.order.print.address', [
            'order' => $order,
        ]);
    }

    public function sent_document($id)
    {
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

        return view('admin.order.print.sent_document', [
            'order' => $order,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function export(Request $request)
    {
        $query = Order::with([
            'customer.user',
            'customer_address',
        ])->whereIn('status', [
            StatusEnum::PROCESS,
            StatusEnum::VALIDATION_ADMIN,
        ]);

        if ($request->search_customer_id) {
            $query->where('customer_id', $request->search_customer_id);
        }

        if ($request->search_regency_id) {
            $regencyId = $request->search_regency_id;
            $query->whereHas('customer_address', function ($qAddress) use ($regencyId) {
                $qAddress->where('regency_id', $regencyId);
            });
        }

        if ($q = $request->input('q')) {
            $query->where(function ($q2) use ($q) {
                $q2->whereLike('invoice_number', $q);
            });
        }

        $preorders = $query->get();

        return Excel::download(new PreorderExport($preorders), 'Pesanan.xlsx');
    }
}
