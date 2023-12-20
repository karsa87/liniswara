<?php

namespace Database\Seeders;

use App\Enums\SettingKeyEnum;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (SettingKeyEnum::getValues() as $setting) {
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
