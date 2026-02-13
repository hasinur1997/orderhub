<?php

namespace App\Domain\Orders\Services;

use App\Models\Product;

class OrderPricingService
{
    /**
     * Calculate subtotal and line item totals for a set of order items.
     *
     * Each item must include a numeric `product_id` and `qty`. Returns an array
     * containing the subtotal and per-line pricing details.
     *
     * @param array<int, array{product_id:int|string, qty:int|string}> $items
     * @return array{subtotal:int, lines:array<int, array{product_id:int, qty:int, unit_price:int, line_total:int}>}
     */
    public function calculate(array $items): array
    {
        $productIds = array_map(fn($i) => (int)$i['product_id'], $items);

        $products = Product::query()
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $subtotal = 0;
        $lines = [];

        foreach ($items as $item) {
            $pid = (int)$item['product_id'];
            $qty = (int)$item['qty'];

            /** @var Product $product */
            $product = $products->get($pid);

            $unit = (int) $product->price;
            $lineTotal = $unit * $qty;

            $subtotal += $lineTotal;

            $lines[] = [
                'product_id' => $pid,
                'qty' => $qty,
                'unit_price' => $unit,
                'line_total' => $lineTotal,
            ];
        }

        return [
            'subtotal' => $subtotal,
            'lines' => $lines,
        ];
    }
}
