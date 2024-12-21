<?php

namespace App\Repositories;

use App\Models\v1\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAllActive($order, $page)
    {
        return Product::where('active', 1)
            ->orderBy('created_at', $order)
            ->paginate($page);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function findById($id)
    {
        return Product::find($id);
    }

    public function update(Product $product, array $data)
    {
        $product->update($data);
        return $product;
    }

    public function delete(Product $product)
    {
        $product->delete();
    }

    public function changeStatus(Product $product, $status)
    {
        $product->active = $status;
        $product->save();
    }
}
