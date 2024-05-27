<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\ListRequest;
use App\Http\Resources\Api\Product\ProductResource;
use App\Models\Product;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListRequest $request)
    {
        $query = Product::with([
            'thumbnail',
            'categories.parent',
        ]);

        if ($q = $request->input('q')) {
            $query->where(function ($qProduct) use ($q) {
                $qProduct->whereLike('name', $q)
                    ->orWhereLike('code', $q);
            });
        }

        if ($categoryId = $request->input('category_id')) {
            $query->whereHas('categories', function ($qCategory) use ($categoryId) {
                $qCategory->where('id', $categoryId);
            });
        }

        if (is_numeric($request->input('order_column'))) {
            $column = $request->input('order_column');
            $columnData = $request->input("columns.$column.data");
            $sorting = $request->input('order_sort');

            if ($sorting == 'desc') {
                $query->orderBy($columnData, 'DESC');
            } else {
                $query->orderBy($columnData, 'ASC');
            }
        }

        $products = $query->paginate($request->get('limit', 15));

        return ProductResource::collection($products);
    }

    /**
     * Display a listing of the resource.
     */
    public function show($id)
    {
        $product = Product::with([
            'thumbnail',
            'categories.parent',
        ])->find($id);

        if (! $product instanceof Product) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }

        return new ProductResource($product);
    }
}
