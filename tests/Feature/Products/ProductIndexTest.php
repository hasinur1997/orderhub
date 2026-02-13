<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_empty_list_when_no_products(): void
    {
        $response = $this->getJson('/api/products');

        $response->assertOk();
        $this->assertSame([], $response->json('data'));
        $this->assertSame(0, $response->json('meta.total'));
    }

    public function test_index_returns_paginated_products_newest_first(): void
    {
        $older = Product::factory()->create([
            'name' => 'Older Product',
            'sku' => 'OLD-001',
            'price' => 100,
            'stock_qty' => 3,
        ]);
        $newer = Product::factory()->create([
            'name' => 'Newer Product',
            'sku' => 'NEW-002',
            'price' => 200,
            'stock_qty' => 5,
        ]);
        $latest = Product::factory()->create([
            'name' => 'Latest Product',
            'sku' => 'LAT-003',
            'price' => 300,
            'stock_qty' => 7,
        ]);

        $response = $this->getJson('/api/products');

        $response->assertOk();
        $response->assertJsonStructure(['data', 'links', 'meta']);

        $data = $response->json('data');
        $this->assertCount(3, $data);

        $this->assertSame($latest->id, $data[0]['id']);
        $this->assertSame($newer->id, $data[1]['id']);
        $this->assertSame($older->id, $data[2]['id']);

        foreach (['id', 'name', 'sku', 'price', 'stock_qty'] as $key) {
            $this->assertArrayHasKey($key, $data[0]);
        }
        $this->assertArrayNotHasKey('created_at', $data[0]);
        $this->assertArrayNotHasKey('updated_at', $data[0]);
    }

    public function test_index_filters_by_prefix_search(): void
    {
        Product::factory()->create(['name' => 'Apple Juice', 'sku' => 'APL-001']);
        Product::factory()->create(['name' => 'Apricot Jam', 'sku' => 'APR-002']);
        Product::factory()->create(['name' => 'Banana Bread', 'sku' => 'BAN-003']);

        $response = $this->getJson('/api/products?search=Ap');

        $response->assertOk();

        $names = collect($response->json('data'))->pluck('name')->all();

        $this->assertSame(['Apricot Jam', 'Apple Juice'], $names);
    }

    public function test_index_paginates_20_per_page_by_default(): void
    {
        Product::factory()->count(25)->create();

        $response = $this->getJson('/api/products');

        $response->assertOk();

        $this->assertCount(20, $response->json('data'));
        $this->assertSame(25, $response->json('meta.total'));
        $this->assertSame(20, $response->json('meta.per_page'));
        $this->assertSame(1, $response->json('meta.current_page'));
    }
}
