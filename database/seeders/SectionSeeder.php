<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;
use App\Models\Farm;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        $farm = Farm::first();

        Section::create([
            'farm_id' => $farm->id,
            'section_name' => 'Cattle Section',
            'section_type' => 'CATTLE',
            'description' => 'Main cattle section',
            'status' => 'active',
        ]);
    }
}