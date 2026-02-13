<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * List products with newest first.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $query = Product::query()
            ->select(['id', 'name', 'sku', 'price', 'stock_qty']);

        if ($search !== '') {
            // Prefer prefix for speed (index-friendly)
            $query->where('name', 'like', addcslashes($search, '%_\\').'%');
            // Product::search($search)->paginate(20);
        }

        $paginator = $query->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return response()->json([
            'data' => $paginator->items(),
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'from' => $paginator->firstItem(),
                'last_page' => $paginator->lastPage(),
                'path' => $paginator->path(),
                'per_page' => $paginator->perPage(),
                'to' => $paginator->lastItem(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    /**
     * Create a new product.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // TODO: replace with FormRequest validation
        $product = Product::create($request->only(['name', 'sku', 'price', 'stock_qty']));

        return response()->json($product, 201);
    }

    /**
     * Show a single product.
     *
     * @return \App\Models\Product
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Update a product.
     *
     * @return \App\Models\Product
     */
    public function update(Request $request, Product $product)
    {
        // TODO: replace with FormRequest validation
        $product->update($request->only(['name', 'sku', 'price', 'stock_qty']));

        return $product;
    }

    /**
     * Delete a product.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->noContent();
    }
}
