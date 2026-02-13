<?php

namespace App\Domain\Orders\Actions;

use App\Domain\Orders\DTOs\CreateOrderData;
use App\Domain\Orders\Services\OrderPricingService;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateOrderAction
{
    /**
     * Create a new action instance.
     *
     * @param  OrderPricingService  $pricingService  Computes current pricing for order lines.
     */
    public function __construct(
        private readonly OrderPricingService $pricingService
    ) {}

    /**
     * Create an order, validate stock, and persist items in a single transaction.
     *
     * @param  CreateOrderData  $data  Input customer and item payload.
     * @return Order The persisted order with customer and item relations loaded.
     *
     * @throws ValidationException When a product is missing or stock is insufficient.
     */
    public function execute(CreateOrderData $data): Order
    {
        return DB::transaction(function () use ($data) {
            // Lock products to prevent race conditions on stock updates
            $productIds = array_map(fn ($i) => (int) $i['product_id'], $data->items);

            $products = Product::query()
                ->whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // Validate stock
            foreach ($data->items as $item) {
                $pid = (int) $item['product_id'];
                $qty = (int) $item['qty'];

                $product = $products->get($pid);
                if (! $product) {
                    throw ValidationException::withMessages([
                        'items' => ["Product {$pid} not found."],
                    ]);
                }

                if ($product->stock_qty < $qty) {
                    throw ValidationException::withMessages([
                        'items' => ["Insufficient stock for SKU: {$product->sku}"],
                    ]);
                }
            }

            // Calculate totals (uses current prices)
            $calc = $this->pricingService->calculate($data->items);

            // Create order
            $order = Order::create([
                'customer_id' => $data->customerId,
                'status' => 'confirmed',
                'subtotal' => $calc['subtotal'],
                'total' => $calc['subtotal'], // later: tax/discount
            ]);

            // Create items + decrement stock
            foreach ($calc['lines'] as $line) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $line['product_id'],
                    'qty' => $line['qty'],
                    'unit_price' => $line['unit_price'],
                    'line_total' => $line['line_total'],
                ]);

                /** @var Product $p */
                $p = $products->get($line['product_id']);
                $p->decrement('stock_qty', $line['qty']);
            }

            return $order->load(['customer', 'items.product']);
        });
    }
}
