<?php

namespace App\Http\Controllers\Marketing;

use App\Exports\Marketing\StockProductExport;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\PreorderService;
use App\Services\ProductService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class StockController extends Controller
{
    const CACHE_DOWNLOAD_EXCEL_STOCK = 'download-check-stock-excel';

    public function __construct(
        private PreorderService $preorderService,
        private ProductService $productService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productPreorder = $this->productService->getCountPreorder();
        $productReady = $this->productService->getCountReady();
        $productLimit = $this->productService->getCountLimit();
        $productEmpty = $this->productService->getCountEmpty();

        $cache = Cache::get(self::CACHE_DOWNLOAD_EXCEL_STOCK);

        return view('marketing.stock.index', [
            'count' => [
                'preorder' => $productPreorder,
                'ready' => $productReady,
                'limit' => $productLimit,
                'empty' => $productEmpty,
            ],
            'cache' => $cache,
        ]);
    }

    public function storeStock(Request $request)
    {
        $productDetails = collect($request->input('product_details', []));

        if ($productDetails->count() <= 0) {
            return response()->json([
                'excel' => '',
                'pdf' => '',
            ]);
        }

        $products = Product::whereIn('id', $productDetails->pluck('product_id'))->get()->keyBy('id');
        $details = collect();
        foreach ($productDetails as $productDetail) {
            if (! $products->has($productDetail['product_id'])) {
                continue;
            }

            $product = $products[$productDetail['product_id']];
            $details->push([
                'product' => $product,
                'name' => $product->name,
                'name' => $product->name,
                'code' => $product->code,
                'stock' => $product->stock,
                'qty' => $productDetail['qty'],
                'price' => $productDetail['price'],
                'total' => $productDetail['price'] * $productDetail['qty'],
                'estimation_date' => $productDetail['estimation_date'] ? $productDetail['estimation_date'] : Carbon::now()->toDateString(),
            ]);
        }

        Cache::put(self::CACHE_DOWNLOAD_EXCEL_STOCK, [
            'zone' => $request->input('zone'),
            'details' => $details,
        ], Carbon::now()->addWeek()->endOfDay());

        return response()->json([
            'excel' => route('marketing.stock.download.excel'),
            'pdf' => '',
        ]);
    }

    public function downloadExcel()
    {
        $record = Cache::get(self::CACHE_DOWNLOAD_EXCEL_STOCK);

        return Excel::download(new StockProductExport($record['details']), 'Stock Produk.xlsx');
    }
}
