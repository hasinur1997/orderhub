<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the current user can create an order.
     */
    public function authorize(): bool
    {
        return true; // later: policy/roles
    }

    /**
     * Define validation rules for creating an order.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1', 'max:1000'],
        ];
    }

    /**
     * Provide custom validation messages for order input.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'items.required' => 'Order items are required.',
            'items.min' => 'At least one item is required.',
        ];
    }
}
