<?php

namespace App\Http\Resources\Api\Product;

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
            $thumbnail = optional($this->thumbnail)->full_url;
        }

        $category = null;
        $categories = null;
        if ($this->categories) {
            foreach ($this->categories as $row) {
                $categories[] = [
                    'id' => $row->id,
                    'name' => $row->full_name,
                ];
            }

            $category = $categories && count($categories) > 0 ? $categories[0] : null;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => html_entity_decode($this->description),
            'stock' => $this->stock,
            'price' => $this->price,
            'price_zone_2' => $this->price_zone_2,
            'price_zone_3' => $this->price_zone_3,
            'price_zone_4' => $this->price_zone_4,
            'price_zone_5' => $this->price_zone_5,
            'price_zone_6' => $this->price_zone_6,
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
