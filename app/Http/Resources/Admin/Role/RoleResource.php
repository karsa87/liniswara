<?php

namespace App\Http\Resources\Admin\Role;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $permissions = [];
        foreach ($this->permissions as $permission) {
            $permissions[] = [
                'id' => $permission->id,
                'name' => $permission->name,
                'key' => $permission->key,
            ];
        }

        $users = [];
        foreach ($this->users as $user) {
            $users[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => Carbon::parse($user->created_at)->toDateTimeString(),
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),
            'users' => $users,
            'permissions' => $permissions,
        ];
    }
}
