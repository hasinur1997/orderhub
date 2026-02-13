<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_destroy_deletes_product(): void
    {
        $product = Product::factory()->create([
            'name' => 'Disposable Item',
            'sku' => 'DEL-001',
            'price' => 120,
            'stock_qty' => 2,
        ]);

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_destroy_returns_404_for_missing_product(): void
    {
        $response = $this->deleteJson('/api/products/999');

        $response->assertNotFound();
    }
}
