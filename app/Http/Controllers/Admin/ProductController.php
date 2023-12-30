<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProductDiscountTypeEnum;
use App\Exports\ProductExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\ProductStoreUpdateRequest;
use App\Http\Resources\Admin\Product\ProductResource;
use App\Models\Product;
use App\Services\StockHistoryLogService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
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
            $query = Product::with([
                'thumbnail',
                'categories',
            ])
                ->offset($request->get('start', 0))
                ->limit($request->get('length', 10));

            if ($q = $request->input('search.value')) {
                $query->where(function ($qProduct) use ($q) {
                    $qProduct->whereLike('name', $q)
                        ->orWhereLike('name', $q);
                });
            }

            if ($categoryId = $request->input('search.category_id')) {
                $query->whereHas('categories', function ($qCategory) use ($categoryId) {
                    $qCategory->where('id', $categoryId);
                });
            }

            $products = $query->get();

            $totalAll = Product::count();

            return ProductResource::collection($products)->additional([
                'recordsTotal' => $totalAll,
                'recordsFiltered' => $products->count(),
            ]);
        }

        return view(
            'admin.product.index'
        );
    }

    public function create()
    {
        return view('admin.product._form', [
            'product' => new Product(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            $input = [
                'name' => $request->input('product_name'),
                'code' => $request->input('product_code'),
                'description' => $request->input('product_description') ? htmlentities($request->input('product_description')) : null,
                'slug' => $request->input('slug'),
                'stock' => $request->input('product_stock'),
                'price' => $request->input('product_price'),
                'price_zone_2' => $request->input('product_price_zone_2'),
                'discount_price' => $request->input('product_discount_price'),
                'discount_percentage' => $request->input('product_discount_percentage'),
                'is_best_seller' => $request->input('product_is_best_seller', false),
                'is_recommendation' => $request->input('product_is_recommendation', false),
                'is_new' => $request->input('product_is_new', false),
                'is_special_discount' => $request->input('product_is_special_discount', false),
                'is_active' => $request->input('product_is_active', false),
                'thumbnail_id' => $request->input('product_thumbnail_id'),
                'discount_type' => $request->input('product_discount_type', ProductDiscountTypeEnum::DISCOUNT_NO),
            ];

            switch ($input['discount_type']) {
                case ProductDiscountTypeEnum::DISCOUNT_PERCENTAGE:
                    $input['discount_price'] = null;
                    break;

                case ProductDiscountTypeEnum::DISCOUNT_PRICE:
                    $input['discount_percentage'] = null;
                    break;

                default:
                    $input['discount_price'] = null;
                    $input['discount_percentage'] = null;
                    break;
            }

            $product = new Product();
            $product->fill($input);

            $categories = $request->input('product_category_id');
            if (
                $product->save()
                && $categories
            ) {
                $product->categories()->sync($categories);
            }

            $this->stockLogService->logStockIn(
                $product,
                $product->id,
                0,
                $product->stock
            );

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
        $product = Product::with([
            'thumbnail',
            'categories',
            'stock_histories' => function ($qStory) {
                $qStory->with(['user', 'product'])->orderBy('created_at', 'DESC');
            },
        ])->find($id);

        if (is_null($product)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        return view('admin.product.show', [
            'product' => $product,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::with([
            'thumbnail',
            'categories',
        ])->find($id);

        if (is_null($product)) {
            return abort(404);
        }

        return view('admin.product._form', [
            'product' => $product,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductStoreUpdateRequest $request, string $id)
    {
        $product = Product::find($id);

        if (is_null($product)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        DB::beginTransaction();
        try {
            $input = [
                'name' => $request->input('product_name'),
                'code' => $request->input('product_code'),
                'description' => $request->input('product_description') ? htmlentities($request->input('product_description')) : null,
                'slug' => $request->input('slug'),
                'stock' => $request->input('product_stock'),
                'price' => $request->input('product_price'),
                'price_zone_2' => $request->input('product_price_zone_2'),
                'discount_price' => $request->input('product_discount_price'),
                'discount_percentage' => $request->input('product_discount_percentage'),
                'is_best_seller' => $request->input('product_is_best_seller', false),
                'is_recommendation' => $request->input('product_is_recommendation', false),
                'is_new' => $request->input('product_is_new', false),
                'is_special_discount' => $request->input('product_is_special_discount', false),
                'is_active' => $request->input('product_is_active', false),
                'thumbnail_id' => $request->input('product_thumbnail_id'),
                'discount_type' => $request->input('product_discount_type', ProductDiscountTypeEnum::DISCOUNT_NO),
            ];

            switch ($input['discount_type']) {
                case ProductDiscountTypeEnum::DISCOUNT_PERCENTAGE:
                    $input['discount_price'] = null;
                    break;

                case ProductDiscountTypeEnum::DISCOUNT_PRICE:
                    $input['discount_percentage'] = null;
                    break;

                default:
                    $input['discount_price'] = null;
                    $input['discount_percentage'] = null;
                    break;
            }

            $oldStock = $product->stock;
            $product->fill($input);

            $categories = $request->input('product_category_id');
            if (
                $product->save()
                && $categories
            ) {
                $product->categories()->sync($categories);
            }

            if ($oldStock > $product->stock) {
                $this->stockLogService->logStockOut(
                    $product,
                    $product->id,
                    $oldStock,
                    $oldStock - $product->stock
                );
            } elseif ($oldStock < $product->stock) {
                $this->stockLogService->logStockIn(
                    $product,
                    $product->id,
                    $oldStock,
                    $product->stock - $oldStock
                );
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::with('thumbnail')->find($id);

        if (is_null($product)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            if ($product->thumbnail) {
                $product->thumbnail->delete();
            }
            $product->stock_histories()->delete();
            $product->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }

    public function ajax_list_product(Request $request)
    {
        $params = $request->all();
        $excludeIds = collect(json_decode($request->get('exid', '[]')));
        $excludeIds->unique()->filter();

        $query = Product::query();

        $q = array_key_exists('query', $params) ? $params['query'] : (array_key_exists('q', $params) ? $params['q'] : '');
        if ($q) {
            $query->whereLike('name', $q)
                ->orWhereLike('code', $q);
        }

        if ($excludeIds->isNotEmpty()) {
            $query->whereNotIn('id', $excludeIds);
        }

        $products = $query->limit(20)->get();
        $list = [];
        foreach ($products as $product) {
            $list[] = [
                'id' => $product->id,
                'text' => $product->name,
                'name' => $product->name,
                'code' => $product->code,
                'stock' => $product->stock,
                'price' => $product->price,
                'discount' => $product->discount,
                'price_zone_2' => $product->price_zone_2,
                'discount_zone_2' => $product->discount_zone_2,
                'discount_description' => $product->discount_description,
            ];
        }

        return response()->json([
            'items' => $list,
            'count' => count($list),
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function export(Request $request)
    {
        $query = Product::with([
            'categories',
        ]);

        if ($request->search_category_id) {
            $qCategoryId = $request->search_category_id;
            $query->whereHas('categories', function ($qCategory) use ($qCategoryId) {
                $qCategory->where('category_id', $qCategoryId);
            });
        }

        if ($q = $request->input('q')) {
            $query->where(function ($q2) use ($q) {
                $q2->whereLike('name', $q);
            });
        }

        $products = $query->get();

        return Excel::download(new ProductExport($products), 'Produk.xlsx');
    }
}
