<?php

namespace Tests\Feature;

use App\Models\Court;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_favorites_page(): void
    {
        $response = $this->get(route('favorites.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_favorites_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('favorites.index'));

        $response->assertStatus(200);
        $response->assertViewIs('favorites.index');
        $response->assertViewHas('courts');
    }

    public function test_favorites_page_displays_users_favorite_courts(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $userFavorite = Court::factory()->create();
        $otherFavorite = Court::factory()->create();

        // Attach favorites
        $user->favoriteCourts()->attach($userFavorite->id);
        $otherUser->favoriteCourts()->attach($otherFavorite->id);

        $response = $this->actingAs($user)->get(route('favorites.index'));

        $response->assertStatus(200);
        $response->assertViewHas('courts');

        $courts = $response->viewData('courts');
        $this->assertTrue($courts->contains($userFavorite));
        $this->assertFalse($courts->contains($otherFavorite));
    }

    public function test_guest_cannot_toggle_favorite_court(): void
    {
        $court = Court::factory()->create();

        $response = $this->post(route('favorites.toggle', $court));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_toggle_favorite_court(): void
    {
        $user = User::factory()->create();
        $court = Court::factory()->create();

        // Test toggling ON (adding to favorites)
        $response = $this->actingAs($user)
            ->from(route('favorites.index')) // Simulate coming from a page to test back() redirect
            ->post(route('favorites.toggle', $court));

        $response->assertRedirect(route('favorites.index'));
        $response->assertSessionHas('success', 'Đã cập nhật danh sách yêu thích.');
        $this->assertTrue($user->favoriteCourts()->where('court_id', $court->id)->exists());

        // Test toggling OFF (removing from favorites)
        $response = $this->actingAs($user)
            ->from(route('favorites.index'))
            ->post(route('favorites.toggle', $court));

        $response->assertRedirect(route('favorites.index'));
        $response->assertSessionHas('success', 'Đã cập nhật danh sách yêu thích.');
        $this->assertFalse($user->favoriteCourts()->where('court_id', $court->id)->exists());
    }
}
