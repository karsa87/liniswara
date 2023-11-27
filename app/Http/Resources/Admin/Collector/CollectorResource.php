<?php

namespace App\Http\Resources\Admin\Collector;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CollectorResource extends JsonResource
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

        $signInFile = null;
        if ($this->signin_file) {
            $signInFile = [
                'id' => optional($this->signin_file)->id,
                'name' => optional($this->signin_file)->name,
                'full_url' => optional($this->signin_file)->full_url,
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'company' => $this->company,
            'address' => $this->address,
            'footer' => $this->footer,
            'billing_notes' => $this->billing_notes,
            'npwp' => $this->npwp,
            'gst' => $this->gst,
            'province' => $province,
            'regency' => $regency,
            'district' => $district,
            'village' => $village,
            'signin_file' => $signInFile,
        ];
    }
}
