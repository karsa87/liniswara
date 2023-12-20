<?php

namespace App\Http\Resources\Admin\Customer;

use App\Enums\CustomerTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $province = null;
        if (optional($this->customer->address)->province) {
            $province = [
                'id' => optional(optional($this->customer->address)->province)->id,
                'name' => optional(optional($this->customer->address)->province)->name,
            ];
        }

        $regency = null;
        if (optional($this->customer->address)->regency) {
            $regency = [
                'id' => optional(optional($this->customer->address)->regency)->id,
                'name' => optional(optional($this->customer->address)->regency)->name,
            ];
        }

        $district = null;
        if (optional($this->customer->address)->district) {
            $district = [
                'id' => optional(optional($this->customer->address)->district)->id,
                'name' => optional(optional($this->customer->address)->district)->name,
            ];
        }

        $village = null;
        if (optional($this->customer->address)->village) {
            $village = [
                'id' => optional(optional($this->customer->address)->village)->id,
                'name' => optional(optional($this->customer->address)->village)->name,
            ];
        }

        return [
            'id' => $this->customer->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'company' => $this->company,
            'type_label' => $this->customer->type ? CustomerTypeEnum::fromValue($this->customer->type)->getLabel() : '',
            'type' => $this->customer->type,
            'address' => optional($this->customer->address)->address,
            'province' => $province,
            'regency' => $regency,
            'district' => $district,
            'village' => $village,
        ];
    }
}
