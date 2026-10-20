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
            'Cầu lông' => [80000, 120000, 100000, 140000],
            'Pickleball' => [100000, 150000, 120000, 180000],
            'Bóng bàn'     => [40000, 60000, 50000, 70000],
            'Tennis'       => [150000, 200000, 180000, 250000],
            'Bơi lội'      => [30000, 40000, 35000, 45000],
            'Gym'          => [50000, 50000, 60000, 60000],
            'Yoga'         => [60000, 80000, 70000, 90000],
            'Bóng chuyền'  => [120000, 150000, 140000, 180000],
            'Boxing'       => [80000, 100000, 90000, 120000],
            'Múa'          => [70000, 90000, 80000, 100000],
            'Võ thuật'    => [60000, 80000, 70000, 90000],
            'Cờ vua'       => [20000, 30000, 25000, 35000],
            'Cờ tướng'     => [20000, 30000, 25000, 35000],
            'Billiards'    => [50000, 70000, 60000, 80000],
            'Trượt băng'   => [100000, 150000, 120000, 180000],
            'Leo núi'      => [80000, 120000, 100000, 150000],
            'Bắn cung'     => [100000, 120000, 110000, 140000],
            'Điền kinh'    => [20000, 30000, 25000, 35000],
            'Cầu mây'      => [80000, 100000, 90000, 120000],
            'Bóng nước'    => [120000, 150000, 140000, 180000],
            'Aerobic'      => [60000, 80000, 70000, 90000],
            'Bóng ném'     => [150000, 200000, 180000, 250000],
        ];

        // Giá chung theo loại môn (court_id = NULL)
        DB::table('price_rules')->whereNull('court_id')->delete();

        foreach ($prices as $typeName => [$wdDay, $wdNight, $weDay, $weNight]) {
            $typeId = DB::table('sport_types')->where('name', $typeName)->value('id');

            $rows = [
                ['weekday', '06:00:00', '17:00:00', $wdDay],
                ['weekday', '17:00:00', '23:00:00', $wdNight],
                ['weekend', '06:00:00', '17:00:00', $weDay],
                ['weekend', '17:00:00', '23:00:00', $weNight],
            ];

            foreach ($rows as [$dayType, $start, $end, $price]) {
                DB::table('price_rules')->insert([
                    'court_id' => null, 'sport_type_id' => $typeId, 'day_type' => $dayType,
                    'start_time' => $start, 'end_time' => $end, 'price' => $price,
                    'status' => 1, 'created_at' => $now, 'updated_at' => $now,
                ]);
            }
        }
    }
}
