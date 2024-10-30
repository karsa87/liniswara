<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProductDiscountTypeEnum;
use App\Exports\ProductExport;
use App\Exports\Template\ProductImportExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\ProductStoreUpdateRequest;
use App\Http\Resources\Admin\Product\ProductResource;
use App\Jobs\Import\ProductImportJob;
use App\Models\Category;
use App\Models\File;
use App\Models\Import;
use App\Models\Product;
use App\Services\StockHistoryLogService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
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
                'categories.parent',
            ])
                ->withSum('order_details as total_sale', 'qty');

            if ($q = $request->input('search.value')) {
                $query->where(function ($qProduct) use ($q) {
                    $qProduct->whereLike('name', $q)
                        ->orWhereLike('code', $q);
                });
            }

            if ($categoryId = $request->input('search.category_id')) {
                // $query->whereHas('categories', function ($qCategory) use ($categoryId) {
                //     $qCategory->where('id', $categoryId);
                // });
                $category = Category::with('child')->where('id', $categoryId)->first();
                if ($category) {
                    $allCategory = collect();
                    $allCategory->push($category);
                    if ($category->child) {
                        $allCategory = $allCategory->merge($category->child);
                    }

                    $query->whereHas('categories', function ($qCategory) use ($allCategory) {
                        $qCategory->whereIn('category_id', $allCategory->pluck('id'));
                    });
                }
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
            }

            $totalAll = (clone $query)->count();
            $products = $query->offset($request->get('start', 0))
                ->limit($request->get('length', 10))
                ->get();

            return ProductResource::collection($products)->additional([
                'recordsTotal' => $totalAll,
                'recordsFiltered' => $totalAll,
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
                'price_zone_3' => $request->input('product_price_zone_3'),
                'price_zone_4' => $request->input('product_price_zone_4'),
                'price_zone_5' => $request->input('product_price_zone_5'),
                'price_zone_6' => $request->input('product_price_zone_6'),
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
                $product->categories()->sync(array_unique($categories));
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
            'categories.parent',
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
                'price_zone_3' => $request->input('product_price_zone_3'),
                'price_zone_4' => $request->input('product_price_zone_4'),
                'price_zone_5' => $request->input('product_price_zone_5'),
                'price_zone_6' => $request->input('product_price_zone_6'),
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
                $product->categories()->sync(array_unique($categories));
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
        $excludeIds = $excludeIds->unique()->filter();

        $query = Product::query();

        $q = array_key_exists('query', $params) ? $params['query'] : (array_key_exists('q', $params) ? $params['q'] : '');
        if ($q) {
            $query->where(function ($qProduct) use ($q) {
                $qProduct->whereLike('name', $q)
                    ->orWhereLike('code', $q);
            });
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
                'price_zone_3' => $product->price_zone_3,
                'discount_zone_3' => $product->discount_zone_3,
                'price_zone_4' => $product->price_zone_4,
                'discount_zone_4' => $product->discount_zone_4,
                'price_zone_5' => $product->price_zone_5,
                'discount_zone_5' => $product->discount_zone_5,
                'price_zone_6' => $product->price_zone_6,
                'discount_zone_6' => $product->discount_zone_6,
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
            'categories.parent',
        ]);

        if ($request->search_category_id) {
            $categoryId = $request->search_category_id;
            $category = Category::with('child')->where('id', $categoryId)->first();
            if ($category) {
                $allCategory = collect();
                $allCategory->push($category);
                if ($category->child) {
                    $allCategory = $allCategory->merge($category->child);
                }

                $query->whereHas('categories', function ($qCategory) use ($allCategory) {
                    $qCategory->whereIn('category_id', $allCategory->pluck('id'));
                });
            }
        }

        if ($q = $request->input('q')) {
            $query->where(function ($q2) use ($q) {
                $q2->whereLike('name', $q);
            });
        }

        $products = $query->get();

        return Excel::download(new ProductExport($products), 'Produk.xlsx');
    }

    /**
     * Display a listing of the resource.
     */
    public function export_template_import()
    {
        return Excel::download(new ProductImportExport(), 'Template Import Produk.xlsx');
    }

    /**
     * Display a listing of the resource.
     */
    public function import(Request $request)
    {
        $request->validate([
            'product_file' => [
                'required',
                'numeric',
                Rule::exists((new File())->getTable(), 'id'),
            ],
        ]);

        $now = Carbon::now();
        $import = Import::create([
            'name' => sprintf(
                'Produk - %s%s%s',
                $now->format('d'),
                $now->format('m'),
                $now->format('y'),
            ),
            'file_id' => $request->product_file,
            'user_id' => auth()->user()->id,
        ]);

        ProductImportJob::dispatch($import);
    }
}
