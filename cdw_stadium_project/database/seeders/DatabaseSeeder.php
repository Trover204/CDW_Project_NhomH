<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SportTypeSeeder::class,
            FacilitySeeder::class,
            CourtSeeder::class,
            PriceRuleSeeder::class,
            PlanSeeder::class,
            NewsAdSeeder::class,
            BookingSeeder::class,
        ]);
    }
}
