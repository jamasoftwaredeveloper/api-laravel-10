<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllActive($order, $page)
    {
        return $this->productRepository->getAllActive($order, $page);
    }

    public function createProduct(array $data)
    {
        return $this->productRepository->create($data);
    }

    public function findProductById($id)
    {
        return $this->productRepository->findById($id);
    }

    public function updateProduct($product, array $data)
    {
        return $this->productRepository->update($product, $data);
    }

    public function deleteProduct($product)
    {
        return $this->productRepository->delete($product);
    }

    public function toggleProductStatus($product, $status)
    {
        return $this->productRepository->changeStatus($product, $status);
    }
}
