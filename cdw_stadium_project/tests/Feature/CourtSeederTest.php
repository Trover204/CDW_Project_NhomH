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

        // Kiểm tra courts đã được seed
        $courtCount = DB::table('courts')->count();
        $this->assertGreaterThan(0, $courtCount);

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
}
