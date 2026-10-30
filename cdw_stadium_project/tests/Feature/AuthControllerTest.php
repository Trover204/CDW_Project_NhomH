<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_with_valid_email()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@gmail.com', // A known good domain
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertDatabaseHas('users', [
            'email' => 'test@gmail.com',
        ]);
    }

    public function test_registration_fails_with_invalid_domain_email()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@thisdomaindoesnotexist.invalid',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertDatabaseMissing('users', [
            'email' => 'test@thisdomaindoesnotexist.invalid',
        ]);
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = \App\Models\User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('home'));
    }

    public function test_login_rate_limiting()
    {
        $user = \App\Models\User::factory()->create();

        // 5 failed attempts
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);
            $this->assertGuest();
        }

        // 6th attempt should hit rate limit
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors(['email']);
    }
}
