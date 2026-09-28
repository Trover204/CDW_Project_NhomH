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
            'Cầu lông'     => [80000, 120000, 100000, 140000],
            'Pickleball'   => [100000, 150000, 120000, 180000],
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
