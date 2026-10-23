<?php

namespace Tests\Feature\Web;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_see_merch_index()
    {
        Product::factory()->count(3)->create(['is_active' => true, 'is_in_stock' => true]);

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('web.products.index');
        $response->assertSee('Sản phẩm chính hãng');
    }

    public function test_guest_is_redirected_from_checkout()
    {
        $product = Product::factory()->create(['is_active' => true, 'is_in_stock' => true]);

        $response = $this->get(route('products.checkout', $product->id));

        $response->assertRedirect(route('login'));
    }

    public function test_auth_user_can_see_checkout()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['is_active' => true, 'is_in_stock' => true]);

        $response = $this->actingAs($user)->get(route('products.checkout', $product->id));

        $response->assertStatus(200);
        $response->assertViewIs('web.products.checkout');
        $response->assertSee($product->name);
    }

    public function test_auth_user_can_purchase_product()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'is_active' => true,
            'is_in_stock' => true,
            'stock_quantity' => 10,
            'cost_price' => 100000,
        ]);

        $response = $this->actingAs($user)->post(route('products.purchase', $product->id), [
            'payment_method' => 'momo',
            'quantity' => 2,
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('sales', [
            'product_id' => $product->id,
            'user_id' => $user->id,
            'quantity' => 2,
            'total_price' => 200000,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 8,
            'is_in_stock' => true,
        ]);
    }
}
