<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_page_loads_successfully()
    {
        $response = $this->get(route('pages.about'));

        $response->assertStatus(200);
        $response->assertViewIs('web.pages.about');
    }

    public function test_contact_page_loads_successfully()
    {
        $response = $this->get(route('pages.contact'));

        $response->assertStatus(200);
        $response->assertViewIs('web.pages.contact');
    }

    public function test_privacy_page_loads_successfully()
    {
        $response = $this->get(route('pages.privacy'));

        $response->assertStatus(200);
        $response->assertViewIs('web.pages.privacy');
    }
}
