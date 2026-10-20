<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourtSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $facilityIds = DB::table('facilities')->orderBy('id')->pluck('id');

        // tên loại môn => [tiền tố tên sân, sức chứa]
        $config = [
            'Bóng đá mini' => ['Sân bóng', 10],
            'Bóng rổ' => ['Sân bóng rổ', 10],
            'Cầu lông' => ['Sân cầu lông', 4],
            'Pickleball' => ['Sân pickleball', 4],
            'Bóng bàn' => ['Bàn bóng bàn', 4],
            'Tennis' => ['Sân tennis', 4],
            'Bơi lội' => ['Hồ bơi', 20],
            'Gym' => ['Phòng gym', 30],
            'Yoga' => ['Phòng yoga', 20],
            'Bóng chuyền' => ['Sân bóng chuyền', 12],
            'Boxing' => ['Sàn boxing', 2],
            'Múa' => ['Phòng múa', 20],
            'Võ thuật' => ['Sàn võ thuật', 10],
            'Cờ vua' => ['Bàn cờ vua', 2],
            'Cờ tướng' => ['Bàn cờ tướng', 2],
            'Billiards' => ['Bàn bida', 4],
            'Trượt băng' => ['Sân trượt băng', 30],
            'Leo núi' => ['Tường leo núi', 1],
            'Bắn cung' => ['Sân bắn cung', 10],
            'Điền kinh' => ['Đường chạy', 50],
            'Cầu mây' => ['Sân cầu mây', 6],
            'Bóng nước' => ['Hồ bóng nước', 14],
            'Aerobic' => ['Phòng aerobic', 20],
            'Bóng ném' => ['Sân bóng ném', 14],
        ];

        foreach ($facilityIds as $facilityId) {
            foreach ($config as $typeName => [$prefix, $capacity]) {
                $typeId = DB::table('sport_types')->where('name', $typeName)->value('id');

                for ($i = 1; $i <= 2; $i++) {
                    DB::table('courts')->updateOrInsert(
                        ['facility_id' => $facilityId, 'name' => "$prefix $i"],
                        [
                            'sport_type_id' => $typeId,
                            'capacity' => $capacity,
                            'description' => 'Có chỗ để xe, nước uống, phòng thay đồ',
                            'status' => 'available',
                            'created_at' => $now, 'updated_at' => $now,
                        ]
                    );
                }
            }
        }

        // Một sân đang bảo trì để demo trạng thái
        DB::table('courts')->where('name', 'Sân pickleball 2')->limit(1)->update(['status' => 'maintenance']);
    }
}
