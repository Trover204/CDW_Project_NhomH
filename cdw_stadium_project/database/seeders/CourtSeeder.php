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
        $config =
            [
                'Bóng đá mini' => ['Sân bóng',       10, 3, 'Cỏ nhân tạo, đèn chiếu sáng, chỗ để xe, phòng thay đồ'],
                'Cầu lông'     => ['Sân cầu lông',    4, 4, 'Trong nhà, có mái che, nước uống, phòng thay đồ'],
                'Pickleball'   => ['Sân pickleball',  4, 2, 'Mặt sân tiêu chuẩn, cho thuê vợt, chỗ để xe'],
                'Tennis'       => ['Sân tennis',      4, 2, 'Mặt cứng, đèn chiếu sáng, có ghế nghỉ'],
                'Bóng rổ'      => ['Sân bóng rổ',    10, 1, 'Ngoài trời, bảng rổ tiêu chuẩn, nước uống'],
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
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]
                    );
                }
            }
        }

        // Một sân đang bảo trì để demo trạng thái
        DB::table('courts')->where('name', 'Sân pickleball 2')->limit(1)->update(['status' => 'maintenance']);
    }
}
