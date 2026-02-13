<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductDatabaseConstraintsTest extends TestCase
{
    use RefreshDatabase;

    public function test_sku_must_be_unique(): void
    {
        Product::factory()->create([
            'name' => 'First',
            'sku' => 'DUP-001',
            'price' => 100,
            'stock_qty' => 1,
        ]);

        $this->expectException(QueryException::class);

        Product::factory()->create([
            'name' => 'Second',
            'sku' => 'DUP-001',
            'price' => 200,
            'stock_qty' => 2,
        ]);
    }
}
