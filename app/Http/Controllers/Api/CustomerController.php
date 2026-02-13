<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * List customers with newest first.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index()
    {
        return Customer::query()->latest()->paginate(20);
    }

    /**
     * Create a new customer.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $customer = Customer::create($request->only(['name', 'email', 'phone']));

        return response()->json($customer, 201);
    }

    /**
     * Show a single customer.
     *
     * @return \App\Models\Customer
     */
    public function show(Customer $customer)
    {
        return $customer;
    }

    /**
     * Update an existing customer.
     *
     * @return \App\Models\Customer
     */
    public function update(Request $request, Customer $customer)
    {
        $customer->update($request->only(['name', 'email', 'phone']));

        return $customer;
    }

    /**
     * Delete a customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->noContent();
    }
}
