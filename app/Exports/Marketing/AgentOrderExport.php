<?php

namespace App\Exports\Marketing;

use App\Enums\Order\StatusEnum;
use App\Enums\Preorder\StatusPaymentEnum;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class AgentOrderExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithMapping
{
    protected $preorders;

    protected $no = 1;

    protected $noOrder = 1;

    protected $lastPreorderId = null;

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
        if ($this->lastPreorderId != $preorder['preorder_id']) {
            $this->noOrder = 1;
        }

        if (isset($preorder['id'])) {
            return [
                $this->noOrder++,
                $preorder['date'],
                $preorder['receiver_name'],
                $preorder['invoice_number'],
                StatusEnum::fromValue($preorder['status'])->getLabel(),
                StatusPaymentEnum::fromValue($preorder['status_payment'])->getLabel(),
                $preorder['total_amount'],
            ];
        }

        $this->lastPreorderId = $preorder['preorder_id'];

        return [
            $this->no++,
            $preorder['preorder_date'],
            $preorder['preorder_receiver_name'],
            $preorder['preorder_invoice_number'],
            '',
            '',
            $preorder['preorder_total_amount'],
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Penerima',
            'No Faktur',
            'Status Order',
            'Status Pembayaran',
            'Nominal',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}
