<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_has_link_to_partnership_page(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee(route('partnership.index'), false);
        $response->assertSee('Đăng ký làm đối tác');
    }

    public function test_guest_can_view_partnership_index_page(): void
    {
        $response = $this->get(route('partnership.index'));

        $response->assertStatus(200);
        $response->assertViewIs('web.partnership.index');
        $response->assertSee('Chương trình Cộng tác viên');
        $response->assertSee('Đăng nhập để đăng ký');
    }

    public function test_authenticated_customer_can_view_partnership_checkout_and_register(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $indexResponse = $this->actingAs($user)->get(route('partnership.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee(route('partnership.checkout'), false);

        $checkoutResponse = $this->actingAs($user)->get(route('partnership.checkout'));
        $checkoutResponse->assertStatus(200);
        $checkoutResponse->assertViewIs('web.partnership.checkout');

        $processResponse = $this->actingAs($user)->post(route('partnership.process'), [
            'payment_method' => 'momo',
        ]);

        $processResponse->assertRedirect(route('partnership.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'pending_ctv',
        ]);
    }
}
