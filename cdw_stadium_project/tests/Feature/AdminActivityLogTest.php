<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_activity_logs_index(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        ActivityLog::factory()->count(25)->create();

        $response = $this->actingAs($admin)->get(route('admin.activity-logs.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.activity-logs.index');
        $response->assertViewHas('activityLogs');

        // Check if pagination is working (default 20 per page)
        $activityLogs = $response->viewData('activityLogs');
        $this->assertEquals(20, $activityLogs->count());
        $this->assertEquals(\App\Models\ActivityLog::count(), $activityLogs->total());
    }

    public function test_staff_can_view_activity_logs_index(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
        ]);

        ActivityLog::factory()->count(5)->create();

        $response = $this->actingAs($staff)->get(route('admin.activity-logs.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.activity-logs.index');
    }

    public function test_customer_cannot_view_activity_logs_index(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $response = $this->actingAs($customer)->get(route('admin.activity-logs.index'));

        $response->assertRedirect(route('home'));
    }

    public function test_unauthenticated_user_cannot_view_activity_logs_index(): void
    {
        $response = $this->get(route('admin.activity-logs.index'));

        $response->assertRedirect(route('login'));
    }
}
