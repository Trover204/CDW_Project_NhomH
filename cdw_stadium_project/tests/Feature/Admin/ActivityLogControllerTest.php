<?php

namespace Tests\Feature\Admin;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure admin middleware passes
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_activity_logs_index()
    {
        ActivityLog::factory()->count(25)->create();

        $response = $this->actingAs($this->admin)->get(route('admin.activity-logs.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.activity-logs.index');
        $response->assertViewHas('activityLogs');

        $activityLogs = $response->viewData('activityLogs');
        $this->assertCount(20, $activityLogs); // 20 due to pagination
    }

    public function test_non_admin_cannot_view_activity_logs_index()
    {
        $user = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($user)->get(route('admin.activity-logs.index'));

        $response->assertRedirect(route('home'));
    }

    public function test_guest_cannot_view_activity_logs_index()
    {
        $response = $this->get(route('admin.activity-logs.index'));

        $response->assertRedirect(route('login'));
    }
}
