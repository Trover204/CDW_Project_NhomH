<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_products_index()
    {
        Product::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('admin.products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.index');
        $response->assertViewHas('products');
    }

    public function test_admin_can_view_create_product_page()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.products.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.create');
    }

    public function test_admin_can_store_product()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('product.jpg');

        $data = [
            'name' => 'Áo Arsenal Sân Nhà 24/25',
            'description' => 'Áo đấu chính thức',
            'team' => 'Arsenal',
            'season' => '2024/25',
            'cost_price' => 250000,
            'stock_quantity' => 50,
            'is_in_stock' => '1',
            'is_active' => '1',
            'image' => $file,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), $data);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Áo Arsenal Sân Nhà 24/25',
            'team' => 'Arsenal',
            'cost_price' => 250000,
            'stock_quantity' => 50,
        ]);

        $product = Product::first();
        $this->assertNotNull($product->image);
        Storage::disk('public')->assertExists($product->image);
    }

    public function test_admin_can_view_edit_product_page()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('admin.products.edit', $product));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.edit');
        $response->assertViewHas('product');
    }

    public function test_admin_can_update_product()
    {
        $product = Product::factory()->create([
            'name' => 'Old Name',
            'cost_price' => 100000,
        ]);

        $data = [
            'name' => 'New Name',
            'description' => $product->description,
            'team' => $product->team,
            'season' => $product->season,
            'cost_price' => 150000,
            'stock_quantity' => $product->stock_quantity,
            'is_in_stock' => '1',
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.products.update', $product), $data);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'New Name',
            'cost_price' => 150000,
        ]);
    }

    public function test_admin_can_delete_product()
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
