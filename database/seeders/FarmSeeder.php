<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Farm;

class FarmSeeder extends Seeder
{
    public function run(): void
    {
        Farm::create([
            'farm_name' => 'Green Valley Farm',
            'farm_code' => 'FARM-001',
            'location' => 'Chattogram, Bangladesh',
            'phone' => '01700000000',
            'currency_code' => 'BDT',
            'status' => 'active',
        ]);
    }
}