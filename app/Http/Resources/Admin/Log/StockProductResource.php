<?php

namespace App\Http\Resources\Admin\Log;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = null;
        if ($this->user) {
            $user = [
                'name' => $this->user->name,
                'phone_number' => $this->user->phone_number,
                'email' => $this->user->email,
            ];
        }

        return [
            'product' => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'code' => $this->product->code,
            ],
            'stock_old' => $this->stock_old,
            'stock_in' => $this->stock_in,
            'stock_out' => $this->stock_out,
            'stock_new' => $this->stock_new,
            'user' => $user,
        ];
    }
}
