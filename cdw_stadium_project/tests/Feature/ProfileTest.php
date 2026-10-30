<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile_page(): void
    {
        $response = $this->get(route('profile.edit'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_profile_page(): void
    {
        $user = User::factory()->create([
            'name' => 'Nguyễn Văn A',
            'email' => 'nguyenvana@example.com',
            'phone' => '0901234567',
        ]);

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Nguyễn Văn A');
        $response->assertSee('nguyenvana@example.com');
        $response->assertSee('0901234567');
    }

    public function test_user_can_update_profile_information(): void
    {
        $user = User::factory()->create([
            'name' => 'Tên Ban Đầu',
            'email' => 'bandau@example.com',
            'phone' => '0911111111',
            'birthday' => '1995-05-15',
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Tên Mới Đổi',
            'email' => 'tenmoi@example.com',
            'phone' => '0922222222',
            'birthday' => '1998-08-20',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertSame('Tên Mới Đổi', $user->name);
        $this->assertSame('tenmoi@example.com', $user->email);
        $this->assertSame('0922222222', $user->phone);
        $this->assertSame('1998-08-20', $user->birthday->format('Y-m-d'));
    }

    public function test_user_cannot_update_birthday_if_under_16_years_old(): void
    {
        $user = User::factory()->create([
            'birthday' => '2000-01-01',
        ]);

        // Trẻ hơn 16 tuổi (ví dụ: sinh 10 năm trước)
        $under16Birthday = now()->subYears(10)->format('Y-m-d');

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'birthday' => $under16Birthday,
        ]);

        $response->assertSessionHasErrors('birthday');
    }

    public function test_user_can_update_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
        ]);

        $response = $this->actingAs($user)->put(route('profile.password.update'), [
            'current_password' => 'oldpassword123',
            'password' => 'newpassword456',
            'password_confirmation' => 'newpassword456',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword456', $user->password));
    }

    public function test_user_cannot_update_password_with_wrong_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
        ]);

        $response = $this->actingAs($user)->put(route('profile.password.update'), [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword456',
            'password_confirmation' => 'newpassword456',
        ]);

        $response->assertSessionHasErrors('current_password');
    }

    public function test_admin_sees_dashboard_button_in_profile(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        $response->assertSee(route('admin.dashboard'), false);
    }

    public function test_customer_does_not_see_dashboard_button_in_profile(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $response = $this->actingAs($customer)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertDontSee(route('admin.dashboard'), false);
    }

    public function test_user_can_view_booking_history_in_profile(): void
    {
        $user = User::factory()->create();

        $court = \App\Models\Court::factory()->create([
            'name' => 'Sân Cầu Lông Số 1',
        ]);

        $booking = \App\Models\Booking::factory()->create([
            'user_id' => $user->id,
            'court_id' => $court->id,
            'total_price' => 120000,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Lịch sử đặt sân');
        $response->assertSee('Sân Cầu Lông Số 1');
        $response->assertSee('120.000đ');
        $response->assertSee('Đã hoàn thành');
    }

    public function test_user_can_view_purchase_history_in_profile(): void
    {
        $user = User::factory()->create();

        $product = \App\Models\Product::factory()->create([
            'name' => 'Áo Cầu Lông Cao Cấp',
        ]);

        $sale = \App\Models\Sale::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'total_price' => 250000,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Lịch sử mua hàng');
        $response->assertSee('Áo Cầu Lông Cao Cấp');
        $response->assertSee('250.000đ');
    }
}
