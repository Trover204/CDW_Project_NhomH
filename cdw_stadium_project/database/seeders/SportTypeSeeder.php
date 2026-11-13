<?php

namespace Database\Seeders;

use App\Models\SportType;
use Database\Seeders\Concerns\SyncsSearchIndex;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SportTypeSeeder extends Seeder
{
    use SyncsSearchIndex;

    public function run(): void
    {
        $now = now();
        $types = [
            ['Bóng đá',    'Sân cỏ nhân tạo 5-7 người'],
            ['Cầu lông',   'Sân cầu lông trong nhà, có mái che'],
            ['Pickleball', 'Sân pickleball tiêu chuẩn'],
            ['Bóng rổ',    'Sân bóng rổ tiêu chuẩn'],
            ['Bóng bàn',   'Bàn bóng bàn tiêu chuẩn trong nhà'],
            ['Tennis',     'Sân quần vợt chuẩn quốc tế'],
        ];

        $allowedNames = array_column($types, 0);

        // Chuyển đổi "Bóng đá mini" sang "Bóng đá" nếu đã tồn tại từ bản seed cũ
        $legacyFootball = DB::table('sport_types')->where('name', 'Bóng đá mini')->first();
        $newFootball = DB::table('sport_types')->where('name', 'Bóng đá')->first();

        if ($legacyFootball && ! $newFootball) {
            DB::table('sport_types')->where('id', $legacyFootball->id)->update([
                'name' => 'Bóng đá',
                'description' => 'Sân cỏ nhân tạo 5-7 người',
                'status' => 1,
                'updated_at' => $now,
            ]);
        } elseif ($legacyFootball && $newFootball) {
            DB::table('courts')->where('sport_type_id', $legacyFootball->id)->update(['sport_type_id' => $newFootball->id]);
            DB::table('price_rules')->where('sport_type_id', $legacyFootball->id)->delete();
            DB::table('sport_types')->where('id', $legacyFootball->id)->delete();
        }

        // Xóa các bộ môn ngoài 6 môn được giữ lại cùng dữ liệu phụ thuộc
        $obsoleteTypeIds = DB::table('sport_types')->whereNotIn('name', $allowedNames)->pluck('id');
        if ($obsoleteTypeIds->isNotEmpty()) {
            $obsoleteCourtIds = DB::table('courts')->whereIn('sport_type_id', $obsoleteTypeIds)->pluck('id');

            if ($obsoleteCourtIds->isNotEmpty()) {
                $obsoleteBookingIds = DB::table('bookings')->whereIn('court_id', $obsoleteCourtIds)->pluck('id');

                if ($obsoleteBookingIds->isNotEmpty()) {
                    DB::table('payments')
                        ->where('payable_type', 'booking')
                        ->whereIn('payable_id', $obsoleteBookingIds)
                        ->delete();
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

            DB::table('price_rules')->whereIn('sport_type_id', $obsoleteTypeIds)->delete();
            DB::table('sport_types')->whereIn('id', $obsoleteTypeIds)->delete();
        }

        foreach ($types as [$name, $desc]) {
            DB::table('sport_types')->updateOrInsert(
                ['name' => $name],
                ['description' => $desc, 'status' => 1, 'created_at' => $now, 'updated_at' => $now]
            );
        }

        $this->syncSearchIndex(SportType::class);
    }
}
