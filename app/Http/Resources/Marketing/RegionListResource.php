<?php

namespace App\Http\Resources\Marketing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegionListResource extends JsonResource
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
            'total_transaction' => $this->preorders_count ?? 0,
            'target' => $this->target ?? 0,
            'total_achieved' => $this->total_achieved ?? 0,
        ];
    }
}
