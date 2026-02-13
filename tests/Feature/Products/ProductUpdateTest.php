<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_updates_product_fields(): void
    {
        $product = Product::factory()->create([
            'name' => 'Old Name',
            'sku' => 'OLD-100',
            'price' => 100,
            'stock_qty' => 4,
        ]);

        $payload = [
            'name' => 'New Name',
            'sku' => 'NEW-100',
            'price' => 250,
            'stock_qty' => 8,
        ];

        $response = $this->putJson("/api/products/{$product->id}", $payload);

        $response->assertOk();
        $response->assertJsonFragment($payload);
        $this->assertDatabaseHas('products', array_merge(['id' => $product->id], $payload));
    }

    public function test_update_ignores_unexpected_fields(): void
    {
        $product = Product::factory()->create([
            'name' => 'Starter Name',
            'sku' => 'STA-001',
            'price' => 200,
            'stock_qty' => 6,
        ]);

        $originalCreatedAt = $product->created_at;

        $payload = [
            'name' => 'Changed Name',
            'sku' => 'STA-002',
            'price' => 220,
            'stock_qty' => 9,
            'created_at' => '1999-01-01 00:00:00',
        ];

        $response = $this->patchJson("/api/products/{$product->id}", $payload);

        $response->assertOk();

        $product->refresh();

        $this->assertTrue($product->created_at->eq($originalCreatedAt));
    }

    public function test_update_returns_404_for_missing_product(): void
    {
        $payload = [
            'name' => 'Missing',
            'sku' => 'MIS-001',
            'price' => 200,
            'stock_qty' => 1,
        ];

        $response = $this->putJson('/api/products/999', $payload);

        $response->assertNotFound();
    }
}
