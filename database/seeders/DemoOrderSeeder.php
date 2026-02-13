<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class DemoOrderSeeder
 * @package Database\Seeders
 */
class DemoOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $customers = Customer::query()->inRandomOrder()->take(10)->get();
        $products  = Product::query()->inRandomOrder()->take(20)->get();

        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($customers, $products) {
            foreach (range(1, 3000000) as $i) {
                $customer = $customers->random();

                $order = Order::create([
                    'customer_id' => $customer->id,
                    'status' => 'confirmed',
                    'subtotal' => 0,
                    'total' => 0,
                ]);

                $itemsCount = rand(1, 4);
                $picked = $products->random($itemsCount);

                $subtotal = 0;

                foreach ($picked as $product) {
                    // keep qty within available stock
                    $maxQty = max(1, min(5, (int)$product->stock_qty));
                    $qty = rand(1, $maxQty);

                    // if stock is empty, skip
                    if ($product->stock_qty < $qty) {
                        continue;
                    }

                    $unit = (int)$product->price;
                    $line = $unit * $qty;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'qty' => $qty,
                        'unit_price' => $unit,
                        'line_total' => $line,
                    ]);

                    $product->decrement('stock_qty', $qty);

                    $subtotal += $line;
                }

                $order->update([
                    'subtotal' => $subtotal,
                    'total' => $subtotal,
                ]);
            }
        });
    }
}
