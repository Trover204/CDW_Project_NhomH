<?php

namespace Tests\Feature\Admin;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_ads_index()
    {
        Ad::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)->get(route('admin.ads.index'));

        $response->assertStatus(200);
        $response->assertViewHas('ads');
    }

    public function test_admin_can_view_create_ad_page()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.ads.create'));

        $response->assertStatus(200);
    }

    public function test_admin_can_store_new_ad()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('ad.jpg');

        $data = [
            'name' => 'Khuyến mãi đặc biệt',
            'link_url' => 'https://example.com/promo',
            'is_active' => '1',
            'description' => 'Sale off 50%',
            'image' => $file,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.ads.store'), $data);

        $response->assertRedirect(route('admin.ads.index'));
        $this->assertDatabaseHas('ads', [
            'name' => 'Khuyến mãi đặc biệt',
            'link_url' => 'https://example.com/promo',
            'is_active' => 1,
            'description' => 'Sale off 50%',
        ]);

        $ad = Ad::first();
        Storage::disk('public')->assertExists($ad->image);
    }

    public function test_admin_can_view_edit_ad_page()
    {
        $ad = Ad::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('admin.ads.edit', $ad));

        $response->assertStatus(200);
        $response->assertViewHas('ad');
    }

    public function test_admin_can_update_ad()
    {
        $ad = Ad::factory()->create([
            'name' => 'Old Name',
            'is_active' => false,
        ]);

        $data = [
            'name' => 'New Name',
            'is_active' => '1',
            'link_url' => 'https://example.com/new',
            'description' => 'New desc',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.ads.update', $ad), $data);

        $response->assertRedirect(route('admin.ads.index'));
        $this->assertDatabaseHas('ads', [
            'id' => $ad->id,
            'name' => 'New Name',
            'is_active' => 1,
        ]);
    }

    public function test_admin_can_delete_ad()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('ad.jpg');
        $path = $file->store('ads', 'public');

        $ad = Ad::factory()->create([
            'image' => $path
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($this->admin)->delete(route('admin.ads.destroy', $ad));

        $response->assertRedirect(route('admin.ads.index'));
        $this->assertDatabaseMissing('ads', ['id' => $ad->id]);
        Storage::disk('public')->assertMissing($path);
    }
}
