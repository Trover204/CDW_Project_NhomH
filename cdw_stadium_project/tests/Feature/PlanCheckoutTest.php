<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_plans_index_page_loads_active_plans()
    {
        $plan = Plan::factory()->create(['status' => true, 'name' => 'Active Plan']);
        $inactivePlan = Plan::factory()->create(['status' => false, 'name' => 'Inactive Plan']);

        $response = $this->get(route('plans.index'));

        $response->assertStatus(200);
        $response->assertSee('Active Plan');
        $response->assertDontSee('Inactive Plan');
    }

    public function test_checkout_page_requires_authentication()
    {
        $plan = Plan::factory()->create();

        $response = $this->get(route('plans.checkout', $plan->id));

        $response->assertRedirect(route('login'));
    }

    public function test_checkout_page_loads_with_payment_options()
    {
        $user = User::factory()->create();
        $plan = Plan::factory()->create([
            'name' => 'Premium Plan',
            'status' => true,
        ]);

        $response = $this->actingAs($user)->get(route('plans.checkout', $plan->id));

        $response->assertStatus(200);
        $response->assertSee('Premium Plan');
        $response->assertSee('Ví MoMo');
        $response->assertSee('Thẻ Visa/Mastercard');
        $response->assertSee('PayPal');
    }

    public function test_subscribe_requires_authentication()
    {
        $plan = Plan::factory()->create();

        $response = $this->post(route('plans.subscribe', $plan->id), [
            'payment_method' => 'momo',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_subscription_fails_with_missing_payment_method()
    {
        $user = User::factory()->create();
        $plan = Plan::factory()->create();

        $response = $this->actingAs($user)->post(route('plans.subscribe', $plan->id), []);

        $response->assertSessionHasErrors(['payment_method']);
        $this->assertDatabaseCount('subscriptions', 0);
    }

    public function test_subscription_fails_with_invalid_payment_method()
    {
        $user = User::factory()->create();
        $plan = Plan::factory()->create();

        $response = $this->actingAs($user)->post(route('plans.subscribe', $plan->id), [
            'payment_method' => 'cash',
        ]);

        $response->assertSessionHasErrors(['payment_method']);
        $this->assertDatabaseCount('subscriptions', 0);
    }

    public function test_subscription_created_with_valid_payment_method()
    {
        $user = User::factory()->create();
        $plan = Plan::factory()->create([
            'name' => 'Premium Plan',
            'price' => 100000,
            'duration_days' => 30,
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
            'status' => 1,
            'price' => 100000,
        ]);

        $subscription = Subscription::with('payment')->first();
        $this->assertNotNull($subscription->starts_at);
        $this->assertNotNull($subscription->expires_at);
        $this->assertEquals(30, $subscription->starts_at->diffInDays($subscription->expires_at));
        $this->assertNotNull($subscription->payment);
        $this->assertEquals('momo', $subscription->payment->payment_method);
        $this->assertEquals(100000, $subscription->payment->amount);
        $this->assertEquals('paid', $subscription->payment->status);
        $this->assertStringStartsWith('TXN_', $subscription->transaction_code);

        $this->assertDatabaseHas('payments', [
            'payable_type' => 'subscription',
            'payable_id' => $subscription->id,
            'user_id' => $user->id,
            'amount' => 100000,
            'payment_method' => 'momo',
            'status' => 'paid',
        ]);

        $this->assertDatabaseCount('subscriptions', 1);
    }
}
