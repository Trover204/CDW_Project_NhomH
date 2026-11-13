<?php

namespace Tests\Feature\Web;

use App\Models\Plan;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatbotTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_sees_login_prompt_in_chatbot_status()
    {
        $response = $this->getJson(route('chatbot.status'));

        $response->assertStatus(200);
        $response->assertJson([
            'can_chat' => false,
            'reason' => 'unauthenticated',
        ]);
    }

    public function test_user_without_active_subscription_cannot_chat()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('chatbot.status'));

        $response->assertStatus(200);
        $response->assertJson([
            'can_chat' => false,
            'reason' => 'no_subscription',
        ]);

        $messageResponse = $this->actingAs($user)->postJson(route('chatbot.message'), [
            'message' => 'Gợi ý cho tôi áo thể thao',
        ]);

        $messageResponse->assertStatus(403);
    }

    public function test_user_with_active_subscription_can_get_status_and_chat()
    {
        $user = User::factory()->create();
        $plan = Plan::create([
            'name' => 'Gói Vàng Siêu Cấp',
            'duration_days' => 30,
            'price' => 500000,
            'status' => true,
        ]);

        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addDays(29),
            'status' => true,
            'price' => 500000,
        ]);

        Product::create([
            'name' => 'Áo Real Madrid Sân Nhà 24/25',
            'description' => 'Áo đấu chính hãng',
            'team' => 'Real Madrid',
            'cost_price' => 450000,
            'stock_quantity' => 10,
            'is_in_stock' => true,
            'is_active' => true,
        ]);

        $statusRes = $this->actingAs($user)->getJson(route('chatbot.status'));
        $statusRes->assertStatus(200);
        $statusRes->assertJson([
            'can_chat' => true,
        ]);

        $chatRes = $this->actingAs($user)->postJson(route('chatbot.message'), [
            'message' => 'Gợi ý cho mình mẫu áo Real Madrid',
            'action' => 'suggest_products',
        ]);

        $chatRes->assertStatus(200);
        $chatRes->assertJsonStructure([
            'success',
            'reply',
            'type',
            'data' => [
                'products',
            ],
            'suggestions',
            'timestamp',
        ]);
        $this->assertTrue($chatRes->json('success'));
        $this->assertNotEmpty($chatRes->json('data.products'));
    }

    public function test_chatbot_validation_rejects_empty_message()
    {
        $user = User::factory()->create();
        $plan = Plan::create([
            'name' => 'Gói Bạc',
            'duration_days' => 30,
            'price' => 200000,
            'status' => true,
        ]);

        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addDays(29),
            'status' => true,
            'price' => 200000,
        ]);

        $res = $this->actingAs($user)->postJson(route('chatbot.message'), [
            'message' => '',
        ]);

        $res->assertStatus(422);
        $res->assertJsonValidationErrors(['message']);
    }
}
