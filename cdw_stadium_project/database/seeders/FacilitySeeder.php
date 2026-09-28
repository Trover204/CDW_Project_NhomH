<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $facilities = [
            ['Sân Nhóm H - Thủ Đức',   '53 Võ Văn Ngân, Thủ Đức, TP.HCM',       '0281111001'],
            ['Sân Nhóm H - Quận 9',    '12 Lê Văn Việt, Quận 9, TP.HCM',         '0281111002'],
            ['Sân Nhóm H - Bình Thạnh','88 Điện Biên Phủ, Bình Thạnh, TP.HCM',   '0281111003'],
        ];

        foreach ($facilities as [$name, $address, $phone]) {
            DB::table('facilities')->updateOrInsert(
                ['name' => $name, 'address' => $address],
                [
                    'phone' => $phone, 'open_time' => '06:00:00', 'close_time' => '23:00:00',
                    'status' => 1, 'created_at' => $now, 'updated_at' => $now,
                ]
            );
        }
    }
}
