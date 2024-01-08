<?php

namespace App\Services;

use App\Models\PreorderDetail;
use App\Models\Product;

class ProductService
{
    /**
     * Get info total product ready
     *
     * **/
    public function getCountReady(): int
    {
        return Product::where('stock', '>', 0)->count();
    }

    /**
     * Get info total product with limit stock
     *
     * @param $limit limit stock product
     * **/
    public function getCountLimit($limit = 10): int
    {
        return Product::where('stock', '<=', $limit)->where('stock', '>', 0)->count();
    }

    /**
     * Get info total product empty
     *
     * **/
    public function getCountEmpty(): int
    {
        return Product::where('stock', '<=', 0)->count();
    }

    /**
     * Get info total product empty
     *
     * **/
    public function getCountPreorder(): int
    {
        $query = Product::addSelect([
            // Key is the alias, value is the sub-select
            'stock_need' => PreorderDetail::query()
                // You can use eloquent methods here
                ->selectRaw('(sum(qty) - sum(qty_order))')
                ->whereColumn('product_id', 'products.id')
                ->whereRaw('qty != qty_order')
                ->groupBy('product_id'),
        ])
            ->havingRaw('stock < stock_need')
            ->groupBy('products.id');

        return $query->count();
    }
}
