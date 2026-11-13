<?php

namespace Tests\Feature;

use App\Models\Court;
use App\Models\PriceRule;
use Database\Seeders\CourtSeeder;
use Database\Seeders\FacilitySeeder;
use Database\Seeders\PriceRuleSeeder;
use Database\Seeders\SportTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CourtSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_court_seeder_and_price_rule_seeder(): void
    {
        $this->seed([
            SportTypeSeeder::class,
            FacilitySeeder::class,
            CourtSeeder::class,
            PriceRuleSeeder::class,
        ]);

        // Kiểm tra đúng 6 bộ môn được seed
        $expectedTypes = ['Bóng đá', 'Cầu lông', 'Pickleball', 'Bóng rổ', 'Bóng bàn', 'Tennis'];
        $seededTypes = DB::table('sport_types')->pluck('name')->toArray();
        $this->assertCount(6, $seededTypes);
        $this->assertEqualsCanonicalizing($expectedTypes, $seededTypes);

        // Kiểm tra courts đã được seed (25 cơ sở x 6 bộ môn x 2 sân = 300 sân)
        $courtCount = DB::table('courts')->count();
        $this->assertSame(300, $courtCount);

        // Kiểm tra court_images đã được seed từ public/images
        $courtImageCount = DB::table('court_images')->count();
        $this->assertGreaterThan(0, $courtImageCount);

        // Kiểm tra sân bóng đá có ảnh sanbanh
        $soccerCourt = Court::where('name', 'Sân bóng 1')->first();
        $this->assertNotNull($soccerCourt);
        $this->assertTrue($soccerCourt->images()->exists());
        $this->assertStringContainsString('images/', $soccerCourt->images()->first()->image_path);

        // Kiểm tra sân cầu lông có ảnh caulong
        $badmintonCourt = Court::where('name', 'Sân cầu lông 1')->first();
        $this->assertNotNull($badmintonCourt);
        $this->assertTrue($badmintonCourt->images()->exists());
        $this->assertStringContainsString('images/caulong', $badmintonCourt->images()->first()->image_path);

        // Kiểm tra price_rules có cả giá chung và giá riêng theo sân
        $generalPriceRules = PriceRule::whereNull('court_id')->count();
        $this->assertGreaterThan(0, $generalPriceRules);

        $courtPriceRules = PriceRule::whereNotNull('court_id')->count();
        $this->assertGreaterThan(0, $courtPriceRules);

        // Kiểm tra quan hệ priceRules của Court
        $this->assertTrue($soccerCourt->priceRules()->exists());
        $this->assertNotNull($soccerCourt->price);
        $this->assertGreaterThan(0, $soccerCourt->price);
    }

    public function test_sport_type_seeder_removes_other_sports_and_syncs_all_related_seeders(): void
    {
        // Tạo dữ liệu bộ môn cũ ("Bóng đá mini", "Bơi lội", "Yoga") trước khi chạy seeder
        $legacyId = DB::table('sport_types')->insertGetId([
            'name' => 'Bóng đá mini',
            'description' => 'Cũ',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $extraId = DB::table('sport_types')->insertGetId([
            'name' => 'Bơi lội',
            'description' => 'Hồ bơi',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->seed([
            \Database\Seeders\UserSeeder::class,
            SportTypeSeeder::class,
            FacilitySeeder::class,
            CourtSeeder::class,
            PriceRuleSeeder::class,
            \Database\Seeders\PlanSeeder::class,
            \Database\Seeders\SubscriptionSeeder::class,
            \Database\Seeders\BookingSeeder::class,
            \Database\Seeders\ReviewSeeder::class,
        ]);

        $expectedTypes = ['Bóng đá', 'Cầu lông', 'Pickleball', 'Bóng rổ', 'Bóng bàn', 'Tennis'];
        $sportTypes = DB::table('sport_types')->pluck('name', 'id');

        $this->assertCount(6, $sportTypes);
        $this->assertEqualsCanonicalizing($expectedTypes, $sportTypes->values()->all());
        $this->assertDatabaseMissing('sport_types', ['id' => $extraId]);
        $this->assertDatabaseHas('sport_types', ['id' => $legacyId, 'name' => 'Bóng đá']);

        // Mọi sân và bảng giá chỉ thuộc về 6 bộ môn này
        $invalidCourts = DB::table('courts')->whereNotIn('sport_type_id', $sportTypes->keys())->count();
        $invalidPrices = DB::table('price_rules')->whereNotIn('sport_type_id', $sportTypes->keys())->count();
        $this->assertSame(0, $invalidCourts);
        $this->assertSame(0, $invalidPrices);
    }
}
