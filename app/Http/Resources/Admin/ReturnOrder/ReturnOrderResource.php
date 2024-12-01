<?php

namespace App\Http\Resources\Admin\ReturnOrder;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReturnOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $createdBy = null;
        if ($this->createdBy) {
            $createdBy = [
                'id' => $this->createdBy->id,
                'name' => $this->createdBy->name,
            ];
        }

        $updatedBy = null;
        if ($this->updatedBy) {
            $updatedBy = [
                'id' => $this->updatedBy->id,
                'name' => $this->updatedBy->name,
            ];
        }

        $branch = null;
        if ($this->branch) {
            $branch = [
                'id' => $this->branch->id,
                'name' => $this->branch->name,
            ];
        }

        $order = null;
        if ($this->order) {
            $order = [
                'id' => $this->branch->id,
                'invoice_number' => $this->order->invoice_number,
            ];
        }

        $details = null;
        if (
            $this->whenLoaded('details')
            && $this->whenHas('details')
        ) {
            foreach ($this->details as $detail) {
                $product = null;
                if ($detail->product) {
                    $product = [
                        'id' => $detail->product->id,
                        'name' => $detail->product->name,
                    ];
                }

                $details[] = [
                    'id' => $detail->id,
                    'product' => $product,
                    'qty' => $detail->qty,
                    'price' => $detail->price,
                    'discount_description' => $detail->discount_description,
                    'discount' => $detail->discount,
                    'total_price' => $detail->total_price,
                    'total_discount' => $detail->total_discount,
                    'total' => $detail->total,
                ];
            }
        }

        return [
            'id' => $this->id,
            'date' => Carbon::parse($this->date)->toDateString(),
            'no_return' => $this->no_return,
            'status' => $this->status,
            'notes' => html_entity_decode($this->notes),
            'tax' => $this->tax,
            'tax_amount' => $this->tax_amount,
            'total_amount_details' => $this->total_amount_details,
            'total_discount_details' => $this->total_discount_details,
            'subtotal' => $this->subtotal,
            'shipping_price' => $this->shipping_price,
            'discount_type' => $this->discount_type,
            'discount_percentage' => $this->discount_percentage,
            'discount_price' => $this->discount_price,
            'total_discount' => $this->total_discount,
            'total_amount' => $this->total_amount,
            'branch' => $branch,
            'order' => $order,
            'details' => $details,
            'created_by' => $createdBy,
            'updated_by' => $updatedBy,
        ];
    }
}
