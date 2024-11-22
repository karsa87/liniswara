<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            ]);

            if ($q = $request->input('search.value')) {
                $query->where(function ($q2) use ($q) {
                    $q2->whereLike('notes', $q);
                });
            }

            if ($branchId = $request->input('search.branch_id')) {
                $query->where('branch_id', $branchId);
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
                        'type' => $detail['type'],
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
                        'type' => $detail['type'],
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
            'details.product',
        ])->find($id);

        if (is_null($prerestock)) {
            return abort(404);
        }

        DB::beginTransaction();
        try {
            $restock = new Restock();
            $restock->branch_id = $prerestock->branch_id;
            $restock->created_by = auth()->user()->id;
            $restock->notes = $prerestock->notes;
            $restock->date = Carbon::now();
            if ($restock->save()) {
                foreach ($prerestock->details as $prerestockDetail) {
                    $restockDetail = new RestockDetail();
                    $restockDetail->restock_id = $restock->id;
                    $restockDetail->product_id = $prerestockDetail->product_id;
                    $restockDetail->qty = $prerestockDetail->qty;
                    $restockDetail->type = $prerestockDetail->type;

                    if ($restockDetail->save()) {
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

                $prerestock->is_migrate = true;
                $prerestock->restock_id = $restock->id;
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
