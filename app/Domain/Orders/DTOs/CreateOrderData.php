<?php

namespace App\Domain\Orders\DTOs;

class CreateOrderData
{
    /**
     * Create a DTO for a new order payload.
     *
     * @param int $customerId
     * @param array<int, array{product_id:int, qty:int}> $items
     */
    public function __construct(
        public int $customerId,
        /** @var array<int, array{product_id:int, qty:int}> */
        public array $items
    ) {}

    /**
     * Build a DTO from the incoming request data array.
     *
     * Expected shape: ['customer_id' => int|string, 'items' => array<int, array{product_id:int, qty:int}>]
     *
     * @param array{customer_id:int|string, items:array<int, array{product_id:int, qty:int}>} $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            customerId: (int) $data['customer_id'],
            items: $data['items']
        );
    }
}
