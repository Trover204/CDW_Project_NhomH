<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LanguageSwitchTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_locale_is_vietnamese(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('<html lang="vi">', false);
        $response->assertSee('Trang chủ');
    }

    public function test_switching_to_english_persists_in_session_and_translates_pages(): void
    {
        $response = $this->from(route('home'))->get(route('lang.switch', 'en'));

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('locale', 'en');

        foreach (['home', 'courts.index', 'news.index', 'plans.index', 'products.index'] as $routeName) {
            $page = $this->withSession(['locale' => 'en'])->get(route($routeName));

            $page->assertStatus(200);
            $page->assertSee('<html lang="en">', false);
            $page->assertSee('Home');
            $page->assertDontSee('Trang chủ');
        }
    }

    public function test_switching_back_to_vietnamese(): void
    {
        $this->withSession(['locale' => 'en'])
            ->from(route('home'))
            ->get(route('lang.switch', 'vi'))
            ->assertSessionHas('locale', 'vi');

        $this->withSession(['locale' => 'vi'])
            ->get(route('home'))
            ->assertSee('Trang chủ');
    }

    public function test_unsupported_locale_is_rejected(): void
    {
        $this->get('/lang/fr')->assertNotFound();
    }

    public function test_every_english_translation_key_has_non_empty_value(): void
    {
        $translations = json_decode(file_get_contents(lang_path('en.json')), true, 512, JSON_THROW_ON_ERROR);

        $this->assertNotEmpty($translations);

        foreach ($translations as $key => $value) {
            $this->assertIsString($value, "Translation for [{$key}] must be a string.");
            $this->assertNotSame('', trim($value), "Translation for [{$key}] must not be empty.");
        }
    }
}
