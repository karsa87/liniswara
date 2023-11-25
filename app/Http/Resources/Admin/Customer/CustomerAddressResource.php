<?php

namespace App\Http\Resources\Admin\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $province = null;
        if ($this->province) {
            $province = [
                'id' => optional($this->province)->id,
                'name' => optional($this->province)->name,
            ];
        }

        $regency = null;
        if ($this->regency) {
            $regency = [
                'id' => optional($this->regency)->id,
                'name' => optional($this->regency)->name,
            ];
        }

        $district = null;
        if ($this->district) {
            $district = [
                'id' => optional($this->district)->id,
                'name' => optional($this->district)->name,
            ];
        }

        $village = null;
        if ($this->village) {
            $village = [
                'id' => optional($this->village)->id,
                'name' => optional($this->village)->name,
            ];
        }

        return [
            'id' => $this->id,
            'customer' => [
                'id' => $this->customer->id,
                'name' => $this->customer->name,
            ],
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'province' => $province,
            'regency' => $regency,
            'district' => $district,
            'village' => $village,
            'is_default' => $this->is_default,
        ];
    }
}
