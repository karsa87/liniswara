<?php

namespace App\Http\Resources\Admin\Customer;

use App\Enums\CustomerTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $province = null;
        if (optional($this->address)->province) {
            $province = [
                'id' => optional(optional($this->address)->province)->id,
                'name' => optional(optional($this->address)->province)->name,
            ];
        }

        $regency = null;
        if (optional($this->address)->regency) {
            $regency = [
                'id' => optional(optional($this->address)->regency)->id,
                'name' => optional(optional($this->address)->regency)->name,
            ];
        }

        $district = null;
        if (optional($this->address)->district) {
            $district = [
                'id' => optional(optional($this->address)->district)->id,
                'name' => optional(optional($this->address)->district)->name,
            ];
        }

        $village = null;
        if (optional($this->address)->village) {
            $village = [
                'id' => optional(optional($this->address)->village)->id,
                'name' => optional(optional($this->address)->village)->name,
            ];
        }

        $area = null;
        $areas = null;
        if ($this->areas) {
            foreach ($this->areas as $row) {
                $areas[] = [
                    'id' => $row->id,
                    'name' => $row->name,
                ];
            }

            $area = $areas && count($areas) > 0 ? $areas[0] : null;
        }

        $schools = null;
        if ($this->schools) {
            $schools = [];
            foreach ($this->schools as $school) {
                $schools[] = [
                    'id' => $school->id,
                    'name' => $school->name,
                    'target' => $school->pivot->target,
                ];
            }
        }

        return [
            'id' => $this->id,
            'name' => optional($this->user)->name,
            'email' => optional($this->user)->email,
            'phone_number' => optional($this->user)->phone_number,
            'company' => optional($this->user)->company,
            'type_label' => $this->type ? CustomerTypeEnum::fromValue($this->type)->getLabel() : '',
            'type' => $this->type,
            'target' => $this->target,
            'address' => optional($this->address)->address,
            'province' => $province,
            'regency' => $regency,
            'district' => $district,
            'village' => $village,
            'area' => $area,
            'areas' => $areas,
            'schools' => $schools,
            'marketing' => $this->marketing,
        ];
    }
}
