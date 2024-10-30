<?php

namespace App\Exports\Template;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductImportExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect([
            [
                'category' => 'Buku Pintar',
                'name' => 'RPUL',
                'code' => 'CO-0001',
                'stock' => '1',
                'price' => '200000',
                'price_zone_2' => '200000',
                'price_zone_3' => '200000',
                'price_zone_4' => '200000',
                'price_zone_5' => '200000',
                'price_zone_6' => '200000',
                'description' => 'Produk ini cocok untuk anak sekolah',
            ],
        ]);
    }

    /**
     * @var Invoice
     */
    public function map($product): array
    {
        return [
            $product['category'],
            $product['name'],
            $product['code'],
            $product['stock'],
            $product['price'],
            $product['price_zone_2'],
            $product['price_zone_3'],
            $product['price_zone_4'],
            $product['price_zone_5'],
            $product['price_zone_6'],
            $product['description'],
        ];
    }

    public function headings(): array
    {
        return [
            'Kategori',
            'Nama Produk',
            'Kode Produk',
            'Stok Produk',
            'Harga Zona 1',
            'Harga Zona 2',
            'Deskripsi Produk',
        ];
    }
}
