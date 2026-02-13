<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_product_and_returns_201(): void
    {
        $payload = [
            'name' => 'Coffee Beans',
            'sku' => 'COF-001',
            'price' => 1299,
            'stock_qty' => 12,
        ];

        $response = $this->postJson('/api/products', $payload);

        $response->assertStatus(201);
        $response->assertJsonFragment($payload);
        $this->assertDatabaseHas('products', $payload);
    }

    public function test_store_defaults_stock_qty_to_zero_when_omitted(): void
    {
        $payload = [
            'name' => 'Green Tea',
            'sku' => 'TEA-001',
            'price' => 899,
        ];

        $response = $this->postJson('/api/products', $payload);

        $response->assertStatus(201);
        $this->assertDatabaseHas('products', array_merge($payload, ['stock_qty' => 0]));
    }

    public function test_store_ignores_unexpected_fields(): void
    {
        $payload = [
            'name' => 'Ceramic Mug',
            'sku' => 'MUG-001',
            'price' => 550,
            'stock_qty' => 5,
            'id' => 999,
            'created_at' => '1999-01-01 00:00:00',
        ];

        $response = $this->postJson('/api/products', $payload);

        $response->assertStatus(201);

        $product = Product::query()->where('sku', 'MUG-001')->first();

        $this->assertNotNull($product);
        $this->assertNotSame(999, $product->id);
        $this->assertNotSame('1999-01-01 00:00:00', $product->created_at->toDateTimeString());
    }
}
