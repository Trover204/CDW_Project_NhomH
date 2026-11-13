<?php

namespace Database\Seeders;

use App\Models\Court;
use Database\Seeders\Concerns\SyncsSearchIndex;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourtSeeder extends Seeder
{
    use SyncsSearchIndex;

    public function run(): void
    {
        $now = now();
        $facilityIds = DB::table('facilities')->orderBy('id')->pluck('id');

        // tên loại môn => [tiền tố tên sân, sức chứa]
        $config = [
            'Bóng đá' => ['Sân bóng', 10],
            'Cầu lông' => ['Sân cầu lông', 4],
            'Pickleball' => ['Sân pickleball', 4],
            'Bóng rổ' => ['Sân bóng rổ', 10],
            'Bóng bàn' => ['Bàn bóng bàn', 4],
            'Tennis' => ['Sân tennis', 4],
        ];

        // Lấy danh sách ảnh sân bóng đá và cầu lông trong public/images
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

                    if ($typeName === 'Bóng đá' && $soccerCount > 0) {
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

        // Dọn dẹp các sân thuộc bộ môn không còn trong danh sách cấu hình
        $allowedSportTypeIds = DB::table('sport_types')->whereIn('name', array_keys($config))->pluck('id');
        $obsoleteCourtIds = DB::table('courts')->whereNotIn('sport_type_id', $allowedSportTypeIds)->pluck('id');
        if ($obsoleteCourtIds->isNotEmpty()) {
            $obsoleteBookingIds = DB::table('bookings')->whereIn('court_id', $obsoleteCourtIds)->pluck('id');
            if ($obsoleteBookingIds->isNotEmpty()) {
                DB::table('payments')->where('payable_type', 'booking')->whereIn('payable_id', $obsoleteBookingIds)->delete();
                DB::table('booking_logs')->whereIn('booking_id', $obsoleteBookingIds)->delete();
                DB::table('reviews')->whereIn('booking_id', $obsoleteBookingIds)->delete();
                DB::table('bookings')->whereIn('id', $obsoleteBookingIds)->delete();
            }
            DB::table('reviews')->whereIn('court_id', $obsoleteCourtIds)->delete();
            DB::table('court_user_likes')->whereIn('court_id', $obsoleteCourtIds)->delete();
            DB::table('court_images')->whereIn('court_id', $obsoleteCourtIds)->delete();
            DB::table('price_rules')->whereIn('court_id', $obsoleteCourtIds)->delete();
            DB::table('courts')->whereIn('id', $obsoleteCourtIds)->delete();
        }

        // Xóa và cập nhật lại hình ảnh sân
        DB::table('court_images')->delete();
        foreach (array_chunk($courtImagesToInsert, 500) as $chunk) {
            DB::table('court_images')->insert($chunk);
        }

        // Một sân đang bảo trì để demo trạng thái
        DB::table('courts')->where('name', 'Sân pickleball 2')->limit(1)->update(['status' => 'maintenance']);

        $this->syncSearchIndex(Court::class);
    }
}
