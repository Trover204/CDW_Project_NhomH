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
            ['Bóng rổ',      'Sân bóng rổ tiêu chuẩn'],
            ['Bóng bàn',     'Bàn bóng bàn tiêu chuẩn trong nhà'],
            ['Tennis',       'Sân quần vợt chuẩn quốc tế'],
            ['Bơi lội',      'Hồ bơi tiêu chuẩn Olympic'],
            ['Gym',          'Phòng tập thể hình đầy đủ thiết bị'],
            ['Yoga',         'Phòng tập yoga yên tĩnh, có gương'],
            ['Bóng chuyền',  'Sân bóng chuyền trong nhà và bãi biển'],
            ['Boxing',       'Phòng tập quyền anh chuyên nghiệp'],
            ['Múa',          'Phòng tập múa rộng rãi'],
            ['Võ thuật',    'Khu vực tập luyện võ thuật với thảm'],
            ['Cờ vua',       'Phòng chơi cờ vua yên tĩnh'],
            ['Cờ tướng',     'Phòng chơi cờ tướng truyền thống'],
            ['Billiards',    'Khu vực giải trí với các bàn bida'],
            ['Trượt băng',   'Sân trượt băng nhân tạo'],
            ['Leo núi',      'Tường leo núi trong nhà'],
            ['Bắn cung',     'Sân bắn cung với bia ngắm chuẩn'],
            ['Điền kinh',    'Đường chạy điền kinh tiêu chuẩn'],
            ['Cầu mây',      'Sân cầu mây chuyên dụng'],
            ['Bóng nước',    'Hồ bơi phục vụ bóng nước'],
            ['Aerobic',      'Phòng nhảy aerobic trang bị âm thanh tốt'],
            ['Bóng ném',     'Sân bóng ném chuẩn'],
        ];

        foreach ($types as [$name, $desc]) {
            DB::table('sport_types')->updateOrInsert(
                ['name' => $name],
                ['description' => $desc, 'status' => 1, 'created_at' => $now, 'updated_at' => $now]
            );
        }
    }
}
