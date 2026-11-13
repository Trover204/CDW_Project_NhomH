<?php

namespace Tests\Feature\Admin;

use App\Models\ActivityLog;
use App\Models\Ad;
use App\Models\Booking;
use App\Models\Comment;
use App\Models\Court;
use App\Models\Facility;
use App\Models\News;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Review;
use App\Models\Sale;
use App\Models\SportType;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SidebarCrudActivityLogTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->customer = User::factory()->create(['role' => 'customer']);
    }

    public function test_facility_crud_and_activity_log(): void
    {
        // Create without explicit version input (version defaults to 0)
        $this->actingAs($this->admin)->post(route('admin.facilities.store'), [
            'name' => 'Cơ sở Test CRUD',
            'district' => 'Thủ Đức',
            'address' => '100 Võ Văn Ngân, TP. Thủ Đức',
            'phone' => '0901112222',
            'open_time' => '06:00',
            'close_time' => '22:00',
            'status' => '1',
        ])->assertRedirect(route('admin.facilities.index'));

        $facility = Facility::where('name', 'Cơ sở Test CRUD')->firstOrFail();
        $this->assertTrue($facility->status);
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'Created',
        ]);

        // Update (uncheck status -> status omitted from request -> should become false)
        $this->actingAs($this->admin)->put(route('admin.facilities.update', $facility), [
            'name' => 'Cơ sở Test CRUD Đã Sửa',
            'district' => 'Thủ Đức',
            'address' => '100 Võ Văn Ngân, TP. Thủ Đức',
            'phone' => '0901112222',
            'open_time' => '06:00',
            'close_time' => '23:00',
            'version' => $facility->version,
        ])->assertRedirect(route('admin.facilities.index'));

        $facility->refresh();
        $this->assertEquals('Cơ sở Test CRUD Đã Sửa', $facility->name);
        $this->assertFalse($facility->status);
        $this->assertEquals(1, $facility->version);
        $this->assertTrue(
            ActivityLog::where('action', 'Updated')
                ->where('description', 'like', "%Updated Facility ID: {$facility->id}%")
                ->exists()
        );

        // Destroy
        $this->actingAs($this->admin)
            ->delete(route('admin.facilities.destroy', $facility))
            ->assertRedirect(route('admin.facilities.index'));

        $this->assertDatabaseMissing('facilities', ['id' => $facility->id]);
        $this->assertTrue(
            ActivityLog::where('action', 'Deleted')
                ->where('description', 'like', "%Deleted Facility ID: {$facility->id}%")
                ->exists()
        );
    }

    public function test_sport_type_crud_and_activity_log(): void
    {
        $this->actingAs($this->admin)->post(route('admin.sport-types.store'), [
            'name' => 'Bóng đá CRUD',
            'description' => 'Môn thể thao vua',
            'status' => '1',
        ])->assertRedirect(route('admin.sport-types.index'));

        $sportType = SportType::where('name', 'Bóng đá CRUD')->firstOrFail();
        $this->assertTrue(
            ActivityLog::where('action', 'Created')
                ->where('description', 'like', "%Created SportType ID: {$sportType->id}%")
                ->exists()
        );

        // Update (redirects to admin.sport-types.index and logs Updated)
        $this->actingAs($this->admin)->put(route('admin.sport-types.update', $sportType), [
            'name' => 'Bóng đá Mini CRUD',
            'description' => 'Đã cập nhật mô tả',
            'version' => $sportType->version,
        ])->assertRedirect(route('admin.sport-types.index'));

        $sportType->refresh();
        $this->assertFalse($sportType->status);
        $this->assertTrue(
            ActivityLog::where('action', 'Updated')
                ->where('description', 'like', "%Updated SportType ID: {$sportType->id}%")
                ->exists()
        );

        // Destroy
        $this->actingAs($this->admin)
            ->delete(route('admin.sport-types.destroy', $sportType))
            ->assertRedirect(route('admin.sport-types.index'));

        $this->assertDatabaseMissing('sport_types', ['id' => $sportType->id]);
        $this->assertTrue(
            ActivityLog::where('action', 'Deleted')
                ->where('description', 'like', "%Deleted SportType ID: {$sportType->id}%")
                ->exists()
        );
    }

    public function test_court_crud_and_activity_log(): void
    {
        $facility = Facility::factory()->create();
        $sportType = SportType::factory()->create();

        $this->actingAs($this->admin)->post(route('admin.courts.store'), [
            'facility_id' => $facility->id,
            'sport_type_id' => $sportType->id,
            'name' => 'Sân Pickleball 01',
            'capacity' => 4,
            'description' => 'Sân tiêu chuẩn quốc tế',
            'status' => 'available',
        ])->assertRedirect(route('admin.courts.index'));

        $court = Court::where('name', 'Sân Pickleball 01')->firstOrFail();
        $this->assertTrue(
            ActivityLog::where('action', 'Created')
                ->where('description', 'like', "%Created Court ID: {$court->id}%")
                ->exists()
        );

        // Update
        $this->actingAs($this->admin)->put(route('admin.courts.update', $court), [
            'facility_id' => $facility->id,
            'sport_type_id' => $sportType->id,
            'name' => 'Sân Pickleball VIP 01',
            'capacity' => 6,
            'description' => 'Sân nâng cấp',
            'status' => 'maintenance',
            'version' => $court->version,
        ])->assertRedirect(route('admin.courts.index'));

        $this->assertTrue(
            ActivityLog::where('action', 'Updated')
                ->where('description', 'like', "%Updated Court ID: {$court->id}%")
                ->exists()
        );
    }

    public function test_optimistic_locking_detects_concurrent_updates_and_prevents_overwriting(): void
    {
        // 1. Facility concurrent update conflict
        $facility = Facility::factory()->create([
            'name' => 'Cơ sở Ban Đầu',
            'version' => 0,
        ]);

        // Admin A và Admin B cùng mở trang sửa ở version = 0
        $staleVersion = $facility->version;

        // Admin A lưu thành công -> version tăng lên 1
        $this->actingAs($this->admin)->put(route('admin.facilities.update', $facility), [
            'name' => 'Cơ sở Do Admin A Cập Nhật',
            'district' => $facility->district,
            'address' => $facility->address,
            'phone' => $facility->phone,
            'open_time' => substr((string) $facility->open_time, 0, 5),
            'close_time' => substr((string) $facility->close_time, 0, 5),
            'status' => '1',
            'version' => $staleVersion,
        ])->assertRedirect(route('admin.facilities.index'));

        $facility->refresh();
        $this->assertEquals(1, $facility->version);
        $this->assertEquals('Cơ sở Do Admin A Cập Nhật', $facility->name);

        // Admin B gửi form sau với version cũ ($staleVersion = 0) -> bị từ chối, dữ liệu của Admin A được bảo toàn
        $conflictResponse = $this->actingAs($this->admin)
            ->from(route('admin.facilities.edit', $facility))
            ->put(route('admin.facilities.update', $facility), [
                'name' => 'Cơ sở Do Admin B Ghi Đè',
                'district' => $facility->district,
                'address' => $facility->address,
                'phone' => $facility->phone,
                'open_time' => substr((string) $facility->open_time, 0, 5),
                'close_time' => substr((string) $facility->close_time, 0, 5),
                'status' => '1',
                'version' => $staleVersion,
            ]);

        $conflictResponse->assertRedirect(route('admin.facilities.edit', $facility));
        $conflictResponse->assertSessionHas('error', Facility::getOptimisticLockErrorMessage());

        $facility->refresh();
        $this->assertEquals(1, $facility->version);
        $this->assertEquals('Cơ sở Do Admin A Cập Nhật', $facility->name);

        // 2. Transactional Sale + Payment optimistic locking rollback
        $product = Product::factory()->create(['cost_price' => 200000, 'stock_quantity' => 50]);
        $sale = Sale::factory()->create([
            'product_id' => $product->id,
            'user_id' => $this->customer->id,
            'quantity' => 1,
            'total_price' => 200000,
            'status' => 'pending',
            'version' => 0,
        ]);

        // Giả lập tiến trình khác đã nâng version của Sale lên 1
        Sale::whereKey($sale->id)->update(['version' => 1]);

        // Request gửi lên với version = 0 cũ -> rollback toàn bộ (kể cả Payment)
        $saleConflict = $this->actingAs($this->admin)
            ->from(route('admin.sales.edit', $sale))
            ->put(route('admin.sales.update', $sale), [
                'product_id' => $product->id,
                'user_id' => $this->customer->id,
                'quantity' => 5,
                'total_price' => 999000,
                'status' => 'completed',
                'sale_date' => now()->toDateString(),
                'payment_method' => 'vnpay',
                'payment_status' => 'paid',
                'version' => 0,
            ]);

        $saleConflict->assertRedirect(route('admin.sales.edit', $sale));
        $saleConflict->assertSessionHas('error', Sale::getOptimisticLockErrorMessage());

        $sale->refresh();
        $this->assertEquals(1, $sale->version);
        $this->assertEquals(200000.0, $sale->total_price);
    }

    public function test_all_other_models_record_activity_logs_on_crud(): void
    {
        $facility = Facility::factory()->create();
        $sportType = SportType::factory()->create();
        $court = Court::factory()->create([
            'facility_id' => $facility->id,
            'sport_type_id' => $sportType->id,
        ]);

        // Product
        $product = Product::factory()->create(['name' => 'Vợt Cầu Lông Pro']);
        $product->update(['name' => 'Vợt Cầu Lông Pro V2']);
        $this->assertTrue(
            ActivityLog::where('action', 'Created')
                ->where('description', 'like', "%Created Product ID: {$product->id}%")
                ->exists()
        );
        $this->assertTrue(
            ActivityLog::where('action', 'Updated')
                ->where('description', 'like', "%Updated Product ID: {$product->id}%")
                ->exists()
        );

        // Plan & Subscription
        $plan = Plan::factory()->create(['name' => 'Gói Kim Cương']);
        $this->assertTrue(
            ActivityLog::where('action', 'Created')
                ->where('description', 'like', "%Created Plan ID: {$plan->id}%")
                ->exists()
        );

        $subscription = Subscription::factory()->create([
            'user_id' => $this->customer->id,
            'plan_id' => $plan->id,
        ]);
        $this->assertTrue(
            ActivityLog::where('action', 'Created')
                ->where('description', 'like', "%Created Subscription ID: {$subscription->id}%")
                ->exists()
        );

        // Sale & Payment
        $sale = Sale::factory()->create([
            'product_id' => $product->id,
            'user_id' => $this->customer->id,
        ]);
        $this->assertTrue(
            ActivityLog::where('action', 'Created')
                ->where('description', 'like', "%Created Sale ID: {$sale->id}%")
                ->exists()
        );

        $payment = Payment::latest('id')->first();
        if ($payment) {
            $this->assertTrue(
                ActivityLog::where('action', 'Created')
                    ->where('description', 'like', "%Created Payment ID: {$payment->id}%")
                    ->exists()
            );
        }

        // Booking & Review
        $booking = Booking::factory()->create([
            'user_id' => $this->customer->id,
            'court_id' => $court->id,
        ]);
        $this->assertTrue(
            ActivityLog::where('action', 'Created')
                ->where('description', 'like', "%Created Booking ID: {$booking->id}%")
                ->exists()
        );

        $review = Review::create([
            'user_id' => $this->customer->id,
            'court_id' => $court->id,
            'booking_id' => $booking->id,
            'rating' => 5,
            'content' => 'Sân rất đẹp',
            'status' => true,
        ]);
        $this->assertTrue(
            ActivityLog::where('action', 'Created')
                ->where('description', 'like', "%Created Review ID: {$review->id}%")
                ->exists()
        );

        // News & Comment & Ad
        $news = News::factory()->create([
            'user_id' => $this->admin->id,
            'title' => 'Giải đấu Pickleball mùa hè',
        ]);
        $this->assertTrue(
            ActivityLog::where('action', 'Created')
                ->where('description', 'like', "%Created News ID: {$news->id}%")
                ->exists()
        );

        $comment = Comment::factory()->create([
            'user_id' => $this->customer->id,
            'news_id' => $news->id,
            'content' => 'Bài viết rất bổ ích',
        ]);
        $this->assertTrue(
            ActivityLog::where('action', 'Created')
                ->where('description', 'like', "%Created Comment ID: {$comment->id}%")
                ->exists()
        );

        $ad = Ad::factory()->create(['name' => 'Banner Khuyến Mãi']);
        $this->assertTrue(
            ActivityLog::where('action', 'Created')
                ->where('description', 'like', "%Created Ad ID: {$ad->id}%")
                ->exists()
        );
    }
}
