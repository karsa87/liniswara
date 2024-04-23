<?php

namespace App\Http\Resources\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionListByMarketingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $customer = null;
        if (
            $this->customer
            && $this->customer->user
        ) {
            $customer = [
                'id' => $this->customer->id,
                'name' => $this->customer->user->name,
            ];
        }

        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'date' => $this->date,
            'customer' => $customer,
            'total_amount' => $this->total_amount ?? 0,
        ];
    }
}
