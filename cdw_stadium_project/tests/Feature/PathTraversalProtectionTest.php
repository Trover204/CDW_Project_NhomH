<?php

namespace Tests\Feature;

use App\Models\Ad;
use App\Models\News;
use App\Models\Product;
use App\Models\User;
use App\Services\SecureFileStorage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PathTraversalProtectionTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public static function maliciousPathProvider(): array
    {
        return [
            'parent directory' => ['../.env'],
            'nested parent directory' => ['ads/../../../.env'],
            'escape into other upload dir' => ['ads/../avatars/victim.jpg'],
            'other upload dir' => ['avatars/victim.jpg'],
            'absolute unix path' => ['/etc/passwd'],
            'windows backslash' => ['ads\\..\\..\\.env'],
            'null byte' => ["ads/evil.jpg\0.php"],
            'sub directory' => ['ads/sub/evil.jpg'],
            'trailing newline' => ["ads/evil.jpg\n"],
            'url encoded traversal is literal but rejected' => ['ads/..%2F.env'],
        ];
    }

    #[DataProvider('maliciousPathProvider')]
    public function test_service_rejects_malicious_paths(string $path): void
    {
        $this->assertFalse(app(SecureFileStorage::class)->isSafePath($path, 'ads'));
    }

    public function test_service_accepts_generated_upload_paths(): void
    {
        $path = app(SecureFileStorage::class)->store(UploadedFile::fake()->image('../../evil.jpg'), 'ads');

        $this->assertStringStartsWith('ads/', $path);
        $this->assertStringNotContainsString('..', $path);
        $this->assertStringNotContainsString('evil', $path);
        $this->assertTrue(app(SecureFileStorage::class)->isSafePath($path, 'ads'));
        Storage::disk('public')->assertExists($path);
    }

    public function test_service_refuses_non_whitelisted_directory(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        app(SecureFileStorage::class)->store(UploadedFile::fake()->image('a.jpg'), '../config');
    }

    public function test_deleting_ad_does_not_delete_files_outside_its_directory(): void
    {
        Storage::disk('public')->put('avatars/victim.jpg', 'victim');
        $ad = Ad::factory()->create(['image' => 'ads/../avatars/victim.jpg']);

        $this->actingAs($this->admin)
            ->delete(route('admin.ads.destroy', $ad))
            ->assertRedirect(route('admin.ads.index'));

        $this->assertDatabaseMissing('ads', ['id' => $ad->id]);
        Storage::disk('public')->assertExists('avatars/victim.jpg');
    }

    public function test_deleting_ad_with_root_escape_path_does_not_crash(): void
    {
        $ad = Ad::factory()->create(['image' => '../../.env']);

        $this->actingAs($this->admin)
            ->delete(route('admin.ads.destroy', $ad))
            ->assertRedirect(route('admin.ads.index'));

        $this->assertDatabaseMissing('ads', ['id' => $ad->id]);
    }

    public function test_updating_product_image_does_not_delete_file_from_other_directory(): void
    {
        Storage::disk('public')->put('news/important.jpg', 'news');
        $product = Product::factory()->create(['image' => 'news/important.jpg']);

        $this->actingAs($this->admin)->put(route('admin.products.update', $product), [
            'name' => 'Áo đấu',
            'cost_price' => 100000,
            'stock_quantity' => 10,
            'image' => UploadedFile::fake()->image('new.jpg'),
        ])->assertRedirect(route('admin.products.index'));

        Storage::disk('public')->assertExists('news/important.jpg');
        $this->assertStringStartsWith('products/', $product->fresh()->image);
    }

    public function test_profile_avatar_update_does_not_delete_file_outside_avatars(): void
    {
        Storage::disk('public')->put('ads/banner.jpg', 'banner');
        $user = User::factory()->create(['avatar' => 'avatars/../ads/banner.jpg']);

        $this->actingAs($user)->put(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ])->assertRedirect(route('profile.edit'));

        Storage::disk('public')->assertExists('ads/banner.jpg');
        $user->refresh();
        $this->assertStringStartsWith('avatars/', $user->avatar);
        $this->assertStringNotContainsString('..', $user->avatar);
        Storage::disk('public')->assertExists($user->avatar);
    }

    public function test_profile_avatar_update_deletes_legit_old_avatar(): void
    {
        $old = UploadedFile::fake()->image('old.jpg')->store('avatars', 'public');
        $user = User::factory()->create(['avatar' => $old]);

        $this->actingAs($user)->put(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => UploadedFile::fake()->image('new.jpg'),
        ])->assertRedirect(route('profile.edit'));

        Storage::disk('public')->assertMissing($old);
    }

    public function test_news_image_url_ignores_traversal_paths(): void
    {
        $news = News::factory()->make(['image' => 'images/../../.env']);

        $this->assertNull($news->image_url);
    }
}
