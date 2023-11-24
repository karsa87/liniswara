<?php

namespace App\Http\Resources\Admin\Components\Region;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $district = null;
        $regency = null;
        $province = null;
        if ($this->district) {
            $district = [
                'id' => optional($this->district)->id,
                'name' => optional($this->district)->name,
            ];

            if ($this->district->regency) {
                $regencyModel = optional($this->district)->regency;
                $regency = [
                    'id' => optional($regencyModel)->id,
                    'name' => optional($regencyModel)->name,
                ];

                if ($regencyModel->province) {
                    $province = [
                        'id' => optional($regencyModel->province)->id,
                        'name' => optional($regencyModel->province)->name,
                    ];
                }
            }
        }

        return [
            'province' => $province,
            'regency' => $regency,
            'district' => $district,
            'village' => [
                'id' => $this->id,
                'name' => $this->name,
            ],
        ];
    }
}
