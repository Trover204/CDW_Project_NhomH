<?php

namespace Tests\Feature\Admin;

use App\Models\Booking;
use App\Models\Court;
use App\Models\Facility;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SportType;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $customer;

    protected Court $court;

    protected Product $product;

    protected Plan $plan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->customer = User::factory()->create(['role' => 'customer']);

        $facility = Facility::factory()->create();
        $sportType = SportType::factory()->create();
        $this->court = Court::factory()->create([
            'facility_id' => $facility->id,
            'sport_type_id' => $sportType->id,
        ]);

        $this->product = Product::factory()->create(['cost_price' => 150000, 'stock_quantity' => 20]);
        $this->plan = Plan::factory()->create(['price' => 499000, 'duration_days' => 30]);
    }

    public function test_admin_can_view_payments_index_with_summary(): void
    {
        Booking::factory()->create([
            'user_id' => $this->customer->id,
            'court_id' => $this->court->id,
            'total_price' => 300000,
            'status' => 'completed',
        ]);

        Sale::factory()->create([
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'total_price' => 150000,
            'status' => 'completed',
        ]);

        Subscription::factory()->create([
            'user_id' => $this->customer->id,
            'plan_id' => $this->plan->id,
            'price' => 499000,
            'status' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.payments.index'));

        $response->assertStatus(200);
        $response->assertSee('Quản lý Thanh toán');
        $response->assertViewHas('summary', function (array $summary) {
            return $summary['total_paid'] === 949000.0
                && $summary['booking_paid'] === 300000.0
                && $summary['sale_paid'] === 150000.0
                && $summary['subscription_paid'] === 499000.0;
        });
    }

    public function test_admin_can_create_update_show_and_delete_payment(): void
    {
        $sale = Sale::factory()->create([
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'total_price' => 250000,
            'status' => 'completed',
        ]);
        // Xóa payment tự tạo để test luồng tạo mới thủ công qua CRUD
        $sale->payment()->delete();

        $storeResponse = $this->actingAs($this->admin)->post(route('admin.payments.store'), [
            'payable_type' => Payment::TYPE_SALE,
            'payable_id' => $sale->id,
            'user_id' => $this->customer->id,
            'amount' => 250000,
            'payment_method' => 'momo',
            'transaction_code' => 'SL-MANUAL-001',
            'status' => 'pending',
            'note' => 'Tạo thủ công từ admin',
        ]);

        $storeResponse->assertRedirect(route('admin.payments.index'));
        $payment = Payment::where('transaction_code', 'SL-MANUAL-001')->firstOrFail();

        $this->assertEquals(Payment::TYPE_SALE, $payment->payable_type);
        $this->assertEquals($sale->id, $payment->payable_id);
        $this->assertEquals(250000.0, $payment->amount);

        // Show
        $this->actingAs($this->admin)
            ->get(route('admin.payments.show', $payment))
            ->assertStatus(200)
            ->assertSee('SL-MANUAL-001');

        // Update sang paid
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.payments.update', $payment), [
            'payable_type' => Payment::TYPE_SALE,
            'payable_id' => $sale->id,
            'user_id' => $this->customer->id,
            'amount' => 250000,
            'payment_method' => 'vnpay',
            'transaction_code' => 'SL-MANUAL-001',
            'status' => 'paid',
            'note' => 'Đã đối soát',
        ]);

        $updateResponse->assertRedirect(route('admin.payments.index'));
        $payment->refresh();
        $this->assertEquals('paid', $payment->status);
        $this->assertEquals('vnpay', $payment->payment_method);
        $this->assertNotNull($payment->paid_at);

        // Delete
        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.payments.destroy', $payment));
        $deleteResponse->assertRedirect(route('admin.payments.index'));
        $this->assertDatabaseMissing('payments', ['id' => $payment->id]);
    }

    public function test_booking_sale_and_subscription_controllers_export_to_payments(): void
    {
        // 1. Tạo Booking qua Admin -> tự sinh Payment(payable_type = booking)
        $this->actingAs($this->admin)->post(route('admin.bookings.store'), [
            'user_id' => $this->customer->id,
            'court_id' => $this->court->id,
            'customer_name' => 'Nguyễn Văn An',
            'customer_phone' => '0901234567',
            'customer_email' => 'an@test.com',
            'booking_date' => now()->toDateString(),
            'start_time' => '18:00',
            'end_time' => '19:00',
            'subtotal' => 300000,
            'discount_amount' => 0,
            'total_price' => 300000,
            'status' => 'completed',
            'payment_method' => 'vnpay',
        ])->assertRedirect(route('admin.bookings.index'));

        $booking = Booking::latest('id')->firstOrFail();
        $this->assertDatabaseHas('payments', [
            'payable_type' => Payment::TYPE_BOOKING,
            'payable_id' => $booking->id,
            'amount' => 300000,
            'status' => 'paid',
        ]);

        // 2. Tạo Sale qua Admin -> tự sinh Payment(payable_type = sale)
        $this->actingAs($this->admin)->post(route('admin.sales.store'), [
            'product_id' => $this->product->id,
            'user_id' => $this->customer->id,
            'customer_name' => $this->customer->name,
            'customer_phone' => '0909999999',
            'quantity' => 2,
            'total_price' => 300000,
            'status' => 'completed',
            'sale_date' => now()->toDateString(),
            'payment_method' => 'momo',
        ])->assertRedirect(route('admin.sales.index'));

        $sale = Sale::latest('id')->firstOrFail();
        $this->assertDatabaseHas('payments', [
            'payable_type' => Payment::TYPE_SALE,
            'payable_id' => $sale->id,
            'amount' => 300000,
            'payment_method' => 'momo',
            'status' => 'paid',
        ]);

        // 3. Tạo Subscription qua Admin -> tự sinh Payment(payable_type = subscription)
        $this->actingAs($this->admin)->post(route('admin.subscriptions.store'), [
            'user_id' => $this->customer->id,
            'plan_id' => $this->plan->id,
            'starts_at' => now()->format('Y-m-d H:i:s'),
            'expires_at' => now()->addDays(30)->format('Y-m-d H:i:s'),
            'status' => 1,
            'price' => 499000,
            'payment_method' => 'visa',
            'transaction_code' => 'SUB-ADMIN-001',
        ])->assertRedirect(route('admin.subscriptions.index'));

        $subscription = Subscription::latest('id')->firstOrFail();
        $this->assertDatabaseHas('payments', [
            'payable_type' => Payment::TYPE_SUBSCRIPTION,
            'payable_id' => $subscription->id,
            'amount' => 499000,
            'payment_method' => 'visa',
            'transaction_code' => 'SUB-ADMIN-001',
            'status' => 'paid',
        ]);
    }
}
