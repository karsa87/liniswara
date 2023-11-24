<?php

namespace Database\Seeders;

use App\Models\Village;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class VillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $villages = File::json(storage_path('data/villages.json'));
        $villageAll = collect($villages['RECORDS']);
        foreach ($villageAll->chunk(500) as $villages) {
            $existingVillages = Village::whereIn('id', $villages->pluck('id'))->get();
            foreach ($existingVillages as $existingVillage) {
                $village = $villages->firstWhere('id', $existingVillage->id);
                $existingVillage->name = $village['name'];
                $existingVillage->regency_id = $village['regency_id'];
                $existingVillage->save();
            }

            $villages = $villages->whereNotIn('id', $existingVillages->pluck('id')->toArray());
            if ($villages->isNotEmpty()) {
                Village::insert($villages->toArray());
            }
        }
    }
}
