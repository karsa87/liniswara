<?php

namespace App\Exports\Template;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerImportExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect([
            [
                'name' => 'Agus Indra',
                'email' => 'agus.indra@gmail.com',
                'company' => 'PT. XXX',
                'phone' => '089878234768',
                'name_address' => 'Agus',
                'address' => 'Bugis Sawojajar',
                'phone_address' => '089878234768',
            ],
        ]);
    }

    /**
     * @var Invoice
     */
    public function map($customer): array
    {
        return [
            $customer['name'],
            $customer['email'],
            $customer['company'],
            $customer['phone'],
            $customer['name_address'],
            $customer['address'],
            $customer['phone_address'],
        ];
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'Perusahaan',
            'Phone',
            'Nama Alamat',
            'Alamat',
            'Phone Alamat',
        ];
    }
}
