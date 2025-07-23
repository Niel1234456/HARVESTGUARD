<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supply;

class SuppliesTableSeeder extends Seeder
{
    public function run()
    {
        Supply::create([
            'name' => 'Fertilizer',
            'description' => 'High quality fertilizer',
            'quantity' => 100,
        ]);

        Supply::create([
            'name' => 'Seeds',
            'description' => 'Wheat seeds',
            'quantity' => 200,
        ]);
    }
}
