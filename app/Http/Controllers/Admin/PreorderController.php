<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Preorder\DiscountTypeEnum;
use App\Enums\Preorder\StatusPaymentEnum;
use App\Enums\Preorder\TaxEnum;
use App\Exports\PreorderExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Preorder\PreorderStoreUpdateRequest;
use App\Http\Requests\Admin\Preorder\PreorderUpdateDiscountRequest;
use App\Http\Requests\Admin\Preorder\PreorderUpdateStatusRequest;
use App\Http\Resources\Admin\Preorder\PreorderResource;
use App\Models\Preorder;
use App\Models\PreorderDetail;
use App\Models\PreorderShipping;
use App\Services\TrackExpeditionService;
use App\Services\WhatsappService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PreorderController extends Controller
{
    public function __construct(
        private TrackExpeditionService $trackExpeditionService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Preorder::with([
                'customer_address',
                'collector',
                'createdBy',
                'updatedBy',
                'customer.user',
                'branch',
                'details' => function ($qDetail) {
                    $qDetail->whereRaw('qty != qty_order');
                },
                'shipping',
            ])
                ->whereHas('details', function ($qDetail) {
                    $qDetail->whereRaw('qty != qty_order');
                });

            if ($q = $request->input('search.value')) {
                $query->where(function ($q2) use ($q) {
                    $q2->whereLike('invoice_number', $q);
                });
            }

            if ($branchId = $request->input('search.branch_id')) {
                $query->where('branch_id', $branchId);
            }

            if ($collectorId = $request->input('search.collector_id')) {
                $query->where('collector_id', $collectorId);
            }

            if ($customerId = $request->input('search.customer_id')) {
                $query->where('customer_id', $customerId);
            }

            if ($marketingId = $request->input('search.marketing_id')) {
                $query->where('marketing', $marketingId);
            }

            if ($status = $request->input('search.status')) {
                $query->where('status', $status);
            }

            if ($status = $request->input('search.status_payment')) {
                $query->where('status_payment', $status);
            }

            if ($status = $request->input('search.method_payment')) {
                $query->where('method_payment', $status);
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
            } else {
                $query->orderBy('created_at', 'DESC');
            }

            $totalAll = (clone $query)->count();
            $preorders = $query->offset($request->get('start', 0))
                ->limit($request->get('length', 10))
                ->get();

            return PreorderResource::collection($preorders)->additional([
                'recordsTotal' => $totalAll,
                'recordsFiltered' => $totalAll,
            ]);
        }

        return view(
            'admin.preorder.index'
        );
    }

    public function create()
    {
        return view('admin.preorder._form', [
            'preorder' => new Preorder(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PreorderStoreUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            $input = [
                'date' => Carbon::parse($request->input('preorder_date'))->toDateString(),
                'paid_at' => $request->input('preorder_paid_at') ? Carbon::parse($request->input('preorder_paid_at'))->toDateString() : null,
                'branch_id' => $request->input('preorder_branch_id'),
                'collector_id' => $request->input('preorder_collector_id'),
                'customer_id' => $request->input('preorder_customer_id'),
                'customer_address_id' => $request->input('preorder_customer_address_id'),
                'status' => $request->input('preorder_status'),
                'status_payment' => $request->input('preorder_status_payment'),
                'method_payment' => $request->input('preorder_method_payment'),
                'marketing' => $request->input('preorder_marketing'),
                'tax' => $request->input('preorder_tax'),
                'zone' => $request->input('preorder_zone'),
                'notes' => $request->input('preorder_notes') ? htmlentities($request->input('preorder_notes')) : null,
                'notes_staff' => $request->input('preorder_notes_staff') ? htmlentities($request->input('preorder_notes_staff')) : null,
                'shipping_price' => $request->input('preorder_shipping_price', 0) ?: 0,
                'discount_type' => $request->input('preorder_discount_type', DiscountTypeEnum::DISCOUNT_NO),
                'discount_percentage' => $request->input('preorder_discount_percentage') ?: 0,
                'discount_price' => $request->input('preorder_discount_price') ?: 0,
            ];

            if ($input['status_payment'] != StatusPaymentEnum::PAID) {
                $input['paid_at'] = null;
            }

            $preorder = new Preorder();
            $preorder->fill($input);
            $preorder->invoice_number = Preorder::nextNoInvoice();

            $totalAmountDetails = 0;
            $totalDiscountDetails = 0;
            if ($preorder->save()) {
                $preorderDetails = collect($request->input('preorder_details'));
                foreach ($preorderDetails as $detail) {
                    $preorderDetail = new PreorderDetail();
                    $totalPrice = $detail['qty'] * $detail['price'];
                    $totalDiscount = $detail['qty'] * ($detail['discount'] ?: 0);
                    $preorderDetail->fill([
                        'preorder_id' => $preorder->id,
                        'product_id' => $detail['product_id'],
                        'price' => $detail['price'],
                        'qty' => $detail['qty'],
                        'discount_description' => $detail['discount_description'],
                        'discount' => $detail['discount'],
                        'total_price' => $totalPrice,
                        'total_discount' => $totalDiscount,
                        'total' => $totalPrice - $totalDiscount,
                    ]);

                    $preorderDetail->save();
                    $totalAmountDetails += $totalPrice;
                    $totalDiscountDetails += $totalDiscount;
                }

                if ($request->preorder_resi) {
                    $preorder->loadMissing([
                        'customer.user',
                        'customer_address',
                    ]);
                    $shipping = new PreorderShipping();
                    $shipping->fill([
                        'preorder_id' => $preorder->id,
                        'resi' => str($request->preorder_resi)->trim()->upper(),
                        'expedition_id' => $request->preorder_expedition_id,
                        'name' => $preorder->customer->user->name ?? '',
                        'email' => $preorder->customer->user->email ?? '',
                        'phone' => $preorder->customer->user->phone ?? '',
                        'address' => $preorder->customer->user->address ?? '',
                        'province_id' => $preorder->customer_address->province_id,
                        'regency_id' => $preorder->customer_address->regency_id,
                        'district_id' => $preorder->customer_address->district_id,
                        'village_id' => $preorder->customer_address->village_id,
                        'shipping_price' => $preorder->shipping_price,
                    ]);
                    $shipping->save();
                }
            }

            $preorder->total_amount_details = $totalAmountDetails;
            $preorder->total_discount_details = $totalDiscountDetails;
            $preorder->subtotal = $totalAmountDetails - $totalDiscountDetails;

            $discountPrice = $preorder->discount_price;
            if ($preorder->discount_type == DiscountTypeEnum::DISCOUNT_PERCENTAGE) {
                $discountPrice = $preorder->subtotal * ($preorder->discount_percentage / 100);
            }

            $preorder->total_amount = $preorder->subtotal - $discountPrice;

            if ($preorder->tax == TaxEnum::PPN_10) {
                $preorder->tax_amount = $preorder->total_amount * 0.1;
            } elseif ($preorder->tax == TaxEnum::GST_6) {
                $preorder->tax_amount = $preorder->total_amount * 0.06;
            } elseif ($preorder->tax == TaxEnum::VAT_20) {
                $preorder->tax_amount = $preorder->total_amount * 0.2;
            }
            $preorder->save();

            DB::commit();

            app()->make(WhatsappService::class)->sentCreatedPreorderMessage(
                $preorder->customer->user->phone_number ?? '',
                $preorder
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

        return view('admin.preorder.show', [
            'preorder' => $preorder,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function detail(string $id)
    {
        $preorder = Preorder::with([
            'customer_address',
            'collector',
            'createdBy',
            'customer.user',
            'customer.addresses',
            'branch',
            'shipping',
            'details.product',
        ])->find($id);

        if (is_null($preorder)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        return new PreorderResource($preorder);
    }

    /**
     * Display the specified resource.
     */
    public function edit(string $id)
    {
        $preorder = Preorder::with([
            'customer_address',
            'collector',
            'createdBy',
            'customer.user',
            'customer.addresses',
            'branch',
            'shipping',
            'details.product',
        ])->find($id);

        if (is_null($preorder)) {
            return abort(404);
        }

        return view('admin.preorder._form', [
            'preorder' => $preorder,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PreorderStoreUpdateRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $input = [
                'date' => Carbon::parse($request->input('preorder_date'))->toDateString(),
                'paid_at' => $request->input('preorder_paid_at') ? Carbon::parse($request->input('preorder_paid_at'))->toDateString() : null,
                'branch_id' => $request->input('preorder_branch_id'),
                'collector_id' => $request->input('preorder_collector_id'),
                'customer_id' => $request->input('preorder_customer_id'),
                'customer_address_id' => $request->input('preorder_customer_address_id'),
                'status' => $request->input('preorder_status'),
                'status_payment' => $request->input('preorder_status_payment'),
                'method_payment' => $request->input('preorder_method_payment'),
                'marketing' => $request->input('preorder_marketing'),
                'tax' => $request->input('preorder_tax'),
                'zone' => $request->input('preorder_zone'),
                'notes' => $request->input('preorder_notes') ? htmlentities($request->input('preorder_notes')) : null,
                'notes_staff' => $request->input('preorder_notes_staff') ? htmlentities($request->input('preorder_notes_staff')) : null,
                'shipping_price' => $request->input('preorder_shipping_price', 0) ?: 0,
                'discount_type' => $request->input('preorder_discount_type', DiscountTypeEnum::DISCOUNT_NO),
                'discount_percentage' => $request->input('preorder_discount_percentage') ?: 0,
                'discount_price' => $request->input('preorder_discount_price') ?: 0,
            ];

            if ($input['status_payment'] != StatusPaymentEnum::PAID) {
                $input['paid_at'] = null;
            }

            $preorder = Preorder::with('details', 'shipping')->find($id);
            if (empty($preorder)) {
                return response()->json([
                    'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                ]);
            }

            $preorder->fill($input);

            $totalAmountDetails = 0;
            $totalDiscountDetails = 0;
            if ($preorder->save()) {
                $preorderDetails = collect($request->input('preorder_details'));
                $detailIds = [];
                foreach ($preorderDetails as $detail) {
                    $preorderDetail = $preorder->details->where('product_id', $detail['product_id'])->first();
                    if (empty($preorderDetail)) {
                        $preorderDetail = new PreorderDetail();
                    }
                    $totalPrice = $detail['qty'] * $detail['price'];
                    $totalDiscount = $detail['qty'] * ($detail['discount'] ?: 0);
                    $preorderDetail->fill([
                        'preorder_id' => $preorder->id,
                        'product_id' => $detail['product_id'],
                        'price' => $detail['price'],
                        'qty' => $detail['qty'],
                        'discount_description' => $detail['discount_description'],
                        'discount' => $detail['discount'],
                        'total_price' => $totalPrice,
                        'total_discount' => $totalDiscount,
                        'total' => $totalPrice - $totalDiscount,
                    ]);

                    $preorderDetail->save();
                    $detailIds[] = $preorderDetail->id;
                    $totalAmountDetails += $totalPrice;
                    $totalDiscountDetails += $totalDiscount;
                }

                if ($detailIds) {
                    $preorder->details()->whereNotIn('id', $detailIds)->forceDelete();
                }

                if ($request->preorder_resi) {
                    $preorder->loadMissing([
                        'customer.user',
                        'customer_address',
                    ]);
                    $shipping = $preorder->shipping ?: new PreorderShipping();
                    $shipping->fill([
                        'preorder_id' => $preorder->id,
                        'resi' => str($request->preorder_resi)->trim()->upper(),
                        'expedition_id' => $request->preorder_expedition_id,
                        'name' => $preorder->customer->user->name ?? '',
                        'email' => $preorder->customer->user->email ?? '',
                        'phone' => $preorder->customer->user->phone ?? '',
                        'address' => $preorder->customer->user->address ?? '',
                        'province_id' => $preorder->customer_address->province_id,
                        'regency_id' => $preorder->customer_address->regency_id,
                        'district_id' => $preorder->customer_address->district_id,
                        'village_id' => $preorder->customer_address->village_id,
                        'shipping_price' => $preorder->shipping_price,
                    ]);
                    $shipping->save();
                } else {
                    if ($preorder->shipping) {
                        $preorder->shipping->delete();
                    }
                }
            }

            $preorder->total_amount_details = $totalAmountDetails;
            $preorder->total_discount_details = $totalDiscountDetails;
            $preorder->subtotal = $totalAmountDetails - $totalDiscountDetails;

            $discountPrice = $preorder->discount_price;
            if ($preorder->discount_type == DiscountTypeEnum::DISCOUNT_PERCENTAGE) {
                $discountPrice = $preorder->subtotal * ($preorder->discount_percentage / 100);
            }

            $preorder->total_amount = $preorder->subtotal - $discountPrice;

            if ($preorder->tax == TaxEnum::PPN_10) {
                $preorder->tax_amount = $preorder->total_amount * 0.1;
            } elseif ($preorder->tax == TaxEnum::GST_6) {
                $preorder->tax_amount = $preorder->total_amount * 0.06;
            } elseif ($preorder->tax == TaxEnum::VAT_20) {
                $preorder->tax_amount = $preorder->total_amount * 0.2;
            }
            $preorder->save();

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
    public function update_discount(PreorderUpdateDiscountRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $preorder = Preorder::with('shipping')->find($id);
            if (empty($preorder)) {
                return response()->json([
                    'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                ]);
            }

            $preorder->fill([
                'shipping_price' => $request->input('preorder_shipping_price') ?: 0,
                'discount_type' => $request->input('preorder_discount_type', DiscountTypeEnum::DISCOUNT_NO),
                'discount_percentage' => $request->input('preorder_discount_percentage') ?: 0,
                'discount_price' => $request->input('preorder_discount_price') ?: 0,
            ]);

            $discountPrice = $preorder->discount_price;
            if ($preorder->discount_type == DiscountTypeEnum::DISCOUNT_PERCENTAGE) {
                $discountPrice = $preorder->subtotal * ($preorder->discount_percentage / 100);
            }

            $preorder->total_amount = $preorder->subtotal - $discountPrice;
            $preorder->save();

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
    public function update_status(PreorderUpdateStatusRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $preorder = Preorder::with('shipping')->find($id);
            if (empty($preorder)) {
                return response()->json([
                    'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                ]);
            }

            $preorder->fill([
                'status' => $request->input('preorder_status'),
                'status_payment' => $request->input('preorder_status_payment'),
                'method_payment' => $request->input('preorder_method_payment'),
                'marketing' => $request->input('preorder_marketing'),
                'paid_at' => $request->input('preorder_paid_at') ? Carbon::parse($request->input('preorder_paid_at'))->toDateString() : null,
            ]);

            if ($preorder->status_payment != StatusPaymentEnum::PAID) {
                $preorder->paid_at = null;
            }

            $preorder->save();

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
        $preorder = Preorder::find($id);

        if (is_null($preorder)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            $preorder->shipping()->delete();
            $preorder->details()->delete();
            $preorder->delete();
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
        $preorder = Preorder::with([
            'shipping.expedition',
        ])->find($id);

        if (is_null($preorder)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        $detailTrack = null;
        if (optional($preorder->shipping)->expedition) {
            $expedition = $preorder->shipping->expedition;
            $track = $this->trackExpeditionService->track(
                $expedition->courier,
                $preorder->shipping->resi
            );

            if ($track) {
                $detailTrack = $track['data'];
            }
        }

        return view('admin.preorder.track', [
            'preorder' => $preorder,
            'detailTrack' => $detailTrack,
        ]);
    }

    public function purchase_order($id)
    {
        $preorder = Preorder::with([
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
            'details' => function ($qDetail) {
                $qDetail->whereRaw('qty != qty_order');
            },
            'details.product',
        ])->find($id);

        return view('admin.preorder.print.purchasing', [
            'preorder' => $preorder,
        ]);
    }

    public function faktur($id)
    {
        $preorder = Preorder::with([
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
            'details' => function ($qDetail) {
                $qDetail->whereRaw('qty != qty_order');
            },
            'details.product',
        ])->find($id);

        return view('admin.preorder.print.faktur', [
            'preorder' => $preorder,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function export(Request $request)
    {
        $query = Preorder::with([
            'customer.user',
            'customer_address',
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

        if ($q = $request->input('1')) {
            $query->where(function ($q2) use ($q) {
                $q2->whereLike('invoice_number', $q);
            });
        }

        $preorders = $query->get();

        return Excel::download(new PreorderExport($preorders), 'Preorder.xlsx');
    }
}
