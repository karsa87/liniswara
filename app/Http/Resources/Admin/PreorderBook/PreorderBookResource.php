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
            'stock_need' => (string) ((int) $this->stock_need ?? '0'),
            'stock' => (string) ((int) $this->stock ?? '0'),
            'total_stock_need' => (string) ($this->total_stock_need <= 0 ? '0' : $this->total_stock_need),
            'total_stock_more' => (string) ($this->total_stock_more <= 0 ? '0' : $this->total_stock_more),
            'total_sale' => (string) ((int) $this->total_sale ?? '0'),
        ];
    }
}
