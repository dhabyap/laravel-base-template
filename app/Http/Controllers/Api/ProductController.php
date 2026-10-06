<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

// Full CRUD example. Thin controller: receive -> call service -> respond.
// To build a new module (Order, Booking)? Copy this file and rename.
class ProductController extends Controller
{
    use ApiResponse;

    public function __construct(private ProductService $service) {}

    // GET /api/v1/products?search=coffee&per_page=20
    public function index(Request $request)
    {
        $data = $this->service->list($request->query('search'), (int) $request->query('per_page', 20));

        return $this->ok(ProductResource::collection($data), 'OK');
    }

    // POST /api/v1/products
    public function store(ProductRequest $request)
    {
        $product = $this->service->create($request->validated());

        return $this->ok(new ProductResource($product), 'Product created', 201);
    }

    // GET /api/v1/products/5
    public function show(Product $product)
    {
        // $product auto-resolved by Laravel from URL ID (route model binding).
        return $this->ok(new ProductResource($product), 'OK');
    }

    // PUT /api/v1/products/5
    public function update(ProductRequest $request, Product $product)
    {
        $product = $this->service->update($product, $request->validated());

        return $this->ok(new ProductResource($product), 'Product updated');
    }

    // DELETE /api/v1/products/5
    public function destroy(Product $product)
    {
        $this->service->delete($product);

        return $this->ok(null, 'Product deleted');
    }
}
