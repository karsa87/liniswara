<?php

namespace App\Http\Resources\Admin\Prerestock;

use App\Enums\PrerestockTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrerestockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $branch = null;
        if ($this->branch) {
            $branch = [
                'id' => $this->branch->id,
                'name' => $this->branch->name,
            ];
        }

        $user = null;
        if ($this->createdBy) {
            $user = [
                'id' => $this->createdBy->id,
                'name' => $this->createdBy->name,
            ];
        }

        $details = null;
        if (
            $this->whenLoaded('details')
            && $this->whenHas('details')
        ) {
            foreach ($this->details as $detail) {
                $product = null;
                if ($detail->product) {
                    $product = [
                        'id' => $detail->product->id,
                        'name' => $detail->product->name,
                    ];
                }

                $details[] = [
                    'id' => $detail->id,
                    'type' => [
                        'key' => $detail->type,
                        'label' => PrerestockTypeEnum::fromValue($detail->type)->getLabel(),
                    ],
                    'product' => $product,
                    'qty' => $detail->qty,
                ];
            }
        }

        return [
            'id' => $this->id,
            'branch' => $branch,
            'notes' => html_entity_decode($this->notes),
            'details' => $details,
            'user' => $user,
            'is_migrate' => $this->is_migrate,
            'restock_id' => $this->restock_id,
        ];
    }
}
