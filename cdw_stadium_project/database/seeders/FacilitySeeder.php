<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        // Ensure districts are seeded
        $now = now();
        $facilities = [
            ['Sân Nhóm H - Thủ Đức',   'Thủ Đức', '53 Võ Văn Ngân, Thủ Đức, TP.HCM',       '0281111001'],
            ['Sân Nhóm H - Quận 9',    'Quận 9', '12 Lê Văn Việt, Quận 9, TP.HCM',         '0281111002'],
            ['Sân Nhóm H - Bình Thạnh', 'Bình Thạnh', '88 Điện Biên Phủ, Bình Thạnh, TP.HCM',   '0281111003'],
            ['Sân Nhóm H - Quận 1',    'Quận 1', '10 Lê Duẩn, Quận 1, TP.HCM',             '0281111004'],
            ['Sân Nhóm H - Quận 7',    'Quận 7', '105 Nguyễn Thị Thập, Quận 7, TP.HCM',    '0281111005'],
        ];

        foreach ($facilities as [$name, $district, $address, $phone]) {
            DB::table('facilities')->updateOrInsert(
                ['name' => $name, 'address' => $address],
                [
                    'district' => $district,
                    'phone' => $phone, 'open_time' => '06:00:00', 'close_time' => '23:00:00',
                    'status' => 1, 'created_at' => $now, 'updated_at' => $now,
                ]
            );
        }
    }
}
