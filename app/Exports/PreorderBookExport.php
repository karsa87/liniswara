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
            $product->product->code,
            $product->product->name,
            $product->total_qty,
        ];
    }

    public function headings(): array
    {
        return [
            'Kode',
            'Produk',
            'Jumlah',
        ];
    }
}