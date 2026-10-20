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
}
