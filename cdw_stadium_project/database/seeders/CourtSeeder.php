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

        // Lấy danh sách ảnh sân bóng đá mini và cầu lông trong public/images
        $soccerFiles = array_map(fn ($f) => 'images/'.basename($f), glob(public_path('images/sanbanh*.*')) ?: []);
        natsort($soccerFiles);
        $soccerFiles = array_values($soccerFiles);

        $badmintonFiles = array_map(fn ($f) => 'images/'.basename($f), glob(public_path('images/caulong*.*')) ?: []);
        natsort($badmintonFiles);
        $badmintonFiles = array_values($badmintonFiles);

        $generalFiles = [];
        if (file_exists(public_path('images/image.png'))) {
            $generalFiles[] = 'images/image.png';
        }
        if (file_exists(public_path('images/turf-bg.jpg'))) {
            $generalFiles[] = 'images/turf-bg.jpg';
        }

        $soccerCount = count($soccerFiles);
        $badmintonCount = count($badmintonFiles);
        $soccerIndex = 0;
        $badmintonIndex = 0;
        $courtImagesToInsert = [];

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

                    $courtId = DB::table('courts')
                        ->where('facility_id', $facilityId)
                        ->where('name', "$prefix $i")
                        ->value('id');

                    if (! $courtId) {
                        continue;
                    }

                    $primaryImage = null;
                    $secondaryImage = null;

                    if ($typeName === 'Bóng đá mini' && $soccerCount > 0) {
                        $primaryImage = $soccerFiles[$soccerIndex % $soccerCount];
                        $secondaryImage = $soccerFiles[($soccerIndex + 1) % $soccerCount];
                        $soccerIndex++;
                    } elseif ($typeName === 'Cầu lông' && $badmintonCount > 0) {
                        $primaryImage = $badmintonFiles[$badmintonIndex % $badmintonCount];
                        $secondaryImage = $badmintonFiles[($badmintonIndex + 1) % $badmintonCount];
                        $badmintonIndex++;
                    } else {
                        if (! empty($generalFiles)) {
                            $primaryImage = $generalFiles[0];
                            if (count($generalFiles) > 1) {
                                $secondaryImage = $generalFiles[1];
                            }
                        }
                    }

                    if ($primaryImage) {
                        $courtImagesToInsert[] = [
                            'court_id' => $courtId,
                            'image_path' => $primaryImage,
                            'is_primary' => true,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];

                        if ($secondaryImage && $secondaryImage !== $primaryImage) {
                            $courtImagesToInsert[] = [
                                'court_id' => $courtId,
                                'image_path' => $secondaryImage,
                                'is_primary' => false,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ];
                        }
                    }
                }
            }
        }

        // Xóa và cập nhật lại hình ảnh sân
        DB::table('court_images')->delete();
        foreach (array_chunk($courtImagesToInsert, 500) as $chunk) {
            DB::table('court_images')->insert($chunk);
        }

        // Một sân đang bảo trì để demo trạng thái
        DB::table('courts')->where('name', 'Sân pickleball 2')->limit(1)->update(['status' => 'maintenance']);
    }
}
