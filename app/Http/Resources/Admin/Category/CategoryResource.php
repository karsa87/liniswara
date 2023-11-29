<?php

namespace App\Http\Resources\Admin\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $image = null;
        if ($this->image) {
            $image = [
                'id' => optional($this->image)->id,
                'name' => optional($this->image)->name,
                'information' => optional($this->image)->information,
                'full_url' => optional($this->image)->full_url,
            ];
        }

        $parent = null;
        if ($this->parent) {
            $parent = [
                'id' => optional($this->parent)->id,
                'name' => optional($this->parent)->name,
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'parent' => $parent,
            'image' => $image,
        ];
    }
}
