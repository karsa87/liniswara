<?php

namespace App\Http\Resources\Admin\PreorderBook;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PreorderBookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product' => [
                'id' => $this->id,
                'name' => $this->name,
                'code' => $this->code,
            ],
            'total_qty' => (string) ((int) $this->stock_need - $this->stock),
        ];
    }
}
