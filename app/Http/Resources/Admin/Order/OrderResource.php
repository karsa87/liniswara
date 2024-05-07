<?php

namespace App\Http\Resources\Admin\Order;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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

        $collector = null;
        if ($this->collector) {
            $collector = [
                'id' => $this->collector->id,
                'name' => $this->collector->name,
            ];
        }

        $customer = null;
        if ($this->customer) {
            $customer = [
                'id' => $this->customer->id,
                'name' => $this->customer->user->name ?? '',
            ];
        }

        $customerAddress = null;
        if ($this->customer && $this->customer_address) {
            $customerAddress = [
                'id' => $this->customer_address->id,
                'name' => $this->customer_address->name,
                'full_address' => $this->customer_address->full_address,
                'summary_address' => $this->customer_address->summary_address,
            ];
        }

        $shipping = null;
        if ($this->shipping) {
            $expedition = null;
            if ($this->shipping->expedition) {
                $expeditionModel = $this->shipping->expedition;
                $expedition = [
                    'id' => $expeditionModel->id,
                    'name' => $expeditionModel->name,
                ];
            }

            $province = null;
            if ($this->shipping->province) {
                $province = [
                    'id' => optional($this->shipping->province)->id,
                    'name' => optional($this->shipping->province)->name,
                ];
            }

            $regency = null;
            if ($this->shipping->regency) {
                $regency = [
                    'id' => optional($this->shipping->regency)->id,
                    'name' => optional($this->shipping->regency)->name,
                ];
            }

            $district = null;
            if ($this->shipping->district) {
                $district = [
                    'id' => optional($this->shipping->district)->id,
                    'name' => optional($this->shipping->district)->name,
                ];
            }

            $village = null;
            if ($this->shipping->village) {
                $village = [
                    'id' => optional($this->shipping->village)->id,
                    'name' => optional($this->shipping->village)->name,
                ];
            }

            $shipping = [
                'id' => $this->shipping->id,
                'resi' => $this->shipping->resi,
                'name' => $this->shipping->name,
                'email' => $this->shipping->email,
                'phone' => $this->shipping->phone,
                'address' => $this->shipping->address,
                'shipping_price' => $this->shipping->shipping_price,
                'notes' => html_entity_decode($this->shipping->notes),
                'province' => $province,
                'regency' => $regency,
                'district' => $district,
                'village' => $village,
                'expedition' => $expedition,
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
            'paid_at' => $this->paid_at ? Carbon::parse($this->paid_at)->toDateString() : '',
            'invoice_number' => $this->invoice_number,
            'status' => $this->status,
            'status_payment' => $this->status_payment,
            'method_payment' => $this->method_payment,
            'marketing' => $this->marketing,
            'notes' => html_entity_decode($this->notes),
            'notes_staff' => html_entity_decode($this->notes_staff),
            'zone' => $this->zone,
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
            'collector' => $collector,
            'customer' => $customer,
            'customer_address' => $customerAddress,
            'shipping' => $shipping,
            'details' => $details,
            'created_by' => $createdBy,
            'updated_by' => $updatedBy,
        ];
    }
}
