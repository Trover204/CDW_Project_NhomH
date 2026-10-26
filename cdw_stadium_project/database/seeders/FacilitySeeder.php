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
            ['Sân Nhóm H - Quận 2',    'Quận 2', '20 Thảo Điền, Quận 2, TP.HCM',             '0281111006'],
            ['Sân Nhóm H - Quận 3',    'Quận 3', '30 Võ Văn Tần, Quận 3, TP.HCM',            '0281111007'],
            ['Sân Nhóm H - Quận 4',    'Quận 4', '40 Tôn Đản, Quận 4, TP.HCM',               '0281111008'],
            ['Sân Nhóm H - Quận 5',    'Quận 5', '50 An Dương Vương, Quận 5, TP.HCM',       '0281111009'],
            ['Sân Nhóm H - Quận 6',    'Quận 6', '60 Hậu Giang, Quận 6, TP.HCM',             '0281111010'],
            ['Sân Nhóm H - Quận 8',    'Quận 8', '80 Phạm Thế Hiển, Quận 8, TP.HCM',         '0281111011'],
            ['Sân Nhóm H - Quận 10',   'Quận 10', '100 Sư Vạn Hạnh, Quận 10, TP.HCM',        '0281111012'],
            ['Sân Nhóm H - Quận 11',   'Quận 11', '110 Lãnh Binh Thăng, Quận 11, TP.HCM',    '0281111013'],
            ['Sân Nhóm H - Quận 12',   'Quận 12', '120 Nguyễn Ảnh Thủ, Quận 12, TP.HCM',     '0281111014'],
            ['Sân Nhóm H - Gò Vấp',    'Gò Vấp', '150 Quang Trung, Gò Vấp, TP.HCM',          '0281111015'],
            ['Sân Nhóm H - Phú Nhuận', 'Phú Nhuận', '160 Phan Xích Long, Phú Nhuận, TP.HCM', '0281111016'],
            ['Sân Nhóm H - Tân Bình',  'Tân Bình', '170 Cộng Hòa, Tân Bình, TP.HCM',         '0281111017'],
            ['Sân Nhóm H - Tân Phú',   'Tân Phú', '180 Lũy Bán Bích, Tân Phú, TP.HCM',       '0281111018'],
            ['Sân Nhóm H - Bình Tân',  'Bình Tân', '190 Kinh Dương Vương, Bình Tân, TP.HCM',  '0281111019'],
            ['Sân Nhóm H - Hóc Môn',   'Hóc Môn', '200 Tô Ký, Hóc Môn, TP.HCM',              '0281111020'],
            ['Sân Nhóm H - Củ Chi',    'Củ Chi', '210 Tỉnh Lộ 8, Củ Chi, TP.HCM',            '0281111021'],
            ['Sân Nhóm H - Nhà Bè',    'Nhà Bè', '220 Huỳnh Tấn Phát, Nhà Bè, TP.HCM',       '0281111022'],
            ['Sân Nhóm H - Bình Chánh', 'Bình Chánh', '230 Quốc Lộ 50, Bình Chánh, TP.HCM',   '0281111023'],
            ['Sân Nhóm H - Cần Giờ',   'Cần Giờ', '240 Duyên Hải, Cần Giờ, TP.HCM',          '0281111024'],
            ['Sân Nhóm H - Dĩ An',     'Dĩ An', '250 DT743, Dĩ An, Bình Dương',              '0281111025'],
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
