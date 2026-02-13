<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * Factory for generating Product model records with realistic data.
 *
 * The definition creates a title-cased name, a unique SKU derived from the
 * name plus a numeric suffix, and reasonable price/stock ranges for tests.
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define default attribute values for new Product model instances.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);
        $sku = strtoupper(Str::slug($name)).'-'.$this->faker->unique()->numberBetween(100, 999);

        return [
            'name' => Str::title($name),
            'sku' => $sku,
            'price' => $this->faker->numberBetween(50, 5000),
            'stock_qty' => $this->faker->numberBetween(10, 300),
        ];
    }
}
