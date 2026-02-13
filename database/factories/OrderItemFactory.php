<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for generating OrderItem model records with realistic data.
 *
 * The definition creates order items linked to orders and products,
 * with calculated line totals based on quantity and unit price.
 */
class OrderItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderItem::class;

    /**
     * Define default attribute values for new OrderItem model instances.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $qty = $this->faker->numberBetween(1, 5);
        $unit = $this->faker->numberBetween(50, 2000);

        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'qty' => $qty,
            'unit_price' => $unit,
            'line_total' => $unit * $qty,
        ];
    }
}
