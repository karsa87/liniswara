<?php

namespace App\Http\Resources\Admin\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $thumbnail = null;
        if ($this->thumbnail) {
            $thumbnail = [
                'id' => optional($this->thumbnail)->id,
                'name' => optional($this->thumbnail)->name,
                'information' => optional($this->thumbnail)->information,
                'full_url' => optional($this->thumbnail)->full_url,
            ];
        }

        $category = null;
        $categories = null;
        if ($this->categories) {
            foreach ($this->categories as $row) {
                $categories[] = [
                    'id' => $row->id,
                    'name' => $row->name,
                ];
            }

            $category = $categories[0];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => html_entity_decode($this->description),
            'stock' => $this->stock,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'discount_percentage' => $this->discount_percentage,
            'is_best_seller' => $this->is_best_seller,
            'is_recommendation' => $this->is_recommendation,
            'is_new' => $this->is_new,
            'is_special_discount' => $this->is_special_discount,
            'is_active' => $this->is_active,
            'category' => $category,
            'categories' => $categories,
            'thumbnail' => $thumbnail,
        ];
    }
}
