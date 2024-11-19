<?php

namespace App\Http\Resources\Admin\Writer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WriterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $products = collect();
        if ($this->products) {
            foreach ($this->products as $product) {
                $products->push([
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code,
                ]);
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'products' => $products,
        ];
    }
}
