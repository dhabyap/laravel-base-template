<?php

namespace App\Services;

use App\Models\Product;

// Business logic lives here, NOT in controller.
// Controller receives request, Service does the work.
class ProductService
{
    // List + name filter + pagination. Select needed columns to stay lean.
    public function list(?string $search, int $perPage = 20)
    {
        return Product::query()
            ->select(['id', 'name', 'price', 'stock'])
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    // Create 1 product. Data already clean from FormRequest.
    public function create(array $data)
    {
        return Product::create($data);
    }

    // Update 1 product.
    public function update(Product $product, array $data)
    {
        $product->update($data);

        return $product->fresh();
    }

    // Delete 1 product.
    public function delete(Product $product): void
    {
        $product->delete();
    }
}
