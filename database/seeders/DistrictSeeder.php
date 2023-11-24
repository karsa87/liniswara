<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = File::json(storage_path('data/districts.json'));
        $districts = collect($districts['RECORDS']);

        $existingDistricts = District::whereIn('id', $districts->pluck('id'))->get();
        foreach ($existingDistricts as $existingDistrict) {
            $district = $districts->firstWhere('id', $existingDistrict->id);
            $existingDistrict->name = $district['name'];
            $existingDistrict->regency_id = $district['regency_id'];
            $existingDistrict->save();
        }

        $districts = $districts->whereNotIn('id', $existingDistricts->pluck('id')->toArray());
        if ($districts->isNotEmpty()) {
            District::insert($districts->toArray());
        }
    }
}
