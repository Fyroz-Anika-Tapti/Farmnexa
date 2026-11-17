<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Animal;


class RollCattleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Animal::create([
    'farm_id' => 1,
    'section_id' => 1,
    'cost_centre_id' => null,
    'animal_code' => 'CAT-001',
    'animal_type' => 'cattle',
    'breed' => 'Holstein Friesian',
    'sex' => 'female',
    'date_of_birth' => '2024-01-15',
    'purchase_date' => '2024-03-10',
    'purchase_value' => 85000,
    'source_type' => 'purchased',
    'status' => 'active',
    'notes' => 'First cattle',
]);
    }
}
