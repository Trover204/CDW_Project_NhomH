<?php

namespace Tests\Feature\Admin;

use App\Models\Booking;
use App\Models\Court;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourtControllerDestroyTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_can_destroy_court_without_constraints()
    {
        $court = Court::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.courts.destroy', $court));

        $response->assertRedirect(route('admin.courts.index'));
        $response->assertSessionHas('success', 'Đã xóa sân.');

        $this->assertDatabaseMissing('courts', [
            'id' => $court->id,
        ]);
    }

    public function test_cannot_destroy_court_with_booking_constraint()
    {
        $court = Court::factory()->create();

        Booking::factory()->create([
            'court_id' => $court->id,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.courts.destroy', $court));

        $response->assertRedirect(route('admin.courts.index'));
        $response->assertSessionHas('error', 'Không thể xóa sân này do có dữ liệu ràng buộc (đơn đặt, hình ảnh hoặc bảng giá).');

        $this->assertDatabaseHas('courts', [
            'id' => $court->id,
        ]);
    }
}
