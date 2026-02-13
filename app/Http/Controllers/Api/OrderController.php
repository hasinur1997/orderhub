<?php

namespace App\Http\Controllers\Api;

use App\Domain\Orders\Actions\CreateOrderAction;
use App\Domain\Orders\DTOs\CreateOrderData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * List the most recent orders with customer and item product relations.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index()
    {
        return Order::query()
            ->with(['customer', 'items.product'])
            ->latest()
            ->paginate(20);
    }

    /**
     * Create a new order (placeholder response until service/transaction is added).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreOrderRequest $request, CreateOrderAction $action)
    {
        $data = CreateOrderData::fromArray($request->validated());
        $order = $action->execute($data);

        return response()->json($order, 201);
    }

    /**
     * Show a single order with customer and item product relations.
     *
     * @return \App\Models\Order
     */
    public function show(Order $order)
    {
        return $order->load(['customer', 'items.product']);
    }

    /**
     * Update an order (placeholder response until status updates are implemented).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Order $order)
    {
        // keep minimal: status update later
        return response()->json(['message' => 'Not implemented'], 501);
    }

    /**
     * Delete an order (not allowed; use cancel endpoint).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Order $order)
    {
        return response()->json(['message' => 'Not allowed. Use cancel endpoint.'], 405);
    }
}
