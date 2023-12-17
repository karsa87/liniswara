<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (SettingKeyEnumL::getValues() as $setting) {
            Setting::updateOrCreate(
                [
                    'key' => $setting,
                ],
                [
                    'key' => $setting,
                ]
            );
        }
    }
}
