<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentSecurityPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_web_response_contains_strict_csp_and_security_headers(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');

        $csp = (string) $response->headers->get('Content-Security-Policy');
        $this->assertNotEmpty($csp);
        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringContainsString("object-src 'none'", $csp);
        $this->assertStringContainsString("base-uri 'self'", $csp);
        $this->assertStringContainsString("frame-ancestors 'self'", $csp);
        $this->assertStringContainsString("script-src-attr 'none'", $csp);
        $this->assertMatchesRegularExpression("/script-src 'self' 'nonce-[A-Za-z0-9+\\/=_-]+'/", $csp);
        $this->assertStringNotContainsString("script-src 'self' 'unsafe-inline'", $csp);
        $this->assertStringNotContainsString("'unsafe-eval'", $csp);
    }

    public function test_inline_scripts_use_matching_csp_nonce(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $csp = (string) $response->headers->get('Content-Security-Policy');
        preg_match("/'nonce-([^']+)'/", $csp, $matches);

        $this->assertNotEmpty($matches[1] ?? null);
        $response->assertSee('nonce="'.$matches[1].'"', false);
    }

    public function test_csp_nonce_is_regenerated_per_request(): void
    {
        $first = (string) $this->get(route('login'))->headers->get('Content-Security-Policy');
        $second = (string) $this->get(route('login'))->headers->get('Content-Security-Policy');

        $this->assertNotSame($first, $second);
    }

    public function test_admin_pages_receive_csp_header(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.ads.index'));

        $response->assertOk();
        $this->assertNotEmpty($response->headers->get('Content-Security-Policy'));
    }
}
