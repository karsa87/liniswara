<?php

namespace App\Http\Resources\Admin\Area;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AreaSchoolResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'target' => $this->areas->first()->pivot->target,
        ];
    }
}
