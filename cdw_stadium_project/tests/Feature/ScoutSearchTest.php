<?php

namespace Tests\Feature;

use App\Models\Ad;
use App\Models\Booking;
use App\Models\Court;
use App\Models\Facility;
use App\Models\News;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\PriceRule;
use App\Models\Product;
use App\Models\Review;
use App\Models\Sale;
use App\Models\SportType;
use App\Models\Subscription;
use App\Models\User;
use App\Scout\Factories\OptimizedSearchParametersFactory;
use Database\Seeders\FacilitySeeder;
use Database\Seeders\PlanSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\SportTypeSeeder;
use Database\Seeders\UserSeeder;
use Elastic\ScoutDriver\Engine as ElasticScoutDriverEngine;
use Elastic\ScoutDriver\Factories\SearchParametersFactoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Searchable;
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

    public function test_searchable_models_configure_elasticsearch_attributes_and_visibility(): void
    {
        $facility = Facility::factory()->create([
            'name' => 'Cơ sở Quận 7',
            'district' => 'Quận 7',
            'address' => '123 Nguyễn Thị Thập',
            'status' => true,
        ]);

        $sportType = SportType::factory()->create([
            'name' => 'Pickleball',
            'status' => true,
        ]);

        $activeCourt = Court::factory()->create([
            'facility_id' => $facility->id,
            'sport_type_id' => $sportType->id,
            'name' => 'Sân VIP 1',
            'status' => 'available',
        ]);

        $inactiveCourt = Court::factory()->create([
            'facility_id' => $facility->id,
            'sport_type_id' => $sportType->id,
            'name' => 'Sân Bảo Trì',
            'status' => 'inactive',
        ]);

        $product = Product::factory()->create([
            'name' => 'Áo đấu Đội tuyển',
            'team' => 'Việt Nam',
            'season' => '2026',
            'is_active' => true,
        ]);

        config(['scout.driver' => 'elasticsearch']);

        $this->assertTrue($activeCourt->shouldBeSearchable());
        $this->assertFalse($inactiveCourt->shouldBeSearchable());
        $this->assertTrue($facility->shouldBeSearchable());
        $this->assertTrue($sportType->shouldBeSearchable());
        $this->assertTrue($product->shouldBeSearchable());

        $courtArray = $activeCourt->fresh(['facility', 'sportType'])->toSearchableArray();
        $this->assertSame('Cơ sở Quận 7', $courtArray['facility_name']);
        $this->assertSame('Quận 7', $courtArray['district']);
        $this->assertSame('Pickleball', $courtArray['sport_type_name']);

        $productArray = $product->toSearchableArray();
        $this->assertSame('Áo đấu Đội tuyển', $productArray['name']);
        $this->assertSame('Việt Nam', $productArray['team']);
    }

    public function test_elasticsearch_scout_driver_indexes_and_searches_records(): void
    {
        $court = Court::factory()->create([
            'name' => 'Sân Tennis Trung Tâm',
            'status' => 'available',
        ]);

        Http::fake([
            '*/_bulk*' => Http::response(['errors' => false], 200),
            '*/_mapping*' => Http::response(['acknowledged' => true], 200),
            '*/courts' => Http::response(['acknowledged' => true], 200),
            '*/courts/_search*' => Http::response([
                'hits' => [
                    'total' => ['value' => 1],
                    'hits' => [
                        [
                            '_id' => (string) $court->id,
                            '_source' => ['id' => $court->id, 'name' => $court->name],
                        ],
                    ],
                ],
            ], 200),
        ]);

        config([
            'scout.driver' => 'elasticsearch',
            'scout.elasticsearch.host' => 'http://elasticsearch:9200',
        ]);

        $court->searchable();

        $results = Court::search('Tennis')->get();

        $this->assertCount(1, $results);
        $this->assertSame($court->id, $results->first()->id);
    }

    public function test_admin_dashboard_search_bar_targets_admin_search_route(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee(route('admin.search'), false);
        $response->assertSee('name="q"', false);
        $response->assertSee('name="mode"', false);
        $response->assertSee('value="elasticsearch"', false);
        $response->assertSee('value="normal"', false);
    }

    public function test_admin_search_supports_switching_between_normal_search_and_elasticsearch(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $facility = Facility::factory()->create([
            'name' => 'Cơ sở Ngưng Hoạt Động',
            'district' => 'Bình Thạnh',
            'status' => false,
        ]);

        $inactiveCourt = Court::factory()->create([
            'facility_id' => $facility->id,
            'name' => 'Sân VIP Đang Ẩn',
            'status' => 'inactive',
        ]);

        // Khi tìm kiếm bằng Elasticsearch (mặc định), các bản ghi không được index (status = inactive) sẽ không xuất hiện trong index ES
        Http::fake([
            '*/_search*' => Http::response([
                'hits' => [
                    'total' => ['value' => 0],
                    'hits' => [],
                ],
            ], 200),
            '*' => Http::response(['acknowledged' => true], 200),
        ]);

        config([
            'scout.driver' => 'elasticsearch',
            'scout.elasticsearch.host' => 'http://elasticsearch:9200',
        ]);

        $esResponse = $this->actingAs($admin)->get(route('admin.search', [
            'q' => 'Đang Ẩn',
            'mode' => 'elasticsearch',
        ]));

        $esResponse->assertStatus(200);
        $esResponse->assertViewHas('searchMode', 'elasticsearch');
        $esResponse->assertDontSee('Sân VIP Đang Ẩn');
        Http::assertSent(fn (Request $request) => str_contains($request->url(), '/courts/_search'));

        // Khi chuyển sang chế độ Tìm kiếm thường (mode = normal), truy vấn trực tiếp SQL Eloquent không gọi HTTP tới Elasticsearch
        Http::fake();

        $normalResponse = $this->actingAs($admin)->get(route('admin.search', [
            'q' => 'Bình Thạnh',
            'mode' => 'normal',
        ]));

        $normalResponse->assertStatus(200);
        $normalResponse->assertViewHas('searchMode', 'normal');
        $normalResponse->assertSee('Tìm kiếm thường (SQL Database)');
        $normalResponse->assertSee('Cơ sở Ngưng Hoạt Động');
        $normalResponse->assertSee($inactiveCourt->name);
        Http::assertNothingSent();
    }

    public function test_admin_search_page_displays_unified_results_and_supports_type_filter(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Facility::factory()->create([
            'name' => 'Cơ sở Pickleball Thủ Đức',
            'district' => 'Thủ Đức',
            'status' => true,
        ]);

        Court::factory()->create([
            'name' => 'Sân Pickleball Pro 01',
            'status' => 'available',
        ]);

        Product::factory()->create([
            'name' => 'Vợt Pickleball Carbon',
            'is_active' => true,
            'is_in_stock' => true,
        ]);

        News::factory()->create([
            'title' => 'Giải vô địch Pickleball Admin Cup',
            'slug' => 'giai-vo-dich-pickleball-admin-cup',
            'status' => 'published',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.search', ['q' => 'Pickleball']));

        $response->assertStatus(200);
        $response->assertViewIs('admin.search');
        $response->assertSee('Cơ sở Pickleball Thủ Đức');
        $response->assertSee('Sân Pickleball Pro 01');
        $response->assertSee('Vợt Pickleball Carbon');
        $response->assertSee('Giải vô địch Pickleball Admin Cup');
        // Các section không có kết quả (ví dụ: Đơn bán hàng, Quảng cáo) không được render ra
        $response->assertDontSee('Quản lý đơn bán hàng');
        $response->assertDontSee('Quản lý quảng cáo');

        $filteredResponse = $this->actingAs($admin)->get(route('admin.search', [
            'q' => 'Pickleball',
            'type' => 'products',
        ]));

        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Vợt Pickleball Carbon');
        $filteredResponse->assertDontSee('Sân Pickleball Pro 01');
    }

    public function test_all_seeder_models_are_searchable_and_configured_in_elasticsearch(): void
    {
        $seederModels = [
            User::class,
            SportType::class,
            Facility::class,
            Court::class,
            PriceRule::class,
            Plan::class,
            Subscription::class,
            News::class,
            Ad::class,
            Booking::class,
            Review::class,
            Product::class,
            Sale::class,
            Payment::class,
        ];

        $indexSettings = config('scout.elasticsearch.index-settings', []);

        foreach ($seederModels as $modelClass) {
            $this->assertTrue(
                in_array(Searchable::class, class_uses_recursive($modelClass)),
                "Model [{$modelClass}] must use Searchable trait."
            );

            $indexName = (new $modelClass)->searchableAs();
            $this->assertArrayHasKey(
                $indexName,
                $indexSettings,
                "Index [{$indexName}] for [{$modelClass}] must be configured in config/scout.php."
            );
        }
    }

    public function test_seeders_automatically_import_models_into_elasticsearch(): void
    {
        Http::fake([
            '*/_delete_by_query*' => Http::response(['deleted' => 0], 200),
            '*/_bulk*' => Http::response(['errors' => false], 200),
            '*' => Http::response(['acknowledged' => true], 200),
        ]);

        config([
            'scout.driver' => 'elasticsearch',
            'scout.elasticsearch.host' => 'http://elasticsearch:9200',
        ]);

        $this->seed([
            UserSeeder::class,
            SportTypeSeeder::class,
            FacilitySeeder::class,
            PlanSeeder::class,
            ProductSeeder::class,
        ]);

        $this->assertGreaterThan(0, User::count());
        $this->assertGreaterThan(0, SportType::count());
        $this->assertGreaterThan(0, Facility::count());
        $this->assertGreaterThan(0, Plan::count());
        $this->assertGreaterThan(0, Product::count());

        Http::assertSent(fn (Request $request) => str_contains($request->url(), '/users/_delete_by_query'));
        Http::assertSent(fn (Request $request) => str_contains($request->url(), '/sport_types/_delete_by_query'));
        Http::assertSent(fn (Request $request) => str_contains($request->url(), '/facilities/_delete_by_query'));
        Http::assertSent(fn (Request $request) => str_contains($request->url(), '/plans/_delete_by_query'));
        Http::assertSent(fn (Request $request) => str_contains($request->url(), '/products/_delete_by_query'));
        Http::assertSent(fn (Request $request) => str_contains($request->url(), '/_bulk'));
    }

    public function test_elasticsearch_golden_rules_and_elastic_scout_driver_integration(): void
    {
        // Kiểm tra tích hợp babenkoivan/elastic-scout-driver
        $factory = app(SearchParametersFactoryInterface::class);
        $this->assertInstanceOf(OptimizedSearchParametersFactory::class, $factory);

        $elasticEngine = app(EngineManager::class)->driver('elastic');
        $this->assertInstanceOf(ElasticScoutDriverEngine::class, $elasticEngine);

        // Kiểm tra Quy tắc 2, 4, 5 trong cấu hình Mapping của 14 index
        $courtsConfig = config('scout.elasticsearch.index-settings.courts');
        $this->assertSame('keyword', $courtsConfig['mappings']['properties']['status']['type']);
        $this->assertSame('keyword', $courtsConfig['mappings']['properties']['district']['type']);
        $this->assertFalse($courtsConfig['mappings']['properties']['description']['norms']);
        $this->assertSame('freqs', $courtsConfig['mappings']['properties']['description']['index_options']);
        $this->assertContains('id', $courtsConfig['_source']);
        $this->assertNotContains('description', $courtsConfig['_source']);

        // Kiểm tra Quy tắc 1 (Filter Context), Quy tắc 3 (search_after), Quy tắc 4 (_source & fields)
        $builder = Court::search('Pickleball')
            ->where('status', 'available')
            ->whereIn('sport_type_id', [1, 2])
            ->searchAfter([1.5, 42]);

        $params = $factory->makeFromBuilder($builder, ['perPage' => 12, 'page' => 3])->toArray();
        $body = $params['body'];

        // Quy tắc 1: Điều kiện cố định nằm trong bool.filter, không nằm trong bool.must
        $this->assertArrayHasKey('filter', $body['query']['bool']);
        $this->assertCount(1, $body['query']['bool']['must']);
        $this->assertArrayHasKey('multi_match', $body['query']['bool']['must'][0]);
        $this->assertContains(['term' => ['status' => 'available']], $body['query']['bool']['filter']);
        $this->assertContains(['terms' => ['sport_type_id' => [1, 2]]], $body['query']['bool']['filter']);

        // Quy tắc 3: search_after được gửi kèm sort tie-breaker và loại bỏ tham số from
        $this->assertSame([1.5, 42], $body['search_after']);
        $this->assertArrayNotHasKey('from', $body);
        $this->assertNotEmpty($body['sort']);

        // Quy tắc 4: _source và fields được giới hạn gọn nhẹ
        $this->assertSame($courtsConfig['_source'], $body['_source']);
        $this->assertSame($courtsConfig['fields'], $body['fields']);

        // Quy tắc 1 khi không có từ khóa: match_all nằm trong bool.filter (_score = 0), không có bool.must
        $emptyKeywordBuilder = Court::search('')->where('status', 'available');
        $emptyKeywordBody = $factory->makeFromBuilder($emptyKeywordBuilder)->toArray()['body'];
        $this->assertArrayNotHasKey('must', $emptyKeywordBody['query']['bool']);
        $this->assertArrayHasKey('filter', $emptyKeywordBody['query']['bool']);
    }
}
