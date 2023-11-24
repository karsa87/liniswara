<?php

namespace App\Http\Resources\Admin\Supplier;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'address' => $this->address,
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),
            'province' => $province,
            'regency' => $regency,
            'district' => $district,
            'village' => $village,
        ];
    }
}
