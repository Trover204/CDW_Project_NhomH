<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SportTypeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $types = [
            ['Bóng đá mini', 'Sân cỏ nhân tạo 5-7 người'],
            ['Cầu lông',     'Sân cầu lông trong nhà, có mái che'],
            ['Pickleball',   'Sân pickleball tiêu chuẩn'],
        ];

        foreach ($types as [$name, $desc]) {
            DB::table('sport_types')->updateOrInsert(
                ['name' => $name],
                ['description' => $desc, 'status' => 1, 'created_at' => $now, 'updated_at' => $now]
            );
        }
    }
}
