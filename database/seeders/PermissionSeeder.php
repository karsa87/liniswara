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

        $this->createPermissionRole();
        $this->createPermissionUser();
        $this->createPermissionBranch();

        $this->syncPermissionRole();
    }

    private function createPermissionRole()
    {
        $permissions = [
            [
                'name' => 'Role - Tambah',
                'key' => 'role-tambah',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Role - Edit',
                'key' => 'role-edit',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Role - View',
                'key' => 'role-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Role - Delete',
                'key' => 'role-delete',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermissionUser()
    {
        $permissions = [
            [
                'name' => 'User - Tambah',
                'key' => 'user-tambah',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'User - Edit',
                'key' => 'user-edit',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'User - View',
                'key' => 'user-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'User - Delete',
                'key' => 'user-delete',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
        ];

        $this->createPermission($permissions);
    }

    private function createPermissionBranch()
    {
        $permissions = [
            [
                'name' => 'Gudang - Tambah',
                'key' => 'branch-tambah',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Gudang - Edit',
                'key' => 'branch-edit',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Gudang - View',
                'key' => 'branch-view',
                'role-slug' => [
                    'developer',
                    'super-admin',
                ],
            ],
            [
                'name' => 'Gudang - Delete',
                'key' => 'branch-delete',
                'role-slug' => [
                    'developer',
                    'super-admin',
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
            $role = Role::whereSlug($roleSlug)->withoutGlobalScope('exclude_developer')->first();
            if ($role) {
                $role->permissions()->syncWithoutDetaching(
                    collect($permissions)->pluck('permission_id')->toArray()
                );
            }
        }
    }
}
