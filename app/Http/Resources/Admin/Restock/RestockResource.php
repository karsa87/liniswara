<?php

namespace App\Http\Resources\Admin\Restock;

use App\Enums\RestockTypeEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestockResource extends JsonResource
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
                        'label' => RestockTypeEnum::fromValue($detail->type)->getLabel(),
                    ],
                    'product' => $product,
                    'qty' => $detail->qty,
                ];
            }
        }

        return [
            'id' => $this->id,
            'date' => Carbon::parse($this->date)->toDateString(),
            'branch' => $branch,
            'notes' => html_entity_decode($this->notes),
            'details' => $details,
            'user' => $user,
        ];
    }
}
