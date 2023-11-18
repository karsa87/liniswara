<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Developer',
                'slug' => 'developer',
            ],
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
            ],
            [
                'name' => 'Sales',
                'slug' => 'sales',
            ],
            [
                'name' => 'Agen',
                'slug' => 'agen',
            ],
            [
                'name' => 'Penulis',
                'slug' => 'penulis',
            ],
            [
                'name' => 'Gudang',
                'slug' => 'gudang',
            ],
        ];

        foreach ($roles as $inputRole) {
            Role::updateOrCreate(
                [
                    'slug' => $inputRole['slug'],
                ],
                [
                    'name' => $inputRole['name'],
                    'slug' => $inputRole['slug'],
                ]
            );
        }
    }
}
