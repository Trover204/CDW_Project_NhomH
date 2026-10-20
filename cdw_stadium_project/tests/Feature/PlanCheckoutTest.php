<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_page_loads_with_payment_options()
    {
        $user = User::factory()->create();
        $plan = Plan::create([
            'name' => 'Premium Plan',
            'price' => 100000,
            'duration_days' => 30,
            'discount_percent' => 10,
            'status' => true,
        ]);

        $response = $this->actingAs($user)->get(route('plans.checkout', $plan->id));

        $response->assertStatus(200);
        $response->assertSee('Premium Plan');
        $response->assertSee('Ví MoMo');
        $response->assertSee('Thẻ Visa/Mastercard');
        $response->assertSee('PayPal');
    }

    public function test_subscription_created_with_valid_payment_method()
    {
        $user = User::factory()->create();
        $plan = Plan::create([
            'name' => 'Premium Plan',
            'price' => 100000,
            'duration_days' => 30,
            'discount_percent' => 10,
            'status' => true,
        ]);

        $response = $this->actingAs($user)->post(route('plans.subscribe', $plan->id), [
            'payment_method' => 'momo',
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success', 'Bạn đã đăng ký thành công gói Premium Plan (Thanh toán qua Momo)');

        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'payment_method' => 'momo',
            'status' => 1,
        ]);

        $this->assertDatabaseCount('subscriptions', 1);
    }
}
