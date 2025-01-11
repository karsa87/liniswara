<?php

namespace App\Http\Resources\Admin\Area;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AreaResource extends JsonResource
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

        $schools = null;
        if ($this->schools) {
            $schools = [];
            foreach ($this->schools as $school) {
                $schools[] = [
                    'id' => optional($school)->id,
                    'name' => optional($school)->name,
                    'target' => optional($school)->pivot->target,
                ];
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'target' => $this->target,
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),
            'province' => $province,
            'regency' => $regency,
            'district' => $district,
            'village' => $village,
            'schools' => $schools,
        ];
    }
}
