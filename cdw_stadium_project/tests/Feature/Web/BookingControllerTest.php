<?php

namespace Tests\Feature\Web;

use App\Models\Court;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_returns_view_with_court_for_authenticated_user()
    {
        $user = User::factory()->create();
        $court = Court::factory()->create();

        $response = $this->actingAs($user)->get(route('bookings.create', $court));

        $response->assertStatus(200);
        $response->assertViewIs('web.bookings.create');
        $response->assertViewHas('court');
        $this->assertEquals($court->id, $response->viewData('court')->id);
    }

    public function test_create_redirects_guest_to_login()
    {
        $court = Court::factory()->create();

        $response = $this->get(route('bookings.create', $court));

        $response->assertRedirect(route('login'));
    }
}
