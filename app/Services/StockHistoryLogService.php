<?php

namespace App\Services;

use App\Models\StockHistory;

class StockHistoryLogService
{
    /**
     * Create log history stock in of product
     *
     * @param  mixed  $from object the transaction from
     * @param  int  $productId product unique ID
     * @param  float  $stockCurrent Stock current of product
     * @param  float  $stockIn Stock in to product
     * @return void
     * **/
    public function logStockIn(
        mixed $from,
        int $productId,
        float $stockCurrent,
        float $stockIn
    ) {
        $this->createStockHistory(
            from: $from,
            productId: $productId,
            stockCurrent: $stockCurrent,
            stockIn: $stockIn
        );
    }

    /**
     * Create log history stock out of product
     *
     * @param  mixed  $from object the transaction from
     * @param  int  $productId product unique ID
     * @param  float  $stockCurrent Stock current of product
     * @param  float  $stockOut Stock out from product
     * @return void
     * **/
    public function logStockOut(
        mixed $from,
        int $productId,
        float $stockCurrent,
        float $stockOut
    ) {
        $this->createStockHistory(
            from: $from,
            productId: $productId,
            stockCurrent: $stockCurrent,
            stockOut: $stockOut
        );
    }

    /**
     * Create log history of stock product
     *
     * @param  mixed  $from object the transaction from
     * @param  int  $productId product unique ID
     * @param  float  $stockCurrent Stock current of product
     * @param  float  $stockIn Stock in to product
     * @param  float  $stockOut Stock out from product
     * @return void
     * **/
    private function createStockHistory(
        mixed $from,
        int $productId,
        float $stockCurrent = 0,
        float $stockIn = 0,
        float $stockOut = 0
    ) {
        StockHistory::create([
            'product_id' => $productId,
            'stock_old' => $stockCurrent,
            'stock_in' => $stockIn,
            'stock_out' => $stockOut,
            'stock_new' => $stockCurrent + ($stockIn ?: 0) - ($stockOut ?: 0),
            'from_id' => $from->id,
            'from_type' => get_class($from),
        ]);
    }
}
