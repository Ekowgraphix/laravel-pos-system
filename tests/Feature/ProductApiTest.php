<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_can_get_all_products()
    {
        Product::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'data' => [
                        '*' => ['id', 'name', 'price', 'count']
                    ]
                ]
            ]);
    }

    public function test_can_get_single_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/v1/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                ]
            ]);
    }

    public function test_can_find_product_by_barcode()
    {
        $product = Product::factory()->create(['barcode' => '123456789']);

        $response = $this->getJson('/api/v1/products/barcode/123456789');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'barcode' => '123456789'
                ]
            ]);
    }

    public function test_can_create_product_when_authenticated()
    {
        Sanctum::actingAs($this->user);
        
        $category = Category::factory()->create();

        $productData = [
            'name' => 'Test Product',
            'price' => 1000,
            'purchase_price' => 500,
            'category_id' => $category->id,
            'count' => 100,
            'description' => 'Test description',
            'low_stock_threshold' => 10,
            'barcode' => 'TEST123',
        ];

        $response = $this->postJson('/api/v1/products', $productData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Product created successfully'
            ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'barcode' => 'TEST123'
        ]);
    }

    public function test_can_update_product_when_authenticated()
    {
        Sanctum::actingAs($this->user);
        
        $product = Product::factory()->create();

        $updateData = [
            'name' => 'Updated Product',
            'price' => 1500,
        ];

        $response = $this->putJson("/api/v1/products/{$product->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Product updated successfully'
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
        ]);
    }

    public function test_can_delete_product_when_authenticated()
    {
        Sanctum::actingAs($this->user);
        
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/v1/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_can_get_low_stock_products()
    {
        Sanctum::actingAs($this->user);
        
        Product::factory()->create(['count' => 5, 'low_stock_threshold' => 10]);
        Product::factory()->create(['count' => 50, 'low_stock_threshold' => 10]);

        $response = $this->getJson('/api/v1/products/low-stock/list');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_filter_products_by_category()
    {
        $category = Category::factory()->create();
        Product::factory()->count(3)->create(['category_id' => $category->id]);
        Product::factory()->count(2)->create();

        $response = $this->getJson("/api/v1/products?category_id={$category->id}");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data.data');
    }

    public function test_product_not_found_returns_404()
    {
        $response = $this->getJson('/api/v1/products/9999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Product not found'
            ]);
    }
}
