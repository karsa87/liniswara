<?php

namespace App\Exports;

use App\Enums\Preorder\StatusEnum;
use App\Enums\Preorder\StatusPaymentEnum;
use App\Enums\Preorder\ZoneEnum;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PreorderExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithMapping
{
    protected $preorders;

    public function __construct($preorders)
    {
        $this->preorders = $preorders;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->preorders;
    }

    /**
     * @var Invoice
     */
    public function map($preorder): array
    {
        $status = StatusEnum::fromValue($preorder->status);
        $statusPayment = StatusPaymentEnum::fromValue($preorder->status_payment);
        $zone = ZoneEnum::fromValue($preorder->zone);

        return [
            Carbon::parse($preorder->date)->toDateString(),
            $preorder->invoice_number,
            optional(optional($preorder->customer)->user)->name,
            optional($preorder->customer_address)->name,
            $zone->getLabel() ?? '',
            $preorder->total_amount,
            $preorder->total_amount + $preorder->shipping_price,
            $status->getLabel() ?? '',
            $statusPayment->getLabel() ?? '',
            strip_tags(html_entity_decode($preorder->notes)),
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal Pesan',
            'No. Faktur',
            'Nama Pemesan',
            'Nama Penerima',
            'Zona',
            'Pesanan Bruto',
            'Pesanan Netto',
            'Status Order',
            'Status Terbayar',
            'Catatan Penjualan',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}
