<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for generating Order model records with realistic data.
 *
 * The definition creates orders linked to customers with default status and zeroed totals.
 */
class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;
    
    /**
     * Define default attribute values for new Order model instances.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'status' => 'confirmed',
            'subtotal' => 0,
            'total' => 0,
        ];
    }

    /**
     * Indicate that the order is cancelled.
     *
     * @return \Database\Factories\OrderFactory
     */
    public function cancelled(): self
    {
        return $this->state(fn() => ['status' => 'cancelled']);
    }
}
