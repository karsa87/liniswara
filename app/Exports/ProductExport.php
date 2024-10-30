<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ProductExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithMapping
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
        $category = null;
        if ($product->categories) {
            $category = $product->categories->first();
        }

        return [
            optional($category)->full_name,
            $product->code,
            $product->name,
            $product->stock,
            $product->discount,
            $product->discount_description,
            $product->price,
            $product->price_zone_2,
            $product->price_zone_3,
            $product->price_zone_4,
            $product->price_zone_5,
            $product->price_zone_6,
        ];
    }

    public function headings(): array
    {
        return [
            'Kategori',
            'Kode Produk',
            'Nama Produk',
            'Jumlah Produk',
            'Diskon Produk',
            'Keterangan Diskon',
            'Harga Zona 1',
            'Harga Zona 2',
            'Harga Zona 3',
            'Harga Zona 4',
            'Harga Zona 5',
            'Harga Zona 6',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}
