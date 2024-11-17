<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = [
            'PAUD',
            'SD',
            'MI',
            'SMP',
            'MTS',
            'SMA',
            'SMK',
            'MAN',
            'MA',
        ];

        foreach ($schools as $school) {
            School::firstOrCreate([
                'name' => $school,
            ]);
        }
    }
}
