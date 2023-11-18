<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    private $rolePermissions;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->rolePermissions = collect();

        $this->createPermissionUser();

        $this->syncPermissionRole();
    }

    private function createPermissionUser()
    {
        $permissions = [
            [
                'name' => 'User - Tambah',
                'key' => 'user-tambah',
                'role-slug' => [
                    'developer',
                    'superadmin',
                ],
            ],
            [
                'name' => 'User - Edit',
                'key' => 'user-edit',
                'role-slug' => [
                    'developer',
                    'superadmin',
                ],
            ],
            [
                'name' => 'User - View',
                'key' => 'user-view',
                'role-slug' => [
                    'developer',
                    'superadmin',
                ],
            ],
            [
                'name' => 'User - Delete',
                'key' => 'user-delete',
                'role-slug' => [
                    'developer',
                    'superadmin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermission($permissions)
    {
        foreach ($permissions as $inputPermission) {
            $permission = Permission::updateOrCreate(
                [
                    'key' => $inputPermission['key'],
                ],
                [
                    'name' => $inputPermission['name'],
                    'key' => $inputPermission['key'],
                ]
            );

            foreach ($inputPermission['role-slug'] as $roleSlug) {
                $this->rolePermissions->push([
                    'role_slug' => $roleSlug,
                    'permission_id' => $permission->id,
                ]);
            }
        }
    }

    private function syncPermissionRole()
    {
        foreach ($this->rolePermissions->groupBy('role_slug') as $roleSlug => $permissions) {
            $role = Role::whereSlug($roleSlug)->first();
            if ($role) {
                $role->permissions()->syncWithoutDetaching(
                    collect($permissions)->pluck('permission_id')->toArray()
                );
            }
        }
    }
}
