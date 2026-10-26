<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PriceRuleSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // loại môn => [thường ngày-sáng, thường ngày-tối, cuối tuần-sáng, cuối tuần-tối]  (VNĐ/giờ)
        $prices = [
            'Bóng đá mini' => [200000, 300000, 250000, 350000],
            'Bóng rổ' => [150000, 220000, 180000, 260000],
            'Cầu lông' => [80000, 120000, 100000, 140000],
            'Pickleball' => [100000, 150000, 120000, 180000],
            'Bóng bàn' => [40000, 60000, 50000, 70000],
            'Tennis' => [150000, 200000, 180000, 250000],
            'Bơi lội' => [30000, 40000, 35000, 45000],
            'Gym' => [50000, 50000, 60000, 60000],
            'Yoga' => [60000, 80000, 70000, 90000],
            'Bóng chuyền' => [120000, 150000, 140000, 180000],
            'Boxing' => [80000, 100000, 90000, 120000],
            'Múa' => [70000, 90000, 80000, 100000],
            'Võ thuật' => [60000, 80000, 70000, 90000],
            'Cờ vua' => [20000, 30000, 25000, 35000],
            'Cờ tướng' => [20000, 30000, 25000, 35000],
            'Billiards' => [50000, 70000, 60000, 80000],
            'Trượt băng' => [100000, 150000, 120000, 180000],
            'Leo núi' => [80000, 120000, 100000, 150000],
            'Bắn cung' => [100000, 120000, 110000, 140000],
            'Điền kinh' => [20000, 30000, 25000, 35000],
            'Cầu mây' => [80000, 100000, 90000, 120000],
            'Bóng nước' => [120000, 150000, 140000, 180000],
            'Aerobic' => [60000, 80000, 70000, 90000],
            'Bóng ném' => [150000, 200000, 180000, 250000],
        ];

        // Xóa toàn bộ bảng giá cũ để đồng bộ dữ liệu mới
        DB::table('price_rules')->delete();

        $rulesToInsert = [];

        // 1. Giá chung theo loại môn (court_id = NULL)
        foreach ($prices as $typeName => [$wdDay, $wdNight, $weDay, $weNight]) {
            $typeId = DB::table('sport_types')->where('name', $typeName)->value('id');
            if (! $typeId) {
                continue;
            }

            $rows = [
                ['weekday', '06:00:00', '17:00:00', $wdDay],
                ['weekday', '17:00:00', '23:00:00', $wdNight],
                ['weekend', '06:00:00', '17:00:00', $weDay],
                ['weekend', '17:00:00', '23:00:00', $weNight],
            ];

            foreach ($rows as [$dayType, $start, $end, $price]) {
                $rulesToInsert[] = [
                    'court_id' => null,
                    'sport_type_id' => $typeId,
                    'day_type' => $dayType,
                    'start_time' => $start,
                    'end_time' => $end,
                    'price' => $price,
                    'status' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // 2. Giá riêng cho từng Sân (gắn court_id cụ thể của từng sân)
        $courts = DB::table('courts')
            ->join('sport_types', 'courts.sport_type_id', '=', 'sport_types.id')
            ->select('courts.id as court_id', 'courts.name as court_name', 'courts.sport_type_id', 'sport_types.name as type_name')
            ->get();

        foreach ($courts as $court) {
            if (! isset($prices[$court->type_name])) {
                continue;
            }

            [$wdDay, $wdNight, $weDay, $weNight] = $prices[$court->type_name];

            // Sân 2 có chất lượng cao/VIP hơn, chênh lệch nhẹ 20.000 VNĐ
            $isVip = str_ends_with(trim($court->court_name), '2');
            $bonus = $isVip ? 20000 : 0;

            $rows = [
                ['weekday', '06:00:00', '17:00:00', $wdDay + $bonus],
                ['weekday', '17:00:00', '23:00:00', $wdNight + $bonus],
                ['weekend', '06:00:00', '17:00:00', $weDay + $bonus],
                ['weekend', '17:00:00', '23:00:00', $weNight + $bonus],
            ];

            foreach ($rows as [$dayType, $start, $end, $price]) {
                $rulesToInsert[] = [
                    'court_id' => $court->court_id,
                    'sport_type_id' => $court->sport_type_id,
                    'day_type' => $dayType,
                    'start_time' => $start,
                    'end_time' => $end,
                    'price' => $price,
                    'status' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        foreach (array_chunk($rulesToInsert, 500) as $chunk) {
            DB::table('price_rules')->insert($chunk);
        }
    }
}
