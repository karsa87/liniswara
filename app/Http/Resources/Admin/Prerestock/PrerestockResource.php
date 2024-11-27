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

        $isMigrate = false;
        $details = collect();
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

                if (
                    ! $isMigrate
                    && $detail->qty_migrate > 0
                ) {
                    $isMigrate = true;
                }

                $details->push([
                    'id' => $detail->id,
                    'type' => [
                        'key' => $detail->type,
                        'label' => PrerestockTypeEnum::fromValue($detail->type)->getLabel(),
                    ],
                    'product' => $product,
                    'qty' => $detail->qty,
                    'qty_migrate' => $detail->qty_migrate,
                    'qty_left' => $detail->qty - $detail->qty_migrate,
                ]);
            }
        }

        $isMigrateAll = $details->where('qty_left', '>', 0)->count() >= 1 ? false : true;

        return [
            'id' => $this->id,
            'label' => $this->label,
            'branch' => $branch,
            'notes' => html_entity_decode($this->notes),
            'details' => $details,
            'user' => $user,
            'is_migrate' => $isMigrate,
            'is_migrate_all' => $isMigrateAll,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
