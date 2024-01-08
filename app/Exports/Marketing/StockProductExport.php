<?php

namespace App\Exports\Marketing;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class StockProductExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithMapping
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
            $product['name'],
            $product['code'],
            $product['qty'],
            $product['stock'],
            $product['price'],
            $product['total'],
            $product['estimation_date'],
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Produk',
            'Kode Produk',
            'Jumlah Produk',
            'Stok Produk',
            'Harga Satuan',
            'Total',
            'Estimasi Ready',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'G' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
}
