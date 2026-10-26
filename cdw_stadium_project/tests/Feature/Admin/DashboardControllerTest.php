<?php

namespace Tests\Feature\Admin;

use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Court;
use App\Models\Facility;
use App\Models\News;
use App\Models\Product;
use App\Models\Review;
use App\Models\Sale;
use App\Models\SportType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard_and_see_stats()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        ActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'login',
            'description' => 'Admin logged in',
            'ip_address' => '127.0.0.1',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');

        $response->assertViewHasAll([
            'tableStats',
            'stats',
            'weeklyRevenue',
            'courtTypes',
            'recentBookings',
            'activityLogs',
        ]);

        $tableStats = $response->viewData('tableStats');
        $this->assertArrayHasKey('sport', $tableStats);
        $this->assertArrayHasKey('facility', $tableStats);
        $this->assertArrayHasKey('booking', $tableStats);
        $this->assertArrayHasKey('payment', $tableStats);
        $this->assertArrayHasKey('product', $tableStats);
        $this->assertArrayHasKey('sale', $tableStats);
        $this->assertArrayHasKey('review', $tableStats);
        $this->assertArrayHasKey('news', $tableStats);

        // Gỡ bỏ phần gói hội viên
        $this->assertArrayNotHasKey('plan', $tableStats);
        $this->assertArrayNotHasKey('subscription', $tableStats);
    }

    public function test_dashboard_displays_correct_table_statistics()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // 1. Sport
        $sport = SportType::factory()->create(['status' => true]);
        SportType::factory()->create(['status' => false]);

        // 2. Facility
        $facility = Facility::factory()->create(['status' => true, 'district' => 'Quận 1']);
        Facility::factory()->create(['status' => false, 'district' => 'Quận 3']);

        $court = Court::factory()->create([
            'facility_id' => $facility->id,
            'sport_type_id' => $sport->id,
        ]);

        // 3. Booking (tự động tạo Payment có payable_type = booking)
        $booking = Booking::factory()->create([
            'user_id' => $admin->id,
            'court_id' => $court->id,
            'status' => 'confirmed',
            'total_price' => 200000,
        ]);

        // 4. Product
        $product = Product::factory()->create([
            'name' => 'Vợt Cầu Lông Pro',
            'cost_price' => 350000,
            'stock_quantity' => 15,
            'is_in_stock' => true,
        ]);

        // 5. Sale (tự động tạo Payment có payable_type = sale)
        Sale::factory()->create([
            'product_id' => $product->id,
            'user_id' => $admin->id,
            'quantity' => 2,
            'total_price' => 700000,
            'status' => 'completed',
        ]);

        // 6. Review
        Review::factory()->create([
            'user_id' => $admin->id,
            'court_id' => $court->id,
            'booking_id' => $booking->id,
            'rating' => 5,
            'content' => 'Sân rất tuyệt vời!',
            'status' => 1,
        ]);

        // 7. News
        News::factory()->create([
            'user_id' => $admin->id,
            'title' => 'Giải đấu thể thao mùa hè',
            'category' => 'Thể thao',
            'status' => 'published',
            'views' => 120,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);

        $tableStats = $response->viewData('tableStats');

        // Check Sport stats (1 active, 1 inactive = 2)
        $this->assertEquals(2, $tableStats['sport']['total']);
        $this->assertEquals(1, $tableStats['sport']['active']);
        $this->assertEquals(1, $tableStats['sport']['inactive']);

        // Check Facility stats (1 active, 1 inactive = 2)
        $this->assertEquals(2, $tableStats['facility']['total']);
        $this->assertEquals(1, $tableStats['facility']['active']);
        $this->assertEquals(1, $tableStats['facility']['inactive']);

        // Check Booking stats
        $this->assertEquals(1, $tableStats['booking']['total']);
        $this->assertEquals(1, $tableStats['booking']['confirmed']);
        $this->assertEquals(200000, $tableStats['booking']['total_revenue']);

        // Check Payment stats (1 from booking + 1 from sale = 2)
        $this->assertEquals(2, $tableStats['payment']['total']);
        $this->assertEquals(2, $tableStats['payment']['paid_count']);
        $this->assertEquals(900000, $tableStats['payment']['paid_revenue']);

        // Check Product stats
        $this->assertEquals(1, $tableStats['product']['total']);
        $this->assertEquals(1, $tableStats['product']['in_stock']);
        $this->assertEquals(15, $tableStats['product']['total_stock']);
        $this->assertEquals(350000, $tableStats['product']['avg_price']);

        // Check Sale stats
        $this->assertEquals(1, $tableStats['sale']['total']);
        $this->assertEquals(1, $tableStats['sale']['completed']);
        $this->assertEquals(2, $tableStats['sale']['total_quantity']);
        $this->assertEquals(700000, $tableStats['sale']['total_revenue']);

        // Check Review stats
        $this->assertEquals(1, $tableStats['review']['total']);
        $this->assertEquals(5.0, $tableStats['review']['avg_rating']);
        $this->assertEquals(1, $tableStats['review']['approved']);

        // Check News stats
        $this->assertEquals(1, $tableStats['news']['total']);
        $this->assertEquals(1, $tableStats['news']['published']);
        $this->assertEquals(120, $tableStats['news']['total_views']);

        // Check HTML renders table names and headers
        $response->assertSee('Thống kê Cơ Sở Dữ Liệu');
        $response->assertSee('sport_types');
        $response->assertSee('facilities');
        $response->assertSee('bookings');
        $response->assertSee('payments');
        $response->assertSee('products');
        $response->assertSee('sales');
        $response->assertSee('reviews');
        $response->assertSee('news');

        // Verify "plans" and "subscriptions" are removed from dashboard table list
        $response->assertDontSee('<code>plans</code>', false);
        $response->assertDontSee('<code>subscriptions</code>', false);
    }

    public function test_non_admin_cannot_access_dashboard()
    {
        $user = User::factory()->create([
            'role' => 'customer',
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertRedirect(route('home'));
    }

    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }
}
