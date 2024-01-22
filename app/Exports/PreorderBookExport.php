<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PreorderBookExport implements FromCollection, WithHeadings, WithMapping
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->products;
    }

    /**
     * @var Invoice
     */
    public function map($product): array
    {
        return [
            $product->code,
            $product->name,
            ((int) $product->stock),
            ((int) $product->stock_need),
            ((int) $product->total_stock_need <= 0 ? 0 : $product->total_stock_need),
            // ((int) $product->total_stock_more <= 0 ? 0 : $product->total_stock_more),
        ];
    }

    public function headings(): array
    {
        return [
            'Kode',
            'Produk',
            'Stok Tersedia',
            'Stok Yang Diorder',
            'Stok Kosong',
            // 'Stok Yang Belum Diorder',
        ];
    }
}
