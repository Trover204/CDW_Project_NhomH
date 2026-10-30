<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebHeaderSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_header_shows_gear_dropdown_and_login_without_register_button(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('id="settingsDropdownBtn"', false);
        $response->assertSee('ti-settings', false);
        $response->assertSee('id="settingsDropdownMenu"', false);
        $response->assertSee('theme-toggle-btn', false);
        $response->assertSee('lang-switch-btn', false);
        $response->assertSee('Đăng nhập');
        $response->assertDontSee('?tab=register', false);
        $response->assertDontSee('settings-logout-item', false);
    }

    public function test_authenticated_header_places_logout_inside_settings_dropdown(): void
    {
        $user = User::factory()->create(['name' => 'Nguyen Van Test']);

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertOk();
        $response->assertSee('Nguyen Van Test');
        $response->assertSee('settings-logout-item', false);
        $response->assertSee('data-submit-form="logout-form"', false);
        $response->assertDontSee('class="btn btn-ghost">Đăng xuất</a>', false);
    }

    public function test_language_switch_toggles_between_vietnamese_and_english(): void
    {
        $switchToEn = $this->from(route('home'))->get(route('lang.switch', 'en'));

        $switchToEn->assertRedirect(route('home'));
        $switchToEn->assertSessionHas('locale', 'en');

        $enResponse = $this->withSession(['locale' => 'en'])->get(route('home'));
        $enResponse->assertOk();
        $enResponse->assertSee('<html lang="en">', false);
        $enResponse->assertSee('Courts');
        $enResponse->assertSee('Membership');
        $enResponse->assertSee('Theme');
        $enResponse->assertSee('Language');

        $switchToVi = $this->from(route('home'))->get(route('lang.switch', 'vi'));

        $switchToVi->assertRedirect(route('home'));
        $switchToVi->assertSessionHas('locale', 'vi');

        $viResponse = $this->withSession(['locale' => 'vi'])->get(route('home'));
        $viResponse->assertOk();
        $viResponse->assertSee('<html lang="vi">', false);
        $viResponse->assertSee('Trang chủ');
        $viResponse->assertSee('Gói thành viên');
        $viResponse->assertSee('Giao diện');
        $viResponse->assertSee('Ngôn ngữ');
    }
}
