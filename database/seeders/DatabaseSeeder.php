<?php

namespace Database\Seeders;

use App\Models\Shipment;
use App\Models\ShipmentProduct;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);

        Shipment::factory()->count(10)->create();
        ShipmentProduct::factory()->count(20)->create();
    }
}
