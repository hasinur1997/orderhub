<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_returns_product(): void
    {
        $product = Product::factory()->create([
            'name' => 'Dark Chocolate',
            'sku' => 'CHO-001',
            'price' => 450,
            'stock_qty' => 20,
        ]);

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $product->id,
            'name' => 'Dark Chocolate',
            'sku' => 'CHO-001',
            'price' => 450,
            'stock_qty' => 20,
        ]);
    }

    public function test_show_returns_404_for_missing_product(): void
    {
        $response = $this->getJson('/api/products/999');

        $response->assertNotFound();
    }
}
