<?php

namespace Tests\Feature\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\Subscription;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentServiceResolvePayableTest extends TestCase
{
    use RefreshDatabase;

    protected PaymentService $paymentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentService = app(PaymentService::class);
    }

    public function test_resolves_booking_payable_correctly(): void
    {
        $booking = Booking::factory()->create();

        $payable = $this->paymentService->resolvePayable(Payment::TYPE_BOOKING, $booking->id);

        $this->assertNotNull($payable);
        $this->assertInstanceOf(Booking::class, $payable);
        $this->assertEquals($booking->id, $payable->id);
    }

    public function test_resolves_sale_payable_correctly(): void
    {
        $sale = Sale::factory()->create();

        $payable = $this->paymentService->resolvePayable(Payment::TYPE_SALE, $sale->id);

        $this->assertNotNull($payable);
        $this->assertInstanceOf(Sale::class, $payable);
        $this->assertEquals($sale->id, $payable->id);
    }

    public function test_resolves_subscription_payable_correctly(): void
    {
        $subscription = Subscription::factory()->create();

        $payable = $this->paymentService->resolvePayable(Payment::TYPE_SUBSCRIPTION, $subscription->id);

        $this->assertNotNull($payable);
        $this->assertInstanceOf(Subscription::class, $payable);
        $this->assertEquals($subscription->id, $payable->id);
    }

    public function test_returns_null_for_invalid_payable_type(): void
    {
        $payable = $this->paymentService->resolvePayable('invalid_type', 1);

        $this->assertNull($payable);
    }

    public function test_returns_null_when_payable_not_found(): void
    {
        $payable = $this->paymentService->resolvePayable(Payment::TYPE_BOOKING, 999999);

        $this->assertNull($payable);
    }
}
