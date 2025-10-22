<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_has_category_relationship()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals($category->id, $product->category->id);
    }

    public function test_product_has_store_relationship()
    {
        $store = Store::factory()->create();
        $product = Product::factory()->create(['store_id' => $store->id]);

        $this->assertInstanceOf(Store::class, $product->store);
        $this->assertEquals($store->id, $product->store->id);
    }

    public function test_product_is_low_stock_returns_correct_value()
    {
        $lowStockProduct = Product::factory()->create([
            'count' => 5,
            'low_stock_threshold' => 10
        ]);

        $normalProduct = Product::factory()->create([
            'count' => 50,
            'low_stock_threshold' => 10
        ]);

        $this->assertTrue($lowStockProduct->isLowStock());
        $this->assertFalse($normalProduct->isLowStock());
    }

    public function test_product_is_out_of_stock()
    {
        $product = Product::factory()->create(['count' => 0]);

        $this->assertTrue($product->isOutOfStock());
    }

    public function test_product_is_expiring_soon()
    {
        $expiringProduct = Product::factory()->create([
            'expiry_date' => now()->addDays(15)
        ]);

        $notExpiringProduct = Product::factory()->create([
            'expiry_date' => now()->addDays(60)
        ]);

        $this->assertTrue($expiringProduct->isExpiringSoon());
        $this->assertFalse($notExpiringProduct->isExpiringSoon());
    }

    public function test_product_is_expired()
    {
        $expiredProduct = Product::factory()->create([
            'expiry_date' => now()->subDays(1)
        ]);

        $validProduct = Product::factory()->create([
            'expiry_date' => now()->addDays(30)
        ]);

        $this->assertTrue($expiredProduct->isExpired());
        $this->assertFalse($validProduct->isExpired());
    }

    public function test_product_needs_reorder()
    {
        $product = Product::factory()->create([
            'count' => 5,
            'reorder_level' => 20
        ]);

        $this->assertTrue($product->needsReorder());
    }

    public function test_product_profit_margin_calculation()
    {
        $product = Product::factory()->create([
            'price' => 1000,
            'purchase_price' => 600
        ]);

        $margin = $product->profitMargin();

        $this->assertEquals(40, $margin);
    }

    public function test_product_total_value_calculation()
    {
        $product = Product::factory()->create([
            'count' => 10,
            'purchase_price' => 500
        ]);

        $this->assertEquals(5000, $product->totalValue());
    }

    public function test_barcode_is_unique()
    {
        Product::factory()->create(['barcode' => 'UNIQUE123']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Product::factory()->create(['barcode' => 'UNIQUE123']);
    }

    public function test_sku_is_unique()
    {
        Product::factory()->create(['sku' => 'SKU-001']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Product::factory()->create(['sku' => 'SKU-001']);
    }
}
