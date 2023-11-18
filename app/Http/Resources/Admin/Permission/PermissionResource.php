<?php

namespace App\Http\Resources\Admin\Permission;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $roles = [];
        foreach ($this->roles as $role) {
            $roles[] = [
                'id' => $role->id,
                'name' => $role->name,
                'url' => '#',
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'key' => $this->key,
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),
            'roles' => $roles,
        ];
    }
}
