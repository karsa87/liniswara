<?php

namespace Database\Seeders;

use App\Models\Regency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class RegencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regencies = File::json(storage_path('data/regencies.json'));
        $regencies = collect($regencies['RECORDS']);

        $existingRegencies = Regency::whereIn('id', $regencies->pluck('id'))->get();
        foreach ($existingRegencies as $existingRegency) {
            $regency = $regencies->firstWhere('id', $existingRegency->id);
            $existingRegency->name = $regency['name'];
            $existingRegency->province_id = $regency['province_id'];
            $existingRegency->save();
        }

        $regencies = $regencies->whereNotIn('id', $existingRegencies->pluck('id')->toArray());
        if ($regencies->isNotEmpty()) {
            Regency::insert($regencies->toArray());
        }
    }
}
