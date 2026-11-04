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
        $response->assertSee('momo_card_clean.jpg');
        $response->assertSee('qr_card_clean.jpg');
        $response->assertSee('PSP2615713900000057');
        $response->assertSee('1000 6118 170');
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

        $this->assertDatabaseHas('payments', [
            'payable_type' => 'sale',
            'user_id' => $user->id,
            'amount' => 200000,
            'payment_method' => 'momo',
            'status' => 'paid',
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 8,
            'is_in_stock' => true,
        ]);
    }

    public function test_auth_user_can_purchase_with_qr_and_customer_details()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'is_active' => true,
            'is_in_stock' => true,
            'stock_quantity' => 5,
            'cost_price' => 350000,
        ]);

        $response = $this->actingAs($user)->post(route('products.purchase', $product->id), [
            'payment_method' => 'qr',
            'quantity' => 1,
            'customer_name' => 'Nguyễn Văn A',
            'customer_phone' => '0912345678',
            'shipping_address' => '123 Đường Lê Lợi, Q1, TP.HCM',
            'note' => 'Size L',
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success');
        $response->assertSessionHas('order_success');

        $this->assertDatabaseHas('sales', [
            'product_id' => $product->id,
            'customer_name' => 'Nguyễn Văn A',
            'customer_phone' => '0912345678',
            'quantity' => 1,
            'total_price' => 350000,
        ]);

        $this->assertDatabaseHas('payments', [
            'payable_type' => 'sale',
            'payment_method' => 'qr',
            'amount' => 350000,
            'status' => 'paid',
        ]);
    }
}
