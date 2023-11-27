<?php

namespace App\Http\Resources\Admin\Expedition;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpeditionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $logo = null;
        if ($this->logo) {
            $logo = [
                'id' => optional($this->logo)->id,
                'name' => optional($this->logo)->name,
                'information' => optional($this->logo)->information,
                'full_url' => optional($this->logo)->full_url,
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo' => $logo,
        ];
    }
}
