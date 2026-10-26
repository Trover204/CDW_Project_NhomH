<?php

namespace Tests\Feature\Admin;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard_and_see_stats()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        ActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'login',
            'description' => 'Admin logged in',
            'ip_address' => '127.0.0.1',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');

        $response->assertViewHasAll([
            'stats',
            'weeklyRevenue',
            'courtTypes',
            'recentBookings',
            'activityLogs',
        ]);
    }

    public function test_non_admin_cannot_access_dashboard()
    {
        $user = User::factory()->create([
            'role' => 'customer',
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        // Depends on admin middleware logic, usually redirect or 403
        $response->assertRedirect(route('home'));
    }

    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }
}
