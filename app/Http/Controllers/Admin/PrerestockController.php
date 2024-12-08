<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PrerestockTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Prerestock\PrerestockMigrateRequest;
use App\Http\Requests\Admin\Prerestock\PrerestockStoreUpdateRequest;
use App\Http\Resources\Admin\Prerestock\PrerestockResource;
use App\Models\PreorderDetail;
use App\Models\Prerestock;
use App\Models\PrerestockDetail;
use App\Models\Restock;
use App\Models\RestockDetail;
use App\Services\StockHistoryLogService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PrerestockController extends Controller
{
    public function __construct(
        private StockHistoryLogService $stockLogService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Prerestock::with([
                'createdBy',
                'branch',
                'details',
            ]);

            if ($q = $request->input('search.value')) {
                $query->where(function ($q2) use ($q) {
                    $q2->whereLike('notes', $q);
                });
            }

            if ($branchId = $request->input('search.branch_id')) {
                $query->where('branch_id', $branchId);
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
            $prerestocks = $query->offset($request->get('start', 0))
                ->limit($request->get('length', 10))
                ->get();

            return PrerestockResource::collection($prerestocks)->additional([
                'recordsTotal' => $totalAll,
                'recordsFiltered' => $totalAll,
            ]);
        }

        return view(
            'admin.prerestock.index'
        );
    }

    public function create()
    {
        return view('admin.prerestock._form', [
            'prerestock' => new Prerestock(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PrerestockStoreUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            $input = [
                'label' => $request->input('prerestock_label'),
                'branch_id' => $request->input('prerestock_branch_id'),
                'notes' => $request->input('prerestock_notes') ? htmlentities($request->input('prerestock_notes')) : null,
            ];

            $prerestock = new Prerestock();
            $prerestock->fill($input);

            if ($prerestock->save()) {
                $prerestockDetails = collect($request->input('prerestock_details'));
                foreach ($prerestockDetails as $detail) {
                    $prerestockDetail = new PrerestockDetail();
                    $prerestockDetail->fill([
                        'prerestock_id' => $prerestock->id,
                        'product_id' => $detail['product_id'],
                        'type' => PrerestockTypeEnum::STOCK_ADD,
                        'qty' => $detail['qty'],
                    ]);

                    $prerestockDetail->save();
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

        return response()->json()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $prerestock = Prerestock::with([
            'createdBy',
            'branch',
            'details.product',
        ])->find($id);

        if (is_null($prerestock)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        return view('admin.prerestock.show', [
            'prerestock' => $prerestock,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function edit(string $id)
    {
        $prerestock = Prerestock::with([
            'createdBy',
            'branch',
            'details.product' => function ($q) {
                $q->addSelect([
                    // Key is the alias, value is the sub-select
                    'total_stock_need' => PreorderDetail::query()
                        // You can use eloquent methods here
                        ->selectRaw('((sum(IFNULL(qty, 0)) - sum(IFNULL(qty_order, 0))) - IFNULL(products.stock, 0))')
                        ->whereColumn('product_id', 'products.id')
                        ->whereRaw('qty != qty_order'),
                ]);
            },
        ])->find($id);

        if (is_null($prerestock)) {
            return abort(404);
        }

        return view('admin.prerestock._form', [
            'prerestock' => $prerestock,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PrerestockStoreUpdateRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $input = [
                'label' => $request->input('prerestock_label'),
                'branch_id' => $request->input('prerestock_branch_id'),
                'notes' => $request->input('prerestock_notes') ? htmlentities($request->input('prerestock_notes')) : null,
            ];

            $prerestock = Prerestock::find($id);
            if (is_null($prerestock)) {
                return response()->json([
                    'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                ], Response::HTTP_NOT_FOUND);
            }
            $prerestock->fill($input);

            if ($prerestock->save()) {
                $detailIds = collect();
                $prerestockDetails = collect($request->input('prerestock_details'));
                foreach ($prerestockDetails as $detail) {
                    $prerestockDetail = PrerestockDetail::firstOrNew([
                        'prerestock_id' => $prerestock->id,
                        'product_id' => $detail['product_id'],
                    ]);

                    $prerestockDetail->fill([
                        'type' => PrerestockTypeEnum::STOCK_ADD,
                        'qty' => $detail['qty'],
                    ]);

                    $prerestockDetail->save();
                    $detailIds->push($prerestockDetail->id);
                }

                if ($detailIds->count() > 0) {
                    PrerestockDetail::whereNotIn('id', $detailIds)->where('prerestock_id', $prerestock->id)->delete();
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

        return response()->json()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $prerestock = Prerestock::with('details.product', 'details.stockHistory')->find($id);

        if (is_null($prerestock)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            foreach ($prerestock->details as $prerestockDetail) {
                $product = $prerestockDetail->product;
                $oldStock = $product->stock;
                if ($prerestockDetail->is_stock_add) {
                    $product->stock = $oldStock - $prerestockDetail->qty;
                    $this->stockLogService->logStockOut(
                        $prerestock,
                        $product->id,
                        $oldStock,
                        $prerestockDetail->qty
                    );
                }

                if ($prerestockDetail->is_stock_minus) {
                    $product->stock = $oldStock + $prerestockDetail->qty;
                    $this->stockLogService->logStockIn(
                        $prerestock,
                        $product->id,
                        $oldStock,
                        $prerestockDetail->qty
                    );
                }
                $product->save();
                $prerestockDetail->delete();
            }
            $prerestock->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }

    public function migrate($id)
    {
        $prerestock = Prerestock::with([
            'createdBy',
            'branch',
            'details' => function ($q) {
                $q->whereRaw('qty > qty_migrate');
            },
            'details.product' => function ($q) {
                $q->addSelect([
                    // Key is the alias, value is the sub-select
                    'total_stock_need' => PreorderDetail::query()
                        // You can use eloquent methods here
                        ->selectRaw('((sum(IFNULL(qty, 0)) - sum(IFNULL(qty_order, 0))) - IFNULL(products.stock, 0))')
                        ->whereColumn('product_id', 'products.id')
                        ->whereRaw('qty != qty_order'),
                ]);
            },
        ])->find($id);

        if (is_null($prerestock)) {
            return abort(404);
        }

        return view('admin.prerestock.migrate', [
            'prerestock' => $prerestock,
        ]);
    }

    public function submit_migrate(PrerestockMigrateRequest $request, $id)
    {
        $details = collect($request->get('prerestock_details') ?? []);
        $prerestock = Prerestock::with([
            'details' => function ($q) use ($details) {
                $q->whereIn('product_id', $details->pluck('product_id'));
            },
        ])->find($id);

        if (is_null($prerestock)) {
            return abort(404);
        }

        DB::beginTransaction();
        try {
            $restock = new Restock();
            $restock->branch_id = $prerestock->branch_id;
            $restock->created_by = auth()->user()->id;
            $restock->notes = sprintf('%s - %s', $prerestock->label, $request->get('prerestock_notes'));
            $restock->prerestock_id = $prerestock->id;
            $restock->date = Carbon::now();
            if ($restock->save()) {
                foreach ($prerestock->details as $prerestockDetail) {
                    $detail = $details->firstWhere('product_id', $prerestockDetail->product_id);
                    if (is_null($detail)) {
                        continue;
                    }

                    $restockDetail = new RestockDetail();
                    $restockDetail->restock_id = $restock->id;
                    $restockDetail->product_id = $prerestockDetail->product_id;
                    $restockDetail->qty = $detail['qty'];
                    $restockDetail->type = $prerestockDetail->type;
                    $restockDetail->prerestock_detail_id = $prerestockDetail->id;

                    if ($restockDetail->save()) {
                        $prerestockDetail->qty_migrate = $prerestockDetail->qty_migrate + $restockDetail->qty;
                        $prerestockDetail->save();

                        $product = $prerestockDetail->product;
                        $oldStock = $product->stock;
                        if ($restockDetail->is_stock_add) {
                            $product->stock = $oldStock + $restockDetail->qty;
                            $this->stockLogService->logStockIn(
                                $restock,
                                $product->id,
                                $oldStock,
                                $restockDetail->qty
                            );
                        }

                        if ($restockDetail->is_stock_minus) {
                            $product->stock = $oldStock - $restockDetail->qty;
                            $this->stockLogService->logStockOut(
                                $restock,
                                $product->id,
                                $oldStock,
                                $restockDetail->qty
                            );
                        }
                        $product->save();
                    }
                }

                $prerestock->save();

                DB::commit();

                return response()->json();
            }
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'message' => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
