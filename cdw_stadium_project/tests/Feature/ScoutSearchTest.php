<?php

namespace Tests\Feature;

use App\Models\Court;
use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScoutSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_displays_text_and_button_search_bar(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('name="q"', false);
        $response->assertDontSee('name="sport_type"', false);
        $response->assertDontSee('name="location"', false);
    }

    public function test_unified_search_returns_matching_courts_and_news_via_scout(): void
    {
        Court::factory()->create([
            'name' => 'Sân Pickleball Thảo Điền',
            'status' => 'available',
        ]);

        Court::factory()->create([
            'name' => 'Sân Bóng Đá Phú Nhuận',
            'status' => 'available',
        ]);

        News::factory()->create([
            'title' => 'Giải đấu Pickleball mở rộng 2026',
            'slug' => 'giai-dau-pickleball-mo-rong-2026',
            'status' => 'published',
        ]);

        News::factory()->create([
            'title' => 'Bí quyết chọn giày chạy bộ',
            'slug' => 'bi-quyet-chon-giay-chay-bo',
            'status' => 'published',
        ]);

        $response = $this->get(route('courts.search', ['q' => 'Pickleball']));

        $response->assertStatus(200);
        $response->assertViewIs('web.courts.search');
        $response->assertSee('Sân Pickleball Thảo Điền');
        $response->assertDontSee('Sân Bóng Đá Phú Nhuận');
        $response->assertSee('Giải đấu Pickleball mở rộng 2026');
        $response->assertDontSee('Bí quyết chọn giày chạy bộ');
    }

    public function test_search_redirects_to_courts_index_when_query_is_empty(): void
    {
        $response = $this->get(route('courts.search', ['q' => '   ']));

        $response->assertRedirect(route('courts.index'));
    }

    public function test_courts_index_searches_courts_via_scout(): void
    {
        Court::factory()->create([
            'name' => 'Sân Cầu Lông Viettel',
            'status' => 'available',
        ]);

        Court::factory()->create([
            'name' => 'Sân Bóng Rổ Quận 7',
            'status' => 'available',
        ]);

        $response = $this->get(route('courts.index', ['q' => 'Viettel']));

        $response->assertStatus(200);
        $response->assertViewIs('web.courts.index');
        $response->assertSee('Sân Cầu Lông Viettel');
        $response->assertDontSee('Sân Bóng Rổ Quận 7');
    }

    public function test_news_index_searches_published_news_via_scout(): void
    {
        News::factory()->create([
            'title' => 'Kỹ thuật đập cầu lông chuẩn xác',
            'slug' => 'ky-thuat-dap-cau-long-chuan-xac',
            'status' => 'published',
        ]);

        News::factory()->create([
            'title' => 'Bản nháp kỹ thuật đập cầu lông',
            'slug' => 'ban-nhap-ky-thuat-dap-cau-long',
            'status' => 'draft',
        ]);

        News::factory()->create([
            'title' => 'Lịch thi đấu bóng đá cuối tuần',
            'slug' => 'lich-thi-dau-bong-da-cuoi-tuan',
            'status' => 'published',
        ]);

        $response = $this->get(route('news.index', ['q' => 'đập cầu lông']));

        $response->assertStatus(200);
        $response->assertViewIs('web.news.index');
        $response->assertSee('Kỹ thuật đập cầu lông chuẩn xác');
        $response->assertDontSee('Bản nháp kỹ thuật đập cầu lông');
        $response->assertDontSee('Lịch thi đấu bóng đá cuối tuần');
    }
}
