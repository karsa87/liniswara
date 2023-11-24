<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = File::json(storage_path('data/provinces.json'));
        $provinces = collect($provinces['RECORDS']);

        $existingProvinces = Province::whereIn('id', $provinces->pluck('id'))->get();
        foreach ($existingProvinces as $existingProvince) {
            $province = $provinces->firstWhere('id', $existingProvince->id);
            $existingProvince->name = $province['name'];
            $existingProvince->save();
        }

        $provinces = $provinces->whereNotIn('id', $existingProvinces->pluck('id')->toArray());
        if ($provinces->isNotEmpty()) {
            Province::insert($provinces->toArray());
        }
    }
}
