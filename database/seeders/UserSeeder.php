<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Developer',
                'email' => 'developer@gmail.com',
                'password' => 'developer@2006',
                'role_slug' => 'developer',
            ],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'password' => 'superadmin@12345',
                'role_slug' => 'super-admin',
            ],
        ];

        foreach ($users as $inputUser) {
            User::updateOrCreate(
                [
                    'email' => $inputUser['email'],
                ],
                [
                    'name' => $inputUser['name'],
                    'email' => $inputUser['email'],
                    'password' => $inputUser['password'],
                ]
            );
        }
    }
}
