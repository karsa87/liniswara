<?php

namespace App\Http\Resources\Admin\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $role = [];
        if ($this->role->isNotEmpty()) {
            $userRole = $this->role->first();
            $role = [
                'id' => $userRole->id,
                'name' => $userRole->name,
                'url' => '#',
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'company' => $this->company,
            'can_access_marketing' => $this->can_access_marketing,
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),
            'role' => $role,
        ];
    }
}
