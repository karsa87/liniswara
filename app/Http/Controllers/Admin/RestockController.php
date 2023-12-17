<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Restock\RestockStoreUpdateRequest;
use App\Http\Resources\Admin\Restock\RestockResource;
use App\Models\Product;
use App\Models\Restock;
use App\Models\RestockDetail;
use App\Services\StockHistoryLogService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RestockController extends Controller
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
            $query = Restock::with([
                'createdBy',
                'branch',
            ])
                ->offset($request->get('start', 0))
                ->limit($request->get('length', 10));

            if ($q = $request->input('search.value')) {
                $query->where(function ($q2) use ($q) {
                    $q2->whereLike('notes', $q);
                });
            }

            if ($branchId = $request->input('search.branch_id')) {
                $query->where('branch_id', $branchId);
            }

            $restocks = $query->get();

            $totalAll = Restock::count();

            return RestockResource::collection($restocks)->additional([
                'recordsTotal' => $totalAll,
                'recordsFiltered' => $restocks->count(),
            ]);
        }

        return view(
            'admin.restock.index'
        );
    }

    public function create()
    {
        return view('admin.restock._form', [
            'restock' => new Restock(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RestockStoreUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            $input = [
                'date' => Carbon::parse($request->input('restock_date'))->toDateString(),
                'branch_id' => $request->input('restock_branch_id'),
                'notes' => $request->input('restock_notes') ? htmlentities($request->input('restock_notes')) : null,
            ];

            $restock = new Restock();
            $restock->fill($input);

            if ($restock->save()) {
                $restockDetails = collect($request->input('restock_details'));
                $products = Product::whereIn('id', $restockDetails->pluck('product_id'))->get()->keyBy('id');
                foreach ($restockDetails as $detail) {
                    $restockDetail = new RestockDetail();
                    $restockDetail->fill([
                        'restock_id' => $restock->id,
                        'product_id' => $detail['product_id'],
                        'type' => $detail['type'],
                        'qty' => $detail['qty'],
                    ]);

                    $restockDetail->save();

                    if ($products->has($restockDetail->product_id)) {
                        $product = $products[$restockDetail->product_id];
                        $oldStock = $product->stock;
                        if ($restockDetail->is_stock_add) {
                            $product->stock = $oldStock + $restockDetail->qty;
                            $this->stockLogService->logStockIn(
                                $restock,
                                $restock->id,
                                $oldStock,
                                $restockDetail->qty
                            );
                        }

                        if ($restockDetail->is_stock_minus) {
                            $product->stock = $oldStock - $restockDetail->qty;
                            $this->stockLogService->logStockOut(
                                $restock,
                                $restock->id,
                                $oldStock,
                                $restockDetail->qty
                            );
                        }
                        $product->save();
                    }
                }
            }

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
        $restock = Restock::with([
            'createdBy',
            'branch',
            'details.product',
        ])->find($id);

        if (is_null($restock)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        return new RestockResource($restock);
    }

    /**
     * Display the specified resource.
     */
    public function edit(string $id)
    {
        $restock = Restock::with([
            'createdBy',
            'branch',
            'details',
        ])->find($id);

        if (is_null($restock)) {
            return abort(404);
        }

        return view('admin.restock._form', [
            'restock' => $restock,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RestockStoreUpdateRequest $request, string $id)
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $restock = Restock::with('details.product', 'details.stockHistory')->find($id);

        if (is_null($restock)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            foreach ($restock->details as $restockDetail) {
                $product = $restockDetail->product;
                $oldStock = $product->stock;
                if ($restockDetail->is_stock_add) {
                    $product->stock = $oldStock - $restockDetail->qty;
                    $this->stockLogService->logStockOut(
                        $restock,
                        $restock->id,
                        $oldStock,
                        $restockDetail->qty
                    );
                }

                if ($restockDetail->is_stock_minus) {
                    $product->stock = $oldStock + $restockDetail->qty;
                    $this->stockLogService->logStockIn(
                        $restock,
                        $restock->id,
                        $oldStock,
                        $restockDetail->qty
                    );
                }
                $product->save();
                $restockDetail->delete();
            }
            $restock->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }
}
